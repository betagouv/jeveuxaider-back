<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
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

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\Models\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook' => [
            'secret' => env('STRIPE_WEBHOOK_SECRET'),
            'tolerance' => env('STRIPE_WEBHOOK_TOLERANCE', 300),
        ],
    ],

    'sendgrid' => [
        'api_key' => env('SENDGRID_API_KEY'),
    ],

    'franceconnect' => [
        'url' => env('FRANCECONNECT_URL'),
        'client_id' => env('FRANCECONNECT_CLIENT_ID'),
        'client_secret' => env('FRANCECONNECT_CLIENT_SECRET'),
    ],

    'sendinblue' => [
        'key' => env('SENDINBLUE_KEY'),
        'sync' => env('SENDINBLUE_SYNC'),
    ],

    'slack' => [
        'hook_url' => env('SLACK_HOOK_URL'),
        'log_channel' => env('SLACK_LOG_CHANNEL', 'produit-logs')
    ],

    'airtable' => [
        'key' => env('AIRTABLE_KEY'),
        'base' => env('AIRTABLE_BASE'),
        'sync' => env('AIRTABLE_SYNC'),
    ],

    'algolia' => [
        'app_id' => env('ALGOLIA_APP_ID'),
        'secret' => env('ALGOLIA_SECRET'),
    ],

    'emailable' => [
        'key' => env('API_EMAILABLE_KEY'),
    ],

    'activityclassifier' => [
        'token' => env('ACTIVITY_CLASSIFIER_TOKEN'),
        'free_token' => env('ACTIVITY_CLASSIFIER_FREE_TOKEN'),
    ],

    'qpv' => [
        'username' => env('QPV_USERNAME'),
        'password' => env('QPV_PASSWORD'),
        'sync' => env('QPV_SYNC'),
    ],

    'sms' => [
        'enable' => env('SMS_ENABLE', false),
        'reroute' => env('SMS_REROUTE', null)
    ]
];
