/**
 * Empapy Caffe - PWA Installation Handler
 * Manages service worker registration and install prompt
 */

(function () {
    'use strict';

    // Store the install prompt event
    let deferredPrompt = null;
    let isInstalled = false;

    // Check if app is already installed
    function checkIfInstalled() {
        // Check if running in standalone mode (most browsers)
        if (window.matchMedia('(display-mode: standalone)').matches) {
            console.log('[PWA] Detected: display-mode standalone');
            return true;
        }
        // Check fullscreen mode (some Android browsers)
        if (window.matchMedia('(display-mode: fullscreen)').matches) {
            console.log('[PWA] Detected: display-mode fullscreen');
            return true;
        }
        // Check minimal-ui mode (some browsers)
        if (window.matchMedia('(display-mode: minimal-ui)').matches) {
            console.log('[PWA] Detected: display-mode minimal-ui');
            return true;
        }
        // Check iOS standalone
        if (window.navigator.standalone === true) {
            console.log('[PWA] Detected: iOS standalone');
            return true;
        }
        // Check if opened from homescreen on Android (TWA)
        if (document.referrer.includes('android-app://')) {
            console.log('[PWA] Detected: Android TWA');
            return true;
        }
        return false;
    }

    // Detect iOS
    function isIOS() {
        return /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
    }

    // Register Service Worker
    async function registerServiceWorker() {
        if ('serviceWorker' in navigator) {
            try {
                const registration = await navigator.serviceWorker.register('/sw.js', {
                    scope: '/'
                });
                console.log('[PWA] Service Worker registered:', registration.scope);

                // Check for updates
                registration.addEventListener('updatefound', () => {
                    const newWorker = registration.installing;
                    newWorker.addEventListener('statechange', () => {
                        if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                            // New version available
                            showUpdateAvailable();
                        }
                    });
                });

                return registration;
            } catch (error) {
                console.error('[PWA] Service Worker registration failed:', error);
            }
        }
    }

    // Create install banner HTML
    function createInstallBanner() {
        const banner = document.createElement('div');
        banner.className = 'pwa-install-banner';
        banner.id = 'pwaInstallBanner';
        banner.innerHTML = `
            <div class="pwa-install-card">
                <button class="pwa-install-close" id="pwaCloseBtn" aria-label="إغلاق">
                    <i class="bi bi-x"></i>
                </button>
                
                <div class="pwa-install-header">
                <div class="pwa-install-icon">
                        <img src="/icons/android/android-launchericon-192-192.png" alt="إمبابي كافيه" onerror="this.src='/logo.jpg';">
                    </div>
                    <div class="pwa-install-info">
                        <div class="pwa-install-title">
                            إمبابي كافيه
                            <span class="verified-badge">
                                <i class="bi bi-check"></i>
                            </span>
                        </div>
                        <div class="pwa-install-subtitle">
                            ثبّت التطبيق للوصول السريع وتجربة أفضل
                        </div>
                    </div>
                </div>
                
                <div class="pwa-install-features">
                    <div class="pwa-feature">
                        <div class="pwa-feature-icon offline">
                            <i class="bi bi-wifi-off"></i>
                        </div>
                        <div class="pwa-feature-text">بدون إنترنت</div>
                    </div>
                    <div class="pwa-feature">
                        <div class="pwa-feature-icon fast">
                            <i class="bi bi-lightning-fill"></i>
                        </div>
                        <div class="pwa-feature-text">سرعة فائقة</div>
                    </div>
                    <div class="pwa-feature">
                        <div class="pwa-feature-icon notify">
                            <i class="bi bi-bell-fill"></i>
                        </div>
                        <div class="pwa-feature-text">إشعارات</div>
                    </div>
                </div>
                
                <div class="pwa-install-actions" id="pwaActions">
                    <button class="pwa-btn pwa-btn-install" id="pwaInstallBtn">
                        <i class="bi bi-download"></i>
                        تثبيت التطبيق
                    </button>
                    <button class="pwa-btn pwa-btn-later" id="pwaLaterBtn">
                        لاحقاً
                    </button>
                </div>
                
                <div class="pwa-ios-instructions" id="pwaIOSInstructions">
                    <div class="pwa-ios-step">
                        <i class="bi bi-box-arrow-up"></i>
                        <span>اضغط على أيقونة المشاركة</span>
                    </div>
                    <div class="pwa-ios-step">
                        <i class="bi bi-plus-square"></i>
                        <span>ثم اختر "إضافة إلى الشاشة الرئيسية"</span>
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(banner);
        return banner;
    }

    // Create installed badge
    function createInstalledBadge() {
        const badge = document.createElement('div');
        badge.className = 'pwa-installed-badge';
        badge.id = 'pwaInstalledBadge';
        badge.innerHTML = `
            <i class="bi bi-check-circle-fill"></i>
            <span>تم تثبيت التطبيق بنجاح!</span>
        `;
        document.body.appendChild(badge);
        return badge;
    }

    // Show install banner
    function showInstallBanner() {
        // Don't show if already installed
        if (isInstalled || checkIfInstalled()) {
            console.log('[PWA] Already installed, not showing banner');
            return;
        }

        // Check if dismissed recently (1 day for testing, can increase later)
        if (localStorage.getItem('pwa-install-dismissed')) {
            const dismissedTime = parseInt(localStorage.getItem('pwa-install-dismissed'));
            const daysPassed = (Date.now() - dismissedTime) / (1000 * 60 * 60 * 24);
            console.log('[PWA] Days since dismiss:', daysPassed.toFixed(2));
            if (daysPassed < 1) {
                console.log('[PWA] Dismissed recently, not showing banner');
                return;
            }
        }

        let banner = document.getElementById('pwaInstallBanner');
        if (!banner) {
            banner = createInstallBanner();
            setupBannerEvents();
        }

        // Show iOS instructions on iOS devices
        if (isIOS()) {
            document.getElementById('pwaActions').style.display = 'none';
            document.getElementById('pwaIOSInstructions').classList.add('show');
        }

        // Show quickly after page loads
        setTimeout(() => {
            banner.classList.add('show');
            console.log('[PWA] Install banner shown!');
        }, 800);
    }

    // Reset function for testing - call from browser console: resetPWAInstall()
    window.resetPWAInstall = function () {
        localStorage.removeItem('pwa-install-dismissed');
        console.log('[PWA] Install dismissed state cleared. Reload the page to see the banner.');
        alert('تم إعادة ضبط حالة التثبيت. أعد تحميل الصفحة لرؤية رسالة التثبيت.');
    };

    // Hide install banner
    function hideInstallBanner() {
        const banner = document.getElementById('pwaInstallBanner');
        if (banner) {
            banner.classList.remove('show');
        }
    }

    // Show footer install section (persistent) + navbar buttons
    function showFooterInstall() {
        if (isInstalled || checkIfInstalled()) {
            return; // Don't show if already installed
        }

        const footerSection = document.getElementById('footerInstallSection');
        const navbarBtn = document.getElementById('navbarInstallBtn');
        const adminNavbarBtn = document.getElementById('adminNavbarInstallBtn');

        // Show footer section if exists
        if (footerSection) {
            footerSection.style.display = 'block';
            if (isIOS()) {
                footerSection.classList.add('ios');
            }
            setupInstallButton('footerInstallBtn');
            console.log('[PWA] Footer install section shown');
        }

        // Show user navbar button if exists
        if (navbarBtn) {
            navbarBtn.style.display = 'flex';
            setupInstallButton('navbarInstallBtn');
            console.log('[PWA] User navbar install button shown');
        }

        // Show admin navbar button if exists
        if (adminNavbarBtn) {
            adminNavbarBtn.style.display = 'flex';
            setupInstallButton('adminNavbarInstallBtn');
            console.log('[PWA] Admin navbar install button shown');
        }
    }

    // Setup click handler for any install button
    function setupInstallButton(buttonId) {
        const btn = document.getElementById(buttonId);
        if (btn && !btn.hasAttribute('data-setup')) {
            btn.setAttribute('data-setup', 'true');
            btn.addEventListener('click', async () => {
                if (deferredPrompt) {
                    deferredPrompt.prompt();
                    const { outcome } = await deferredPrompt.userChoice;
                    console.log('[PWA] Install button clicked - User choice:', outcome);

                    if (outcome === 'accepted') {
                        showInstalledBadge();
                        isInstalled = true;
                        hideFooterInstall();
                    }

                    deferredPrompt = null;
                    hideInstallBanner();
                } else if (isIOS()) {
                    showInstallBanner();
                }
            });
        }
    }

    // Hide footer install section + navbar buttons
    function hideFooterInstall() {
        const elements = [
            'footerInstallSection',
            'navbarInstallBtn',
            'adminNavbarInstallBtn'
        ];

        elements.forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.style.display = 'none';
            }
        });
    }

    // Setup banner event listeners
    function setupBannerEvents() {
        // Install button
        document.getElementById('pwaInstallBtn').addEventListener('click', async () => {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                console.log('[PWA] User choice:', outcome);

                if (outcome === 'accepted') {
                    showInstalledBadge();
                    isInstalled = true;
                }

                deferredPrompt = null;
                hideInstallBanner();
            }
        });

        // Later button
        document.getElementById('pwaLaterBtn').addEventListener('click', () => {
            localStorage.setItem('pwa-install-dismissed', Date.now().toString());
            hideInstallBanner();
        });

        // Close button
        document.getElementById('pwaCloseBtn').addEventListener('click', () => {
            localStorage.setItem('pwa-install-dismissed', Date.now().toString());
            hideInstallBanner();
        });
    }

    // Show installed badge
    function showInstalledBadge() {
        let badge = document.getElementById('pwaInstalledBadge');
        if (!badge) {
            badge = createInstalledBadge();
        }

        badge.classList.add('show');

        // Auto-hide after 5 seconds
        setTimeout(() => {
            badge.classList.remove('show');
            setTimeout(() => badge.remove(), 500);
        }, 5000);
    }

    // Show update available notification - silent auto-update
    function showUpdateAvailable() {
        // Silent update - just log and reload automatically in the background
        console.log('[PWA] New version available - will update on next reload');
        // Don't show annoying popup - the user will get the new version on next visit
    }

    // Listen for beforeinstallprompt event
    window.addEventListener('beforeinstallprompt', (e) => {
        console.log('[PWA] beforeinstallprompt fired');
        e.preventDefault();
        deferredPrompt = e;
        showInstallBanner();
        showFooterInstall(); // Also show footer install button
    });

    // Listen for app installed event
    window.addEventListener('appinstalled', (e) => {
        console.log('[PWA] App installed!');
        isInstalled = true;
        hideInstallBanner();
        hideFooterInstall(); // Hide footer install button
        showInstalledBadge();
        deferredPrompt = null;
    });

    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    function init() {
        // Check if already installed
        isInstalled = checkIfInstalled();

        // Register service worker
        registerServiceWorker();

        // If iOS and not installed, show banner and footer after delay
        if (isIOS() && !isInstalled) {
            setTimeout(() => {
                showInstallBanner();
                showFooterInstall();
            }, 5000);
        }

        // Show footer install if not installed (for when deferredPrompt already fired)
        if (!isInstalled) {
            showFooterInstall();
        }

        console.log('[PWA] Initialized. Installed:', isInstalled);
    }
})();
