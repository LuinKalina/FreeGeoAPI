<?php

namespace YupItsZac\FreeGeoBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use YupItsZac\FreeGeoBundle\Entity\Apps;
use Symfony\Component\HttpFoundation\Response;
use YupItsZac\FreeGeoBundle\Entity\Config;


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


            $body = 'Your app has been registered with the FreeGeo API and is ready to go! Your app API keys are listed below, along with some helpful links.<br><br>If you have any questions about the API or how to interact with the data, just shoot me a tweet ( <a href="http://www.twitter.com/YupItsZac">@YupItsZac</a> or visit the FreeGeo API website at <a href="http://freegeo.yupitszac.com">http://freegeo.yupitszac.com</a> for docs and code samples.<br><br><b>App Name:</b> '.$title.'<br><b>Private Key:</b> '.$private.'<br><b>Public Key:</b> '.$pub.'<br><br>The keys listed above are what you will use to authenticate your app against the FreeGeo API so you can request and manipulate data. BOTH keys are required to generate your session token. For mroe information on authenticating, please see the <a href="http://freegeo.yupitszac.com/docs/authenticate/session">Session Authentication Manual</a>.<br><br>All geospatial points provided by the FreeGeo API are based on the work of the <a href="https://github.com/delight-im/FreeGeoDB">FreeGeo DB</a> Github project. Feel free to contribute geospatial information to that repository!';

            $config = array();
            $config['api_key'] = Config::MAILGUN_API_KEY;
            $config['api_url'] = Config::MAILGUN_API_URL;
         
            $message = array();
            $message['from'] = "FreeGeo API <".Config::FROM_EMAIL_ADDRESS.">";
            $message['to'] = $email;
            $message['h:Reply-To'] = Config::FROM_EMAIL_ADDRESS;
            $message['subject'] = "FreeGeo API Keys";
            $message['html'] = $this->renderView('YupItsZacFreeGeoBundle:Email:email.standard.html.twig', array('fname' => $fname, 'emailHeader' => 'Your App API Keys', 'emailBody' => $body));
         
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $config['api_url']);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, "api:{$config['api_key']}");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_POST, true); 
            curl_setopt($ch, CURLOPT_POSTFIELDS,$message);
         
            $result = curl_exec($ch);
         
            curl_close($ch);
         
            return $this->render('YupItsZacFreeGeoBundle:Web:appregistered.html.twig', array('appName' => $title, 'email' => $email));


        }

        return $this->render('YupItsZacFreeGeoBundle:Web:appregister.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
