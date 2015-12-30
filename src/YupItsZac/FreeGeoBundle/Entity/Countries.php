<?php

namespace YupItsZac\FreeGeoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Countries
 *
 * @ORM\Table(name="countries")
 * @ORM\Entity
 */
class Countries
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
     * @ORM\Column(name="sovereign", type="string", length=32, nullable=true)
     */
    private $sovereign;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=40, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="formal", type="string", length=52, nullable=true)
     */
    private $formal;

    /**
     * @var string
     *
     * @ORM\Column(name="economy_level", type="string", length=26, nullable=true)
     */
    private $economyLevel;

    /**
     * @var string
     *
     * @ORM\Column(name="income_level", type="string", length=23, nullable=true)
     */
    private $incomeLevel;

    /**
     * @var string
     *
     * @ORM\Column(name="iso_alpha2", type="string", length=3, nullable=true)
     */
    private $isoAlpha2;

    /**
     * @var string
     *
     * @ORM\Column(name="iso_alpha3", type="string", length=3, nullable=true)
     */
    private $isoAlpha3;

    /**
     * @var integer
     *
     * @ORM\Column(name="iso_numeric3", type="integer", nullable=true)
     */
    private $isoNumeric3;

    /**
     * @var string
     *
     * @ORM\Column(name="continent", type="string", length=23, nullable=true)
     */
    private $continent;

    /**
     * @var string
     *
     * @ORM\Column(name="subregion", type="string", length=25, nullable=true)
     */
    private $subregion;

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
     * @return Countries
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
     * Set sovereign
     *
     * @param string $sovereign
     *
     * @return Countries
     */
    public function setSovereign($sovereign)
    {
        $this->sovereign = $sovereign;

        return $this;
    }

    /**
     * Get sovereign
     *
     * @return string
     */
    public function getSovereign()
    {
        return $this->sovereign;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Countries
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
     * Set formal
     *
     * @param string $formal
     *
     * @return Countries
     */
    public function setFormal($formal)
    {
        $this->formal = $formal;

        return $this;
    }

    /**
     * Get formal
     *
     * @return string
     */
    public function getFormal()
    {
        return $this->formal;
    }

    /**
     * Set economyLevel
     *
     * @param string $economyLevel
     *
     * @return Countries
     */
    public function setEconomyLevel($economyLevel)
    {
        $this->economyLevel = $economyLevel;

        return $this;
    }

    /**
     * Get economyLevel
     *
     * @return string
     */
    public function getEconomyLevel()
    {
        return $this->economyLevel;
    }

    /**
     * Set incomeLevel
     *
     * @param string $incomeLevel
     *
     * @return Countries
     */
    public function setIncomeLevel($incomeLevel)
    {
        $this->incomeLevel = $incomeLevel;

        return $this;
    }

    /**
     * Get incomeLevel
     *
     * @return string
     */
    public function getIncomeLevel()
    {
        return $this->incomeLevel;
    }

    /**
     * Set isoAlpha2
     *
     * @param string $isoAlpha2
     *
     * @return Countries
     */
    public function setIsoAlpha2($isoAlpha2)
    {
        $this->isoAlpha2 = $isoAlpha2;

        return $this;
    }

    /**
     * Get isoAlpha2
     *
     * @return string
     */
    public function getIsoAlpha2()
    {
        return $this->isoAlpha2;
    }

    /**
     * Set isoAlpha3
     *
     * @param string $isoAlpha3
     *
     * @return Countries
     */
    public function setIsoAlpha3($isoAlpha3)
    {
        $this->isoAlpha3 = $isoAlpha3;

        return $this;
    }

    /**
     * Get isoAlpha3
     *
     * @return string
     */
    public function getIsoAlpha3()
    {
        return $this->isoAlpha3;
    }

    /**
     * Set isoNumeric3
     *
     * @param integer $isoNumeric3
     *
     * @return Countries
     */
    public function setIsoNumeric3($isoNumeric3)
    {
        $this->isoNumeric3 = $isoNumeric3;

        return $this;
    }

    /**
     * Get isoNumeric3
     *
     * @return integer
     */
    public function getIsoNumeric3()
    {
        return $this->isoNumeric3;
    }

    /**
     * Set continent
     *
     * @param string $continent
     *
     * @return Countries
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
     * Set subregion
     *
     * @param string $subregion
     *
     * @return Countries
     */
    public function setSubregion($subregion)
    {
        $this->subregion = $subregion;

        return $this;
    }

    /**
     * Get subregion
     *
     * @return string
     */
    public function getSubregion()
    {
        return $this->subregion;
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
