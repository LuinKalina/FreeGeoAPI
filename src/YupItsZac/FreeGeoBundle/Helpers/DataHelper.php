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
use YupItsZac\FreeGeoBundle\Entity\ApiRequestObject;
use \DateTime;

class DataHelper extends Controller {

    /**
     * Find ports near given coordinate set
     * @author zbrown
     *
     * @param ApiRequestObject $apiRequest
     * @return bool
     * @throws \Doctrine\DBAL\DBALException
     */
    public function findNearPort(ApiRequestObject $apiRequest) {

        $latitude = $apiRequest->getLatitude();
        $longitude = $apiRequest->getLongitude();
        $limit = $apiRequest->getLimit();
        $maximum = $apiRequest->getMaximum();

        $qb = $this->getDoctrine()->getEntityManager()->getConnection();

        $q = $qb->prepare('SELECT name, X(GeomFromText(coordinates_wkt)) AS latitude, Y(GeomFromText(coordinates_wkt)) AS longitude, SQRT( POW(69.1 * (X(GeomFromText(coordinates_wkt)) - :lat), 2) + POW(69.1 * (:lon - Y(GeomFromText(coordinates_wkt))) * COS(X(GeomFromText(coordinates_wkt)) / 57.3), 2)) AS distance FROM ports HAVING distance < :max ORDER BY distance ASC LIMIT '.$limit);

        $q->bindValue('lat', $latitude);
        $q->bindValue('lon', $longitude);
        $q->bindValue('max', $maximum);

        return $q->execute();
    }

    /**
     * Detect timezone for given coordinate set
     * @author zbrown
     *
     * @param ApiRequestObject $apiRequest
     * @return bool
     * @throws \Doctrine\DBAL\DBALException
     */
    public function timeZone(ApiRequestObject $apiRequest) {

        $latitude = $apiRequest->getLatitude();
        $longitude = $apiRequest->getLongitude();

        $qb = $this->getDoctrine()->getEntityManager()->getConnection();
        $q = $qb->prepare('SELECT name, offset, places, dst_places, MBRContains(GeomFromText(coordinates_wkt), POINT(":lat", ":lon")) AS contain FROM time_zones HAVING contain > 0 ORDER BY contain DESC LIMIT 1');
        $q->bindValue('lat', $latitude);
        $q->bindValue('lon', $longitude);

        return $q->execute();
    }

    /**
     * Detect country of given coordinate set
     * @auhtor zbrown
     *
     * @param ApiRequestObject $apiRequest
     * @return bool
     * @throws \Doctrine\DBAL\DBALException
     */
    public function detectCountry(ApiRequestObject $apiRequest) {

        $latitude = $apiRequest->getLatitude();
        $longitude = $apiRequest->getLongitude();

        $qb = $this->getDoctrine()->getEntityManager()->getConnection();
        $q = $qb->prepare('SELECT name AS country, sovereign, formal, economy_level AS economy, income_level AS income, MBRContains(GeomFromText(coordinates_wkt), POINT(":lat", ":lon")) AS contain FROM countries HAVING contain > 0 ORDER BY contain DESC LIMIT 1');
        $q->bindValue('lat', $latitude);
        $q->bindValue('lon', $longitude);

        return $q->execute();
    }


    /**
     * Find cities near given coordiate set
     * @author zbrown
     *
     * @param ApiRequestObject $apiRequest
     * @return bool
     * @throws \Doctrine\DBAL\DBALException
     */
    public function findNearCity(ApiRequestObject $apiRequest) {

        $qb = $this->getDoctrine()->getEntityManager()->getConnection();
        $q = $qb->prepare('SELECT name, name_alt, is_capital AS capital, region, time_zone, X(GeomFromText(coordinates_wkt)) AS latitude, Y(GeomFromText(coordinates_wkt)) AS longitude, SQRT( POW(69.1 * (X(GeomFromText(coordinates_wkt)) - :lat), 2) + POW(69.1 * (:lon - Y(GeomFromText(coordinates_wkt))) * COS(X(GeomFromText(coordinates_wkt)) / 57.3), 2)) AS distance FROM cities HAVING distance < :max ORDER BY distance ASC LIMIT '.$apiRequest->getLimit());

        $q->bindValue('lat', $apiRequest->getLatitude());
        $q->bindValue('lon', $apiRequest->getLongitude());
        $q->bindValue('max', $apiRequest->getMaximum());

        return $q->execute();
    }

