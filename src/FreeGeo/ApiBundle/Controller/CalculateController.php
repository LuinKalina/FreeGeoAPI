<?php

namespace YupItsZac\FreeGeoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use YupItsZac\FreeGeoBundle\Api\Model\CalculateModel;
use YupItsZac\FreeGeoBundle\Entity\Strings;
use YupItsZac\FreeGeoBundle\Helpers\ResponseHelper;
use YupItsZac\FreeGeoBundle\Entity\ApiRequestObject;

class CalculateController extends Controller {

    /**
     * Detect timezone for coord
     * @author zbrown
     *
     * @param Request $requestObject
     */
    public function distanceAction(Request $requestObject) {

        $calculateModel = new CalculateModel();
        $apiRequest = $calculateModel->prepareRequestData($requestObject, 'detectTimezone');

        if(!$apiRequest instanceOf ApiRequestObject) {
            ResponseHelper::prepareResponse($apiRequest['status'], $apiRequest['reason'], $apiRequest['msg']);
        }

        $dataTimezone = $calculateModel->calculateDistance($apiRequest);

        ResponseHelper::prepareResponse(Strings::API_STATUS_SUCCESS, Strings::API_REASON_SUCCESS, Strings::API_MSG_SUCCESS, $dataTimezone);

    }

}
