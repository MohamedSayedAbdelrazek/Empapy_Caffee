/**
 * Empapy Caffe - Service Worker
 * Provides offline functionality and caching
 */

importScripts('https://www.gstatic.com/firebasejs/10.7.1/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging-compat.js');

const CACHE_NAME = 'empapy-caffe-v1';
const OFFLINE_URL = '/offline';

// Assets to cache immediately on install
const PRECACHE_ASSETS = [
    '/',
    '/offline',
    '/css/app.css',
    '/css/creative-effects.css',
    '/css/enhancements.css',
    '/css/user-dropdown.css',
    '/css/pwa-install.css',
    '/js/app.js',
    '/js/pwa.js',
    '/logo.jpg',
    '/icons/android/android-launchericon-192-192.png',
    '/icons/android/android-launchericon-512-512.png',
    'https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap'
];

// Install event - cache essential assets
self.addEventListener('install', (event) => {
    console.log('[SW] Installing Service Worker...');

    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => {
                console.log('[SW] Caching app shell...');
                return cache.addAll(PRECACHE_ASSETS);
            })
            .then(() => {
                console.log('[SW] Install complete!');
                return self.skipWaiting();
            })
            .catch((err) => {
                console.log('[SW] Cache failed:', err);
            })
    );
});

// Activate event - clean up old caches
self.addEventListener('activate', (event) => {
    console.log('[SW] Activating Service Worker...');

    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames
                    .filter((name) => name !== CACHE_NAME)
                    .map((name) => {
                        console.log('[SW] Deleting old cache:', name);
                        return caches.delete(name);
                    })
            );
        }).then(() => {
            console.log('[SW] Claiming clients...');
            return self.clients.claim();
        })
    );
});

// Fetch event - serve from cache or network
self.addEventListener('fetch', (event) => {
    const request = event.request;
    const url = new URL(request.url);

    // Skip non-GET requests
    if (request.method !== 'GET') return;

    // Skip admin routes (always fresh)
    if (url.pathname.startsWith('/admin')) return;

    // Skip API routes (always fresh)
    if (url.pathname.startsWith('/api')) return;

    // Handle navigation requests
    if (request.mode === 'navigate') {
        event.respondWith(
            fetch(request)
                .then((response) => {
                    // Cache successful responses
                    if (response.ok) {
                        const responseClone = response.clone();
                        caches.open(CACHE_NAME).then((cache) => {
                            cache.put(request, responseClone);
                        });
                    }
                    return response;
                })
                .catch(() => {
                    // Offline - return cached page or offline page
                    return caches.match(request)
                        .then((cachedResponse) => {
                            if (cachedResponse) {
                                return cachedResponse;
                            }
                            return caches.match(OFFLINE_URL);
                        });
                })
        );
        return;
    }

    // Handle static assets - Cache First strategy
    if (isStaticAsset(url.pathname)) {
        event.respondWith(
            caches.match(request)
                .then((cachedResponse) => {
                    if (cachedResponse) {
                        // Return cached version, update in background
                        fetchAndCache(request);
                        return cachedResponse;
                    }
                    return fetchAndCache(request);
                })
        );
        return;
    }

    // Default - Network First with cache fallback
    event.respondWith(
        fetch(request)
            .then((response) => {
                if (response.ok) {
                    const responseClone = response.clone();
                    caches.open(CACHE_NAME).then((cache) => {
                        cache.put(request, responseClone);
                    });
                }
                return response;
            })
            .catch(() => {
                return caches.match(request);
            })
    );
});

// Helper function to check if URL is a static asset
function isStaticAsset(pathname) {
    const staticExtensions = [
        '.css', '.js', '.png', '.jpg', '.jpeg', '.gif', '.svg',
        '.woff', '.woff2', '.ttf', '.eot', '.ico', '.webp'
    ];
    return staticExtensions.some(ext => pathname.endsWith(ext));
}

// Helper function to fetch and cache
function fetchAndCache(request) {
    // Only cache http/https requests - skip chrome-extension and other schemes
    if (!request.url.startsWith('http://') && !request.url.startsWith('https://')) {
        return fetch(request);
    }

    return fetch(request)
        .then((response) => {
            if (response.ok) {
                const responseClone = response.clone();
                caches.open(CACHE_NAME).then((cache) => {
                    // Double check URL before caching
                    if (request.url.startsWith('http://') || request.url.startsWith('https://')) {
                        cache.put(request, responseClone);
                    }
                });
            }
            return response;
        })
        .catch(() => {
            return caches.match(request);
        });
}

// Handle push notifications (future use)
// === FIREBASE MESSAGING LOGIC ===

// Firebase configuration
const firebaseConfig = {
    apiKey: "AIzaSyC9xBlrJOtsMPWgGwJnMLmuVkYiCDCJF_M",
    authDomain: "empapy-caffe.firebaseapp.com",
    projectId: "empapy-caffe",
    storageBucket: "empapy-caffe.firebasestorage.app",
    messagingSenderId: "345834961954",
    appId: "1:345834961954:web:d9e1c1df8e54be93935e7b",
    measurementId: "G-47B8S82J14"
};

// Initialize Firebase
try {
    firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();

    // Handle background messages
    messaging.onBackgroundMessage((payload) => {
        console.log('[FCM SW] Background message received:', payload);

        // Check if app is in foreground (focused)
        self.clients.matchAll({ type: 'window', includeUncontrolled: true }).then((windowClients) => {
            const isFocused = windowClients.some(client => client.focused);

            // Notify clients regardless (so they can update badges/lists/play sound if they want)
            windowClients.forEach((client) => {
                client.postMessage({
                    type: 'NOTIFICATION_RECEIVED',
                    payload: payload
                });
            });

            // IF FOCUSED: Do NOT show system notification (App handles Toast + Sound)
            // This prevents "Double Sound" (System Ding + App Mp3)
            if (isFocused) {
                console.log('[FCM SW] App in foreground, suppressing system notification.');
                return;
            }

            // Support both notification (from backend) and data-only messages
            const title = payload.notification?.title || payload.data?.title || 'إمبابي كافيه';
            const body = payload.notification?.body || payload.data?.body || '';
            const icon = payload.notification?.icon || payload.data?.icon || '/icons/android/android-launchericon-192-192.png';

            const notificationTitle = title;
            const notificationOptions = {
                body: body,
                icon: icon,
                badge: '/icons/android/android-launchericon-72-72.png',
                vibrate: [100, 50, 100],
                tag: payload.data?.type || 'general',
                renotify: true,
                requireInteraction: true,
                data: payload.data || {},
                actions: [
                    { action: 'open', title: 'فتح' },
                    { action: 'close', title: 'إغلاق' }
                ]
            };

            // Show notification (Only in background)
            self.registration.showNotification(notificationTitle, notificationOptions);
        });
    });
} catch (e) {
    console.error('[FCM SW] Failed to initialize Firebase:', e);
}

// Handle notification click (Merged Logic)
self.addEventListener('notificationclick', (event) => {
    console.log('[FCM SW] Notification clicked:', event);
    event.notification.close();

    const urlToOpen = event.notification.data?.url || '/';

    if (event.action === 'close') {
        return;
    }

    // Open or focus the relevant page
    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then((windowClients) => {
            // Check if there's already a window open
            for (const client of windowClients) {
                if (client.url.includes(urlToOpen) && 'focus' in client) {
                    return client.focus();
                }
            }
            // If no window is open, open a new one
            if (clients.openWindow) {
                return clients.openWindow(urlToOpen);
            }
        })
    );
});

console.log('[SW+FCM] Service Worker loaded!');
