const CACHE_VERSION = 2.12;
const file_ext = [".jpg",".png",".svg",".bmp",".ttf",".eot",".woff",".woff2","css",".js",".map"];
let Cache = 'static-cache-v' + CACHE_VERSION;
let strategy = "cache-first";
const filesToCache = [
    `/serviceworker.js?v=${CACHE_VERSION}`,
    `/offline?v=${CACHE_VERSION}`,
    `/css/app.css?v=${CACHE_VERSION}`,
    `/css/d_dashboard.css?v=${CACHE_VERSION}`,
    `/css/p_dashboard.css?v=${CACHE_VERSION}`,
    `/css/login.css?v=${CACHE_VERSION}`,
    `/css/mobile_login.css?v=${CACHE_VERSION}`,
    `/css/persianDatepicker-default.css?v=${CACHE_VERSION}`,
    `/js/app.js?v=${CACHE_VERSION}`,
    `/js/d_dashboard.js?v=${CACHE_VERSION}`,
    `/js/jquery.mask.js?v=${CACHE_VERSION}`,
    `/js/kernel.js?v=${CACHE_VERSION}`,
    `/js/login.js?v=${CACHE_VERSION}`,
    `/js/mobile_login.js?v=${CACHE_VERSION}`,
    `/js/p_dashboard.js?v=${CACHE_VERSION}`,
    `/js/persianDatepicker.min.js?v=${CACHE_VERSION}`,
    `/img/no-internet.png?v=${CACHE_VERSION}`,
    `/img/arrow-down.svg?v=${CACHE_VERSION}`,
    `/img/arrow-left.svg?v=${CACHE_VERSION}`,
    `/img/arrow-right.svg?v=${CACHE_VERSION}`,
    `/img/check_sample.jpg?v=${CACHE_VERSION}`,
    `/img/comma.svg?v=${CACHE_VERSION}`,
    `/img/login_bg.jpg?v=${CACHE_VERSION}`,
    `/img/login_side_mobile_bg.png?v=${CACHE_VERSION}`,
    `/img/login_window_side_img.png?v=${CACHE_VERSION}`,
    `/img/logo.png?v=${CACHE_VERSION}`,
    `/img/mainlogo.png?v=${CACHE_VERSION}`,
    `/img/mobile_logo.png?v=${CACHE_VERSION}`,
    `/img/notification_badge.png?v=${CACHE_VERSION}`,
    `/img/new_notification.png?v=${CACHE_VERSION}`,
];
self.addEventListener("install", event => {
    this.skipWaiting();
    event.waitUntil(
        caches.open(Cache)
            .then((cache) => {
                return cache.addAll(filesToCache);
            })

    )
});

self.addEventListener('activate', event => {
    this.skipWaiting();
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames
                    .filter(cacheName => (cacheName.startsWith("static-cache-v") || cacheName.startsWith("pwa-v")))
                    .filter(cacheName => (cacheName !== Cache))
                    .map(cacheName => caches.delete(cacheName))
            );
        })
    );
});

self.addEventListener('fetch', (event) => {
    if (file_ext.some(v => event.request.url.toLowerCase().includes(v)) && strategy === "cache-first") {
        event.respondWith(caches.open(Cache).then((cache) => {
            event.request.url = event.request.url + `?v=${CACHE_VERSION}`;
            return cache.match(event.request.url).then((cachedResponse) => {
                if (cachedResponse) {
                    return cachedResponse;
                }
                return fetch(event.request).then((fetchedResponse) => {
                    cache.put(event.request , fetchedResponse.clone());
                    return fetchedResponse;
                }).catch(err => {return caches.match('offline');});
            });
        }))
    }
    else {
        event.respondWith(
            fetch(event.request).then((fetchedResponse) => {
                return fetchedResponse;
            }).catch(err => {return caches.match('offline');})
        )
    }
});

self.addEventListener("notificationclick",(event) => {
    event.waitUntil(() => {self.clients.openWindow(event.notification.data.action_route);event.notification.close();});
});

self.addEventListener('push', (event) => {
    let msg = event.data.json();
    event.waitUntil(
        self.clients.matchAll({ type: 'window' }).then(function(clientList) {
            const client = clientList.find(c => c.visibilityState === 'visible') // <- This validation
            if (event.data && !client) {
                event.waitUntil(self.registration.showNotification(msg.title, {
                    body: msg.body,
                    icon: msg.icon,
                    badge: msg.badge,
                    data: msg.data,
                    tag: 'push',
                    renotify: true
                }));
            }
        })
    )
})
