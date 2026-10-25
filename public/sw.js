/**
 * Unikosa NA — Service Worker
 *
 * Strategy:
 *  - Precache the offline fallback + core icons on install.
 *  - Navigations: network-first, falling back to the cached page or /offline.
 *  - Static assets (build/, css, js, images): stale-while-revalidate.
 *  - Never cache non-GET, admin (/admin), or member portal requests.
 */
const VERSION = 'v1';
const CACHE = `unikosa-${VERSION}`;
const OFFLINE_URL = '/offline';
const PRECACHE = [
    OFFLINE_URL,
    '/icons/icon-192.png',
    '/icons/icon-512.png',
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE).then((cache) => cache.addAll(PRECACHE)).then(() => self.skipWaiting())
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys()
            .then((keys) => Promise.all(keys.filter((k) => k !== CACHE).map((k) => caches.delete(k))))
            .then(() => self.clients.claim())
    );
});

function isBypassed(url) {
    return url.pathname.startsWith('/admin')
        || url.pathname.startsWith('/member')
        || url.pathname.startsWith('/livewire')
        || url.pathname.startsWith('/captcha')
        || url.pathname.startsWith('/donate');
}

self.addEventListener('fetch', (event) => {
    const { request } = event;

    if (request.method !== 'GET') return;

    const url = new URL(request.url);
    if (url.origin !== self.location.origin || isBypassed(url)) return;

    // HTML navigations → network-first with offline fallback.
    if (request.mode === 'navigate') {
        event.respondWith(
            fetch(request)
                .then((response) => {
                    const copy = response.clone();
                    caches.open(CACHE).then((cache) => cache.put(request, copy));
                    return response;
                })
                .catch(() => caches.match(request).then((cached) => cached || caches.match(OFFLINE_URL)))
        );
        return;
    }

    // Static assets → stale-while-revalidate.
    if (/\.(?:css|js|png|jpg|jpeg|svg|gif|webp|ico|woff2?|ttf)$/i.test(url.pathname)) {
        event.respondWith(
            caches.match(request).then((cached) => {
                const network = fetch(request)
                    .then((response) => {
                        if (response && response.status === 200) {
                            const copy = response.clone();
                            caches.open(CACHE).then((cache) => cache.put(request, copy));
                        }
                        return response;
                    })
                    .catch(() => cached);
                return cached || network;
            })
        );
    }
});
