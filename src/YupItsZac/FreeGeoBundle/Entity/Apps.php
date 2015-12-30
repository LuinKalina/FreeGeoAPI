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
     * @ORM\Column(name="FirstName", type="string", length=255, nullable=false)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="LastName", type="string", length=255, nullable=false)
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="Email", type="string", length=500, nullable=false)
     */
    private $email;

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
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return Apps
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return Apps
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Apps
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
