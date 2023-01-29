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

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
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

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
        ],
        'projects_doc' => [
            'driver' => 'local',
            'root' => storage_path('app/public/projects_doc'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],
        'contracts_doc' => [
            'driver' => 'local',
            'root' => storage_path('app/public/contracts_doc'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],
        'contractors_doc' => [
            'driver' => 'local',
            'root' => storage_path('app/public/contractors_doc'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],
        'invoice_payments_receipt' => [
            'driver' => 'local',
            'root' => storage_path('app/public/invoice_payments_receipt'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],
        'worker_payments_receipt' => [
            'driver' => 'local',
            'root' => storage_path('app/public/worker_payments_receipt'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],
        'invoice_images' => [
            'driver' => 'local',
            'root' => storage_path('app/public/invoice_images'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],
        'menu_item_icons' => [
            'driver' => 'local',
            'root' => storage_path('app/public/menu_item_icons'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],
        'signs' => [
            'driver' => 'local',
            'root' => storage_path('app/public/signs'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],
        'users_doc' => [
            'driver' => 'local',
            'root' => storage_path('app/public/users_doc'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],
        'menu_header_icons' => [
            'driver' => 'local',
            'root' => storage_path('app/public/menu_header_icons'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],
        'daily_leave_docs' => [
            'driver' => 'local',
            'root' => storage_path('app/public/daily_leave_docs'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],
        'hourly_leave_docs' => [
            'driver' => 'local',
            'root' => storage_path('app/public/hourly_leave_docs'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],
        'system_log' => [
            'driver' => 'local',
            'root' => storage_path('app/public/system_log'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
