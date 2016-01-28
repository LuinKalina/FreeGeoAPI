<?php
/**
 * Created by PhpStorm.
 * User: zbrown
 * Date: 27.12.15
 * Time: 18:43
 */

namespace YupItsZac\FreeGeoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use YupItsZac\FreeGeoBundle\Form\UsersType;
use YupItsZac\FreeGeoBundle\Entity\Users;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use YupItsZac\FreeGeoBundle\Entity\Config;
use YupItsZac\FreeGeoBundle\Entity\Strings;
use Symfony\Component\Validator\Constraints as Assert;


class RegistrationController extends Controller {

    public function registerAction(Request $request, $pageTitle) {

        $user = new Users();
        $form = $this->createForm(new UsersType(), $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setStatus(1);
            $user->setRoles('ROLE_USER');

            $emailConstraint = new Assert\Email();
            $emailConstraint->message = 'Invalid email address';

            $contactEmail = $user->getEmail();
            $contactFirstName = $user->getFirstname();

            $errorList = $this->get('validator')->validate(
                $contactEmail,
                $emailConstraint
            );

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $message = array();
            $message['from'] = 'FreeGeo API <'.Config::FROM_EMAIL_ADDRESS.'>';
            $message['to'] = $contactEmail;
            $message['subject'] = Strings::REGISTRATION_EMAIL_SUBJECT_WELCOME;
            $message['html'] = $this->renderView('YupItsZacFreeGeoBundle:Email:registration.completed.html.twig', array('firstName' => $contactFirstName, 'emailHeader' => Strings::REGISTRATION_EMAIL_MSG_WELCOME, 'projectName' => Config::PROJECT_NAME, 'githubUrl' => Config::GITHUB_MAIN_REPO, 'twitterUrl' => Strings::TWITTER_URL, 'twitterUser' => Strings::TWITTER_USER, 'baseUrl' => Config::BASE_URL_PROD));

            $this->sendMailWithMailgun($message);

            return $this->redirectToRoute('free_geo_registration_completed');
        }

        return $this->render('YupItsZacFreeGeoBundle:Registration:register.html.twig', array(
                'form' => $form->createView(),
                'title' => $pageTitle
            )
        );

    }

    public function completeAction($pageTitle) {

        return $this->render('YupItsZacFreeGeoBundle:Registration:registration.completed.html.twig', array('title' => $pageTitle));
    }

    private function sendMailWithMailgun($messageArray) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, Config::MAILGUN_API_URL);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, 'api:'.Config::MAILGUN_API_KEY);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$messageArray);

        $result = curl_exec($ch);

        curl_close($ch);
    }
}
