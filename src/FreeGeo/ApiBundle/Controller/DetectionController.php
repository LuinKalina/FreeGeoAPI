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

class DetectionController extends Controller {

    /**
     * Detect timezone for coord
     * @author zbrown
     *
     * @param Request $requestObject
     */
    public function timezoneAction(Request $requestObject) {

        $detectionModel = $this->get('detection_model');
        $apiRequest = $detectionModel->prepareRequestData($requestObject, 'detectTimezone');

        if(!$apiRequest instanceOf ApiRequestObject) {
            return new JsonResponse(ResponseHelper::prepareResponse($apiRequest['status'], $apiRequest['reason'], $apiRequest['msg']));
        }

        $dataTimezone = $detectionModel->detectTimezone($apiRequest);

        return new JsonResponse(ResponseHelper::prepareResponse(Strings::API_STATUS_SUCCESS, Strings::API_REASON_SUCCESS, Strings::API_MSG_SUCCESS, $dataTimezone));

    }

    public function countryAction(Request $requestObject){

        $detectionModel = $this->get('detection_model');
        $apiRequest = $detectionModel->prepareRequestData($requestObject, 'detectTimezone');

        if(!$apiRequest instanceOf ApiRequestObject) {
            return new JsonResponse(ResponseHelper::prepareResponse($apiRequest['status'], $apiRequest['reason'], $apiRequest['msg']));
        }

        $dataCountry = $detectionModel->detectCountry($apiRequest);

        return new JsonResponse(ResponseHelper::prepareResponse(Strings::API_STATUS_SUCCESS, Strings::API_REASON_SUCCESS, Strings::API_MSG_SUCCESS, $dataCountry));

    }

}
