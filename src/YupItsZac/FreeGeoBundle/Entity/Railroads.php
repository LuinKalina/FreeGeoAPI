<?php

namespace YupItsZac\FreeGeoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Railroads
 *
 * @ORM\Table(name="railroads")
 * @ORM\Entity
 */
class Railroads
{
    /**
     * @var string
     *
     * @ORM\Column(name="coordinates_wkt", type="string", length=19561, nullable=false)
     */
    private $coordinatesWkt;

    /**
     * @var integer
     *
     * @ORM\Column(name="mult_track", type="integer", nullable=true)
     */
    private $multTrack;

    /**
     * @var integer
     *
     * @ORM\Column(name="electric", type="integer", nullable=true)
     */
    private $electric;

    /**
     * @var string
     *
     * @ORM\Column(name="continent", type="string", length=13, nullable=true)
     */
    private $continent;

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
     * @return Railroads
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
     * Set multTrack
     *
     * @param integer $multTrack
     *
     * @return Railroads
     */
    public function setMultTrack($multTrack)
    {
        $this->multTrack = $multTrack;

        return $this;
    }

    /**
     * Get multTrack
     *
     * @return integer
     */
    public function getMultTrack()
    {
        return $this->multTrack;
    }

    /**
     * Set electric
     *
     * @param integer $electric
     *
     * @return Railroads
     */
    public function setElectric($electric)
    {
        $this->electric = $electric;

        return $this;
    }

    /**
     * Get electric
     *
     * @return integer
     */
    public function getElectric()
    {
        return $this->electric;
    }

    /**
     * Set continent
     *
     * @param string $continent
     *
     * @return Railroads
     */
    public function setContinent($continent)
    {
        $this->continent = $continent;

        return $this;
    }

    /**
     * Get continent
     *
     * @return string
     */
    public function getContinent()
    {
        return $this->continent;
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
