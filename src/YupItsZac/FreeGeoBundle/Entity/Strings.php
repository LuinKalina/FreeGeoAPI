<?php
/**
 * Created by PhpStorm.
 * User: YupItsZac
 * Date: 05.12.15
 * Time: 16:25
 */

namespace YupItsZac\FreeGeoBundle\Entity;


class Strings {

    const APP_REGISTER_EMAIL_SUBJECT = 'New App Registration - FreeGeo API';
    const TWITTER_URL = 'http://twitter.com/FreeGeoAPI';
    const TWITTER_USER = '@FreeGeoAPI';


    //API Email Strings

    const API_KEY_RESET_EMAIL_SUBJECT = 'Your new API Keys';

    //API Response Strings

    const API_MSG_INVALID_SESSION = 'The session token provided is invalid.';
    const API_MSG_MISSING_CREDENTIALS = 'API credentials required. Provide an authorized keyset.';
    const API_MSG_REVOKED_CREDENTIALS = 'The credentials provided have been revoked.';
    const API_MSG_SUCCESS = 'Completed without errors.';
    const API_MSG_KEY_RESET_SUCCESS = 'Your API credentials have been reset. Please check your email for the new keyset.';
    const API_MSG_KEY_RESET_FAILED_DB = 'Unable to save new keyset to DB.';
    const API_MSG_ERROR_LOCATING_SESSION = 'Unable to locate app information from session token.';
    const API_MSG_STATUS_ONLINE = 'Online';
    const API_MSG_SOME_SERVICES_OFFLINE = 'One or more FreeGEO API services are currently offline. :(';
    const API_MSG_ALL_SERVICES_ONLINE = 'All FreeGeo API services are online and operating normally. :)';
    const API_MSG_APP_CONVERSION_REQUIRED = 'App conversion required to authenticate. https://freegeoapi.org/conversion/apps for more information.';
    const API_MSG_MISSING_PARAMS = 'Some of the required parameters are missing. Please refer to the FreeGeo docs for help.';

    const API_REASON_INVALID_SESSION = 'Invalid session.';
    const API_REASON_SUCCESS = 'completed action';
    const API_REASON_FORBIDDEN = 'Unauthorized.';
    const API_REASON_DB_ERROR = 'db-error';
    const API_REASON_APP_CONVERSION = 'Invalid App State';
    const API_REASON_MISSING_PARAMS = 'Required parameters missing.';

    const API_STATUS_FATAL = 'fatal';
    const API_STATUS_SUCCESS = 'success';

    const REGISTRATION_EMAIL_MSG_WELCOME = 'Welcome to FreeGeo API!';
    const REGISTRATION_EMAIL_SUBJECT_WELCOME = 'Your FreeGeo Developer Account';

    const UI_MSG_APP_UPDATED = 'Your app has been successfully updated!';
    const UI_MSG_APP_KEYS_RESET = 'Your app keyset has been refreshed!';
    const UI_MSG_INVALID_PRIVATE_KEY = 'Invalid private key.';
}