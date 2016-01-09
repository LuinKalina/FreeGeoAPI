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

    const MAILGUN_API_KEY = 'key-9345b04beb5cf698fd0d4f64363ad551';
    const MAILGUN_API_URL = 'https://api.mailgun.net/v3/mailer.freegeoapi.org/messages';
    const API_STATUS_CHECK_SESSION_TOKEN = 'a685fb3bd11540e7dcbd75327e273170';
    const API_STATUS_CHECK_PUBLIC_KEY = '3ce56e8a4fbada15e5d68bb0dcabb3de';

    const FROM_EMAIL_ADDRESS = 'noreply@freegeoapi.org';
    const ENVIRONMENT = 'dev';

    const PUBLIC_ADDRESS = 'freegeoapi.org';
    const WEB_PROTOCOL_PROD = 'https://';
    const WEB_PROTOCOL_DEV = 'http://';

    const WEB_ADDRESS_LOCAL = 'http://freegeo.dev';

    const BASE_URL_PROD = 'https://www.freegeoapi.org';

    const GITHUB_MAIN_REPO = 'http://www.github.com/YupItsZac/FreeGeoAPI';

}