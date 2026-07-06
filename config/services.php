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

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'stripe' => [
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
     * National Unikosa site — OAuth2 provider (Laravel Passport).
     * The NA site authenticates members against the national identity provider.
     */
    'national' => [
        'base_url' => env('NATIONAL_OAUTH_BASE_URL', 'https://unikosa.sadorect.com'),
        'client_id' => env('NATIONAL_OAUTH_CLIENT_ID'),
        'client_secret' => env('NATIONAL_OAUTH_CLIENT_SECRET'),
        'redirect' => env('NATIONAL_OAUTH_REDIRECT'),
        // Passport endpoints (override only if the national site customised them).
        'authorize_path' => env('NATIONAL_OAUTH_AUTHORIZE_PATH', '/oauth/authorize'),
        'token_path' => env('NATIONAL_OAUTH_TOKEN_PATH', '/oauth/token'),
        'user_path' => env('NATIONAL_OAUTH_USER_PATH', '/api/user'),
        'scope' => env('NATIONAL_OAUTH_SCOPE', ''),
    ],

];
