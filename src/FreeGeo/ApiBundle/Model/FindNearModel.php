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

class FindNearModel extends Controller {

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
     * Locate nearby airports
     * @author zbrown
     *
     * @param ApiRequestObject $apiRequest
     * @return bool
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getNearbyAirports(ApiRequestObject $apiRequest)
    {

        $latitude = $apiRequest->getLatitude();
        $longitude = $apiRequest->getLongitude();
        $limit = $apiRequest->getLimit();
        $max = $apiRequest->getMaximum();

        $qb = $this->getDoctrine()->getEntityManager()->getConnection();
        $q = $qb->prepare('SELECT name, type, icao_code, iata_code, X(GeomFromText(coordinates_wkt)) AS latitude, Y(GeomFromText(coordinates_wkt)) AS longitude, SQRT( POW(69.1 * (X(GeomFromText(coordinates_wkt)) - :lat), 2) + POW(69.1 * (:lon - Y(GeomFromText(coordinates_wkt))) * COS(X(GeomFromText(coordinates_wkt)) / 57.3), 2)) AS distance FROM airports HAVING distance < :max ORDER BY distance ASC LIMIT ' . $limit);

        $q->bindValue('lat', $latitude);
        $q->bindValue('lon', $longitude);
        $q->bindValue('max', $max);

        $q->execute();
        $result = $q->fetchAll();

        if(empty($result)) {
            return 'No airports found near given coordinate set.';
        } else {
            return $result;
        }
    }

    /**
     * Get nearby cities
     * @author zbrown
     *
     * @param ApiRequestObject $apiRequest
     * @return bool
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getNearbyCities(ApiRequestObject $apiRequest)
    {

        $qb = $this->getDoctrine()->getEntityManager()->getConnection();
        $q = $qb->prepare('SELECT name, name_alt, is_capital AS capital, region, time_zone, X(GeomFromText(coordinates_wkt)) AS latitude, Y(GeomFromText(coordinates_wkt)) AS longitude, SQRT( POW(69.1 * (X(GeomFromText(coordinates_wkt)) - :lat), 2) + POW(69.1 * (:lon - Y(GeomFromText(coordinates_wkt))) * COS(X(GeomFromText(coordinates_wkt)) / 57.3), 2)) AS distance FROM cities HAVING distance < :max ORDER BY distance ASC LIMIT ' . $apiRequest->getLimit());

        $q->bindValue('lat', $apiRequest->getLatitude());
        $q->bindValue('lon', $apiRequest->getLongitude());
        $q->bindValue('max', $apiRequest->getMaximum());

        $q->execute();
        $result = $q->fetchAll();

        if(empty($result)) {
            return 'No cities found near coordinate set.';
        } else {
            return $result;
        }
    }

    /**
     * Get nearby lakes
     * @author zbrown
     *
     * @param ApiRequestObject $apiRequest
     * @return bool
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getNearbyLakes(ApiRequestObject $apiRequest)
    {

        $qb = $this->getDoctrine()->getEntityManager()->getConnection();
        $q = $qb->prepare('SELECT name, name_alt, dam_name, X(GeomFromText(coordinates_wkt)) AS latitude, Y(GeomFromText(coordinates_wkt)) AS longitude, SQRT( POW(69.1 * (X(GeomFromText(coordinates_wkt)) - :lat), 2) + POW(69.1 * (:lon - Y(GeomFromText(coordinates_wkt))) * COS(X(GeomFromText(coordinates_wkt)) / 57.3), 2)) AS distance FROM lakes HAVING distance < :max ORDER BY distance ASC LIMIT ' . $apiRequest->getLimit());

        $q->bindValue('lat', $apiRequest->getLatitude());
        $q->bindValue('lon', $apiRequest->getLongitude());
        $q->bindValue('max', $apiRequest->getMaximum());

        $q->execute();
        $result = $q->fetchAll();

        if(empty($result)) {
            return 'No lakes found near coordinate set.';
        } else {
            return $result;
        }
    }

    /**
     * Get nearby Ports
     * @author zbrown
     *
     * @param ApiRequestObject $apiRequest
     * @return bool
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getNearbyPorts(ApiRequestObject $apiRequest)
    {

        $qb = $this->getDoctrine()->getEntityManager()->getConnection();

        $q = $qb->prepare('SELECT name, X(GeomFromText(coordinates_wkt)) AS latitude, Y(GeomFromText(coordinates_wkt)) AS longitude, SQRT( POW(69.1 * (X(GeomFromText(coordinates_wkt)) - :lat), 2) + POW(69.1 * (:lon - Y(GeomFromText(coordinates_wkt))) * COS(X(GeomFromText(coordinates_wkt)) / 57.3), 2)) AS distance FROM ports HAVING distance < :max ORDER BY distance ASC LIMIT ' . $apiRequest->getLimit());

        $q->bindValue('lat', $apiRequest->getLatitude());
        $q->bindValue('lon', $apiRequest->getLongitude());
        $q->bindValue('max', $apiRequest->getMaximum());

        $q->execute();
        $result = $q->fetchAll();

        if(empty($result)) {
            return 'No ports found near coordinate set.';
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
        } elseif ($requestValidation === false) {
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