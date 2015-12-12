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

class ApiController extends Controller {

    public function authAction(Request $request) {

    	if($request->request->has('public') === false || $request->request->has('secret') === false) {

            return ResponseHelper::prepareResponse(Strings::API_STATUS_FATAL, Strings::API_REASON_FORBIDDEN, Strings::API_MSG_MISSING_CREDENTIALS);
        }

        $public = $request->request->get('public');
        $secret = $request->request->get('secret');

        $app = $this->getDoctrine()->getRepository('YupItsZacFreeGeoBundle:Apps')->findOneBy(array('publickey' => $public));

        if($app === null) {

            return ResponseHelper::prepareResponse(Strings::API_STATUS_FATAL, Strings::API_REASON_FORBIDDEN, Strings::API_MSG_MISSING_CREDENTIALS);
        }

        $appId = $app->getId();
        $appTitle = $app->getApptitle();
        $appStatus = $app->getStatus();
        
        if($appStatus != 'Active') {

            return ResponseHelper::prepareResponse(Strings::API_STATUS_FATAL, Strings::API_REASON_FORBIDDEN, Strings::API_MSG_REVOKED_CREDENTIALS);
        }

        $sessionKey = md5(time().$public.time().$secret.time().$appId);

        $em = $this->getDoctrine()->getEntityManager();

        $session = new Session();
        $session->setSession($sessionKey);
        $session->setPublic($public);
        $session->setSecret($secret);
        $session->setAppid($appId);
        $now = new DateTime('now');
        $session->setTimestamp($now);
        $em->persist($session);
        $em->flush();

        $payload = array('session' => $sessionKey);

        return ResponseHelper::prepareResponse(Strings::API_STATUS_SUCCESS, Strings::API_REASON_SUCCESS, Strings::API_MSG_SUCCESS, $payload);


    }

    public function findNearAirportAction(Request $request) {

        $session = $request->request->get('session');
        $lat = $request->request->get('lat');
        $lon = $request->request->get('lon');
        $limit = $request->request->get('limit');
        $max = $request->request->get('max');

        if(!$this->verifyAppSession($session)) {
            return ResponseHelper::prepareResponse(Strings::API_STATUS_FATAL, Strings::API_REASON_INVALID_SESSION, Strings::API_MSG_INVALID_SESSION);
        }

        $em = $this->getDoctrine()->getEntityManager();

        $con = $em->getConnection();

        $q = $con->prepare('SELECT name, type, icao_code, iata_code, X(GeomFromText(coordinates_wkt)) AS latitude, Y(GeomFromText(coordinates_wkt)) AS longitude, SQRT( POW(69.1 * (X(GeomFromText(coordinates_wkt)) - :lat), 2) + POW(69.1 * (:lon - Y(GeomFromText(coordinates_wkt))) * COS(X(GeomFromText(coordinates_wkt)) / 57.3), 2)) AS distance FROM airports HAVING distance < :max ORDER BY distance ASC LIMIT '.$limit);

        $q->bindValue('lat', $lat);
        $q->bindValue('lon', $lon);
        $q->bindValue('max', $max);

        $q->execute();

        return ResponseHelper::prepareResponse(Strings::API_STATUS_SUCCESS, Strings::API_REASON_SUCCESS, Strings::API_MSG_SUCCESS, $q->fetchAll());

    }

    public function findNearCityAction(Request $request) {

        $session = $request->request->get('session');
        $lat = $request->request->get('lat');
        $lon = $request->request->get('lon');
        $limit = $request->request->get('limit');
        $max = $request->request->get('max');

        if(!$this->verifyAppSession($session)) {
            return ResponseHelper::prepareResponse(Strings::API_STATUS_FATAL, Strings::API_REASON_INVALID_SESSION, Strings::API_MSG_INVALID_SESSION);
        }

        $em = $this->getDoctrine()->getEntityManager();

        $con = $em->getConnection();

        $q = $con->prepare('SELECT name, name_alt, is_capital AS capital, region, time_zone, X(GeomFromText(coordinates_wkt)) AS latitude, Y(GeomFromText(coordinates_wkt)) AS longitude, SQRT( POW(69.1 * (X(GeomFromText(coordinates_wkt)) - :lat), 2) + POW(69.1 * (:lon - Y(GeomFromText(coordinates_wkt))) * COS(X(GeomFromText(coordinates_wkt)) / 57.3), 2)) AS distance FROM cities HAVING distance < :max ORDER BY distance ASC LIMIT '.$limit);

        $q->bindValue('lat', $lat);
        $q->bindValue('lon', $lon);
        $q->bindValue('max', $max);

        $q->execute();

        return ResponseHelper::prepareResponse(Strings::API_STATUS_SUCCESS, Strings::API_REASON_SUCCESS, Strings::API_MSG_SUCCESS, $q->fetchAll());

    }

