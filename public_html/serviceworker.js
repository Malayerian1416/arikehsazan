const staticCacheName = "pwa-v" + new Date().getTime();
const filesToCache = [
    '/serviceworker.js',
    '/offline',
    '/css/app.css',
    '/css/d_dashboard.css',
    '/css/p_dashboard.css',
    '/css/login.css',
    '/css/mobile_login.css',
    '/css/persianDatepicker-default.css',
    '/js/app.js',
    '/js/d_dashboard.js',
    '/js/jquery.mask.js',
    '/js/kernel.js',
    '/js/login.js',
    '/js/mobile_login.js',
    '/js/p_dashboard.js',
    '/js/persianDatepicker.min.js',
    'img/unplug-icon.png',
    'img/mobile_logo.png',
    '/img/pwa/icons/logo-48.png',
    '/img/pwa/icons/logo-72.png',
    '/img/pwa/icons/logo-96.png',
    '/img/pwa/icons/logo-120.png',
    '/img/pwa/icons/logo-128.png',
    '/img/pwa/icons/logo-144.png',
    '/img/pwa/icons/logo-152.png',
    '/img/pwa/icons/logo-180.png',
    '/img/pwa/icons/logo-192.png',
    '/img/pwa/icons/logo-384.png',
    '/img/pwa/icons/logo-512.png',
    '/img/pwa/splash/splash-640-1136.png',
    '/img/pwa/splash/splash-750-1334.png',
    '/img/pwa/splash/splash-828-1792.png',
    '/img/pwa/splash/splash-1080-2340.png',
    '/img/pwa/splash/splash-1125-2436.png',
    '/img/pwa/splash/splash-1170-2532.png',
    '/img/pwa/splash/splash-1242-2208.png',
    '/img/pwa/splash/splash-1242-2688.png',
    '/img/pwa/splash/splash-1284-2778.png',
    '/img/pwa/splash/splash-1536-2048.png',
    '/img/pwa/splash/splash-1668-2224.png',
    '/img/pwa/splash/splash-1668-2388.png',
    '/img/pwa/splash/splash-2048-2732.png',
    '/fonts/Lalezar-Regular.ttf'
];

// Cache on install
self.addEventListener("install", event => {
    this.skipWaiting();
    event.waitUntil(
        caches.open(staticCacheName)
            .then(cache => {
                return cache.addAll(filesToCache);
            })
    )
});

// Clear cache on activate
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames
                    .filter(cacheName => (cacheName.startsWith("pwa-")))
                    .filter(cacheName => (cacheName !== staticCacheName))
                    .map(cacheName => caches.delete(cacheName))
            );
        })
    );
});

// Serve from Cache
self.addEventListener("fetch", event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => {
                return response || fetch(event.request);
            })
            .catch(() => {
                return caches.match('offline');
            })
    )
});
