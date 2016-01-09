<?php

namespace YupItsZac\FreeGeoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cities
 *
 * @ORM\Table(name="cities")
 * @ORM\Entity
 */
class Cities
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
     * @ORM\Column(name="name", type="string", length=33, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="name_alt", type="string", length=43, nullable=true)
     */
    private $nameAlt;

    /**
     * @var string
     *
     * @ORM\Column(name="name_ascii", type="string", length=39, nullable=true)
     */
    private $nameAscii;

    /**
     * @var string
     *
     * @ORM\Column(name="is_capital", type="decimal", precision=2, scale=1, nullable=true)
     */
    private $isCapital;

    /**
     * @var string
     *
     * @ORM\Column(name="is_world_city", type="decimal", precision=2, scale=1, nullable=true)
     */
    private $isWorldCity;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_mega_city", type="integer", nullable=true)
     */
    private $isMegaCity;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=32, nullable=true)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="country_iso_alpha3", type="string", length=3, nullable=true)
     */
    private $countryIsoAlpha3;

    /**
     * @var string
     *
     * @ORM\Column(name="region", type="string", length=43, nullable=true)
     */
    private $region;

    /**
     * @var string
     *
     * @ORM\Column(name="time_zone", type="string", length=30, nullable=true)
     */
    private $timeZone;

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
     * @return Cities
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
     * Set name
     *
     * @param string $name
     *
     * @return Cities
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
     * Set nameAlt
     *
     * @param string $nameAlt
     *
     * @return Cities
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
     * Set nameAscii
     *
     * @param string $nameAscii
     *
     * @return Cities
     */
    public function setNameAscii($nameAscii)
    {
        $this->nameAscii = $nameAscii;

        return $this;
    }

    /**
     * Get nameAscii
     *
     * @return string
     */
    public function getNameAscii()
    {
        return $this->nameAscii;
    }

    /**
     * Set isCapital
     *
     * @param string $isCapital
     *
     * @return Cities
     */
    public function setIsCapital($isCapital)
    {
        $this->isCapital = $isCapital;

        return $this;
    }

    /**
     * Get isCapital
     *
     * @return string
     */
    public function getIsCapital()
    {
        return $this->isCapital;
    }

    /**
     * Set isWorldCity
     *
     * @param string $isWorldCity
     *
     * @return Cities
     */
    public function setIsWorldCity($isWorldCity)
    {
        $this->isWorldCity = $isWorldCity;

        return $this;
    }

    /**
     * Get isWorldCity
     *
     * @return string
     */
    public function getIsWorldCity()
    {
        return $this->isWorldCity;
    }

    /**
     * Set isMegaCity
     *
     * @param integer $isMegaCity
     *
     * @return Cities
     */
    public function setIsMegaCity($isMegaCity)
    {
        $this->isMegaCity = $isMegaCity;

        return $this;
    }

    /**
     * Get isMegaCity
     *
     * @return integer
     */
    public function getIsMegaCity()
    {
        return $this->isMegaCity;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return Cities
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set countryIsoAlpha3
     *
     * @param string $countryIsoAlpha3
     *
     * @return Cities
     */
    public function setCountryIsoAlpha3($countryIsoAlpha3)
    {
        $this->countryIsoAlpha3 = $countryIsoAlpha3;

        return $this;
    }

    /**
     * Get countryIsoAlpha3
     *
     * @return string
     */
    public function getCountryIsoAlpha3()
    {
        return $this->countryIsoAlpha3;
    }

    /**
     * Set region
     *
     * @param string $region
     *
     * @return Cities
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set timeZone
     *
     * @param string $timeZone
     *
     * @return Cities
     */
    public function setTimeZone($timeZone)
    {
        $this->timeZone = $timeZone;

        return $this;
    }

    /**
     * Get timeZone
     *
     * @return string
     */
    public function getTimeZone()
    {
        return $this->timeZone;
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
