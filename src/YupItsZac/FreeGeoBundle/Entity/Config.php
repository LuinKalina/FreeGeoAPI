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

    const MAILGUN_API_KEY = 'key-78cb2ed8a825cd58ad869a766a16389f';
    const MAILGUN_API_URL = 'https://api.mailgun.net/v3/mailer.freegeoapi.org';

    const FROM_EMAIL_ADDRESS = 'noreply@freegeoapi.org';
    const ENVIRONMENT = 'dev';

    const PUBLIC_ADDRESS = 'freegeoapi.org';
    const WEB_PROTOCOL_PROD = 'https://';
    const WEB_PROTOCOL_DEV = 'http://';

    const WEB_ADDRESS_LOCAL = 'http://freegeo.dev';

    const BASE_URL_PROD = 'https://freegeoapi.org';

    const GITHUB_MAIN_REPO = 'http://www.github.com/YupItsZac/FreeGeoAPI';

}