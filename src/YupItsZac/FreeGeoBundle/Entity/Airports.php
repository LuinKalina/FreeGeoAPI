<?php

namespace YupItsZac\FreeGeoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Airports
 *
 * @ORM\Table(name="airports")
 * @ORM\Entity
 */
class Airports
{
    /**
     * @var string
     *
     * @ORM\Column(name="coordinates_wkt", type="string", length=47, nullable=false)
     */
    private $coordinatesWkt;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=18, nullable=true)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=44, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="icao_code", type="string", length=5, nullable=true)
     */
    private $icaoCode;

    /**
     * @var string
     *
     * @ORM\Column(name="iata_code", type="string", length=4, nullable=true)
     */
    private $iataCode;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set coordinatesWkt
     *
     * @param string $coordinatesWkt
     *
     * @return Airports
     */
    public function setCoordinatesWkt($coordinatesWkt)
    {
        $this->coordinatesWkt = $coordinatesWkt;

        return $this;
    }

    /**
     * Get coordinatesWkt
     *
     * @return string
     */
    public function getCoordinatesWkt()
    {
        return $this->coordinatesWkt;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Airports
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Airports
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set icaoCode
     *
     * @param string $icaoCode
     *
     * @return Airports
     */
    public function setIcaoCode($icaoCode)
    {
        $this->icaoCode = $icaoCode;

        return $this;
    }

    /**
     * Get icaoCode
     *
     * @return string
     */
    public function getIcaoCode()
    {
        return $this->icaoCode;
    }

    /**
     * Set iataCode
     *
     * @param string $iataCode
     *
     * @return Airports
     */
    public function setIataCode($iataCode)
    {
        $this->iataCode = $iataCode;

        return $this;
    }

    /**
     * Get iataCode
     *
     * @return string
     */
    public function getIataCode()
    {
        return $this->iataCode;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
