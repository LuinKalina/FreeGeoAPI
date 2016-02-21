<?php

namespace FreeGeo\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use YupItsZac\FreeGeoBundle\Entity\Strings;
use YupItsZac\FreeGeoBundle\Helpers\ResponseHelper;
use YupItsZac\FreeGeoBundle\Entity\ApiRequestObject;
use FreeGeo\ApiBundle\Model\ApiAuthModel;

class ApiAuthController extends Controller {

    /**
     * Create new API session token
     * @author zbrown
     *
     * @param Request $requestObject
     * @return JsonResponse
     */
    public function sessionAction(Request $requestObject) {

        $authModel = $this->get('authenticate_model');
        $apiRequest = $authModel->prepareRequestData($requestObject, 'authenticate');

        if(!$apiRequest instanceof ApiRequestObject) {
            return new JsonResponse(ResponseHelper::prepareResponse($apiRequest['status'], $apiRequest['reason'], $apiRequest['msg'], 'test'));
        }

        $applicationObject = $authModel->getApplicationObject($apiRequest);

        if(is_null($applicationObject)) {
            return new JsonResponse(ResponseHelper::prepareResponse(Strings::API_STATUS_FATAL, Strings::API_REASON_FORBIDDEN, Strings::API_MSG_MISSING_CREDENTIALS));
        }

        if($applicationObject->getAssoc() === null) {
            return new JsonResponse(ResponseHelper::prepareResponse(Strings::API_STATUS_FATAL, Strings::API_REASON_APP_CONVERSION, Strings::API_MSG_APP_CONVERSION_REQUIRED));
        }

        $appId = $applicationObject->getId();
        $appStatus = $applicationObject->getStatus();

        if($appStatus != 'Active') {

            return new JsonResponse(ResponseHelper::prepareResponse(Strings::API_STATUS_FATAL, Strings::API_REASON_FORBIDDEN, Strings::API_MSG_REVOKED_CREDENTIALS));
        }

        $sessionToken = $authModel->persistNewAppSession($apiRequest, $appId);

        $payload = array('session' => $sessionToken);

        return new JsonResponse(ResponseHelper::prepareResponse(Strings::API_STATUS_SUCCESS, Strings::API_REASON_SUCCESS, Strings::API_MSG_SUCCESS, $payload));

    }
}