<?php

namespace YupItsZac\FreeGeoBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use YupItsZac\FreeGeoBundle\Entity\Apps;
use Symfony\Component\HttpFoundation\Response;
use YupItsZac\FreeGeoBundle\Entity\Config;
use YupItsZac\FreeGeoBundle\Entity\Strings;


class WebController extends Controller {

    public function indexAction() {

        return $this->render('YupItsZacFreeGeoBundle:Web:index.html.twig');
    }

    public function statusAction() {

    	return $this->render('YupItsZacFreeGeoBundle:Web:status.html.twig');
    }

    public function authenticateAction() {

    	return $this->render('YupItsZacFreeGeoBundle:Docs:authenticate.html.twig');
    }

    public function findNearAction() {

        return $this->render('YupItsZacFreeGeoBundle:Docs:findnear.html.twig');
    }

    public function findNearAirportsAction() {

    	return $this->render('YupItsZacFreeGeoBundle:Docs:findnearairport.html.twig');
    }

    public function findnearCitiesAction() {

        return $this->render('YupItsZacFreeGeoBundle:Docs:findnearcities.html.twig');
    }

    public function findnearPortsAction() {

        return $this->render('YupItsZacFreeGeoBundle:Docs:findnearports.html.twig');
    }

    public function findnearLakesAction() {

        return $this->render('YupItsZacFreeGeoBundle:Docs:findnearlakes.html.twig');
    }

    public function findnearRoadsAction() {

        return $this->render('YupItsZacFreeGeoBundle:Docs:findnearroads.html.twig');
    }

    public function findnearRailroadsAction() {

        return $this->render('YupItsZacFreeGeoBundle:Docs:findnearrailroads.html.twig');
    }

    public function detectTimeZoneAction() {

        return $this->render('YupItsZacFreeGeoBundle:Docs:detecttimezone.html.twig');
    }

    public function calculateDistanceAction() {

        return $this->render('YupItsZacFreeGeoBundle:Docs:calculatedistance.html.twig');
    }

    public function resetKeysAction() {

        return $this->render('YupItsZacFreeGeoBundle:Docs:resetkeys.html.twig');
    }

    public function highTodoAction() {

        return $this->render('YupItsZacFreeGeoBundle:todo:high.html.twig');
    }

    public function mediumTodoAction() {

        return $this->render('YupItsZacFreeGeoBundle:todo:medium.html.twig');
    }

    public function lowTodoAction() {

        return $this->render('YupItsZacFreeGeoBundle:todo:low.html.twig');
    }

    public function contributeAction() {

        return $this->render('YupItsZacFreeGeoBundle:contribute:index.html.twig');
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
            $message['from'] = "FreeGeo API <".Config::FROM_EMAIL_ADDRESS.">";
            $message['to'] = $email;
            $message['h:Reply-To'] = Config::FROM_EMAIL_ADDRESS;
            $message['subject'] = Strings::APP_REGISTER_EMAIL_SUBJECT;
            $message['html'] = $this->renderView('YupItsZacFreeGeoBundle:Email:email.standard.html.twig', array('fname' => $fname, 'emailHeader' => 'Your App API Keys', 'emailBody' => $body));
         
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, Config::MAILGUN_API_URL);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, "api:{".Config::MAILGUN_API_KEY."}");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_POST, true); 
            curl_setopt($ch, CURLOPT_POSTFIELDS,$message);
         
            $result = curl_exec($ch);
         
            curl_close($ch);
         
            return $this->render('YupItsZacFreeGeoBundle:Web:appregistered.html.twig', array('projectName' => Config::PROJECT_NAME, 'githubUrl' => Config::GITHUB_MAIN_REPO, 'twitterUrl' => Strings::TWITTER_URL, 'twitterUser' => Strings::TWITTER_USER, 'baseUrl' => Config::BASE_URL_PROD, 'appName' => $title, 'email' => $email));


        }

        return $this->render('YupItsZacFreeGeoBundle:Web:appregister.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
