<?php

namespace YupItsZac\FreeGeoBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use YupItsZac\FreeGeoBundle\Entity\Users;
use Symfony\Component\HttpFoundation\Response;
use YupItsZac\FreeGeoBundle\Entity\Config;
use YupItsZac\FreeGeoBundle\Entity\Strings;
use YupItsZac\FreeGeoBundle\Form\RegistrationType;
use Symfony\Component\Security\Core\User\User;


class AuthenticateController extends Controller {

    public function loginAction($pageTitle) {

        return $this->render('YupItsZacFreeGeoBundle:Authenticate:login.html.twig', array(
            'projectName' => Config::PROJECT_NAME,
            'title' => $pageTitle
        ));
    }

    public function registerAction($pageTitle) {

        $registration = new Users();

        $form = $this->createForm(new RegistrationType(), $registration, ['action' => $this->generateUrl('free_geo_user_register_process'), 'method' => 'POST']);

        return $this->render('YupItsZacFreeGeoBundle:Authenticate:register.html.twig', ['form' => $form->createView(), 'projectName' => Config::PROJECT_NAME, 'title' => $pageTitle]);

    }

    public function processRegistrationAction(Request $req) {

        $em   = $this->getDoctrine()->getManager();
        $form = $this->createForm(new RegistrationType(), new Users());
        $form->handleRequest($req);

        $user = new Users();
        $user = $form->getData();

        $pwd = $user->getPassword();

        $factory = $this->get('security.encoder_factory');
        $encoder = $factory->getEncoder($user);
        $pwd = $encoder->encodePassword($user, $pwd);
        $user->setPassword($pwd);
        $user->setStatus(1);
        $user->setAdmin(0);
        $user->setHash(md5(time().time()));

        $em->persist($user);
        $em->flush();

        $url = $this->generateUrl('free_geo_user_login');
        return $this->redirect($url);
    }

    public function indexAction() {

        return $this->render('YupItsZacFreeGeoBundle:Web:index.html.twig');
    }
}
