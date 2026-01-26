// Service Worker for Empapy Caffe PWA

// 1. Import Firebase Messaging Service Worker
// This restores the "missing" logic for Push Notifications
try {
  importScripts('/firebase-messaging-sw.js');
} catch (e) {
  console.error('Failed to import firebase-messaging-sw.js', e);
}

// 2. PWA Caching Strategy (Cache First, Network Fallback)
const CACHE_NAME = 'empapy-v2';
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

// Fetch event - serve from cache, fallback to network
self.addEventListener('fetch', event => {
  // Ignore non-GET requests (like POST)
  if (event.request.method !== 'GET') return;

  // Ignore admin/api routes from caching to ensure fresh data
  const url = new URL(event.request.url);
  if (url.pathname.startsWith('/admin') || url.pathname.startsWith('/api')) {
    return;
  }

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
          caches.open(CACHE_NAME)
            .then(cache => {
              cache.put(event.request, responseToCache);
            });
          return response;
        });
      })
  );
});
