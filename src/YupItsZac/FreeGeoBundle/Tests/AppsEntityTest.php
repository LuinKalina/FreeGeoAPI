<?php

namespace YupItsZac\FreeGeoBundle\Tests;

use YupItsZac\FreeGeoBundle\Entity\Apps;

class AppsEntityTest extends \PHPUnit_Framework_TestCase {

    private $app;

    public function setUp() {

        $this->app = new Apps();
        $this->app->setAssoc(1);
        $this->app->setAppdescription('testdescription');
        $this->app->setApptitle('testtitle');
        $this->app->setAppwebsite('testwebsite');
        $this->app->setBeta(1);
        $this->app->setHash('testhash');
        $this->app->setPublickey('testpublickey');
        $this->app->setSecretkey('testsecretkey');
        $this->app->setStatus('teststatus');
    }

    public function testGetAssoc() {
        $this->assertEquals(1, $this->app->getAssoc());
    }

    public function testGetAppDescription() {
        $this->assertEquals('testdescription', $this->app->getAppdescription());
    }

    public function testGetApptitle() {
        $this->assertEquals('testtitle', $this->app->getApptitle());
    }

    public function testGetAppwebsite() {
        $this->assertEquals('testwebsite', $this->app->getAppwebsite());
    }

    public function testGetBeta() {
        $this->assertEquals(1, $this->app->getBeta());
    }

    public function testGetHash() {
        $this->assertEquals('testhash', $this->app->getHash());
    }

    public function testGetPublickey() {
        $this->assertEquals('testpublickey', $this->app->getPublickey());
    }

    public function testGetSecretKey() {
        $this->assertEquals('testsecretkey', $this->app->getSecretkey());
    }

    public function testGetStatus() {
        $this->assertEquals('teststatus', $this->app->getStatus());
    }
}