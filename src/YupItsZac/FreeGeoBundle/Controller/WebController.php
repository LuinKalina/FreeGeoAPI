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

    public function staticRenderAction($dir, $action, $pageTitle) {

        return $this->render('YupItsZacFreeGeoBundle:'.$dir.':'.$action.'.html.twig', array('title' => $pageTitle));
    }

    public function indexAction() {

        return $this->render('YupItsZacFreeGeoBundle:Web:index.html.twig', array('title' => 'Home'));
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
}
