var staticCacheName = 'jmk-pwa-v2';
var filesToCache = [
  '/offline',
  '/manifest.json',
  '/logo.png',
  '/images/icons/icon-192x192.png',
  '/images/icons/icon-512x512.png'
];

// Cache on install - skipWaiting untuk langsung aktif
self.addEventListener('install', event => {
  self.skipWaiting();
  event.waitUntil(
    caches.open(staticCacheName).then(cache => {
      return Promise.all(
        filesToCache.map(url => cache.add(url).catch(() => null))
      );
    })
  );
});

// Clear cache on activate - claim clients untuk auto update
self.addEventListener('activate', event => {
  event.waitUntil(
    Promise.all([
      // Hapus cache lama
      caches.keys().then(cacheNames => {
        return Promise.all(
          cacheNames
            .filter(cacheName => cacheName.startsWith('pwa-'))
            .filter(cacheName => cacheName !== staticCacheName)
            .map(cacheName => caches.delete(cacheName))
        );
      }),
      // Ambil kontrol semua client tanpa perlu refresh
      self.clients.claim()
    ])
  );
});

// Serve from Cache - Network First untuk HTML, Cache First untuk assets
self.addEventListener('fetch', event => {
  const url = new URL(event.request.url);

  // Skip non-GET requests
  if (event.request.method !== 'GET') return;
  if (url.origin !== self.location.origin) return;

  // Jangan cache endpoint dinamis atau file besar agar PWA tidak terasa berat/stale.
  if (
    url.pathname.startsWith('/storage/') ||
    url.pathname.startsWith('/customer/media-proxy') ||
    url.pathname.startsWith('/kwitansi/') ||
    url.pathname.includes('/json') ||
    url.pathname.includes('/broadcast') ||
    url.pathname.includes('/chat/') ||
    url.pathname.includes('/admin-chat/')
  ) {
    event.respondWith(fetch(event.request));
    return;
  }

  // Network first untuk HTML pages (agar selalu dapat update terbaru)
  if (event.request.mode === 'navigate' || event.request.headers.get('accept')?.includes('text/html')) {
    event.respondWith(
      fetch(event.request)
        .then(response => {
          return response;
        })
        .catch(() => {
          return caches.match('/offline');
        })
    );
    return;
  }

  // Stale-while-revalidate untuk static assets supaya cepat tapi tetap update.
  event.respondWith(
    caches.open(staticCacheName).then(cache => {
      return caches.match(event.request).then(cachedResponse => {
        var fetchPromise = fetch(event.request).then(networkResponse => {
          if (networkResponse && networkResponse.ok) {
            cache.put(event.request, networkResponse.clone());
          }
          return networkResponse;
        }).catch(() => cachedResponse);

        return cachedResponse || fetchPromise;
      })
    }).catch(() => caches.match('/offline'))
  );
});

// Listen for message to force update
self.addEventListener('message', event => {
  if (event.data && event.data.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }
});

self.addEventListener('push', event => {
  const data = event.data ? event.data.json() : {};
  event.waitUntil(
    self.registration.showNotification(data.title, {
      body: data.body,
      icon: '/icons/icon-192x192.png',
      badge: '/icons/badge-72x72.png' // icon kecil di pojok
    })
  );

  // Opsional update badge di homescreen
  if ('setAppBadge' in navigator) {
    navigator.setAppBadge(data.count).catch(console.error);
  }
});
