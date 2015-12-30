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

    public function dashboardAction() {

        return new Response('Developer dashboard');
    }

}