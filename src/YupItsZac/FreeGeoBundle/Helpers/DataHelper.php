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

    /**
     * Fetch all port data within constraints
     * @author zbrown
     *
     * @param $lat
     * @param $lon
     * @param $limit
     * @param $max
     * @return bool
     * @throws \Doctrine\DBAL\DBALException
     */
    public function findNearPort($lat, $lon, $limit, $max) {

        $qb = $this->getDoctrine()->getEntityManager()->getConnection();

        $q = $this->dbConnection->prepare('SELECT name, X(GeomFromText(coordinates_wkt)) AS latitude, Y(GeomFromText(coordinates_wkt)) AS longitude, SQRT( POW(69.1 * (X(GeomFromText(coordinates_wkt)) - :lat), 2) + POW(69.1 * (:lon - Y(GeomFromText(coordinates_wkt))) * COS(X(GeomFromText(coordinates_wkt)) / 57.3), 2)) AS distance FROM ports HAVING distance < :max ORDER BY distance ASC LIMIT '.$limit);

        $q->bindValue('lat', $lat);
        $q->bindValue('lon', $lon);
        $q->bindValue('max', $max);

        return $q->execute();
    }

    /**
     * Select timezone data that contains coords
     * @author zbrown
     *
     * @param $lat
     * @param $lon
     * @return bool
     * @throws \Doctrine\DBAL\DBALException
     */
    public function timeZone($lat, $lon) {

        $q = $this->dbConnection->prepare('SELECT name, offset, places, dst_places, MBRContains(GeomFromText(coordinates_wkt), POINT(":lat", ":lon")) AS contain FROM time_zones HAVING contain > 0 ORDER BY contain DESC LIMIT 1');
        $q->bindValue('lat', $lat);
        $q->bindValue('lon', $lon);

        return $q->execute();
    }

    /**
     * Select country data that contains coords
     * @author zbrown
     *
     * @param $lat
     * @param $lon
     * @return bool
     * @throws \Doctrine\DBAL\DBALException
     */
    public function detectCountry($lat, $lon) {

        $q = $this->dbConnection->prepare('SELECT name AS country, sovereign, formal, economy_level AS economy, income_level AS income, MBRContains(GeomFromText(coordinates_wkt), POINT(":lat", ":lon")) AS contain FROM countries HAVING contain > 0 ORDER BY contain DESC LIMIT 1');
        $q->bindValue('lat', $lat);
        $q->bindValue('lon', $lon);

        return $q->execute();
    }

    /**
     * Select airport data within constraints
     * @author zbrown
     *
     * @param $lat
     * @param $lon
     * @param $limit
     * @param $max
     * @return bool
     * @throws \Doctrine\DBAL\DBALException
     */
    public function findNearAirport($lat, $lon, $limit, $max) {

        $q = $this->dbConnection->prepare('SELECT name, type, icao_code, iata_code, X(GeomFromText(coordinates_wkt)) AS latitude, Y(GeomFromText(coordinates_wkt)) AS longitude, SQRT( POW(69.1 * (X(GeomFromText(coordinates_wkt)) - :lat), 2) + POW(69.1 * (:lon - Y(GeomFromText(coordinates_wkt))) * COS(X(GeomFromText(coordinates_wkt)) / 57.3), 2)) AS distance FROM airports HAVING distance < :max ORDER BY distance ASC LIMIT '.$limit);

        $q->bindValue('lat', $lat);
        $q->bindValue('lon', $lon);
        $q->bindValue('max', $max);

        return $q->execute();
    }

    /**
     * Select city data thats within constrainst
     * @auhtor zbrown
     *
     * @param $lat
     * @param $lon
     * @param $limit
     * @param $max
     * @return bool
     * @throws \Doctrine\DBAL\DBALException
     */
    public function findNearCity($lat, $lon, $limit, $max) {

        $q = $this->dbConnection->prepare('SELECT name, name_alt, is_capital AS capital, region, time_zone, X(GeomFromText(coordinates_wkt)) AS latitude, Y(GeomFromText(coordinates_wkt)) AS longitude, SQRT( POW(69.1 * (X(GeomFromText(coordinates_wkt)) - :lat), 2) + POW(69.1 * (:lon - Y(GeomFromText(coordinates_wkt))) * COS(X(GeomFromText(coordinates_wkt)) / 57.3), 2)) AS distance FROM cities HAVING distance < :max ORDER BY distance ASC LIMIT '.$limit);

        $q->bindValue('lat', $lat);
        $q->bindValue('lon', $lon);
        $q->bindValue('max', $max);

        return $q->execute();
    }

    /**
     * Select lake data that meets constraints
     * @author zbrown
     *
     * @param $lat
     * @param $lon
     * @param $limit
     * @param $max
     * @return bool
     * @throws \Doctrine\DBAL\DBALException
     */
    public function findNearLake($lat, $lon, $limit, $max) {

        $q = $this->dbConnection->prepare('SELECT name, name_alt, dam_name, X(GeomFromText(coordinates_wkt)) AS latitude, Y(GeomFromText(coordinates_wkt)) AS longitude, SQRT( POW(69.1 * (X(GeomFromText(coordinates_wkt)) - :lat), 2) + POW(69.1 * (:lon - Y(GeomFromText(coordinates_wkt))) * COS(X(GeomFromText(coordinates_wkt)) / 57.3), 2)) AS distance FROM lakes HAVING distance < :max ORDER BY distance ASC LIMIT '.$limit);

        $q->bindValue('lat', $lat);
        $q->bindValue('lon', $lon);
        $q->bindValue('max', $max);

        return $q->execute();
    }

    /**
     * Reset app credentials
     * @author zbrown
     *
     * @param $publicKey
     * @param $privateKey
     * @param $appId
     * @return bool
     * @throws \Doctrine\DBAL\DBALException
     */
    public function resetKeys($publicKey, $privateKey, $appId) {

        $q = $this->dbConnection->prepare('UPDATE Apps SET PublicKey=:publicKey, SecretKey=:privateKey WHERE id=:appId');
        $q->bindValue('publicKey', $publicKey);
        $q->bindValue('privateKey', $privateKey);
        $q->bindvalue('appId', $appId);

        $q->execute();

        return true;
    }

    /**
     * Get current API session
     * @author zbrown
     *
     * @param $session
     * @return object
     */
    public function fetchAppSession($session) {

        $app = $this->getDoctrine()->getRepository('YupItsZacFreeGeoBundle:Session')->findOneBy(array('session' => $session));

        return $app;

    }

    /**
     * Select app information by ID
     * @author zbrown
     *
     * @param $appId
     * @return object
     */
    public function fetchAppById($appId) {

        $app = $this->getDoctrine()->getRepository('YupItsZacFreeGeoBundle:Apps')->findOneBy(array('id' => $appId));

        return $app;
    }

    /**
     * Select app information by public key
     * @auhtor zbrown
     *
     * @param $publicKey
     * @return object
     */
    public function fetchAppByPublicKey($publicKey) {

        $app = $this->getDoctrine()->getRepository('YupItsZacFreeGeoBundle:Apps')->findOneBy(array('public' => $publicKey));

        return $app;
    }

    /**
     * Prepare email t be send with Mailgun
     * @author zbrown
     *
     * @param $contactEmail
     * @param $contactFirstName
     * @param $appTitle
     * @param $publicKey
     * @param $privateKey
     * @return array
     */
    public function prepareMessage($contactEmail, $contactFirstName, $appTitle, $publicKey, $privateKey) {

        $message = array();
        $message['from'] = 'FreeGeo API <'.Config::FROM_EMAIL_ADDRESS.'>';
        $message['to'] = $contactEmail;
        $message['subject'] = Strings::API_KEY_RESET_EMAIL_SUBJECT;
        $message['html'] = $this->renderView('YupItsZacFreeGeoBundle:Email:apikeys.reset.html.twig', array('firstName' => $contactFirstName, 'emailHeader' => Strings::API_MSG_KEY_RESET_SUCCESS, 'appTitle' => $appTitle, 'publicKey' => $publicKey, 'privateKey' => $privateKey, 'projectName' => Config::PROJECT_NAME, 'githubUrl' => Config::GITHUB_MAIN_REPO));

        return $message;
    }

    /**
     * Verify an app session by session key
     * @author zbrown
     *
     * @param $session
     * @return array
     */
    public function verifyAppSession($session) {

        $app = $this->fetchAppSession($session);

        $sessionStatus = array();

        if($app === null) {
            $sessionStatus['validSession'] = false;
        } else {
            $sessionStatus['validSession'] = true;
            $sessionStatus['isBeta'] = $app->getIsbeta();
            $sessionStatus['sessionType'] = $this->getSessionType();
        }

        return $sessionStatus;
    }

    /**
     * Calculate distance between two coord points
     * @author zbrown
     *
     * @param $longitudeFirst
     * @param $latitudeFirst
     * @param $longitudeSecond
     * @param $latitudeSecond
     * @param $unit
     * @param $round
     * @return array
     */
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

    /**
     * Save a newly created authentication
     * @author zbrown
     *
     * @param $publicKey
     * @param $privateKey
     * @param $appId
     */
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

    /**
     * Generate session token
     * @author zbrown
     *
     * @return string
     */
    public function generateSessionToken() {

        return md5(time().time());
    }

    /**
     * Check status of API services
     * @author zbrown
     *
     * @return array
     */
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

    /**
     * Determine session type
     * @author zbrown
     *
     * @param $sessionToken
     * @param null $publicKey
     * @return string
     */
    public function getSessionType($sessionToken, $publicKey = null) {

        //2 = Normal API requests, 1 = API Status Check

        if($sessionToken == Config::API_STATUS_CHECK_SESSION_TOKEN || $publicKey == Config::API_STATUS_CHECK_PUBLIC_KEY) {
            return '1';
        } else {
            return '2';
        }
    }

    /**
     * Make API status check request
     * @author zbrown
     *
     * @param $url
     * @return mixed
     */
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

    /**
     * Determine if any of the services are offline
     * @author zbrown
     *
     * @param $serviceStatus
     * @return string
     */
    public function checkForOfflineStatus($serviceStatus) {

        foreach($serviceStatus as $key => $val) {

            if(in_array('Offline', $val)) {
                return 'offline';
            } else {
                return 'online';
            }
        }
    }

    /**
     * Get user object as public array
     * @author zbrown
     *
     * @param $userObject
     * @return array
     */
    public function getUserObjectAsArray($userObject) {

        $userArray = array(
            'firstName' => $userObject->getFirstname(),
            'lastName' => $userObject->getLastname(),
            'emailAddress' => $userObject->getEmail(),
            'isActive' => $userObject->getIsactive(),
            'isRole' => $userObject->getRoles(),
            'userId' => $userObject->getId()
        );

        return $userArray;
    }

    /**
     * Select list of apps registered by user
     * @author zbrown
     *
     * @param $userId
     * @return bool
     * @throws \Doctrine\DBAL\DBALException
     */
    public function fetchAllUserApps($userId) {

        $appList = $this->getDoctrine()->getRepository('YupItsZacFreeGeoBundle:Apps')->findBy(array('Assoc' => $userId));

        return $appList;
    }

}