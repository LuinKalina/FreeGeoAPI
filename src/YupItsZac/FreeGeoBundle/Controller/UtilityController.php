<?php

namespace YupItsZac\FreeGeoBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UtilityController extends Controller {

    public function generateAction() {

        $public = md5(time().time());
        $secret = md5(time().'secret'.time());

        $resp = array(
            'public' => $public,
            'secret' => $secret
        );

        return new JsonResponse($resp);
    }

    public function findNearAction() {



    }
}
