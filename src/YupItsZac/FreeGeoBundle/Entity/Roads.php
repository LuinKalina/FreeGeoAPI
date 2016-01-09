<?php

namespace YupItsZac\FreeGeoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Roads
 *
 * @ORM\Table(name="roads")
 * @ORM\Entity
 */
class Roads
{
    /**
     * @var string
     *
     * @ORM\Column(name="coordinates_wkt", type="string", length=8154, nullable=false)
     */
    private $coordinatesWkt;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=17, nullable=true)
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="length_km", type="integer", nullable=true)
     */
    private $lengthKm;

    /**
     * @var string
     *
     * @ORM\Column(name="continent", type="string", length=20, nullable=true)
     */
    private $continent;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_express", type="integer", nullable=true)
     */
    private $isExpress;

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
     * @return Roads
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
     * @return Roads
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
     * Set lengthKm
     *
     * @param integer $lengthKm
     *
     * @return Roads
     */
    public function setLengthKm($lengthKm)
    {
        $this->lengthKm = $lengthKm;

        return $this;
    }

    /**
     * Get lengthKm
     *
     * @return integer
     */
    public function getLengthKm()
    {
        return $this->lengthKm;
    }

    /**
     * Set continent
     *
     * @param string $continent
     *
     * @return Roads
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
     * Set isExpress
     *
     * @param integer $isExpress
     *
     * @return Roads
     */
    public function setIsExpress($isExpress)
    {
        $this->isExpress = $isExpress;

        return $this;
    }

    /**
     * Get isExpress
     *
     * @return integer
     */
    public function getIsExpress()
    {
        return $this->isExpress;
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
