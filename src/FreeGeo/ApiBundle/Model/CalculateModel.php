<?php

namespace YupItsZac\FreeGeoBundle\Api\Model;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Yaml\Yaml;
use YupItsZac\FreeGeoBundle\Entity\Config;
use YupItsZac\FreeGeoBundle\Entity\ApiRequestObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use YupItsZac\FreeGeoBundle\Entity\Strings;

class CalculateModel extends Controller {

    /**
     * Calculate distance between two coord sets
     * @author zbrown
     *
     * @param ApiRequestObject $apiRequest
     * @return array
     */
    public function calculateDistance(ApiRequestObject $apiRequest) {

        $primaryLatitude = $apiRequest->getLatitude();
        $primaryLongitude = $apiRequest->getLongitude();

        $secondaryLatitude = $apiRequest->getSecondaryLatitude();
        $secondaryLongitude = $apiRequest->getSecondaryLongitude();

        $metricUnit = $apiRequest->getMetricUnit();
        $roundMath = $apiRequest->getRoundMath();

        $payload = array();
        $theta = $primaryLongitude - $secondaryLongitude;
        $dist = sin(deg2rad($primaryLatitude)) * sin(deg2rad($secondaryLatitude)) +  cos(deg2rad($primaryLatitude)) * cos(deg2rad($secondaryLatitude)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtolower($metricUnit);

        if($unit == 'k') {
            $distance = $miles * 1.609344;
            $payload['unitName'] = 'kilometers';
        } else if($unit == 'n') {
            $distance = $miles * 0.8684;
            $payload['unitName'] = 'nautical miles';
        } else {
            $distance = $miles;
            $payload['unitName'] = 'miles';
        }

        if(!empty($roundMath)) {
            $final = round($distance, $roundMath);
        } else {
            $final = $distance;
        }

        $payload = [
            'distance' => $final,
            'metric' => strtoupper($apiRequest->getMetricUnit())
        ];

        return $payload;
    }

    /**
     * Validate ApiRequest
     * @author zbrown
     *
     * @param ApiRequestObject $apiRequest
     * @return bool|string
     */
    public function validateRequest(ApiRequestObject $apiRequest, $endpoint) {

        if($apiRequest->getSessionToken() == Config::API_STATUS_CHECK_SESSION_TOKEN || $apiRequest->getPublicKey() == Config::API_STATUS_CHECK_PUBLIC_KEY) {
            return 'status';
        }

        $requiredConfig = Yaml::parse(file_get_contents($this->get('kernel')->locateResource(Config::API_REQUIRED_PARAMS_CONFIG)));
        $requiredParameters = $requiredConfig['required']['api'][$endpoint];

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

        $app = $this->getDoctrine()->getRepository('YupItsZacFreeGeoBundle:Session')->findOneBy(array('session' => $apiRequest->getSessionToken()));

        return (is_null($app))? false : true;

    }

    public function prepareRequestData(Request $requestObject, $endpoint) {
        $postData  = $requestObject->get('request')->request->all();

        $apiRequest = new ApiRequestObject();
        $apiRequest->setAttributesByArray($postData);

        $postData  = $this->get('request')->request->all();

        $apiRequest = new ApiRequestObject();
        $apiRequest->setAttributesByArray($postData);

        $requestValidation = $this->validateRequest($apiRequest, $endpoint);

        if($requestValidation == false) {
            return [
                'status' => Strings::API_STATUS_FATAL,
                'reason' => Strings::API_REASON_INVALID_SESSION,
                'msg' => Strings::API_MSG_INVALID_SESSION
            ];

        } elseif($requestValidation == 'status') {
            return [
                'status' => Strings::API_STATUS_SUCCESS,
                'reason' => Strings::API_REASON_SUCCESS,
                'msg' => Strings::API_MSG_STATUS_ONLINE
            ];

        } elseif($requestValidation == 'invalid') {
            return [
                'status' => Strings::API_STATUS_FATAL,
                'reason' => Strings::API_REASON_MISSING_PARAMS,
                'msg' => Strings::API_MSG_MISSING_PARAMS
            ];

        } else {
            return $apiRequest;
        }
    }


}