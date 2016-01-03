<?php

namespace YupItsZac\FreeGeoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Apps
 *
 * @ORM\Table(name="apps")
 * @ORM\Entity
 */
class Apps
{
    /**
     * @var string
     *
     * @ORM\Column(name="AppTitle", type="string", length=255, nullable=false)
     */
    private $apptitle;

    /**
     * @var string
     *
     * @ORM\Column(name="AppDescription", type="string", length=500, nullable=false)
     */
    private $appdescription;

    /**
     * @var string
     *
     * @ORM\Column(name="AppWebsite", type="string", length=500, nullable=false)
     */
    private $appwebsite;

    /**
     * @var string
     *
     * @ORM\Column(name="PublicKey", type="string", length=500, nullable=false)
     */
    private $publickey;

    /**
     * @var string
     *
     * @ORM\Column(name="SecretKey", type="string", length=500, nullable=false)
     */
    private $secretkey;

    /**
     * @var string
     *
     * @ORM\Column(name="Status", type="string", length=10, nullable=false)
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="Beta", type="integer", nullable=true)
     */
    private $beta;

    /**
     * @var integer
     *
     * @ORM\Column(name="Assoc", type="integer", nullable=true)
     */
    private $assoc;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set apptitle
     *
     * @param string $apptitle
     *
     * @return Apps
     */
    public function setApptitle($apptitle)
    {
        $this->apptitle = $apptitle;

        return $this;
    }

    /**
     * Get apptitle
     *
     * @return string
     */
    public function getApptitle()
    {
        return $this->apptitle;
    }

    /**
     * Set appdescription
     *
     * @param string $appdescription
     *
     * @return Apps
     */
    public function setAppdescription($appdescription)
    {
        $this->appdescription = $appdescription;

        return $this;
    }

    /**
     * Get appdescription
     *
     * @return string
     */
    public function getAppdescription()
    {
        return $this->appdescription;
    }

    /**
     * Set appwebsite
     *
     * @param string $appwebsite
     *
     * @return Apps
     */
    public function setAppwebsite($appwebsite)
    {
        $this->appwebsite = $appwebsite;

        return $this;
    }

    /**
     * Get appwebsite
     *
     * @return string
     */
    public function getAppwebsite()
    {
        return $this->appwebsite;
    }

    /**
     * Set publickey
     *
     * @param string $publickey
     *
     * @return Apps
     */
    public function setPublickey($publickey)
    {
        $this->publickey = $publickey;

        return $this;
    }

    /**
     * Get publickey
     *
     * @return string
     */
    public function getPublickey()
    {
        return $this->publickey;
    }

    /**
     * Set secretkey
     *
     * @param string $secretkey
     *
     * @return Apps
     */
    public function setSecretkey($secretkey)
    {
        $this->secretkey = $secretkey;

        return $this;
    }

    /**
     * Get secretkey
     *
     * @return string
     */
    public function getSecretkey()
    {
        return $this->secretkey;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Apps
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set beta
     *
     * @param integer $beta
     *
     * @return Apps
     */
    public function setBeta($beta)
    {
        $this->beta = $beta;

        return $this;
    }

    /**
     * Get beta
     *
     * @return integer
     */
    public function getBeta()
    {
        return $this->beta;
    }

    /**
     * Set assoc
     *
     * @param integer $assoc
     *
     * @return Apps
     */
    public function setAssoc($assoc)
    {
        $this->assoc = $assoc;

        return $this;
    }

    /**
     * Get assoc
     *
     * @return integer
     */
    public function getAssoc()
    {
        return $this->assoc;
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
