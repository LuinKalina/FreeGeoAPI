<?php

namespace FreeGeo\ApiBundle\Model;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Yaml\Yaml;
use YupItsZac\FreeGeoBundle\Entity\Apps;
use YupItsZac\FreeGeoBundle\Entity\Session;
use YupItsZac\FreeGeoBundle\Entity\Config;
use YupItsZac\FreeGeoBundle\Entity\ApiRequestObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use YupItsZac\FreeGeoBundle\Entity\Strings;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DetectionModel extends Controller {

    /**
     * @var ContainerInterface
     */
    protected $container;


    /**
     * @author zbrown
     *
     * FindNearModel constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    /**
     * Detect timezone of coords
     * @author zbrown
     *
     * @param ApiRequestObject $apiRequest
     * @return bool
     * @throws \Doctrine\DBAL\DBALException
     */
    public function detectTimezone(ApiRequestObject $apiRequest) {

        $qb = $this->getDoctrine()->getEntityManager()->getConnection();
        $q = $qb->prepare('SELECT name, type, icao_code, iata_code, X(GeomFromText(coordinates_wkt)) AS latitude, Y(GeomFromText(coordinates_wkt)) AS longitude, SQRT( POW(69.1 * (X(GeomFromText(coordinates_wkt)) - :lat), 2) + POW(69.1 * (:lon - Y(GeomFromText(coordinates_wkt))) * COS(X(GeomFromText(coordinates_wkt)) / 57.3), 2)) AS distance FROM airports HAVING distance < :max ORDER BY distance ASC LIMIT '.$apiRequest->getLimit());

        $q->bindValue('lat', $apiRequest->getLatitude());
        $q->bindValue('lon', $apiRequest->getLongitude());
        $q->bindValue('max', $apiRequest->getMaximum());

        $q->execute();
        $result = $q->fetchAll();

        if(empty($result)) {
            return 'Unable to detect timezone.';
        } else {
            return $result;
        }
    }

    /**
     * Detect country of coords
     * @author zbrown
     *
     * @param ApiRequestObject $apiRequest
     * @return bool
     * @throws \Doctrine\DBAL\DBALException
     */
    public function detectCountry(ApiRequestObject $apiRequest) {

        $qb = $this->getDoctrine()->getEntityManager()->getConnection();
        $q = $qb->prepare('SELECT name AS country, sovereign, formal, economy_level AS economy, income_level AS income, MBRContains(GeomFromText(coordinates_wkt), POINT(":lat", ":lon")) AS contain FROM countries HAVING contain > 0 ORDER BY contain DESC LIMIT 1');
        $q->bindValue('lat', $apiRequest->getLatitude());
        $q->bindValue('lon', $apiRequest->getLongitude());

        $q->execute();
        $result = $q->fetchAll();

        if(empty($result)) {
            return 'Unable to detect country.';
        } else {
            return $result;
        }
    }

    /**
     * Validate ApiRequest
     * @author zbrown
     *
     * @param ApiRequestObject $apiRequest
     * @return bool|string
     */
    public function validateRequest(ApiRequestObject $apiRequest, $endpoint)
    {

        if ($apiRequest->getSessionToken() == Config::API_STATUS_CHECK_SESSION_TOKEN) {
            return 'status';
        }

        $requiredParamsConfig = Yaml::parse(file_get_contents($this->container->get('kernel')->locateResource(Config::API_REQUIRED_PARAMS_CONFIG)));

        $requiredParameters = $requiredParamsConfig['required']['api'][$endpoint];

        $validRequest = true;
        foreach($requiredParameters as $key => $value) {

            if ($value === true) {
                $action = 'get'.$key;
                $res = $apiRequest->{$action}();

                if (empty($res)) {
                    return 'invalid';
                    break;
                }
            }
        }

        $session = $this->getDoctrine()->getRepository('YupItsZacFreeGeoBundle:Session')->findOneBy(array('session' => $apiRequest->getSessionToken()));

        return (is_null($session)) ? false : true;

    }

    public function prepareRequestData(Request $requestObject, $endpoint)
    {

        $apiRequest = new ApiRequestObject();
        $apiRequest->setAttributesByArray($requestObject);

        $requestValidation = $this->validateRequest($apiRequest, $endpoint);

        if ($requestValidation === true) {
            return $apiRequest;
        }elseif ($requestValidation === false) {
            return [
                'status' => Strings::API_STATUS_FATAL,
                'reason' => Strings::API_REASON_INVALID_SESSION,
                'msg' => Strings::API_MSG_INVALID_SESSION
            ];

        } elseif ($requestValidation == 'status') {
            return [
                'status' => Strings::API_STATUS_SUCCESS,
                'reason' => Strings::API_REASON_SUCCESS,
                'msg' => Strings::API_MSG_STATUS_ONLINE
            ];

        } elseif ($requestValidation == 'invalid') {
            return [
                'status' => Strings::API_STATUS_FATAL,
                'reason' => Strings::API_REASON_MISSING_PARAMS,
                'msg' => Strings::API_MSG_MISSING_PARAMS
            ];

        }
    }

}