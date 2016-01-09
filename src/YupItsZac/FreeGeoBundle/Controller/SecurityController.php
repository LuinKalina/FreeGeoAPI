<?php
/**
 * Created by PhpStorm.
 * User: zbrown
 * Date: 27.12.15
 * Time: 17:43
 */

namespace YupItsZac\FreeGeoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class SecurityController extends Controller {

    public function loginAction(Request $request) {

        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('YupItsZacFreeGeoBundle:Login:login.html.twig',
            array(
                'last_username' => $lastUsername,
                'error'         => $error,
            )
        );

    }

    public function loginCheckAction(Request $request) {

    }

    public function logoutAction() {

        $this->get('security.token_storage')->setToken(null);
        $this->get('request')->getSession()->invalidate();

        return $this->render('YupItsZacFreeGeoBundle:Login:login.html.twig');
    }

}