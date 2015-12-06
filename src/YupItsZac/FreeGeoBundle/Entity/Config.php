<?php
/**
 * Created by PhpStorm.
 * User: yupitszac
 * Date: 05.12.15
 * Time: 15:18
 */

namespace YupItsZac\FreeGeoBundle\Entity;


class Config {

    const PROJECT_NAME = 'FreeGeo API';

    const MAILGUN_API_KEY = '...';
    const MAILGUN_API_URL = '...';

    const FROM_EMAIL_ADDRESS = 'freegeo@yupitszac.com';
    const ENVIRONMENT = 'dev';

    const PUBLIC_ADDRESS = 'www.freegeoapi.org';
    const WEB_PROTOCOL_PROD = 'https://';
    const WEB_PROTOCOL_DEV = 'http://';

    const BASE_URL_PROD = 'https://www.freegeoapi.org';

    const GITHUB_MAIN_REPO = 'http://www.github.com/YupItsZac/FreeGeoAPI';

}