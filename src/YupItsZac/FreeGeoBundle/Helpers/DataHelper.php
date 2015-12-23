<?php

namespace YupItsZac\FreeGeoBundle\Helpers;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use YupItsZac\FreeGeoBundle\Entity\Session;
use YupItsZac\FreeGeoBundle\Entity\Config;
use YupItsZac\FreeGeoBundle\Entity\Strings;
use \DateTime;

class DataHelper extends Controller {

    private $dbConnection;

    public function __construct() {

        if(isset($this->dbConnection)) {
            $em = $this->getDoctrine()->getEntityManager();
            $qb = $em->createQueryBuilder();

            $this->dbConnection = $em->getConnection();
        }
    }

    public function findNearPort($lat, $lon, $limit, $max) {

        $q = $this->dbConnection->prepare('SELECT name, X(GeomFromText(coordinates_wkt)) AS latitude, Y(GeomFromText(coordinates_wkt)) AS longitude, SQRT( POW(69.1 * (X(GeomFromText(coordinates_wkt)) - :lat), 2) + POW(69.1 * (:lon - Y(GeomFromText(coordinates_wkt))) * COS(X(GeomFromText(coordinates_wkt)) / 57.3), 2)) AS distance FROM ports HAVING distance < :max ORDER BY distance ASC LIMIT '.$limit);

        $q->bindValue('lat', $lat);
        $q->bindValue('lon', $lon);
        $q->bindValue('max', $max);

        return $q->execute();
    }

    public function timeZone($lat, $lon) {

        $q = $this->dbConnection->prepare('SELECT name, offset, places, dst_places, MBRContains(GeomFromText(coordinates_wkt), POINT(":lat", ":lon")) AS contain FROM time_zones HAVING contain > 0 ORDER BY contain DESC LIMIT 1');
        $q->bindValue('lat', $lat);
        $q->bindValue('lon', $lon);

        return $q->execute();
    }

    public function detectCountry($lat, $lon) {

        $q = $this->dbConnection->prepare('SELECT name AS country, sovereign, formal, economy_level AS economy, income_level AS income, MBRContains(GeomFromText(coordinates_wkt), POINT(":lat", ":lon")) AS contain FROM countries HAVING contain > 0 ORDER BY contain DESC LIMIT 1');
        $q->bindValue('lat', $lat);
        $q->bindValue('lon', $lon);

        return $q->execute();
    }

    public function findNearAirport($lat, $lon, $limit, $max) {

        $q = $this->dbConnection->prepare('SELECT name, type, icao_code, iata_code, X(GeomFromText(coordinates_wkt)) AS latitude, Y(GeomFromText(coordinates_wkt)) AS longitude, SQRT( POW(69.1 * (X(GeomFromText(coordinates_wkt)) - :lat), 2) + POW(69.1 * (:lon - Y(GeomFromText(coordinates_wkt))) * COS(X(GeomFromText(coordinates_wkt)) / 57.3), 2)) AS distance FROM airports HAVING distance < :max ORDER BY distance ASC LIMIT '.$limit);

        $q->bindValue('lat', $lat);
        $q->bindValue('lon', $lon);
        $q->bindValue('max', $max);

        return $q->execute();
    }

    public function findNearCity($lat, $lon, $limit, $max) {

        $q = $this->dbConnection->prepare('SELECT name, name_alt, is_capital AS capital, region, time_zone, X(GeomFromText(coordinates_wkt)) AS latitude, Y(GeomFromText(coordinates_wkt)) AS longitude, SQRT( POW(69.1 * (X(GeomFromText(coordinates_wkt)) - :lat), 2) + POW(69.1 * (:lon - Y(GeomFromText(coordinates_wkt))) * COS(X(GeomFromText(coordinates_wkt)) / 57.3), 2)) AS distance FROM cities HAVING distance < :max ORDER BY distance ASC LIMIT '.$limit);

        $q->bindValue('lat', $lat);
        $q->bindValue('lon', $lon);
        $q->bindValue('max', $max);

        return $q->execute();
    }

    public function findNearLake($lat, $lon, $limit, $max) {

        $q = $this->dbConnection->prepare('SELECT name, name_alt, dam_name, X(GeomFromText(coordinates_wkt)) AS latitude, Y(GeomFromText(coordinates_wkt)) AS longitude, SQRT( POW(69.1 * (X(GeomFromText(coordinates_wkt)) - :lat), 2) + POW(69.1 * (:lon - Y(GeomFromText(coordinates_wkt))) * COS(X(GeomFromText(coordinates_wkt)) / 57.3), 2)) AS distance FROM lakes HAVING distance < :max ORDER BY distance ASC LIMIT '.$limit);

        $q->bindValue('lat', $lat);
        $q->bindValue('lon', $lon);
        $q->bindValue('max', $max);

        return $q->execute();
    }

    public function resetKeys($publicKey, $privateKey, $appId) {

        $q = $this->dbConnection->prepare('UPDATE Apps SET PublicKey=:publicKey, SecretKey=:privateKey WHERE id=:appId');
        $q->bindValue('publicKey', $publicKey);
        $q->bindValue('privateKey', $privateKey);
        $q->bindvalue('appId', $appId);

        $q->execute();

        return true;
    }

    public function fetchAppSession($session) {

        $app = $this->getDoctrine()->getRepository('YupItsZacFreeGeoBundle:Session')->findOneBy(array('session' => $session));

        return $app;

    }

    public function fetchAppById($appId) {

        $app = $this->getDoctrine()->getRepository('YupItsZacFreeGeoBundle:Apps')->findOneBy(array('id' => $appId));

        return $app;
    }

    public function prepareMessage($contactEmail, $contactFirstName, $appTitle, $publicKey, $privateKey) {

        $message = array();
        $message['from'] = 'FreeGeo API <'.Config::FROM_EMAIL_ADDRESS.'>';
        $message['to'] = $contactEmail;
        $message['subject'] = Strings::API_KEY_RESET_EMAIL_SUBJECT;
        $message['html'] = $this->renderView('YupItsZacFreeGeoBundle:Email:apikeys.reset.html.twig', array('firstName' => $contactFirstName, 'emailHeader' => Strings::API_MSG_KEY_RESET_SUCCESS, 'appTitle' => $appTitle, 'publicKey' => $publicKey, 'privateKey' => $privateKey, 'projectName' => Config::PROJECT_NAME, 'githubUrl' => Config::GITHUB_MAIN_REPO));

        return $message;
    }

    public function verifyAppSession($session) {

        $app = $this->fetchAppSession($session);

        if($app === null) {
            return false;
        } else {
            return true;
        }
    }
}