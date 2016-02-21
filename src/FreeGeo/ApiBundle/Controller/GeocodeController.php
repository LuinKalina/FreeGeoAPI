<?php

namespace YupItsZac\FreeGeoBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use YupItsZac\FreeGeoBundle\Api\Model\FindNearModel;
use YupItsZac\FreeGeoBundle\Entity\Session;
use YupItsZac\FreeGeoBundle\Entity\Config;
use YupItsZac\FreeGeoBundle\Entity\Strings;
use \DateTime;
use YupItsZac\FreeGeoBundle\Helpers\ResponseHelper;
use YupItsZac\FreeGeoBundle\Helpers\DataHelper;
use YupItsZac\FreeGeoBundle\Entity\ApiRequestObject;
use FreeGeo\ApiBundle\Model\GeocodeModel;

class GeocodeController extends Controller {

    /**
     * Reverse geocode coords
     * @auhtor zbrown
     *
     * @param Request $requestObject
     */
    public function reverseAction(Request $requestObject) {

        $geocodeModel = new GeocodeModel();
        $apiRequest = $geocodeModel->prepareRequestData($requestObject, 'reverseGeocode');

        if(!$apiRequest instanceOf ApiRequestObject) {
            ResponseHelper::prepareResponse($apiRequest['status'], $apiRequest['reason'], $apiRequest['msg']);
        }

        $dataReverse = $geocodeModel->reverseGeocode($apiRequest);

        ResponseHelper::prepareResponse(Strings::API_STATUS_SUCCESS, Strings::API_REASON_SUCCESS, Strings::API_MSG_SUCCESS, $dataReverse);
    }

}
