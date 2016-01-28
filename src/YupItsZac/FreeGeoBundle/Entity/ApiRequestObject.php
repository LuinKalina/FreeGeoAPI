<?php

namespace YupItsZac\FreeGeoBundle\Entity;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Tests\Fixtures\AnnotatedClasses\AbstractClass;
use YupItsZac\FreeGeoBundle\Entity\Session;
use YupItsZac\FreeGeoBundle\Entity\Config;
use YupItsZac\FreeGeoBundle\Entity\Strings;
use \DateTime;

class ApiRequestObject
{

    private $_geoLatitude = null;
    private $_geoLongitude = null;
    private $_secondaryLatitude = null;
    private $_secondaryLongitude = null;
    private $_sessionToken = null;
    private $_publicKey = null;
    private $_privateKey = null;
    private $_returnLimit = null;
    private $_maximumDistance = null;
    private $_metricUnit = null;
    private $_roundMath = null;

    private $_attributesMap = [
        'latitude' => '_geoLatitude',
        'lat' => '_geoLatitude',
        'lata' => '_geoLatitude',
        'latb' => '_secondaryLatitude',
        'longitude' => '_geoLongitude',
        'lon' => '_geoLongitude',
        'lona' => '_geoLongitude',
        'lonb' => '_secondaryLongitude',
        'session' => '_sessionToken',
        'public' => '_publicKey',
        'private' => '_privateKey',
        'secret' => '_privateKey',
        'limit' => '_returnLimit',
        'max' => '_maximumDistance',
        'metric' => '_metricUnit',
        'round' => '_roundMath'
    ];


    /**
     * Set object attributes by array
     * @author zbrown
     *
     * @param array $postData
     */
    public function setAttributesByArray(array $postData)
    {

        foreach ($postData as $key => $value) {
            error_log(print_r('Key: '.$key, true), 0);
            if (isset($this->_attributesMap[$key])) {
                $mapItem = $this->_attributesMap[$key];
                error_log(print_r('Map: '.$mapItem, true), 0);
                error_log(print_r('Value: '.$value, true), 0);
                $this->$mapItem = $value;
            }
        }
    }

    /**
     * Get lat
     * @author zbrown
     *
     * @return mixed
     */
    public function getLatitude()
    {

        return $this->_geoLatitude;
    }

    /**
     * Set lat
     * @author zbrown
     *
     * @param $geoLatitude
     * @return $this
     */
    public function setLatitude($geoLatitude)
    {
        $this->_geoLatitude = $geoLatitude;
    }

    /**
     * Get lon
     * @author zbrown
     *
     * @return mixed
     */
    public function getLongitude()
    {

        return $this->_geoLongitude;
    }

    /**
     * Set lon
     * @author zbrown
     *
     * @param $geoLongitude
     * @return $this
     */
    public function setLongitude($geoLongitude)
    {
        $this->_geoLongitude = $geoLongitude;

    }

    /**
     * Get session token
     * @author zbrown
     *
     * @return mixed
     */
    public function getSessionToken()
    {

        return $this->_sessionToken;
    }

    /**
     * Set session token
     * @author zbrown
     *
     * @param $sessionToken
     */
    public function setSessionToken($sessionToken)
    {

        $this->_sessionToken = $sessionToken;
    }

    /**
     * Get public key
     * @author zbrown
     *
     * @return mixed
     */
    public function getPublicKey()
    {
        return $this->_publicKey;
    }

    /**
     * Set public key
     * @author zbrown
     *
     * @param $publicKey
     * @return $this
     */
    public function setPublicKey($publicKey)
    {

        $this->_publicKey = $publicKey;
    }

    /**
     * Get private key
     * @author zbrown
     *
     * @return mixed
     */
    public function getPrivateKey()
    {
        return $this->_privateKey;
    }

    /**
     * Set private key
     * @author zbrown
     *
     * @param $privateKey
     * @return $this
     */
    public function setPrivateKey($privateKey)
    {
        $this->_privateKey = $privateKey;
    }

    /**
     * Get return limit
     * @author zbrown
     *
     * @return mixed
     */
    public function getLimit()
    {
        return $this->_returnLimit;
    }

    /**
     * Set return limit
     * @author zbrown
     *
     * @param $returnLimit
     * @return $this
     */
    public function setLimit($returnLimit)
    {

        $this->_returnLimit = $returnLimit;
    }

    /**
     * Get maximum distance
     * @author zbrown
     *
     * @return mixed
     */
    public function getMaximum()
    {
        return $this->_maximumDistance;
    }

    /**
     * Set maximum distance
     * @author zbrown
     *
     * @param $maximumDistance
     * @return $this
     */
    public function setMaximum($maximumDistance)
    {
        $this->_maximumDistance = $maximumDistance;
    }

    /**
     * Get secondary latitude
     * @author zbrown
     *
     * @return null
     */
    public function getSecondaryLatitude() {
        return $this->_secondaryLatitude;
    }

    /**
     * Set secondary latitude
     * @author zbrown
     *
     * @param $secondaryLatitude
     */
    public function setSecondaryLatitude($secondaryLatitude) {

        $this->_secondaryLatitude = $secondaryLatitude;
    }

    /**
     * Get secondary longitude
     * @author zbrown
     *
     * @return null
     */
    public function getSecondaryLongitude() {
        return $this->_secondaryLongitude;
    }

    /**
     * Set secondary longitude
     * @author zbrown
     *
     * @param $secondaryLongitude
     */
    public function setSecondaryLongitude($secondaryLongitude) {

        $this->_secondaryLongitude = $secondaryLongitude;
    }

    /**
     * Get metric unit
     * @author zbrown
     *
     * @return null
     */
    public function getMetricUnit() {
        return $this->_metricUnit;
    }

    /**
     * Set metric unit
     * @author zbrown
     *
     * @param $metricUnit
     */
    public function setMetricUnit($metricUnit) {
        $this->_metricUnit = $metricUnit;
    }

    /**
     * Get round math
     * @author zbrown
     *
     * @return null
     */
    public function getRoundMath() {
        return $this->_roundMath;
    }

    /**
     * Set round math
     * @author zbrown
     *
     * @param $roundMath
     */
    public function setRoundMath($roundMath) {
        $this->_roundMath = $roundMath;
    }
}