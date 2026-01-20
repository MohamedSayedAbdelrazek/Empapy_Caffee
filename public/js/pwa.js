/**
 * Empapy Caffe - PWA Installation Handler
 * Manages service worker registration and install prompt
 */

(function () {
    'use strict';

    // Debug mode - set to false for production
    const DEBUG_MODE = false;

    // Conditional logging wrapper
    function pwaLog(...args) {
        if (DEBUG_MODE) {
            console.log(...args);
        }
    }

    // Store the install prompt event
    let deferredPrompt = null;
    let isInstalled = false;
    const INSTALL_FLAG_KEY = 'pwa-installed';

    // Standalone-only detection (real installed PWA - checks display mode only)
    function isStandalone() {
        // Check if running in standalone mode (most browsers)
        if (window.matchMedia('(display-mode: standalone)').matches) {
            return true;
        }
        // Check fullscreen mode (some Android browsers)
        if (window.matchMedia('(display-mode: fullscreen)').matches) {
            return true;
        }
        // Check minimal-ui mode (some browsers)
        if (window.matchMedia('(display-mode: minimal-ui)').matches) {
            return true;
        }
        // Check iOS standalone
        if (window.navigator.standalone === true) {
            return true;
        }
        // Check if opened from homescreen on Android (TWA)
        if (document.referrer.includes('android-app://')) {
            return true;
        }
        return false;
    }

    // UI helper: hide install buttons if installed previously OR running standalone
    function isInstalledForUi() {
        return isStandalone() || getStoredInstalled();
    }

    // Legacy function for backward compatibility
    function checkIfInstalled() {
        return isInstalledForUi();
    }

    // Read stored install state
    function getStoredInstalled() {
        return localStorage.getItem(INSTALL_FLAG_KEY) === 'true';
    }

    // Store install state (persisted)
    function setStoredInstalled(value) {
        if (value) {
            localStorage.setItem(INSTALL_FLAG_KEY, 'true');
        } else {
            localStorage.removeItem(INSTALL_FLAG_KEY);
        }
    }

    // Mark app as installed
    function markAsInstalled() {
        isInstalled = true;
        setStoredInstalled(true);
        pwaLog('[PWA] Marked as installed');
    }

    // Re-validate stored install state (uninstall detection)
    async function resolveStoredInstalled() {
        const stored = getStoredInstalled();
        if (!stored) {
            return false;
        }

        // If running in standalone, it's definitely installed
        if (isStandalone()) {
            return true;
        }

        // Try to confirm installed apps on supported browsers
        if ('getInstalledRelatedApps' in navigator) {
            try {
                const relatedApps = await navigator.getInstalledRelatedApps();
                if (!relatedApps || relatedApps.length === 0) {
                    setStoredInstalled(false);
                    return false;
                }
                return true;
            } catch (error) {
                console.warn('[PWA] getInstalledRelatedApps failed:', error);
            }
        }

        // Fallback: keep stored state if we cannot verify
        return true;
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
                pwaLog('[PWA] Service Worker registered:', registration.scope);

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
        // Don't show if already installed (standalone or stored)
        if (isInstalled || isInstalledForUi()) {
            pwaLog('[PWA] Already installed, not showing banner');
            return;
        }

        // Check if dismissed recently (1 day for testing, can increase later)
        if (localStorage.getItem('pwa-install-dismissed')) {
            const dismissedTime = parseInt(localStorage.getItem('pwa-install-dismissed'));
            const daysPassed = (Date.now() - dismissedTime) / (1000 * 60 * 60 * 24);
            pwaLog('[PWA] Days since dismiss:', daysPassed.toFixed(2));
            if (daysPassed < 1) {
                pwaLog('[PWA] Dismissed recently, not showing banner');
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
            pwaLog('[PWA] Install banner shown!');
        }, 800);
    }

    // Reset function for testing - call from browser console: resetPWAInstall()
    window.resetPWAInstall = function () {
        localStorage.removeItem('pwa-install-dismissed');
        localStorage.removeItem('pwa-installed');
        pwaLog('[PWA] All PWA states cleared. Reload the page to see the install buttons.');
        alert('تم إعادة ضبط حالة التثبيت. أعد تحميل الصفحة لرؤية أزرار التثبيت.');
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
        if (isInstalled || isInstalledForUi()) {
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
            pwaLog('[PWA] Footer install section shown');
        }

        // Show user navbar button if exists
        if (navbarBtn) {
            navbarBtn.style.display = 'flex';
            setupInstallButton('navbarInstallBtn');
            pwaLog('[PWA] User navbar install button shown');
        }

        // Show admin navbar button if exists
        if (adminNavbarBtn) {
            adminNavbarBtn.style.display = 'flex';
            setupInstallButton('adminNavbarInstallBtn');
            pwaLog('[PWA] Admin navbar install button shown');
        }
    }

    // Setup click handler for any install button
    function setupInstallButton(buttonId) {
        const btn = document.getElementById(buttonId);
        if (btn && !btn.hasAttribute('data-setup')) {
            btn.setAttribute('data-setup', 'true');
            btn.addEventListener('click', async () => {
                if (deferredPrompt) {
                    // Browser has install prompt ready
                    deferredPrompt.prompt();
                    const { outcome } = await deferredPrompt.userChoice;
                    pwaLog('[PWA] User choice:', outcome);

                    if (outcome === 'accepted') {
                        showInstalledBadge();
                        isInstalled = true;
                        setStoredInstalled(true);
                        hideFooterInstall();
                    }

                    deferredPrompt = null;
                    hideInstallBanner();
                } else if (isIOS()) {
                    // iOS - show instructions banner
                    showInstallBanner();
                } else {
                    // Do nothing when prompt not available (professional UX)
                    pwaLog('[PWA] Install prompt not available yet');
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
                pwaLog('[PWA] User choice:', outcome);

                if (outcome === 'accepted') {
                    showInstalledBadge();
                    isInstalled = true;
                    setStoredInstalled(true);
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
        pwaLog('[PWA] New version available - will update on next reload');
        // Don't show annoying popup - the user will get the new version on next visit
    }

    // Listen for beforeinstallprompt event
    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();

        // If already installed, ignore
        if (isInstalledForUi()) {
            pwaLog('[PWA] Already installed, ignoring beforeinstallprompt');
            return;
        }

        // Store the prompt for later use
        deferredPrompt = e;
        pwaLog('[PWA] Install prompt available');

        // NOW show install buttons (professional - only when prompt is ready)
        showInstallBanner();
        showFooterInstall();
    });

    // Listen for app installed event
    window.addEventListener('appinstalled', (e) => {
        pwaLog('[PWA] App installed!');
        isInstalled = true;
        markAsInstalled();
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

    async function init() {
        // Check if already installed
        const standaloneInstalled = isStandalone();
        const storedInstalled = await resolveStoredInstalled();
        isInstalled = standaloneInstalled || storedInstalled;

        pwaLog('[PWA] Init - Standalone:', standaloneInstalled, 'Stored:', storedInstalled);

        // Register service worker
        registerServiceWorker();

        // For Android/Desktop: Do NOT show install buttons here
        // Wait for beforeinstallprompt event (professional UX)

        // For iOS only: Show banner with instructions (no beforeinstallprompt exists on iOS)
        if (isIOS() && !isInstalled) {
            setTimeout(() => {
                showInstallBanner();
                showFooterInstall();
            }, 3000);
        }

        pwaLog('[PWA] Initialized. Installed:', isInstalled);
    }
})();
