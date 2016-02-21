<?php

namespace YupItsZac\FreeGeoBundle\Helpers;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use YupItsZac\FreeGeoBundle\Entity\Session;
use YupItsZac\FreeGeoBundle\Entity\Config;
use YupItsZac\FreeGeoBundle\Entity\Strings;
use \DateTime;

class ResponseHelper extends Controller {

    static public function prepareResponse($status, $reason, $msg = null, $payload = null) {

        $response = array(
            'status' => $status,
            'reason' => $reason,
            'message' => $msg,
            'payload' => $payload
        );

        return $response;
    }
}