    /**
     * Find lakes near given cordinate set
     * @author zbrown
     *
     * @param ApiRequestObject $apiRequest
     * @return bool
     * @throws \Doctrine\DBAL\DBALException
     */
    public function findNearLake(ApiRequestObject $apiRequest) {

        $qb = $this->getDoctrine()->getEntityManager()->getConnection();
        $q = $qb->prepare('SELECT name, name_alt, dam_name, X(GeomFromText(coordinates_wkt)) AS latitude, Y(GeomFromText(coordinates_wkt)) AS longitude, SQRT( POW(69.1 * (X(GeomFromText(coordinates_wkt)) - :lat), 2) + POW(69.1 * (:lon - Y(GeomFromText(coordinates_wkt))) * COS(X(GeomFromText(coordinates_wkt)) / 57.3), 2)) AS distance FROM lakes HAVING distance < :max ORDER BY distance ASC LIMIT '.$apiRequest->getLimit());

        $q->bindValue('lat', $apiRequest->getLatitude());
        $q->bindValue('lon', $apiRequest->getLongitude());
        $q->bindValue('max', $apiRequest->getMaximum());

        return $q->execute();
    }

    /**
     * Reset application API keyset
     * @author zbrown
     *
     * @param ApiRequestObject $apiRequest
     * @param $appId
     * @return bool
     * @throws \Doctrine\DBAL\DBALException
     */
    public function resetKeys(ApiRequestObject $apiRequest, $appId) {

        $qb = $this->getDoctrine()->getEntityManager()->getConnection();
        $q = $qb->prepare('UPDATE Apps SET PublicKey=:publicKey, SecretKey=:privateKey WHERE id=:appId');
        $q->bindValue('publicKey', $apiRequest->getPublicKey());
        $q->bindValue('privateKey', $apiRequest->getPrivateKey());
        $q->bindvalue('appId', $appId);

        $q->execute();

        return true;
    }

    /**
     * Get application session data
     * @author zbrown
     *
     * @param ApiRequestObject $apiRequest
     * @return object
     */
    public function fetchAppSession(ApiRequestObject $apiRequest) {

        $app = $this->getDoctrine()->getRepository('YupItsZacFreeGeoBundle:Session')->findOneBy(array('session' => $apiRequest->getSessionToken()));

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
     * Fetch application info by public key
     * @author zbrown
     *
     * @param ApiRequestObject $apiRequest
     * @return object
     */
    public function fetchAppByPublicKey(ApiRequestObject $apiRequest) {

        $app = $this->getDoctrine()->getRepository('YupItsZacFreeGeoBundle:Apps')->findOneBy(array('publickey' => $apiRequest->getPublicKey()));

        return $app;
    }

    /**
     * Select app information by hash
     * @author zbrown
     *
     * @param $appHash
     * @return object
     */
    public function fetchAppByHash($appHash) {

        $app = $this->getDoctrine()->getRepository('YupItsZacFreeGeoBundle:Apps')->findOneBy(array('hash' => $appHash));

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
     * Verify an application by its session token
     * @author zbrown
     *
     * @param ApiRequestObject $apiRequest
     * @return array
     */

    public function verifyAppSession(ApiRequestObject $apiRequest) {

        $app = $this->fetchAppSession($apiRequest->getSessionToken());

        $sessionStatus = array();

        if($app === null) {
            $sessionStatus['validSession'] = false;
        } else {
            $sessionStatus['validSession'] = true;
            $sessionStatus['isBeta'] = $app->getIsbeta();
            $sessionStatus['sessionType'] = $this->getSessionType($apiRequest);
        }

        return $sessionStatus;
    }

    /**
     * Calculate distance between two coordinate sets
     * @author zbrown
     *
     * @param ApiRequestObject $apiRequest
     * @return array
     */
    public function calculateDistance(ApiRequestObject $apiRequest) {

        $primaryLatitude = $apiRequest->getLatitude();
        $primaryLongitude = $apiRequest->getLongitude();

        $secondaryLatitude = $apiRequest->getSecondaryLatitude();
        $secondaryLongitude = $apiRequest->getSecondaryLongitude();

        $metricUnit = $apiRequest->getMetricUnit();
        $roundMath = $apiRequest->getMetricUnit();

        $payload = array();
        $theta = $primaryLongitude - $secondaryLongitude;
        $dist = sin(deg2rad($primaryLatitude)) * sin(deg2rad($secondaryLatitude)) +  cos(deg2rad($primaryLatitude)) * cos(deg2rad($secondaryLatitude)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtolower($metricUnit);

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

        if(!empty($roundMath)) {
            $final = round($distance, $roundMath);
        } else {
            $final = $distance;
        }

        $payload['distance'] = $final;

        $payload = array('distance' => $calculation['distance'], 'metric' => strtoupper($apiRequest->getMetricUnit()), 'fullMetric' => $calculation['unitName']);


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
        $this->getDoctrine()->getEntityManager()->persist($session);
        $this->getDoctrine()->getEntityManager()->flush();

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
     * @param ApiRequestObject $apiRequest
     * @return string
     */
    public function getSessionType(ApiRequestObject $apiRequest) {

        //2 = Normal API requests, 1 = API Status Check

        if($apiRequest->getSessionToken() == Config::API_STATUS_CHECK_SESSION_TOKEN || $apiRequest->getPublicKey() == Config::API_STATUS_CHECK_PUBLIC_KEY) {
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
