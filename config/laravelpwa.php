<?php

return [
    'name' => 'LaravelPWA',
    'manifest' => [
        'name' => 'اریکه سازان توس',
        'short_name' => 'اریکه سازان توس',
        'start_url' => '/',
        'background_color' => '#103d41',
        'theme_color' => '#103d41',
        'display' => 'standalone',
        'orientation'=> 'portrait',
        'status_bar'=> '#103d41',
        'icons' => [
            '48x48' => [
                'path' => '/img/pwa/icons/logo-48.png',
                'type' => 'image/png',
                'purpose' => 'maskable'
            ],
            '72x72' => [
                'path' => '/img/pwa/icons/logo-72.png',
                'type' => 'image/png',
                'purpose' => 'maskable'
            ],
            '96x96' => [
                'path' => '/img/pwa/icons/logo-96.png',
                'type' => 'image/png',
                'purpose' => 'any'
            ],
            '120x120' => [
                'path' => '/img/pwa/icons/logo-120.png',
                'type' => 'image/png',
                'purpose' => 'any'
            ],
            '128x128' => [
                'path' => '/img/pwa/icons/logo-128.png',
                'type' => 'image/png',
                'purpose' => 'any'
            ],
            '144x144' => [
                'path' => '/img/pwa/icons/logo-144.png',
                'type' => 'image/png',
                'purpose' => 'any'
            ],
            '152x152' => [
                'path' => '/img/pwa/icons/logo-152.png',
                'type' => 'image/png',
                'purpose' => 'any'
            ],
            '180x180' => [
                'path' => '/img/pwa/icons/logo-180.png',
                'type' => 'image/png',
                'purpose' => 'any'
            ],
            '192x192' => [
                'path' => '/img/pwa/icons/logo-192.png',
                'type' => 'image/png',
                'purpose' => 'any'
            ],
            '256x256' => [
                'path' => '/img/pwa/icons/logo-256.png',
                'type' => 'image/png',
                'purpose' => 'any'
            ],
            '384x384' => [
                'path' => '/img/pwa/icons/logo-384.png',
                'type' => 'image/png',
                'purpose' => 'any'
            ],
            '512x512' => [
                'path' => '/img/pwa/icons/logo-512.png',
                'type' => 'image/png',
                'purpose' => 'any'
            ],
        ],
        'splash' => [
            '640x1136' => '/img/pwa/splash/splash-640-1136.png',
            '750x1334' => '/img/pwa/splash/splash-750-1334.png',
            '828x1792' => '/img/pwa/splash/splash-828-1792.png',
            '1080x2340' => '/img/pwa/splash/splash-1080-2340.png',
            '1125x2436' => '/img/pwa/splash/splash-1125-2436.png',
            '1170x2532' => '/img/pwa/splash/splash-1170-2532.png',
            '1242x2208' => '/img/pwa/splash/splash-1242-2208.png',
            '1242x2688' => '/img/pwa/splash/splash-1242-2688.png',
            '1284x2778' => '/img/pwa/splash/splash-1284-2778.png',
            '1536x2048' => '/img/pwa/splash/splash-1536-2048.png',
            '1668x2224' => '/img/pwa/splash/splash-1668-2224.png',
            '1668x2388' => '/img/pwa/splash/splash-1668-2388.png',
            '2048x2732' => '/img/pwa/splash/splash-2048-2732.png',
        ],
        'shortcuts' => [
            [
                'name' => 'اریکه سازان',
                'description' => 'نرم افزار مدیریت پروژه',
                'url' => '/',
                'icons' => [
                    "src" => "/img/pwa/icons/logo-96.png",
                    "type" => "image/png",
                    "purpose" => "any"
                ]
            ]
        ],
        'custom' => []
    ]
];
