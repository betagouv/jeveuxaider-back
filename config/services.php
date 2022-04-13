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
        'sync' => env('SENDINBLUE_SYNC')
    ],

    'slack' => [
        'hook_url' => env('SLACK_HOOK_URL'),
    ],

    'airtable' => [
        'key' => env('AIRTABLE_KEY'),
        'base' => env('AIRTABLE_BASE'),
        'sync' => env('AIRTABLE_SYNC')
    ],
];
