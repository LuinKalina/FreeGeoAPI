<?php

namespace YupItsZac\FreeGeoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Session
 *
 * @ORM\Table(name="session")
 * @ORM\Entity
 */
class Session
{
    /**
     * @var string
     *
     * @ORM\Column(name="Session", type="string", length=500, nullable=false)
     */
    private $session;

    /**
     * @var string
     *
     * @ORM\Column(name="Public", type="string", length=500, nullable=false)
     */
    private $public;

    /**
     * @var string
     *
     * @ORM\Column(name="Secret", type="string", length=500, nullable=false)
     */
    private $secret;

    /**
     * @var string
     *
     * @ORM\Column(name="AppId", type="string", length=255, nullable=false)
     */
    private $appid;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set session
     *
     * @param string $session
     *
     * @return Session
     */
    public function setSession($session)
    {
        $this->session = $session;

        return $this;
    }

    /**
     * Get session
     *
     * @return string
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * Set public
     *
     * @param string $public
     *
     * @return Session
     */
    public function setPublic($public)
    {
        $this->public = $public;

        return $this;
    }

    /**
     * Get public
     *
     * @return string
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * Set secret
     *
     * @param string $secret
     *
     * @return Session
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;

        return $this;
    }

    /**
     * Get secret
     *
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * Set appid
     *
     * @param string $appid
     *
     * @return Session
     */
    public function setAppid($appid)
    {
        $this->appid = $appid;

        return $this;
    }

    /**
     * Get appid
     *
     * @return string
     */
    public function getAppid()
    {
        return $this->appid;
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
