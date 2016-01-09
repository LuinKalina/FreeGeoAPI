<?php
/**
 * Created by PhpStorm.
 * User: zbrown
 * Date: 27.12.15
 * Time: 18:00
 */

namespace YupItsZac\FreeGeoBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use YupItsZac\FreeGeoBundle\Entity\Apps;
use Symfony\Component\HttpFoundation\Response;
use YupItsZac\FreeGeoBundle\Entity\Config;
use YupItsZac\FreeGeoBundle\Entity\Strings;
use YupItsZac\FreeGeoBundle\Helpers\DataHelper;

class DeveloperController extends Controller {

    private $dataHelper;

    public function __construct() {

        $this->dataHelper = new DataHelper();
    }

    public function dashboardAction($notifySuccess = null) {

        $params = $this->getDashboardContent();
        $params['notifySuccess'] = $notifySuccess;

        return $this->render('YupItsZacFreeGeoBundle:Developer:dashboard.html.twig', $params);
    }

    private function getDashboardContent() {

        $userArray = $this->dataHelper->getUserObjectAsArray($this->get('security.token_storage')->getToken()->getUser());

        $qb = $this->getDoctrine()->getEntityManager()->getConnection();

        $q = $qb->prepare('SELECT * FROM apps WHERE Assoc=:uid');

        $q->bindValue('uid', $userArray['userId']);
        $q->execute();

        $appList = $q->fetchAll();

        if($appList) {
            $params = array (
                'userObject' => $userArray,
                'appList' => $appList
            );
        } else {
            $params = array(
                'userObject' => $userArray
            );
        }

        return $params;
    }

    public function appRegisterAction(Request $request) {

        $app = new Apps();


        $form = $this->createFormBuilder($app)
            ->add('apptitle', 'text', array('label' => 'App Title'))
            ->add('appwebsite', 'url', array('label' => 'App Website'))
            ->add('appdescription', 'textarea', array('label' => 'App Description'))
            ->add('save', 'submit', array('label' => 'Register App'))
            ->getForm();

        $form->handleRequest($request);

        if($form->isValid()) {

            $title = $form['apptitle']->getData();
            $website = $form['appwebsite']->getData();
            $description = $form['appdescription']->getData();

            $pub = md5(time().$title.time());

            $private = md5(time().time());

            $userArray = $this->dataHelper->getUserObjectAsArray($this->get('security.token_storage')->getToken()->getUser());

            $app = new Apps();
            $app->setApptitle($title);
            $app->setAppwebsite($website);
            $app->setAppdescription($description);
            $app->setStatus('Active');
            $app->setPublickey($pub);
            $app->setSecretkey($private);
            $app->setAssoc($userArray['userId']);
            $app->setHash(md5(time()));

            $em = $this->getDoctrine()->getEntityManager();

            $em->persist($app);
            $em->flush();

            $message = array();
            $message['from'] = Config::PROJECT_NAME." <".Config::FROM_EMAIL_ADDRESS.">";
            $message['to'] = $userArray['emailAddress'];
            $message['h:Reply-To'] = Config::FROM_EMAIL_ADDRESS;
            $message['subject'] = Strings::APP_REGISTER_EMAIL_SUBJECT;
            $message['html'] = $this->renderView('YupItsZacFreeGeoBundle:Email:newapp.registered.html.twig', array('appTitle' => $title, 'projectName' => Config::PROJECT_NAME, 'githubUrl' => Config::GITHUB_MAIN_REPO, 'twitterUrl' => Strings::TWITTER_URL, 'twitterUser' => Strings::TWITTER_USER, 'baseUrl' => Config::BASE_URL_PROD, 'firstName' => $userArray['firstName'], 'emailHeader' => Strings::APP_REGISTER_EMAIL_SUBJECT));

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

            return $this->render('YupItsZacFreeGeoBundle:Developer:appregistered.html.twig', array('appName' => $title));


        }

        return $this->render('YupItsZacFreeGeoBundle:Developer:appregister.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function appSettingsAction(Request $request, $appHash, $actionType, $notifySuccess = null) {

        $userArray = $this->dataHelper->getUserObjectAsArray($this->get('security.token_storage')->getToken()->getUser());

        $app = $this->getDoctrine()->getRepository('YupItsZacFreeGeoBundle:Apps')->findOneBy(array('hash' => $appHash));

        if($app->getAssoc() != $userArray['userId']) {
            $this->redirectToRoute('free_geo_developer_dashboard');
        }

        if($actionType == 'refresh') {
            $publicKey = md5(time().$app->getId().time());
            $privateKey = md5(time().$app->getApptitle().time());

            $app->setPublickey($publicKey);
            $app->setSecretkey($privateKey);

            $em = $this->getDoctrine()->getEntityManager();

            $em->persist($app);
            $em->flush();

            $notifySuccess = Strings::UI_MSG_APP_KEYS_RESET;

        } else {

            $publicKey = $app->getPublickey();
            $privateKey = $app->getSecretkey();
        }

        $form = $this->createFormBuilder($app)
            ->add('apptitle', 'text', array('label' => 'App Title'))
            ->add('appwebsite', 'url', array('label' => 'App Website'))
            ->add('appdescription', 'textarea', array('label' => 'App Description'))
            ->add('hash', 'hidden')
            ->add('save', 'submit', array('label' => 'Save'))
            ->getForm();

        $form->handleRequest($request);

        if($form->isValid()) {

            $title = $form['apptitle']->getData();
            $website = $form['appwebsite']->getData();
            $description = $form['appdescription']->getData();
            $hash = $form['hash']->getData();

            $app = $this->getDoctrine()->getRepository('YupItsZacFreeGeoBundle:Apps')->findOneBy(array('hash' => $appHash));

            $app->setApptitle($title);
            $app->setAppwebsite($website);
            $app->setAppdescription($description);

            $em = $this->getDoctrine()->getEntityManager();

            $em->persist($app);
            $em->flush();

            $notifySuccess = Strings::UI_MSG_APP_UPDATED;

        }

        return $this->render('YupItsZacFreeGeoBundle:Developer:app.settings.html.twig', array(
            'form' => $form->createView(),
            'privateKey' => $privateKey,
            'publicKey' => $publicKey,
            'notifySuccess' => $notifySuccess,
            'appHash' => $appHash
        ));
    }

    public function deleteAppAction($appHash) {

        $userArray = $this->dataHelper->getUserObjectAsArray($this->get('security.token_storage')->getToken()->getUser());

        $app = $this->getDoctrine()->getRepository('YupItsZacFreeGeoBundle:Apps')->findOneBy(array('hash' => $appHash));

        if($app->getAssoc() != $userArray['userId']) {
            $this->redirectToRoute('free_geo_developer_dashboard');
        }

        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($app);
        $em->flush();

        return $this->redirectToRoute('free_geo_developer_dashboard');

    }

}