    public function findNearLakeAction(Request $request) {

        $session = $request->request->get('session');
        $lat = $request->request->get('lat');
        $lon = $request->request->get('lon');
        $limit = $request->request->get('limit');
        $max = $request->request->get('max');

        if(!$this->verifyAppSession($session)) {
            return ResponseHelper::prepareResponse(Strings::API_STATUS_FATAL, Strings::API_REASON_INVALID_SESSION, Strings::API_MSG_INVALID_SESSION);
        }

        $em = $this->getDoctrine()->getEntityManager();

        $con = $em->getConnection();

        $q = $con->prepare('SELECT name, name_alt, dam_name, X(GeomFromText(coordinates_wkt)) AS latitude, Y(GeomFromText(coordinates_wkt)) AS longitude, SQRT( POW(69.1 * (X(GeomFromText(coordinates_wkt)) - :lat), 2) + POW(69.1 * (:lon - Y(GeomFromText(coordinates_wkt))) * COS(X(GeomFromText(coordinates_wkt)) / 57.3), 2)) AS distance FROM lakes HAVING distance < :max ORDER BY distance ASC LIMIT '.$limit);

        $q->bindValue('lat', $lat);
        $q->bindValue('lon', $lon);
        $q->bindValue('max', $max);
        // $q->bindValue('lim', $limit);

        $q->execute();

        return ResponseHelper::prepareResponse(Strings::API_STATUS_SUCCESS, Strings::API_REASON_SUCCESS, Strings::API_MSG_SUCCESS, $q->fetchAll());

    }

    public function findNearPortAction(Request $request) {

        $session = $request->request->get('session');
        $lat = $request->request->get('lat');
        $lon = $request->request->get('lon');
        $limit = $request->request->get('limit');
        $max = $request->request->get('max');

        if(!$this->verifyAppSession($session)) {
            return ResponseHelper::prepareResponse(Strings::API_STATUS_FATAL, Strings::API_REASON_INVALID_SESSION, Strings::API_MSG_INVALID_SESSION);
        }

        $em = $this->getDoctrine()->getEntityManager();

        $con = $em->getConnection();

        $q = $con->prepare('SELECT name, X(GeomFromText(coordinates_wkt)) AS latitude, Y(GeomFromText(coordinates_wkt)) AS longitude, SQRT( POW(69.1 * (X(GeomFromText(coordinates_wkt)) - :lat), 2) + POW(69.1 * (:lon - Y(GeomFromText(coordinates_wkt))) * COS(X(GeomFromText(coordinates_wkt)) / 57.3), 2)) AS distance FROM ports HAVING distance < :max ORDER BY distance ASC LIMIT '.$limit);

        $q->bindValue('lat', $lat);
        $q->bindValue('lon', $lon);
        $q->bindValue('max', $max);

        $q->execute();

        return ResponseHelper::prepareResponse(Strings::API_STATUS_SUCCESS, Strings::API_REASON_SUCCESS, Strings::API_MSG_SUCCESS, $q->fetchAll());

    }

    public function timeZoneAction(Request $request) {

        $session = $request->request->get('session');
        $lat = $request->request->get('lat');
        $lon = $request->request->get('lon');

        if(!$this->verifyAppSession($session)) {
            return ResponseHelper::prepareResponse(Strings::API_STATUS_FATAL, Strings::API_REASON_INVALID_SESSION, Strings::API_MSG_INVALID_SESSION);
        }

        $em = $this->getDoctrine()->getEntityManager();

        $con = $em->getConnection();

        $q = $con->prepare('SELECT name, offset, places, dst_places, MBRContains(GeomFromText(coordinates_wkt), POINT(":lat", ":lon")) AS contain FROM time_zones HAVING contain > 0 ORDER BY contain DESC LIMIT 1');
        $q->bindValue('lat', $lat);
        $q->bindValue('lon', $lon);

        $q->execute();

        return ResponseHelper::prepareResponse(Strings::API_STATUS_SUCCESS, Strings::API_REASON_SUCCESS, Strings::API_MSG_SUCCESS, $q->fetchAll());
    }

