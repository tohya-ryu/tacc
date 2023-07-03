<?php

# Application Configuration
#
$config['version'] = '1.0.0';
$config['app_name'] = 'tacc';
$config['available_languages'] = [
    'en'    => 'en',
    'en-US' => 'en',
];
$config['default_language'] = 'en';

# Locales
#
# IETF Language Tag => Locale Class Name
#
$config['locales']['en'] = 'LocaleEn';

# Cookies
#
$config['cookies']['auth-login-memory'] = [
    'value'            => null,
    'duration-days'    => 14,
    'duration-hours'   => 0,
    'duration-minutes' => 0,
    'duration-seconds' => 0,
    'domain'           => $_SERVER['SERVER_NAME'],
    'tls-only'         => true,
    'http-only'        => true,
    'samesite'         => null # Lax, None, Strict
];
