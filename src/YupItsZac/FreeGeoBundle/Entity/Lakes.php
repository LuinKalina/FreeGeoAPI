<?php

namespace YupItsZac\FreeGeoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Lakes
 *
 * @ORM\Table(name="lakes")
 * @ORM\Entity
 */
class Lakes
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
     * @ORM\Column(name="name", type="string", length=53, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="name_alt", type="string", length=40, nullable=true)
     */
    private $nameAlt;

    /**
     * @var string
     *
     * @ORM\Column(name="dam_name", type="string", length=34, nullable=true)
     */
    private $damName;

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
     * @return Lakes
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
     * @return Lakes
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
     * @return Lakes
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
     * Set damName
     *
     * @param string $damName
     *
     * @return Lakes
     */
    public function setDamName($damName)
    {
        $this->damName = $damName;

        return $this;
    }

    /**
     * Get damName
     *
     * @return string
     */
    public function getDamName()
    {
        return $this->damName;
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
