<?php

namespace YupItsZac\FreeGeoBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use YupItsZac\FreeGeoBundle\Entity\Apps;
use Symfony\Component\HttpFoundation\Response;
use YupItsZac\FreeGeoBundle\Entity\Config;
use YupItsZac\FreeGeoBundle\Entity\Strings;
use YupItsZac\FreeGeoBundle\Helpers\DataHelper;


class WebController extends Controller {

    private $dataHelper;

    public function __construct() {

        $this->dataHelper = new dataHelper();
    }

    public function staticRenderAction($dir, $action) {

        return $this->render('YupItsZacFreeGeoBundle:'.$dir.':'.$action.'.html.twig');
    }

    public function indexAction() {

        return $this->render('YupItsZacFreeGeoBundle:Web:index.html.twig');
    }

    public function statusCheckAction() {

        $serviceStatus = $this->dataHelper->checkStatusServices();

        if($this->dataHelper->checkForOfflineStatus($serviceStatus) == 'offline') {
            $serviceNote = Strings::API_MSG_SOME_SERVICES_OFFLINE;
        } else {
            $serviceNote = Strings::API_MSG_ALL_SERVICES_ONLINE;
        }

        return $this->render('YupItsZacFreeGeoBundle:Web:status.html.twig', array('servicesStatus' => $serviceStatus, 'serviceNote' => $serviceNote));

    }

    public function appRegisterAction(Request $request) {

        $app = new Apps();


        $form = $this->createFormBuilder($app)
            ->add('firstname', 'text', array('label' => 'First Name', 'required' => true))
            ->add('lastname', 'text', array('label' => 'Last Name'))
            ->add('email', 'email', array('label' => 'Email Address'))
            ->add('apptitle', 'text', array('label' => 'App Title'))
            ->add('appwebsite', 'url', array('label' => 'App Website'))
            ->add('appdescription', 'textarea', array('label' => 'App Description'))
            ->add('save', 'submit', array('label' => 'Register App'))
            ->getForm();

        $form->handleRequest($request);

        if($form->isValid()) {

            $fname = $form['firstname']->getData();
            $lname = $form['lastname']->getData();
            $email = $form['email']->getData();
            $title = $form['apptitle']->getData();
            $website = $form['appwebsite']->getData();
            $description = $form['appdescription']->getData();

            $pub = md5(time().$email.$title.time());

            $private = md5($fname.$email.time().time());

            $app = new Apps();
            $app->setFirstname($fname);
            $app->setLastname($lname);
            $app->setEmail($email);
            $app->setApptitle($title);
            $app->setAppwebsite($website);
            $app->setAppdescription($description);
            $app->setStatus('Active');
            $app->setPublickey($pub);
            $app->setSecretkey($private);

            $em = $this->getDoctrine()->getEntityManager();

            $em->persist($app);
            $em->flush();

            $message = array();
            $message['from'] = Config::PROJECT_NAME." <".Config::FROM_EMAIL_ADDRESS.">";
            $message['to'] = $email;
            $message['h:Reply-To'] = Config::FROM_EMAIL_ADDRESS;
            $message['subject'] = Strings::APP_REGISTER_EMAIL_SUBJECT;
            $message['html'] = $this->renderView('YupItsZacFreeGeoBundle:Email:registration.completed.html.twig', array('appTitle' => $title, 'privateKey' => $private, 'publicKey' => $pub, 'projectName' => Config::PROJECT_NAME, 'githubUrl' => Config::GITHUB_MAIN_REPO, 'twitterUrl' => Strings::TWITTER_URL, 'twitterUser' => Strings::TWITTER_USER, 'baseUrl' => Config::BASE_URL_PROD, 'fname' => $fname, 'emailHeader' => Strings::APP_REGISTER_EMAIL_SUBJECT));
         
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, Config::MAILGUN_API_URL);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, 'api:'.Config::MAILGUN_API_KEY);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_POST, true); 
            curl_setopt($ch, CURLOPT_POSTFIELDS,$message);
         
            $result = curl_exec($ch);
         
            curl_close($ch);
         
            return $this->render('YupItsZacFreeGeoBundle:Web:appregistered.html.twig', array('projectName' => Config::PROJECT_NAME, 'twitterUrl' => Strings::TWITTER_URL, 'twitterUser' => Strings::TWITTER_USER, 'baseUrl' => Config::BASE_URL_PROD, 'appName' => $title, 'email' => $email));


        }

        return $this->render('YupItsZacFreeGeoBundle:Web:appregister.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
