/**
 * Firebase Messaging Service Worker
 * Handles background push notifications
 */

importScripts('https://www.gstatic.com/firebasejs/10.7.1/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging-compat.js');

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
firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();

// Handle background messages
messaging.onBackgroundMessage((payload) => {
    console.log('[FCM SW] Background message received:', payload);

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

    // Show notification
    self.registration.showNotification(notificationTitle, notificationOptions);

    // Play sound (will be handled by the page if open)
    self.clients.matchAll({ type: 'window', includeUncontrolled: true }).then((clients) => {
        clients.forEach((client) => {
            client.postMessage({
                type: 'NOTIFICATION_RECEIVED',
                payload: payload
            });
        });
    });
});

// Handle notification click
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

console.log('[FCM SW] Firebase Messaging Service Worker loaded');
