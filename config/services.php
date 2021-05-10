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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'onesignal' => [
        'app_id' => env('ONESIGNAL_APP_ID'),
        'rest_api_key' => env('ONESIGNAL_REST_API_KEY'),

        'dashboard_app_id' => env('ONESIGNAL_DASHBOARD_APP_ID'),
        'dashboard_rest_api_key' => env('ONESIGNAL_DASHBOARD_REST_API_KEY'),

        'restaurant_app_id' => env('ONESIGNAL_RESTAURANT_APP_ID'),
        'restaurant_rest_api_key' => env('ONESIGNAL_RESTAURANT_REST_API_KEY'),
        'restaurant_app_android_channel_id' => env('ONESIGNAL_RESTAURANT_APP_ANDROID_CHANNEL_ID'),
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID', ''),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET', ''),
        'redirect' => config('app.url').'/login/facebook/callback',
    ],
    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID', ''),
        'client_secret' => env('GOOGLE_CLIENT_SECRET', ''),
        'redirect' => config('app.url').'/login/google/callback',
    ],
    'twitter' => [
        'client_id' => env('TWITTER_CLIENT_ID', ''),
        'client_secret' => env('TWITTER_CLIENT_SECRET', ''),
        'redirect' => config('app.url').'/login/twitter/callback',
    ],
    'linkedin' => [
        'client_id' => env('LINKEDIN_CLIENT_ID', ''),
        'client_secret' => env('LINKEDIN_CLIENT_SECRET', ''),
        'redirect' => config('app.url').'/login/linkedin/callback',
    ],

    'tookan' => [
        'status' => env('TOOKAN_STATUS', false),
        'base_url' => env('TOOKAN_BASE_URL', 'https://api.tookanapp.com/v2/'),
        'api_key' => env('TOOKAN_API_KEY', '5a6a6086f44308454b472f71475b25401de1c1f923d873385f1d02'),
        'default_team_id' => '890117',
    ],

];
