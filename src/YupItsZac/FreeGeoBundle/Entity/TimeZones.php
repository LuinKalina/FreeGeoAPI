<?php

namespace YupItsZac\FreeGeoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TimeZones
 *
 * @ORM\Table(name="time_zones")
 * @ORM\Entity
 */
class TimeZones
{
    /**
     * @var string
     *
     * @ORM\Column(name="coordinates_wkt", type="text", length=16777215, nullable=false)
     */
    private $coordinatesWkt;

    /**
     * @var string
     *
     * @ORM\Column(name="offset", type="decimal", precision=5, scale=2, nullable=true)
     */
    private $offset;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=9, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="places", type="string", length=139, nullable=true)
     */
    private $places;

    /**
     * @var string
     *
     * @ORM\Column(name="dst_places", type="string", length=253, nullable=true)
     */
    private $dstPlaces;

    /**
     * @var string
     *
     * @ORM\Column(name="name_alt", type="string", length=25, nullable=true)
     */
    private $nameAlt;

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
     * @return TimeZones
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
     * Set offset
     *
     * @param string $offset
     *
     * @return TimeZones
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * Get offset
     *
     * @return string
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return TimeZones
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
     * Set places
     *
     * @param string $places
     *
     * @return TimeZones
     */
    public function setPlaces($places)
    {
        $this->places = $places;

        return $this;
    }

    /**
     * Get places
     *
     * @return string
     */
    public function getPlaces()
    {
        return $this->places;
    }

    /**
     * Set dstPlaces
     *
     * @param string $dstPlaces
     *
     * @return TimeZones
     */
    public function setDstPlaces($dstPlaces)
    {
        $this->dstPlaces = $dstPlaces;

        return $this;
    }

    /**
     * Get dstPlaces
     *
     * @return string
     */
    public function getDstPlaces()
    {
        return $this->dstPlaces;
    }

    /**
     * Set nameAlt
     *
     * @param string $nameAlt
     *
     * @return TimeZones
     */
    public function setNameAlt($nameAlt)
    {
        $this->nameAlt = $nameAlt;

        return $this;
    }

    /**
     * Get nameAlt
     *
     * @return string
     */
    public function getNameAlt()
    {
        return $this->nameAlt;
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
