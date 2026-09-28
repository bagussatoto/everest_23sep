const CACHE_NAME = 'MGK-V2026-0022';
const ASSETS_TO_CACHE = [
//  '/',
  '/index.php/Login',
  '/favicon.png'
];

// Install Service Worker & Cache Core Assets
self.addEventListener('install', event => {
    self.skipWaiting();
  event.waitUntil(
    caches.open(CACHE_NAME).then(cache => {
      return cache.addAll(ASSETS_TO_CACHE);
    })
  );
});

// Activate Service Worker & Clean Old Caches
self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cache => {
          if (cache !== CACHE_NAME) {
            return caches.delete(cache);
          }
        })
      );
    })
  );
});

self.addEventListener('fetch', event => {
    if (!event.request.url.startsWith('http')) return;

    // Jangan intervensi request non-GET (POST, PUT, DELETE, dll)
    if (event.request.method !== 'GET') return;

// Jika request adalah navigasi (memuat halaman HTML/PHP baru) -> Gunakan Network First
if (event.request.mode === 'navigate') {
    event.respondWith(
        fetch(event.request)
            .then(networkResponse => {
            // Opsional: simpan ke cache untuk mode offline
            return caches.open(CACHE_NAME).then(cache => {
                cache.put(event.request, networkResponse.clone());
                return networkResponse;
            });
            })
            .catch(() => {
                // Jika offline, coba ambil halaman terakhir dari cache
                return caches.match(event.request);
            })
);
return;
}

    // Tentukan apakah ini aset statis (CSS, JS, Image, Font)
    const staticDestinations = ['style', 'script', 'image', 'font'];
    const isStatic = staticDestinations.includes(event.request.destination);

    // Jika BUKAN aset statis (artinya XHR/AJAX/Fetch dinamis), biarkan langsung ke network tanpa cache
    if (!isStatic) {
        return;
    }

    // Untuk aset statis (CSS, JS, Gambar) -> Gunakan Stale-While-Revalidate (seperti sebelumnya)
event.respondWith(
    caches.match(event.request).then(cachedResponse => {
        if (cachedResponse) {
            fetch(event.request).then(networkResponse => {
                if (networkResponse.status === 200) {
                    caches.open(CACHE_NAME).then(cache => cache.put(event.request, networkResponse));
                }
            }).catch(err => console.log('Offline, no background update'));
                return cachedResponse;
        }
    return fetch(event.request);
    })
);
});
