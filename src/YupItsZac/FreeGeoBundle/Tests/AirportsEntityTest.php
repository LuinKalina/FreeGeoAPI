<?php

namespace YupItsZac\FreeGeoBundle\Tests;

use YupItsZac\FreeGeoBundle\Controller\DeveloperController;
use YupItsZac\FreeGeoBundle\Entity\Airports;

class AirportsEntityTest extends \PHPUnit_Framework_TestCase
{

    private $airportsEntity;

    public function setUp()
    {
        $this->airportsEntity = new Airports();
        $this->airportsEntity->setCoordinatesWkt('testcoordinates')
            ->setType('testtype')
            ->setIataCode('testcode')
            ->setName('testairport');

    }

    public function testGetCoordinatesWkt() {
        $this->assertEquals('testcoordinates', $this->airportsEntity->getCoordinatesWkt());
    }

    public function testGetType() {
        $this->assertEquals('testtype', $this->airportsEntity->getType());
    }

    public function testGetName() {
        $this->assertEquals('testairport', $this->airportsEntity->getName());
    }

    public function testGetIataCode() {
        $this->assertEquals('testcode', $this->airportsEntity->getIataCode());
    }


}
