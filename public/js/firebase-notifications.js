/**
 * Firebase Push Notifications - Frontend Initialization
 * Handles permission, token registration, and foreground notifications
 */

(function () {
    'use strict';

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

    const VAPID_KEY = 'BB_6B4n5vvVhVvzJlQSJhtVDgCZ-5BMHQR_vaZsJt3862E59iG5NWBncya4kqNeG7suv-d-gFn6zSo79ne3IzJI';

    // Debug mode - set to false for production
    const DEBUG_MODE = false;

    // Conditional logging wrapper
    function fcmLog(...args) {
        if (DEBUG_MODE) {
            console.log(...args);
        }
    }

    let messaging = null;
    let notificationSound = null;
    let swRegistration = null;

    // Initialize Firebase
    async function initFirebase() {
        try {
            // Check if Firebase is already loaded
            if (typeof firebase === 'undefined') {
                fcmLog('[FCM] Loading Firebase SDK...');
                await loadFirebaseSDK();
            }

            // Check if already initialized
            if (!firebase.apps.length) {
                firebase.initializeApp(firebaseConfig);
            }
            messaging = firebase.messaging();

            // Wait for existing Service Worker (PWA)
            swRegistration = await navigator.serviceWorker.ready;
            fcmLog('[FCM] Using existing Service Worker:', swRegistration.scope);

            // NOTE: useServiceWorker is deprecated in Firebase v9+
            // The service worker registration is passed to getToken instead

            // Handle foreground messages - this receives messages when browser tab is focused
            fcmLog('[FCM] Setting up onMessage listener...');
            messaging.onMessage((payload) => {
                fcmLog('[FCM] 🔔 Foreground message RECEIVED:', payload);
                handleForegroundNotification(payload);
            });
            fcmLog('[FCM] onMessage listener attached successfully');

            // Also listen via navigator for broadcast messages
            navigator.serviceWorker.addEventListener('message', (event) => {
                fcmLog('[FCM] Service Worker message event:', event.data);
                if (event.data && event.data.type === 'NOTIFICATION_RECEIVED') {
                    handleForegroundNotification(event.data.payload);
                }
            });

            // Initialize notification sound
            notificationSound = new Audio('/sounds/notification.mp3');
            notificationSound.volume = 0.5;

            return true;
        } catch (error) {
            console.error('[FCM] Initialization error:', error);
            return false;
        }
    }

    // Load Firebase SDK dynamically
    function loadFirebaseSDK() {
        return new Promise((resolve, reject) => {
            const script1 = document.createElement('script');
            script1.src = 'https://www.gstatic.com/firebasejs/10.7.1/firebase-app-compat.js';
            script1.onload = () => {
                const script2 = document.createElement('script');
                script2.src = 'https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging-compat.js';
                script2.onload = resolve;
                script2.onerror = reject;
                document.head.appendChild(script2);
            };
            script1.onerror = reject;
            document.head.appendChild(script1);
        });
    }

    // Request notification permission and get token
    async function requestNotificationPermission() {
        try {
            const permission = await Notification.requestPermission();

            if (permission === 'granted') {
                fcmLog('[FCM] Notification permission granted');
                const token = await getToken();
                return token;
            } else {
                fcmLog('[FCM] Notification permission denied');
                return null;
            }
        } catch (error) {
            console.error('[FCM] Permission request error:', error);
            return null;
        }
    }

    // Get FCM token
    async function getToken() {
        try {
            if (!messaging) {
                await initFirebase();
            }

            if (!swRegistration) {
                swRegistration = await navigator.serviceWorker.ready;
            }

            // Pass serviceWorkerRegistration for Firebase v10
            const token = await messaging.getToken({
                vapidKey: window.firebaseVapidKey,
                serviceWorkerRegistration: swRegistration
            });

            if (token) {
                fcmLog('[FCM] Token obtained:', token.substring(0, 30) + '...');
                await registerTokenWithServer(token);
                return token;
            }

            fcmLog('[FCM] No token available');
            return null;
        } catch (error) {
            console.error('[FCM] Token error:', error);
            return null;
        }
    }

    // Register token with Laravel backend
    async function registerTokenWithServer(token) {
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            const response = await fetch('/api/device/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    token: token,
                    device_type: 'web',
                    device_name: navigator.userAgent.substring(0, 100),
                }),
            });

            if (response.ok) {
                fcmLog('[FCM] Token registered with server');
                localStorage.setItem('fcm_token_registered', 'true');
            } else {
                console.error('[FCM] Token registration failed:', await response.text());
            }
        } catch (error) {
            console.error('[FCM] Token registration error:', error);
        }
    }

    // Handle foreground notification
    let lastProcessedMessageId = null;

    function handleForegroundNotification(payload) {
        // Prevent duplicates (deduplication)
        const messageId = payload.messageId || (payload.data && payload.data.google_message_id) || JSON.stringify(payload.data);
        if (messageId === lastProcessedMessageId) {
            fcmLog('[FCM] Duplicate message ignored:', messageId);
            return;
        }
        lastProcessedMessageId = messageId;
        // Reset after 2 seconds to allow similar messages later
        setTimeout(() => lastProcessedMessageId = null, 2000);

        fcmLog('[FCM] Handling notification:', payload);

        const { notification, data } = payload;

        // For data-only messages, title/body are in data object
        const title = notification?.title || data?.title || 'إشعار جديد';
        const body = notification?.body || data?.body || '';
        const icon = notification?.icon || data?.icon;

        // If in admin dashboard, delegate to the dashboard handler and SKIP default UI
        if (typeof window.handleFirebaseMessage === 'function') {
            // Pass reformatted payload with notification object for dashboard handler
            window.handleFirebaseMessage({
                notification: { title, body, icon },
                data: data
            });
            return; // EXIT here to prevent double toast/sound
        }

        // If NOT in admin dashboard, show default UI
        playNotificationSound();

        // Show custom toast notification
        showNotificationToast({
            title: title,
            body: body,
            icon: icon,
            type: data?.type || 'general',
            url: data?.url || data?.click_action || '/',
        });
    }

    // Play notification sound
    function playNotificationSound() {
        try {
            if (notificationSound) {
                notificationSound.currentTime = 0;
                notificationSound.play().catch(e => fcmLog('[FCM] Sound play blocked:', e));
            }
        } catch (error) {
            fcmLog('[FCM] Sound error:', error);
        }
    }

    // Show toast notification
    function showNotificationToast({ title, body, icon, type, url }) {
        // Create toast container if doesn't exist
        let container = document.getElementById('fcm-toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'fcm-toast-container';
            container.className = 'fcm-toast-container';
            document.body.appendChild(container);
        }

        // Create toast element
        const toast = document.createElement('div');
        toast.className = `fcm-toast fcm-toast-${type}`;
        toast.innerHTML = `
            <div class="fcm-toast-icon">
                <img src="${icon || '/icons/android/android-launchericon-96-96.png'}" alt="icon">
            </div>
            <div class="fcm-toast-content">
                <div class="fcm-toast-title">${escapeHtml(title)}</div>
                <div class="fcm-toast-body">${escapeHtml(body)}</div>
            </div>
            <button class="fcm-toast-close" aria-label="إغلاق">
                <i class="bi bi-x"></i>
            </button>
        `;

        // Add click handler
        toast.addEventListener('click', (e) => {
            if (!e.target.closest('.fcm-toast-close')) {
                window.location.href = url;
            }
        });

        // Add close handler
        toast.querySelector('.fcm-toast-close').addEventListener('click', (e) => {
            e.stopPropagation();
            removeToast(toast);
        });

        // Add to container
        container.appendChild(toast);

        // Trigger animation
        requestAnimationFrame(() => {
            toast.classList.add('show');
        });

        // Auto remove after 8 seconds
        setTimeout(() => {
            removeToast(toast);
        }, 8000);
    }

    // Remove toast with animation
    function removeToast(toast) {
        toast.classList.remove('show');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }

    // Helper to escape HTML
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Listen for messages from service worker
    navigator.serviceWorker?.addEventListener('message', (event) => {
        if (event.data?.type === 'NOTIFICATION_RECEIVED') {
            playNotificationSound();
        }
    });

    // Show permission request prompt
    function showPermissionPrompt() {
        if (Notification.permission === 'default') {
            // Create beautiful permission prompt
            const prompt = document.createElement('div');
            prompt.className = 'fcm-permission-prompt';
            prompt.innerHTML = `
                <div class="fcm-permission-card">
                    <div class="fcm-permission-icon">
                        <i class="bi bi-bell-fill"></i>
                    </div>
                    <div class="fcm-permission-text">
                        <strong>تفعيل الإشعارات</strong>
                        <p>احصل على تحديثات فورية عن طلباتك والعروض الجديدة</p>
                    </div>
                    <div class="fcm-permission-actions">
                        <button class="fcm-btn-allow">تفعيل</button>
                        <button class="fcm-btn-later">لاحقاً</button>
                    </div>
                </div>
            `;

            prompt.querySelector('.fcm-btn-allow').addEventListener('click', async () => {
                prompt.remove();
                await requestNotificationPermission();
            });

            prompt.querySelector('.fcm-btn-later').addEventListener('click', () => {
                prompt.remove();
                localStorage.setItem('fcm_prompt_dismissed', Date.now().toString());
            });

            document.body.appendChild(prompt);

            setTimeout(() => prompt.classList.add('show'), 100);
        }
    }

    // Initialize on page load
    async function init() {
        // Only initialize if notifications are supported
        if (!('Notification' in window) || !('serviceWorker' in navigator)) {
            fcmLog('[FCM] Push notifications not supported');
            return;
        }

        await initFirebase();

        // If permission already granted, get token
        if (Notification.permission === 'granted') {
            await getToken();
        } else if (Notification.permission === 'default') {
            // Check if prompt was recently dismissed
            const dismissed = localStorage.getItem('fcm_prompt_dismissed');
            if (!dismissed || Date.now() - parseInt(dismissed) > 86400000) { // 24 hours
                // COORDINATED: Wait for PWA install banner to be dismissed/hidden first
                // This prevents overlapping/stacking prompts
                const checkAndShowPrompt = () => {
                    const pwaBanner = document.getElementById('pwaInstallBanner');
                    const pwaBannerVisible = pwaBanner && pwaBanner.classList.contains('show');

                    if (!pwaBannerVisible) {
                        // Show FCM prompt after a delay
                        setTimeout(showPermissionPrompt, 2000);
                    } else {
                        // PWA banner is visible, check again later
                        setTimeout(checkAndShowPrompt, 3000);
                    }
                };

                // Initial check after 5 seconds (giving PWA time to show first)
                setTimeout(checkAndShowPrompt, 5000);
            }
        }
    }

    // Clear app badge (called when user views notifications)
    function clearBadge() {
        // Clear badge using Badging API
        if ('clearAppBadge' in navigator) {
            navigator.clearAppBadge().catch(err => {
                fcmLog('[FCM] Badge clear failed:', err);
            });
        }

        // Also notify Service Worker to reset its count
        if (swRegistration && swRegistration.active) {
            swRegistration.active.postMessage({ type: 'RESET_BADGE' });
        }

        fcmLog('[FCM] Badge cleared');
    }

    // Auto-clear badge when app becomes visible
    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'visible') {
            // Clear badge after a short delay to let user see it first
            setTimeout(clearBadge, 2000);
        }
    });

    // Expose functions globally
    window.FCM = {
        init,
        requestPermission: requestNotificationPermission,
        getToken,
        showToast: showNotificationToast,
        playSound: playNotificationSound,
        clearBadge: clearBadge,
    };

    // Auto-initialize
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
