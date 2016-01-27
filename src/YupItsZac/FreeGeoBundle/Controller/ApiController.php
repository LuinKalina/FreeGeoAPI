<?php

namespace YupItsZac\FreeGeoBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use YupItsZac\FreeGeoBundle\Entity\Session;
use YupItsZac\FreeGeoBundle\Entity\Config;
use YupItsZac\FreeGeoBundle\Entity\Strings;
use \DateTime;
use YupItsZac\FreeGeoBundle\Helpers\ResponseHelper;
use YupItsZac\FreeGeoBundle\Helpers\DataHelper;
use YupItsZac\FreeGeoBundle\Entity\ApiRequestObject;

class ApiController extends Controller {

    private $dataHelper;

    public function __construct() {

        $this->dataHelper = new DataHelper();
    }

    /**
     * Authenticate app session
     * @author zbrown
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function authAction(Request $request) {

        $postData  = $this->get('request')->request->all();

        $apiRequest = new ApiRequestObject();
        $apiRequest->setAttributesByArray($postData);

    	if(is_null($apiRequest->getPublicKey()) || is_null($apiRequest->getPrivateKey())) {

            if($this->dataHelper->getSessionType($apiRequest) == 1) {
                //This is just a status check
                return ResponseHelper::prepareResponse(Strings::API_STATUS_SUCCESS, Strings::API_REASON_SUCCESS, Strings::API_MSG_STATUS_ONLINE);
            } else {
                return ResponseHelper::prepareResponse(Strings::API_STATUS_FATAL, Strings::API_REASON_FORBIDDEN, Strings::API_MSG_MISSING_CREDENTIALS);
            }
        }

        $app = $this->getDoctrine()->getRepository('YupItsZacFreeGeoBundle:Apps')->findOneBy(array('publickey' => $apiRequest->getPublicKey()));

        if($app === null) {
            return ResponseHelper::prepareResponse(Strings::API_STATUS_FATAL, Strings::API_REASON_FORBIDDEN, Strings::API_MSG_MISSING_CREDENTIALS);
        }

        if($app->getAssoc() === null) {
            return ResponseHelper::prepareResponse(Strings::API_STATUS_FATAL, Strings::API_REASON_APP_CONVERSION, Strings::API_MSG_APP_CONVERSION_REQUIRED);
        }

        $appId = $app->getId();
        $appStatus = $app->getStatus();
        
        if($appStatus != 'Active') {

            return ResponseHelper::prepareResponse(Strings::API_STATUS_FATAL, Strings::API_REASON_FORBIDDEN, Strings::API_MSG_REVOKED_CREDENTIALS);
        }

        $sessionToken = $this->persistNewAppSession($apiRequest, $appId);

        $payload = array('session' => $sessionToken);

        return ResponseHelper::prepareResponse(Strings::API_STATUS_SUCCESS, Strings::API_REASON_SUCCESS, Strings::API_MSG_SUCCESS, $payload);


    }

    /**
     * Find airports near given coords
     * @author zbrown
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function findNearAirportAction(Request $request) {

        $postData  = $this->get('request')->request->all();

        $apiRequest = new ApiRequestObject();
        $apiRequest->setAttributesByArray($postData);

        if($this->dataHelper->getSessionType($apiRequest) == 1) {
            //This is just a status check
            return ResponseHelper::prepareResponse(Strings::API_STATUS_SUCCESS, Strings::API_REASON_SUCCESS, Strings::API_MSG_STATUS_ONLINE);
        }

        if(!$this->dataHelper->verifyAppSession($apiRequest)) {
            return ResponseHelper::prepareResponse(Strings::API_STATUS_FATAL, Strings::API_REASON_INVALID_SESSION, Strings::API_MSG_INVALID_SESSION);
        }

        $dataAirports = $this->findNearAirport($apiRequest);

        return ResponseHelper::prepareResponse(Strings::API_STATUS_SUCCESS, Strings::API_REASON_SUCCESS, Strings::API_MSG_SUCCESS, $dataAirports);

    }

    /**
     * Find cities near given coords
     * @author zbrown
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function findNearCityAction(Request $request) {

        $postData  = $this->get('request')->request->all();

        $apiRequest = new ApiRequestObject();
        $apiRequest->setAttributesByArray($postData);

        if($this->dataHelper->getSessionType($apiRequest) == 1) {
            //This is just a status check
            return ResponseHelper::prepareResponse(Strings::API_STATUS_SUCCESS, Strings::API_REASON_SUCCESS, Strings::API_MSG_STATUS_ONLINE);
        }

        if(!$this->dataHelper->verifyAppSession($apiRequest)) {
            return ResponseHelper::prepareResponse(Strings::API_STATUS_FATAL, Strings::API_REASON_INVALID_SESSION, Strings::API_MSG_INVALID_SESSION);
        }

        $dataCities = $this->dataHelper->findNearCity($apiRequest);

        return ResponseHelper::prepareResponse(Strings::API_STATUS_SUCCESS, Strings::API_REASON_SUCCESS, Strings::API_MSG_SUCCESS, $dataCities);

    }

    /**
     * Find lakes near given coords
     * @author zbrown
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function findNearLakeAction(Request $request) {

        $postData  = $this->get('request')->request->all();

        $apiRequest = new ApiRequestObject();
        $apiRequest->setAttributesByArray($postData);

        if($this->dataHelper->getSessionType($apiRequest) == 1) {
            //This is just a status check
            return ResponseHelper::prepareResponse(Strings::API_STATUS_SUCCESS, Strings::API_REASON_SUCCESS, Strings::API_MSG_STATUS_ONLINE);
        }

        if(!$this->dataHelper->verifyAppSession($apiRequest)) {
            return ResponseHelper::prepareResponse(Strings::API_STATUS_FATAL, Strings::API_REASON_INVALID_SESSION, Strings::API_MSG_INVALID_SESSION);
        }

        $dataLakes = $this->dataHelper->findNearLake($apiRequest);

        return ResponseHelper::prepareResponse(Strings::API_STATUS_SUCCESS, Strings::API_REASON_SUCCESS, Strings::API_MSG_SUCCESS, $dataLakes);

    }

    /**
     * Find ports near given coords
     * @author zbrown
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function findNearPortAction(Request $request) {

        $postData  = $this->get('request')->request->all();

        $apiRequest = new ApiRequestObject();
        $apiRequest->setAttributesByArray($postData);

        if($this->dataHelper->getSessionType($apiRequest) == 1) {
            //This is just a status check
            return ResponseHelper::prepareResponse(Strings::API_STATUS_SUCCESS, Strings::API_REASON_SUCCESS, Strings::API_MSG_STATUS_ONLINE);
        }

        if(!$this->dataHelper->verifyAppSession($apiRequest)) {
            return ResponseHelper::prepareResponse(Strings::API_STATUS_FATAL, Strings::API_REASON_INVALID_SESSION, Strings::API_MSG_INVALID_SESSION);
        }

        $dataPorts = $this->dataHelper->findNearPort($apiRequest);

        return ResponseHelper::prepareResponse(Strings::API_STATUS_SUCCESS, Strings::API_REASON_SUCCESS, Strings::API_MSG_SUCCESS, $dataPorts);

    }

    /**
     * Find timezone of given coords
     * @author zbrown
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function timeZoneAction(Request $request) {

        $postData  = $this->get('request')->request->all();

        $apiRequest = new ApiRequestObject();
        $apiRequest->setAttributesByArray($postData);

        if($this->dataHelper->getSessionType($apiRequest) == 1) {
            //This is just a status check
            return ResponseHelper::prepareResponse(Strings::API_STATUS_SUCCESS, Strings::API_REASON_SUCCESS, Strings::API_MSG_STATUS_ONLINE);
        }

        if(!$this->dataHelper->verifyAppSession($apiRequest)) {
            return ResponseHelper::prepareResponse(Strings::API_STATUS_FATAL, Strings::API_REASON_INVALID_SESSION, Strings::API_MSG_INVALID_SESSION);
        }

        $dataTimeZone = $this->dataHelper->timeZone($apiRequest);

        return ResponseHelper::prepareResponse(Strings::API_STATUS_SUCCESS, Strings::API_REASON_SUCCESS, Strings::API_MSG_SUCCESS, $dataTimeZone);
    }

    /**
     * Detect country of given coords
     * @author zbrown
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function detectCountryAction(Request $request) {

        $postData  = $this->get('request')->request->all();

        $apiRequest = new ApiRequestObject();
        $apiRequest->setAttributesByArray($postData);

        if($this->dataHelper->getSessionType($apiRequest) == 1) {
            //This is just a status check
            return ResponseHelper::prepareResponse(Strings::API_STATUS_SUCCESS, Strings::API_REASON_SUCCESS, Strings::API_MSG_STATUS_ONLINE);
        }

        if(!$this->dataHelper->verifyAppSession($apiRequest)) {
            return ResponseHelper::prepareResponse(Strings::API_STATUS_FATAL, Strings::API_REASON_INVALID_SESSION, Strings::API_MSG_INVALID_SESSION);
        }

        $dataCountry = $this->dataHelper->detectCountry($apiRequest);

        return ResponseHelper::prepareResponse(Strings::API_STATUS_SUCCESS, Strings::API_REASON_SUCCESS, Strings::API_MSG_SUCCESS, $dataCountry);

    }

    /**
     * Calculate distance between given coords
     * @author zbrown
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function calculateDistanceAction(Request $request) {

        $postData  = $this->get('request')->request->all();

        $apiRequest = new ApiRequestObject();
        $apiRequest->setAttributesByArray($postData);

        if($this->dataHelper->getSessionType($apiRequest) == 1) {
            //This is just a status check
            return ResponseHelper::prepareResponse(Strings::API_STATUS_SUCCESS, Strings::API_REASON_SUCCESS, Strings::API_MSG_STATUS_ONLINE);
        }

        if($this->dataHelper->verifyAppSession($apiRequest)['validSession'] === false) {
            return ResponseHelper::prepareResponse(Strings::API_STATUS_FATAL, Strings::API_REASON_INVALID_SESSION, Strings::API_MSG_INVALID_SESSION);
        }

        $calculation = $this->dataHelper->calculateDistance($apiRequest);

        $payload = array('distance' => $calculation['distance'], 'metric' => strtoupper($apiRequest->getMetricUnit()), 'fullMetric' => $calculation['unitName']);

        return ResponseHelper::prepareResponse(Strings::API_STATUS_SUCCESS, Strings::API_REASON_SUCCESS, Strings::API_MSG_SUCCESS, $payload);
    }

    /**
     * Reset API keyset
     * @author zbrown
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function resetKeysAction(Request $request) {

        $postData  = $this->get('request')->request->all();

        $apiRequest = new ApiRequestObject();
        $apiRequest->setAttributesByArray($postData);

        if($this->dataHelper->getSessionType($apiRequest) == 1) {
            //This is just a status check
            return ResponseHelper::prepareResponse(Strings::API_STATUS_SUCCESS, Strings::API_REASON_SUCCESS, Strings::API_MSG_STATUS_ONLINE);
        }

        if(!$this->dataHelper->verifyAppSession($apiRequest)) {
            return ResponseHelper::prepareResponse(Strings::API_STATUS_FATAL, Strings::API_REASON_INVALID_SESSION, Strings::API_MSG_INVALID_SESSION);
        }

        $appSession = $this->dataHelper->fetchAppSession($apiRequest);

        if(!$appSession) {
            return ResponseHelper::prepareResponse(Strings::API_STATUS_FATAL, Strings::API_REASON_INVALID_SESSION, Strings::API_MSG_ERROR_LOCATING_SESSION);
        }

        $publicKey = md5(time().$appSession->getPublic().time());
        $privateKey = md5(time().time().$appSession->getSecret().time());
        $appId = $appSession->getAppid();

        if($this->dataHelper->resetKeys($apiRequest, $appId) === false) {
            return ResponseHelper::prepareResponse(Strings::API_STATUS_FATAL, Strings::API_REASON_DB_ERROR, Strings::API_MSG_KEY_RESET_FAILED_DB);
        }

        $app = $this->dataHelper->fetchAppById($appId);

        $contactEmail = $app->getEmail();
        $appTitle = $app->getApptitle();
        $contactFirstName = $app->getFirstname();

        $message = $this->dataHelper->prepareMessage($contactEmail, $contactFirstName, $appTitle, $publicKey, $privateKey);

        $this->sendEmailWithMailgun($message);

        return ResponseHelper::prepareResponse(Strings::API_STATUS_SUCCESS, Strings::API_REASON_SUCCESS, Strings::API_MSG_KEY_RESET_SUCCESS);

    }

    /**
     * Send email with Mailgun
     * @author zbrown
     *
     * @param $message
     */
    private function sendEmailWithMailgun($message) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, Config::MAILGUN_API_URL);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, 'api:{'.Config::MAILGUN_API_KEY.'}');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$message);

        $result = curl_exec($ch);

        curl_close($ch);
    }

    /**
     * Persist app session to DB
     * @author zbrown
     *
     * @param $publicKey
     * @param $privateKey
     * @param $appId
     */
    private function persistNewAppSession(ApiRequestObject $apiRequest, $appId) {

        $publicKey = $apiRequest->getPublicKey();
        $privateKey = $apiRequest->getPrivateKey();

        $sessionToken = $this->dataHelper->generateSessionToken();

        $session = new Session();
        $session->setSession($sessionToken);
        $session->setPublic($publicKey);
        $session->setSecret($privateKey);
        $session->setAppid($appId);
        $now = new DateTime('now');
        $session->setTimestamp($now);
        $this->getDoctrine()->getEntityManager()->persist($session);
        $this->getDoctrine()->getEntityManager()->flush();

        return $sessionToken;

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
    private function findNearAirport(ApiRequestObject $apiRequest) {

        $latitude = $apiRequest->getLatitude();
        $longitude = $apiRequest->getLongitude();
        $limit = $apiRequest->getLimit();
        $max = $apiRequest->getMaximum();

        $qb = $this->getDoctrine()->getEntityManager()->getConnection();
        $q = $qb->prepare('SELECT name, type, icao_code, iata_code, X(GeomFromText(coordinates_wkt)) AS latitude, Y(GeomFromText(coordinates_wkt)) AS longitude, SQRT( POW(69.1 * (X(GeomFromText(coordinates_wkt)) - :lat), 2) + POW(69.1 * (:lon - Y(GeomFromText(coordinates_wkt))) * COS(X(GeomFromText(coordinates_wkt)) / 57.3), 2)) AS distance FROM airports HAVING distance < :max ORDER BY distance ASC LIMIT '.$limit);

        $q->bindValue('lat', $latitude);
        $q->bindValue('lon', $longitude);
        $q->bindValue('max', $max);

        return $q->execute();
    }
}
