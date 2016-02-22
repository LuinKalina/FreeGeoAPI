<?php

namespace FreeGeo\ApiBundle\Model;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use YupItsZac\FreeGeoBundle\Entity\Apps;
use YupItsZac\FreeGeoBundle\Entity\Session;
use YupItsZac\FreeGeoBundle\Entity\Config;
use YupItsZac\FreeGeoBundle\Entity\ApiRequestObject;
use \DateTime;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\HttpFoundation\Request;
use YupItsZac\FreeGeoBundle\Entity\Strings;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Query;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ApiAuthModel extends Controller {

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @author zbrown
     *
     * ApiAuthModel constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    /**
     * Verify that all required parameters are present
     * @author zbrown
     *
     * @param ApiRequestObject $apiRequest
     * @return bool
     */
    public function verifyRequiredParameters(ApiRequestObject $apiRequest) {

        if(empty($apiRequest->getPrivateKey()) || empty($apiRequest->getPublicKey())) {
            return false;
        } else {
            return true;
        }

    }

    /**
     * Determine session type
     * @author zbrown
     *
     * @param ApiRequestObject $apiRequest
     * @return string
     */
    public function getSessionType(ApiRequestObject $apiRequest) {

        //2 = Normal API requests, 1 = API Status Check

        if($apiRequest->getSessionToken() == Config::API_STATUS_CHECK_SESSION_TOKEN || $apiRequest->getPublicKey() == Config::API_STATUS_CHECK_PUBLIC_KEY) {
            return '1';
        } else {
            return '2';
        }
    }

    public function getApplicationObject(ApiRequestObject $apiRequest) {

        error_log('Getting app session');
        return $this->getDoctrine()->getRepository('YupItsZacFreeGeoBundle:Apps')->findOneBy(array('publickey' => $apiRequest->getPublicKey()));

    }


    /**
     * Persist new app session to DB
     * @author zbrown
     *
     * @param ApiRequestObject $apiRequest
     * @param $applicationId
     * @return string
     */
    public function persistNewAppSession(ApiRequestObject $apiRequest, $applicationId) {

        $publicKey = $apiRequest->getPublicKey();
        $privateKey = $apiRequest->getPrivateKey();

        $sessionToken = $this->generateSessionToken($applicationId);

        $session = new Session();
        $session->setSession($sessionToken);
        $session->setPublic($publicKey);
        $session->setSecret($privateKey);
        $session->setAppid($applicationId);
        $now = new DateTime('now');
        $session->setTimestamp($now);
        $this->getDoctrine()->getEntityManager()->persist($session);
        $this->getDoctrine()->getEntityManager()->flush();

        return $sessionToken;

    }

    private function generateSessionToken($applicationId) {

        return md5(time().$applicationId.time());

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

            if ($value == 1) {
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