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

class ApiController extends Controller {

    public function authAction(Request $request) {

    	if($request->request->has('public') === false || $request->request->has('secret') === false) {

	    	$resp = array(
	    		'status' => 'fatal',
	    		'reason' => 'Unauthorized',
	    		'message' => 'Provide app API credentials.',
                'request' => $request->request->all()
	    	);

	    	return new JsonResponse($resp);

    	}

        $public = $request->request->get('public');
        $secret = $request->request->get('secret');

        $app = $this->getDoctrine()->getRepository('YupItsZacFreeGeoBundle:Apps')->findOneBy(array('publickey' => $public));

        if($app === null) {

            $resp = array(
                'status' => 'fatal',
                'reason' => 'unauthroized',
                'message' => 'The supplied API credentials are invalid, or revoked.'
            );

            return new JsonResponse($resp);
        }

        $appId = $app->getId();
        $appTitle = $app->getApptitle();
        $appStatus = $app->getStatus();
        
        if($appStatus != 'Active') {

            $resp = array(
                'status' => 'fatal',
                'reason' => 'Unauthorized',
                'message' => 'App for public key '.$public.' is no longer active. The status is '.$appStatus.'. For questions, contact support online at freegeo.yupitszac.com'
            );

            return new JsonResponse($resp);
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

        $resp = array(
            'status' => 'success',
            'reason' => 'authorized',
            'payload' => array('session' => $sessionKey)
        );



    	return new JsonResponse($resp);
        
    }

    public function findNearAirportAction(Request $request) {

        $session = $request->request->get('session');
        $lat = $request->request->get('lat');
        $lon = $request->request->get('lon');
        $limit = $request->request->get('limit');
        $max = $request->request->get('max');

        // $lat = '-112.01363529772982';
        // $lon = '33.43586076394977';
        // $limit = 30;
        // $max = 2500;

        $app = $this->getDoctrine()->getRepository('YupItsZacFreeGeoBundle:Session')->findOneBy(array('session' => $session));

        if($app === null) {

            $resp = array(
                'status' => 'fatal',
                'reason' => 'unauthorized',
                'message' => 'Invalid session key '.$session.'. Please authenticate your app. More info at freegeo.yupitszac.com/docs/authenticate'
            );

            return new JsonResponse($resp);
        }

        $em = $this->getDoctrine()->getEntityManager();

        $con = $em->getConnection();

        $q = $con->prepare('SELECT name, type, icao_code, iata_code, X(GeomFromText(coordinates_wkt)) AS latitude, Y(GeomFromText(coordinates_wkt)) AS longitude, SQRT( POW(69.1 * (X(GeomFromText(coordinates_wkt)) - :lat), 2) + POW(69.1 * (:lon - Y(GeomFromText(coordinates_wkt))) * COS(X(GeomFromText(coordinates_wkt)) / 57.3), 2)) AS distance FROM airports HAVING distance < :max ORDER BY distance ASC LIMIT '.$limit);

        $q->bindValue('lat', $lat);
        $q->bindValue('lon', $lon);
        $q->bindValue('max', $max);
        // $q->bindValue('lim', $limit);

        $q->execute();

        $resp = array(
            'status' => 'success',
            'reason' => 'completed action',
            'payload' => $q->fetchAll()
        );

        return new JsonResponse($resp);

    }

    public function findNearCityAction(Request $request) {

        $session = $request->request->get('session');
        $lat = $request->request->get('lat');
        $lon = $request->request->get('lon');
        $limit = $request->request->get('limit');
        $max = $request->request->get('max');

       $app = $this->getDoctrine()->getRepository('YupItsZacFreeGeoBundle:Session')->findOneBy(array('session' => $session));

        if($app === null) {

            $resp = array(
                'status' => 'fatal',
                'reason' => 'unauthorized',
                'message' => 'Invalid session key '.$session.'. Please authenticate your app. More info at freegeo.yupitszac.com/docs/authenticate'
            );

            return new JsonResponse($resp);
        }

        $em = $this->getDoctrine()->getEntityManager();

        $con = $em->getConnection();

        $q = $con->prepare('SELECT name, name_alt, is_capital AS capital, region, time_zone, X(GeomFromText(coordinates_wkt)) AS latitude, Y(GeomFromText(coordinates_wkt)) AS longitude, SQRT( POW(69.1 * (X(GeomFromText(coordinates_wkt)) - :lat), 2) + POW(69.1 * (:lon - Y(GeomFromText(coordinates_wkt))) * COS(X(GeomFromText(coordinates_wkt)) / 57.3), 2)) AS distance FROM cities HAVING distance < :max ORDER BY distance ASC LIMIT '.$limit);

        $q->bindValue('lat', $lat);
        $q->bindValue('lon', $lon);
        $q->bindValue('max', $max);

        $q->execute();

        $resp = array(
            'status' => 'success',
            'reason' => 'completed action',
            'payload' => $q->fetchAll()
        );

        return new JsonResponse($resp);

    }

    public function findNearLakeAction(Request $request) {

        $session = $request->request->get('session');
        $lat = $request->request->get('lat');
        $lon = $request->request->get('lon');
        $limit = $request->request->get('limit');
        $max = $request->request->get('max');

        // $lat = '-112.01363529772982';
        // $lon = '33.43586076394977';
        // $limit = 30;
        // $max = 2500;

        $app = $this->getDoctrine()->getRepository('YupItsZacFreeGeoBundle:Session')->findOneBy(array('session' => $session));

        if($app === null) {

            $resp = array(
                'status' => 'fatal',
                'reason' => 'unauthorized',
                'message' => 'Invalid session key '.$session.'. Please authenticate your app. More info at freegeo.yupitszac.com/docs/authenticate'
            );

            return new JsonResponse($resp);
        }

        $em = $this->getDoctrine()->getEntityManager();

        $con = $em->getConnection();

        $q = $con->prepare('SELECT name, name_alt, dam_name, X(GeomFromText(coordinates_wkt)) AS latitude, Y(GeomFromText(coordinates_wkt)) AS longitude, SQRT( POW(69.1 * (X(GeomFromText(coordinates_wkt)) - :lat), 2) + POW(69.1 * (:lon - Y(GeomFromText(coordinates_wkt))) * COS(X(GeomFromText(coordinates_wkt)) / 57.3), 2)) AS distance FROM lakes HAVING distance < :max ORDER BY distance ASC LIMIT '.$limit);

        $q->bindValue('lat', $lat);
        $q->bindValue('lon', $lon);
        $q->bindValue('max', $max);
        // $q->bindValue('lim', $limit);

        $q->execute();

        $resp = array(
            'status' => 'success',
            'reason' => 'completed action',
            'payload' => $q->fetchAll()
        );

        return new JsonResponse($resp);

    }

    public function findNearPortAction(Request $request) {

        $session = $request->request->get('session');
        $lat = $request->request->get('lat');
        $lon = $request->request->get('lon');
        $limit = $request->request->get('limit');
        $max = $request->request->get('max');

        // $lat = '-112.01363529772982';
        // $lon = '33.43586076394977';
        // $limit = 30;
        // $max = 2500;

        $app = $this->getDoctrine()->getRepository('YupItsZacFreeGeoBundle:Session')->findOneBy(array('session' => $session));

        if($app === null) {

            $resp = array(
                'status' => 'fatal',
                'reason' => 'unauthorized',
                'message' => 'Invalid session key '.$session.'. Please authenticate your app. More info at freegeo.yupitszac.com/docs/authenticate'
            );

            return new JsonResponse($resp);
        }

//        $this->verifyAppSession($session);

        $em = $this->getDoctrine()->getEntityManager();

        $con = $em->getConnection();

        $q = $con->prepare('SELECT name, X(GeomFromText(coordinates_wkt)) AS latitude, Y(GeomFromText(coordinates_wkt)) AS longitude, SQRT( POW(69.1 * (X(GeomFromText(coordinates_wkt)) - :lat), 2) + POW(69.1 * (:lon - Y(GeomFromText(coordinates_wkt))) * COS(X(GeomFromText(coordinates_wkt)) / 57.3), 2)) AS distance FROM ports HAVING distance < :max ORDER BY distance ASC LIMIT '.$limit);

        $q->bindValue('lat', $lat);
        $q->bindValue('lon', $lon);
        $q->bindValue('max', $max);
        // $q->bindValue('lim', $limit);

        $q->execute();

        $resp = array(
            'status' => 'success',
            'reason' => 'completed action',
            'payload' => $q->fetchAll()
        );

        return new JsonResponse($resp);

    }

    public function timeZoneAction(Request $request) {

        $session = $request->request->get('session');
        $lat = $request->request->get('lat');
        $lon = $request->request->get('lon');

        if($this->verifyAppSession($session) === false) {

            die('false');
        }

        $em = $this->getDoctrine()->getEntityManager();

        $con = $em->getConnection();

        $q = $con->prepare('SELECT name, offset, places, dst_places, MBRContains(GeomFromText(coordinates_wkt), POINT(":lat", ":lon")) AS contain FROM time_zones HAVING contain > 0 ORDER BY contain DESC LIMIT 1');
        $q->bindValue('lat', $lat);
        $q->bindValue('lon', $lon);

        $q->execute();

        $resp = array(
            'status' => 'success',
            'reason' => 'completed action',
            'payload' => $q->fetchAll()
        );

        return new JsonResponse($resp);
    }

    public function detectCountryAction(Request $request) {

        $session = $request->request->get('session');
        $lat = $request->request->get('lat');
        $lon = $request->request->get('lon');

        $app = $this->getDoctrine()->getRepository('YupItsZacFreeGeoBundle:Session')->findOneBy(array('session' => $session));

        if($app === null) {

            $resp = array(
                'status' => 'fatal',
                'reason' => 'unauthorized',
                'message' => 'Invalid session key '.$session.'. Please authenticate your app. More info at freegeo.yupitszac.com/docs/authenticate'
            );

            return new JsonResponse($resp);
        }

        $em = $this->getDoctrine()->getEntityManager();

        $con = $em->getConnection();
 
        $q = $con->prepare('SELECT name AS country, sovereign, formal, economy_level AS economy, income_level AS income, MBRContains(GeomFromText(coordinates_wkt), POINT(":lat", ":lon")) AS contain FROM countries HAVING contain > 0 ORDER BY contain DESC LIMIT 1');
        $q->bindValue('lat', $lat);
        $q->bindValue('lon', $lon);

        $q->execute();

        $resp = array(
            'status' => 'success',
            'reason' => 'completed action',
            'alert' => 'This section of the API is NOT fully developed and is not yet production ready. Not all country boundaries have been added. Contribute at github.com/delight-im/FreeGeoDB',
            'payload' => $q->fetchAll()
        );

        return new JsonResponse($resp);

    }

    public function calculateDistanceAction(Request $request) {

        $session = $request->request->get('session');
        $lata = $request->request->get('lata');
        $lona = $request->request->get('lona');

        $latb = $request->request->get('latb');
        $lonb = $request->request->get('lonb');

        $metric = $request->request->get('metric');

        $round = $request->request->get('round');

        $app = $this->getDoctrine()->getRepository('YupItsZacFreeGeoBundle:Session')->findOneBy(array('session' => $session));

        if($app === null) {

            $resp = array(
                'status' => 'fatal',
                'reason' => 'unauthorized',
                'message' => 'Invalid session key '.$session.'. Please authenticate your app. More info at freegeo.yupitszac.com/docs/authenticate'
            );

            return new JsonResponse($resp);
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

        $resp = array(
            'status' => 'success',
            'reason' => 'completed action',
            'note' => 'Please note, this distance calculation does not take into account roads.',
            'payload' => array('distance' => $final, 'metric' => strtoupper($metric), 'fullMetric' => $name)
        );

        return new JsonResponse($resp);
    }

    public function resetKeysAction(Request $request) {

        $session = $request->request->get('session');

        $app = $this->getDoctrine()->getRepository('YupItsZacFreeGeoBundle:Session')->findOneBy(array('session' => $session));

        if($app === null) {

            $resp = array(
                'status' => 'fatal',
                'reason' => 'unauthorized',
                'message' => 'Invalid session key '.$session.'. Please authenticate your app. More info at freegeo.yupitszac.com/docs/authenticate'
            );

            return new JsonResponse($resp);
        }

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

        $body = 'Your app API credentials for <b>'.$title.'</b> have been successfully reset. Your new credentials are listed below.<br><br>If you have any questions or need any help, tweet <a href="http://twitter.com/FreeGeoAPI">@FreeGeoAPI</a> and <a href="http://twitter.com/YupItsZac">@YupItsZac</a>.<br><br><b>App Name:</b> '.$title.'<br><b>New Private:</b> '.$private.'<br><b>New Public:</b> '.$public.'<br><br><b>NOTE:</b> Any currently active session tokens for your app have been removed. You must re-authenticate with your new keyset before making any other requests.';

        $config = array();
        $config['api_key'] = "key-46cb002424c2a5f4dc6c4c18b6a07303";
        $config['api_url'] = "https://api.mailgun.net/v3/yupitszac.com/messages";
     
        $message = array();
        $message['from'] = "Zac @ FreeGeo API <me@yupitszac.com>";
        $message['to'] = $email;
        $message['h:Reply-To'] = "me@yupitszac.com";
        $message['subject'] = "New FreeGeo API Keys";
        $message['html'] = $this->renderView('YupItsZacFreeGeoBundle:Email:email.standard.html.twig', array('fname' => $fname, 'emailHeader' => 'Your New App API Keys', 'emailBody' => $body));
     
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $config['api_url']);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "api:{$config['api_key']}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_POST, true); 
        curl_setopt($ch, CURLOPT_POSTFIELDS,$message);
     
        $result = curl_exec($ch);
     
        curl_close($ch);

        $resp = array(
            'status' => 'success',
            'reason' =>  'completed action',
            'message' => 'The API keys have been reset for the '.$title.' app. We have sent an email to the address registered with your app containing your new API credentials.'
        );

        return new JsonResponse($resp);

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

    private function jsonBuilder($status, $reason, $message, $payload) {

        if($status == 'fatal') {

            $resp = array(
                'status' => 'fatal',
                'reason' => $reason,
                'message' => $message
            );

            return new JsonResponse($resp);

        } else {

            echo 'done';

            $resp = array(
                'status' => $status,
                'reason' => $reason,
                'message' => $message,
                'payload' => $payload
            );

            return new JsonResponse($resp);
        }
    }
}


// SET @startlat = '-112.01363529772982';
// SET @startlng = '33.43586076394977';
// SET @max = '2500';
// SET @limit = '20';

// SET @lat = '-73.03392986299986'

// SET @lon = '21.15673149000004'


// SELECT name, name_alt, is_capital AS capital, region, time_zone, X(GeomFromText(coordinates_wkt)) AS latitude, Y(GeomFromText(coordinates_wkt)) AS longitude, SQRT(
//     POW(69.1 * (X(GeomFromText(coordinates_wkt)) - @startlat), 2) +
//     POW(69.1 * (@startlng - Y(GeomFromText(coordinates_wkt))) * COS(X(GeomFromText(coordinates_wkt)) / 57.3), 2)) AS distance
// FROM cities HAVING distance < @max ORDER BY distance

//SELECT * FROM time_zones WHERE ST_CONTAINS(polygons.geom, POINT('-142.46507296504296', '74.90013915357542'))