    public function detectCountryAction(Request $request) {

        $session = $request->request->get('session');
        $lat = $request->request->get('lat');
        $lon = $request->request->get('lon');

        if(!$this->verifyAppSession($session)) {
            return ResponseHelper::prepareResponse(Strings::API_STATUS_FATAL, Strings::API_REASON_INVALID_SESSION, Strings::API_MSG_INVALID_SESSION);
        }

        $em = $this->getDoctrine()->getEntityManager();

        $con = $em->getConnection();
 
        $q = $con->prepare('SELECT name AS country, sovereign, formal, economy_level AS economy, income_level AS income, MBRContains(GeomFromText(coordinates_wkt), POINT(":lat", ":lon")) AS contain FROM countries HAVING contain > 0 ORDER BY contain DESC LIMIT 1');
        $q->bindValue('lat', $lat);
        $q->bindValue('lon', $lon);

        $q->execute();

        return ResponseHelper::prepareResponse(Strings::API_STATUS_SUCCESS, Strings::API_REASON_SUCCESS, Strings::API_MSG_SUCCESS, $q->fetchAll());

    }

    public function calculateDistanceAction(Request $request) {

        $session = $request->request->get('session');
        $lata = $request->request->get('lata');
        $lona = $request->request->get('lona');

        $latb = $request->request->get('latb');
        $lonb = $request->request->get('lonb');

        $metric = $request->request->get('metric');

        $round = $request->request->get('round');

        if(!$this->verifyAppSession($session)) {
            return ResponseHelper::prepareResponse(Strings::API_STATUS_FATAL, Strings::API_REASON_INVALID_SESSION, Strings::API_MSG_INVALID_SESSION);
        }

        $theta = $lona - $lonb;
        $dist = sin(deg2rad($lata)) * sin(deg2rad($latb)) +  cos(deg2rad($lata)) * cos(deg2rad($latb)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtolower($metric);

        if($unit == 'k') {
            $distance = $miles * 1.609344;
            $name = 'kilometers';
        } else if($unit == 'n') {
            $distance = $miles * 0.8684;
            $name = 'nautical miles';
        } else {
            $distance = $miles;
            $name = 'miles';
        }

        if(!empty($round)) {
            $final = round($distance, $round);
        } else {
            $final = $distance;
        }

        $payload = array('distance' => $final, 'metric' => strtoupper($metric), 'fullMetric' => $name);

        return ResponseHelper::prepareResponse(Strings::API_STATUS_SUCCESS, Strings::API_REASON_SUCCESS, Strings::API_MSG_SUCCESS, $payload);
    }

    public function resetKeysAction(Request $request) {

        $session = $request->request->get('session');

        if(!$this->verifyAppSession($session)) {
            return ResponseHelper::prepareResponse(Strings::API_STATUS_FATAL, Strings::API_REASON_INVALID_SESSION, Strings::API_MSG_INVALID_SESSION);
        }

        $app = $this->getDoctrine()->getRepository('YupItsZacFreeGeoBundle:Session')->findOneBy(array('session' => $session));

        $public = md5(time().$app->getPublic().time());
        $private = md5(time().time().$app->getSecret().time());
        $appid = $app->getAppid();


        $em = $this->getDoctrine()->getEntityManager();

        $con = $em->getConnection();
 
        $q = $con->prepare('UPDATE Apps SET PublicKey=:pub, SecretKey=:priv WHERE id=:aid');
        $q->bindValue('pub', $public);
        $q->bindValue('priv', $private);
        $q->bindvalue('aid', $appid);

        $q->execute();

        $app = $this->getDoctrine()->getRepository('YupItsZacFreeGeoBundle:Apps')->findOneBy(array('id' => $appid));

        $email = $app->getEmail();
        $title = $app->getApptitle();
        $fname = $app->getFirstname();

        $message = array();
        $message['from'] = 'FreeGeo API <'.Config::FROM_EMAIL_ADDRESS.'>';
        $message['to'] = $email;
        $message['subject'] = Strings::API_KEY_RESET_EMAIL_SUBJECT;
        $message['html'] = $this->renderView('YupItsZacFreeGeoBundle:Email:apikeys.reset.html.twig', array('firstName' => $fname, 'emailHeader' => Strings::API_MSG_KEY_RESET_SUCCESS, 'appTitle' => $title, 'publicKey' => $public, 'privateKey' => $private, 'projectName' => Config::PROJECT_NAME, 'githubUrl' => Config::GITHUB_MAIN_REPO));
     
        $this->sendEmailWithMailgun($message);

        return ResponseHelper::prepareResponse(Strings::API_STATUS_SUCCESS, Strings::API_REASON_SUCCESS, Strings::API_MSG_KEY_RESET_SUCCESS);

    }

    //Private API functions

    private function verifyAppSession($session) {

        $app = $this->getDoctrine()->getRepository('YupItsZacFreeGeoBundle:Session')->findOneBy(array('session' => $session));

        if($app === null) {
            return false;
        } else {
            return true;
        }
    }

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
}
