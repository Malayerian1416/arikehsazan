const staticCacheName = "pwa-v" + new Date().getTime();
const filesToCache = [
    '/serviceworker.js',
    '/offline',
    '/css/app.css',
    '/css/fontiran.css',
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
    "/img/unplug-icon.png",
    "/img/arrow-down.svg",
    "/img/arrow-left.svg",
    "/img/arrow-right.svg",
    "/img/check_sample.jpg",
    "/img/comma.svg",
    "/img/login_bg.jpg",
    "/img/login_side_mobile_bg.png",
    "/img/login_window_side_img.png",
    "/img/logo.png",
    "/img/mainlogo.png",
    "/img/mobile_logo.png",
    '/img/pwa/icons/logo-48.png',
    '/img/pwa/icons/logo-72.png',
    '/img/pwa/icons/logo-96.png',
    '/img/pwa/icons/logo-120.png',
    '/img/pwa/icons/logo-128.png',
    '/img/pwa/icons/logo-144.png',
    '/img/pwa/icons/logo-152.png',
    '/img/pwa/icons/logo-180.png',
    '/img/pwa/icons/logo-192.png',
    '/img/pwa/icons/logo-256.png',
    '/img/pwa/icons/logo-384.png',
    '/img/pwa/icons/logo-512.png',
    "/img/pwa/splash/splash-2048-2732.png",
    "/img/pwa/splash/splash-640-1136.png",
    "/img/pwa/splash/splash-750-1334.png",
    "/img/pwa/splash/splash-828-1792.png",
    "/img/pwa/splash/splash-1080-2340.png",
    "/img/pwa/splash/splash-1125-2436.png",
    "/img/pwa/splash/splash-1170-2532.png",
    "/img/pwa/splash/splash-1242-2208.png",
    "/img/pwa/splash/splash-1242-2688.png",
    "/img/pwa/splash/splash-1284-2778.png",
    "/img/pwa/splash/splash-1536-2048.png",
    "/img/pwa/splash/splash-1668-2224.png",
    "/img/pwa/splash/splash-1668-2388.png",
    '/fonts/iranyekanwebblackfanum.eot',
    '/fonts/iranyekanwebboldfanum.eot',
    '/fonts/iranyekanwebextrablackfanum.eot',
    '/fonts/iranyekanwebextraboldfanum.eot',
    '/fonts/iranyekanweblightfanum.eot',
    '/fonts/iranyekanwebmediumfanum.eot',
    '/fonts/iranyekanwebregularfanum.eot',
    '/fonts/iranyekanwebthinfanum.eot',
    '/fonts/iranyekanwebboldfanum.svg',
    '/fonts/iranyekanwebextrablackfanum.svg',
    '/fonts/iranyekanwebextraboldfanum.svg',
    '/fonts/iranyekanweblightfanum.svg',
    '/fonts/iranyekanwebmediumfanum.svg',
    '/fonts/iranyekanwebregularfanum.svg',
    '/fonts/iranyekanwebthinfanum.svg',
    '/fonts/iranyekanwebblackfanum.svg',
    '/fonts/iranyekanwebblackfanum.ttf',
    '/fonts/iranyekanwebboldfanum.ttf',
    '/fonts/iranyekanwebextrablackfanum.ttf',
    '/fonts/iranyekanwebextraboldfanum.ttf',
    '/fonts/iranyekanweblightfanum.ttf',
    '/fonts/iranyekanwebmediumfanum.ttf',
    '/fonts/iranyekanwebregularfanum.ttf',
    '/fonts/iranyekanwebthinfanum.ttf',
    '/fonts/iranyekanwebblackfanum.woff',
    '/fonts/iranyekanwebboldfanum.woff',
    '/fonts/iranyekanwebextrablackfanum.woff',
    '/fonts/iranyekanwebextraboldfanum.woff',
    '/fonts/iranyekanweblightfanum.woff',
    '/fonts/iranyekanwebmediumfanum.woff',
    '/fonts/iranyekanwebregularfanum.woff',
    '/fonts/iranyekanwebthinfanum.woff',
    '/fonts/Lalezar-Regular.ttf',
    '/fonts/Sedaghat.woff2',
    '/fonts/BMitra.eot',
    '/fonts/BMitra.ttf',
    '/fonts/BMitra.woff',
    '/fonts/BMitraBold.eot',
    '/fonts/BMitraBold.ttf',
    '/fonts/BMitraBold.woff',
    '/fonts/IranNastaliq.ttf',
    '/fonts/vendor/@fortawesome/fontawesome-free/webfa-brands-400.woff',
    '/fonts/vendor/@fortawesome/fontawesome-free/webfa-brands-400.woff2',
    '/fonts/vendor/@fortawesome/fontawesome-free/webfa-regular-400.eot',
    '/fonts/vendor/@fortawesome/fontawesome-free/webfa-regular-400.svg',
    '/fonts/vendor/@fortawesome/fontawesome-free/webfa-regular-400.ttf',
    '/fonts/vendor/@fortawesome/fontawesome-free/webfa-regular-400.woff',
    '/fonts/vendor/@fortawesome/fontawesome-free/webfa-regular-400.woff2',
    '/fonts/vendor/@fortawesome/fontawesome-free/webfa-solid-900.eot',
    '/fonts/vendor/@fortawesome/fontawesome-free/webfa-solid-900.svg',
    '/fonts/vendor/@fortawesome/fontawesome-free/webfa-solid-900.ttf',
    '/fonts/vendor/@fortawesome/fontawesome-free/webfa-solid-900.woff',
    '/fonts/vendor/@fortawesome/fontawesome-free/webfa-solid-900.woff2',
    '/fonts/vendor/@fortawesome/fontawesome-free/webfa-brands-400.eot',
    '/fonts/vendor/@fortawesome/fontawesome-free/webfa-brands-400.svg',
    '/fonts/vendor/@fortawesome/fontawesome-free/webfa-brands-400.ttf',
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
