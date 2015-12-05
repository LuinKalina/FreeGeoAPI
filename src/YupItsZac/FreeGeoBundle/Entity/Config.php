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

    const MAILGUN_API_KEY = 'key-46cb002424c2a5f4dc6c4c18b6a07303';
    const MAILGUN_API_URL = 'https://api.mailgun.net/v3/yupitszac.com/messages';

    const FROM_EMAIL_ADDRESS = 'freegeo@yupitszac.com';
    const ENVIRONMENT = 'dev';

    const PUBLIC_ADDRESS = 'www.freegeoapi.org';
    const WEB_PROTOCOL_PROD = 'https://';
    const WEB_PROTOCOL_DEV = 'http://';

    const BASE_URL_PROD = 'https://www.freegeoapi.org';

    const GITHUB_MAIN_REPO = 'http://www.github.com/YupItsZac/FreeGeoAPI';

}