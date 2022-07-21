<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    's3_prefix' => env('S3_PREFIX'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3", "rackspace"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        'media' => [
            'driver' => 's3',
            'root' => 'public',
            'key' => env('S3_AK'),
            'secret' => env('S3_SK'),
            'endpoint' => env('S3_ENDPOINT'),
            'region' => env('S3_REGION'),
            'bucket' => env('S3_BUCKET'),
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('S3_AK'),
            'secret' => env('S3_SK'),
            'endpoint' => env('S3_ENDPOINT'),
            'region' => env('S3_REGION'),
            'bucket' => env('S3_BUCKET'),
        ],

        's3_prod' => [
            'driver' => 's3',
            'key' => env('S3_PROD_AK'),
            'secret' => env('S3_PROD_SK'),
            'endpoint' => env('S3_PROD_ENDPOINT'),
            'region' => env('S3_PROD_REGION'),
            'bucket' => env('S3_PROD_BUCKET'),
        ],
    ],

];
