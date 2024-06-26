<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'everestpass' => [
        'session_max_lifetime' => env('EVERESTPASS_SESSION_MAX_LIFETIME'),
        'private_key' => [
            'passphrase' => env('EVERESTPASS_PRIVATE_KEY_PASSPHRASE'),
            'path' => env('EVERESTPASS_PRIVATE_KEY_PATH') ?: storage_path('everestpass-private.key'),
        ],
    ],

    'facebook' => [
        'enabled' => !empty(env('FACEBOOK_CLIENT_ID')) && !empty(env('FACEBOOK_CLIENT_SECRET')),
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => '/external-auth/facebook/callback',
    ],

    'google' => [
        'enabled' => !empty(env('GOOGLE_CLIENT_ID')) && !empty(env('GOOGLE_CLIENT_SECRET')),
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => '/external-auth/google/callback',
    ],

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.eu.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

];
