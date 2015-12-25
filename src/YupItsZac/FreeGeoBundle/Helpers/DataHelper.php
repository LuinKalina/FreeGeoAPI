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
    private $entityManager;

    public function __construct() {

        if(isset($this->dbConnection)) {
            $this->entityManager = $this->getDoctrine()->getEntityManager();
            $this->queryBuilder = $this->entityManager->createQueryBuilder();

            $this->dbConnection = $this->entityManager->getConnection();
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

    public function fetchAppByPublicKey($publicKey) {

        $app = $this->getDoctrine()->getRepository('YupItsZacFreeGeoBundle:Apps')->findOneBy(array('public' => $publicKey));

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

        $sessionStatus = array();

        if($app === null) {
            $sessionStatus['validSession'] = false;
        } else {
            $sessionStatus['validSession'] = true;
            $sessionStatus['sessionType'] = $this->getSessionType();
        }

        return $sessionStatus;
    }

    public function calculateDistance($longitudeFirst, $latitudeFirst, $longitudeSecond, $latitudeSecond, $unit, $round) {

        $payload = array();

        $theta = $longitudeFirst - $longitudeSecond;
        $dist = sin(deg2rad($latitudeFirst)) * sin(deg2rad($latitudeSecond)) +  cos(deg2rad($latitudeFirst)) * cos(deg2rad($latitudeSecond)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtolower($unit);

        if($unit == 'k') {
            $distance = $miles * 1.609344;
            $payload['unitName'] = 'kilometers';
        } else if($unit == 'n') {
            $distance = $miles * 0.8684;
            $payload['unitName'] = 'nautical miles';
        } else {
            $distance = $miles;
            $payload['unitName'] = 'miles';
        }

        if(!empty($round)) {
            $final = round($distance, $round);
        } else {
            $final = $distance;
        }

        $payload['distance'] = $final;

        return $payload;

    }

    public function persistNewSession($publicKey, $privateKey, $appId) {

        $sessionToken = $this->generateSessionToken();

        $session = new Session();
        $session->setSession($sessionToken);
        $session->setPublic($publicKey);
        $session->setSecret($privateKey);
        $session->setAppid($appId);
        $now = new DateTime('now');
        $session->setTimestamp($now);
        $this->entityManager->persist($session);
        $this->entityManager->flush();

    }

    public function generateSessionToken() {

        return md5(time().time());
    }

    public function checkStatusServices() {

        $currentStatus = array();
        $serviceList = array(
            'Authenticate - POST' => '/api/authenticate/session',
            'findNearAirport - POST' => '/api/location/near/airport',
            'findNearCity - POST' => '/api/location/near/city',
            'findNearPort -POST' => '/api/location/near/port',
            'Timezone Detection - POST' => '/api/detect/timezone',
            'Distance Calculation - POST' => '/api/calculate/distance',
            'Country Detection - POST' => '/api/detect/country',
            'API Credential Reset - POST' => '/api/credentials/reset'
        );

        foreach($serviceList as $key => $val) {

            $status = $this->makeStatusRequest($val)['message'];
            if($status == 'Online') {
                $class = 'serviceOnline';
            } else {
                $class = 'serviceOffline';
                $status = 'Offline';
            }

            $currentStatus[$key] = array(
                'Status' => $status,
                'Endpoint' => $val,
                'Class' => $class
            );
        }

        return $currentStatus;

    }

    public function getSessionType($sessionToken, $publicKey = null) {

        //2 = Normal API requests, 1 = API Status Check

        if($sessionToken == Config::API_STATUS_CHECK_SESSION_TOKEN || $publicKey == Config::API_STATUS_CHECK_PUBLIC_KEY) {
            return '1';
        } else {
            return '2';
        }
    }

    private function makeStatusRequest($url) {

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => Config::BASE_URL_PROD.$url,
            CURLOPT_USERAGENT => 'FreeGeo::API::StatusChecker',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => array(
                'session' => Config::API_STATUS_CHECK_SESSION_TOKEN,
                'public' => Config::API_STATUS_CHECK_PUBLIC_KEY
            )
        ));

        $resp = curl_exec($curl);
        curl_close($curl);

        return json_decode($resp, true);
    }

    public function checkForOfflineStatus($serviceStatus) {

        foreach($serviceStatus as $key => $val) {

            if(in_array('Offline', $val)) {
                return 'offline';
            } else {
                return 'online';
            }
        }
    }
}