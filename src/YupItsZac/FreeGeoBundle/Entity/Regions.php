<?php

namespace YupItsZac\FreeGeoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Regions
 *
 * @ORM\Table(name="regions")
 * @ORM\Entity
 */
class Regions
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
     * @ORM\Column(name="iso_alpha2", type="string", length=2, nullable=true)
     */
    private $isoAlpha2;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=44, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="name_alt", type="string", length=129, nullable=true)
     */
    private $nameAlt;

    /**
     * @var string
     *
     * @ORM\Column(name="type_en", type="string", length=27, nullable=true)
     */
    private $typeEn;

    /**
     * @var string
     *
     * @ORM\Column(name="code_hasc", type="string", length=8, nullable=true)
     */
    private $codeHasc;

    /**
     * @var string
     *
     * @ORM\Column(name="region", type="string", length=43, nullable=true)
     */
    private $region;

    /**
     * @var string
     *
     * @ORM\Column(name="region_cod", type="string", length=15, nullable=true)
     */
    private $regionCod;

    /**
     * @var string
     *
     * @ORM\Column(name="postal", type="string", length=3, nullable=true)
     */
    private $postal;

    /**
     * @var string
     *
     * @ORM\Column(name="country_iso_alpha3", type="string", length=3, nullable=true)
     */
    private $countryIsoAlpha3;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=36, nullable=true)
     */
    private $country;

    /**
     * @var integer
     *
     * @ORM\Column(name="gn_id", type="integer", nullable=true)
     */
    private $gnId;

    /**
     * @var string
     *
     * @ORM\Column(name="gn_name", type="string", length=72, nullable=true)
     */
    private $gnName;

    /**
     * @var string
     *
     * @ORM\Column(name="gn_a1_code", type="string", length=10, nullable=true)
     */
    private $gnA1Code;

    /**
     * @var string
     *
     * @ORM\Column(name="region_sub", type="string", length=41, nullable=true)
     */
    private $regionSub;

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
     * @return Regions
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
     * Set isoAlpha2
     *
     * @param string $isoAlpha2
     *
     * @return Regions
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
     * Set name
     *
     * @param string $name
     *
     * @return Regions
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
     * @return Regions
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
     * Set typeEn
     *
     * @param string $typeEn
     *
     * @return Regions
     */
    public function setTypeEn($typeEn)
    {
        $this->typeEn = $typeEn;

        return $this;
    }

    /**
     * Get typeEn
     *
     * @return string
     */
    public function getTypeEn()
    {
        return $this->typeEn;
    }

    /**
     * Set codeHasc
     *
     * @param string $codeHasc
     *
     * @return Regions
     */
    public function setCodeHasc($codeHasc)
    {
        $this->codeHasc = $codeHasc;

        return $this;
    }

    /**
     * Get codeHasc
     *
     * @return string
     */
    public function getCodeHasc()
    {
        return $this->codeHasc;
    }

    /**
     * Set region
     *
     * @param string $region
     *
     * @return Regions
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
     * Set regionCod
     *
     * @param string $regionCod
     *
     * @return Regions
     */
    public function setRegionCod($regionCod)
    {
        $this->regionCod = $regionCod;

        return $this;
    }

    /**
     * Get regionCod
     *
     * @return string
     */
    public function getRegionCod()
    {
        return $this->regionCod;
    }

    /**
     * Set postal
     *
     * @param string $postal
     *
     * @return Regions
     */
    public function setPostal($postal)
    {
        $this->postal = $postal;

        return $this;
    }

    /**
     * Get postal
     *
     * @return string
     */
    public function getPostal()
    {
        return $this->postal;
    }

    /**
     * Set countryIsoAlpha3
     *
     * @param string $countryIsoAlpha3
     *
     * @return Regions
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
     * Set country
     *
     * @param string $country
     *
     * @return Regions
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
     * Set gnId
     *
     * @param integer $gnId
     *
     * @return Regions
     */
    public function setGnId($gnId)
    {
        $this->gnId = $gnId;

        return $this;
    }

    /**
     * Get gnId
     *
     * @return integer
     */
    public function getGnId()
    {
        return $this->gnId;
    }

    /**
     * Set gnName
     *
     * @param string $gnName
     *
     * @return Regions
     */
    public function setGnName($gnName)
    {
        $this->gnName = $gnName;

        return $this;
    }

    /**
     * Get gnName
     *
     * @return string
     */
    public function getGnName()
    {
        return $this->gnName;
    }

    /**
     * Set gnA1Code
     *
     * @param string $gnA1Code
     *
     * @return Regions
     */
    public function setGnA1Code($gnA1Code)
    {
        $this->gnA1Code = $gnA1Code;

        return $this;
    }

    /**
     * Get gnA1Code
     *
     * @return string
     */
    public function getGnA1Code()
    {
        return $this->gnA1Code;
    }

    /**
     * Set regionSub
     *
     * @param string $regionSub
     *
     * @return Regions
     */
    public function setRegionSub($regionSub)
    {
        $this->regionSub = $regionSub;

        return $this;
    }

    /**
     * Get regionSub
     *
     * @return string
     */
    public function getRegionSub()
    {
        return $this->regionSub;
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
