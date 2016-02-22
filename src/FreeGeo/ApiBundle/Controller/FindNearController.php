<?php

namespace FreeGeo\ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FreeGeo\ApiBundle\Model\FindNearModel;
use YupItsZac\FreeGeoBundle\Entity\Session;
use YupItsZac\FreeGeoBundle\Entity\Config;
use YupItsZac\FreeGeoBundle\Entity\Strings;
use \DateTime;
use YupItsZac\FreeGeoBundle\Helpers\ResponseHelper;
use YupItsZac\FreeGeoBundle\Helpers\DataHelper;
use YupItsZac\FreeGeoBundle\Entity\ApiRequestObject;
use Symfony\Component\Yaml\Yaml;

class FindNearController extends Controller {

    /**
     * Fetch airport data within constraints
     * @author zbrown
     *
     * @param Request $requestObject
     * @return JsonResponse
     */
    public function airportsAction(Request $requestObject) {

        $findNearModel = $this->get('FindNearModel');
        $apiRequest = $findNearModel->prepareRequestData($requestObject, 'findNearAirports');

        if(!$apiRequest instanceof ApiRequestObject) {
            return new JsonResponse(ResponseHelper::prepareResponse($apiRequest['status'], $apiRequest['reason'], $apiRequest['msg']));
        }

        $airportsList = $findNearModel->getNearbyAirports($apiRequest);

        return new JsonResponse(ResponseHelper::prepareResponse(Strings::API_STATUS_SUCCESS, Strings::API_REASON_SUCCESS, Strings::API_MSG_SUCCESS, $airportsList));

    }

    /**
     * Get nearby cities
     * @author zbrown
     *
     * @param Request $requestObject
     * @return JsonResponse
     */
    public function citiesAction(Request $requestObject) {

        $findNearModel = $this->get('FindNearModel');
        $apiRequest = $findNearModel->prepareRequestData($requestObject, 'findNearCities');

        if(!$apiRequest instanceOf ApiRequestObject) {
            return new JsonResponse(ResponseHelper::prepareResponse($apiRequest['status'], $apiRequest['reason'], $apiRequest['msg']));
        }

        $dataCities = $findNearModel->getNearbyCities($apiRequest);

        return new JsonResponse(ResponseHelper::prepareResponse(Strings::API_STATUS_SUCCESS, Strings::API_REASON_SUCCESS, Strings::API_MSG_SUCCESS, $dataCities));

    }

    /**
     * Get nearby lakes
     * @author zbrown
     *
     * @param Request $requestObject
     * @return JsonResponse
     */
    public function lakesAction(Request $requestObject) {

        $findNearModel = $this->get('FindNearModel');
        $apiRequest = $findNearModel->prepareRequestData($requestObject, 'findNearLakes');

        if(!$apiRequest instanceOf ApiRequestObject) {
            ResponseHelper::prepareResponse($apiRequest['status'], $apiRequest['reason'], $apiRequest['msg']);
        }

        $dataLakes = $findNearModel->getNearbyLakes($apiRequest);

        return new JsonResponse(ResponseHelper::prepareResponse(Strings::API_STATUS_SUCCESS, Strings::API_REASON_SUCCESS, Strings::API_MSG_SUCCESS, $dataLakes));

    }

    /**
     * Get nearby ports
     * @author zbrown
     *
     * @param Request $requestObject
     * @return JsonResponse
     */
    public function portsAction(Request $requestObject) {

        $findNearModel = $this->get('FindNearModel');
        $apiRequest = $findNearModel->prepareRequestData($requestObject, 'findNearPorts');

        if(!$apiRequest instanceOf ApiRequestObject) {
            new JsonResponse(ResponseHelper::prepareResponse($apiRequest['status'], $apiRequest['reason'], $apiRequest['msg']));
        }

        $dataPorts = $findNearModel->getNearbyPorts($apiRequest);

        return new JsonResponse(ResponseHelper::prepareResponse(Strings::API_STATUS_SUCCESS, Strings::API_REASON_SUCCESS, Strings::API_MSG_SUCCESS, $dataPorts));

    }



}
