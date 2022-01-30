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
                'purpose' => 'maskable any'
            ],
            '72x72' => [
                'path' => '/img/pwa/icons/logo-72.png',
                'purpose' => 'maskable any'
            ],
            '96x96' => [
                'path' => '/img/pwa/icons/logo-96.png',
                'purpose' => 'maskable any'
            ],
            '120x120' => [
                'path' => '/img/pwa/icons/logo-120.png',
                'purpose' => 'maskable any'
            ],
            '128x128' => [
                'path' => '/img/pwa/icons/logo-128.png',
                'purpose' => 'maskable any'
            ],
            '144x144' => [
                'path' => '/img/pwa/icons/logo-144.png',
                'purpose' => 'maskable any'
            ],
            '152x152' => [
                'path' => '/img/pwa/icons/logo-152.png',
                'purpose' => 'maskable any'
            ],
            '180x180' => [
                'path' => '/img/pwa/icons/logo-180.png',
                'purpose' => 'maskable any'
            ],
            '192x192' => [
                'path' => '/img/pwa/icons/logo-192.png',
                'purpose' => 'maskable any'
            ],
            '384x384' => [
                'path' => '/img/pwa/icons/logo-384.png',
                'purpose' => 'maskable any'
            ],
            '512x512' => [
                'path' => '/img/pwa/icons/logo-512.png',
                'purpose' => 'maskable any'
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
                'name' => 'Shortcut Link 1',
                'description' => 'Shortcut Link 1 Description',
                'url' => '/shortcutlink1',
                'icons' => [
                    "src" => "/img/pwa/icons/logo-72x72.png",
                    "purpose" => "maskable any"
                ]
            ],
            [
                'name' => 'Shortcut Link 2',
                'description' => 'Shortcut Link 2 Description',
                'url' => '/shortcutlink2'
            ]
        ],
        'custom' => []
    ]
];
