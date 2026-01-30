// Service Worker for Empapy Caffe PWA

// 1. Import Firebase Messaging Service Worker
// This restores the "missing" logic for Push Notifications
try {
  importScripts('/firebase-messaging-sw.js');
} catch (e) {
  console.error('Failed to import firebase-messaging-sw.js', e);
}

// 2. PWA Caching Strategy (Cache First, Network Fallback)
const CACHE_NAME = 'empapy-v6-scroll-fix-final';
const urlsToCache = [
  '/',
  '/css/app.css',
  '/js/app.js',
  '/icons/android/android-launchericon-192-192.png',
  '/icons/android/android-launchericon-512-512.png',
  '/manifest.json'
];

// Install event - cache essential files
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => cache.addAll(urlsToCache))
      .then(() => self.skipWaiting())
  );
});

// Activate event - clean up old caches
self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (cacheName !== CACHE_NAME) {
            return caches.delete(cacheName);
          }
        })
      );
    }).then(() => self.clients.claim())
  );
});

// Fetch event - Smart caching strategy
self.addEventListener('fetch', event => {
  // Ignore non-GET requests (like POST)
  if (event.request.method !== 'GET') return;

  const url = new URL(event.request.url);

  // CRITICAL: Never cache these routes (authentication, checkout, Stripe)
  // These need fresh CSRF tokens every time
  const neverCacheRoutes = [
    '/login',
    '/register',
    '/logout',
    '/password',
    '/checkout',
    '/cart',
    '/admin',
    '/api',
    '/sanctum',
    '/account'
  ];

  // Check if URL should never be cached
  const shouldNotCache = neverCacheRoutes.some(route => url.pathname.startsWith(route))
    || url.href.includes('stripe.com');

  if (shouldNotCache) {
    // Network only - no caching at all
    return;
  }

  // Check if this is a navigation request (HTML page)
  const isNavigationRequest = event.request.mode === 'navigate'
    || (event.request.headers.get('accept') && event.request.headers.get('accept').includes('text/html'));

  // Check if this is a static asset
  const isStaticAsset = /\.(css|js|png|jpg|jpeg|gif|svg|woff|woff2|ttf|ico|webp)$/i.test(url.pathname);

  if (isNavigationRequest) {
    // NETWORK FIRST for HTML pages - ensures fresh content after login/logout
    event.respondWith(
      fetch(event.request)
        .then(response => {
          // Cache the fresh response for offline use
          if (response && response.status === 200) {
            const responseToCache = response.clone();
            caches.open(CACHE_NAME).then(cache => {
              cache.put(event.request, responseToCache);
            });
          }
          return response;
        })
        .catch(() => {
          // If network fails, try cache (offline mode)
          return caches.match(event.request);
        })
    );
  } else if (isStaticAsset) {
    // CACHE FIRST for static assets (CSS, JS, images)
    event.respondWith(
      caches.match(event.request)
        .then(response => {
          if (response) {
            return response;
          }
          return fetch(event.request).then(response => {
            if (!response || response.status !== 200 || response.type !== 'basic') {
              return response;
            }
            const responseToCache = response.clone();
            caches.open(CACHE_NAME).then(cache => {
              cache.put(event.request, responseToCache);
            });
            return response;
          });
        })
    );
  }
  // For other requests, let browser handle normally (no caching)
});
