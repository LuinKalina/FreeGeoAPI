<?php

namespace YupItsZac\FreeGeoBundle\Tests;

use YupItsZac\FreeGeoBundle\Entity\ApiRequestObject;

class ApiRequestObjectTest extends \PHPUnit_Framework_TestCase
{

    private $apiRequestObject;

    public function setUp()
    {
        $this->apiRequestObject = new ApiRequestObject();

        $this->apiRequestObject->setLatitude('testlatitude');
        $this->apiRequestObject->setLongitude('testlongitude');
        $this->apiRequestObject->setLimit('testlimit');
        $this->apiRequestObject->setMaximum('testmaximum');
        $this->apiRequestObject->setMetricUnit('testmetric');

    }

    public function testInstanceOfObject() {
        $this->assertInstanceOf('YupItsZac\FreeGeoBundle\Entity\ApiRequestObject', $this->apiRequestObject);
    }

    public function testGetLongitude() {
        $this->assertEquals('testlongitude', $this->apiRequestObject->getLongitude());
    }

    public function testGetLatitude() {
        $this->assertEquals('testlatitude', $this->apiRequestObject->getLatitude());
    }

    public function testGetLimit() {
        $this->assertEquals('testlimit', $this->apiRequestObject->getLimit());
    }

    public function testGetMaximum() {
        $this->assertEquals('testmaximum', $this->apiRequestObject->getMaximum());
    }

    public function testGetMetricUnit() {
        $this->assertEquals('testmetric', $this->apiRequestObject->getMetricUnit());
    }


}
