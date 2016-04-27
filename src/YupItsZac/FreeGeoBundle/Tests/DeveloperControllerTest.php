<?php

namespace YupItsZac\FreeGeoBundle\Tests;

use YupItsZac\FreeGeoBundle\Controller\DeveloperController;

class DeveloperControllerTest extends \PHPUnit_Framework_TestCase
{

    private $developerController;

    public function setUp()
    {
       $this->developerController = new DeveloperController();
    }

    public function testTestFunction() {
        $this->assertEquals('test', $this->developerController->testFunction());
    }
}
