/**
 * Empapy Caffe - PWA Installation Handler
 * Manages service worker registration and install prompt
 */

(function () {
    'use strict';

    // Store the install prompt event
    let deferredPrompt = null;
    let isInstalled = false;
    const INSTALL_FLAG_KEY = 'pwa-installed';

    // Check if app is already installed (ONLY check actual display mode, NOT localStorage)
    function checkIfInstalled() {
        // Check if running in standalone mode (most browsers)
        if (window.matchMedia('(display-mode: standalone)').matches) {
            console.log('[PWA] Running in standalone mode');
            return true;
        }
        // Check fullscreen mode (some Android browsers)
        if (window.matchMedia('(display-mode: fullscreen)').matches) {
            console.log('[PWA] Running in fullscreen mode');
            return true;
        }
        // Check minimal-ui mode (some browsers)
        if (window.matchMedia('(display-mode: minimal-ui)').matches) {
            console.log('[PWA] Running in minimal-ui mode');
            return true;
        }
        // Check iOS standalone
        if (window.navigator.standalone === true) {
            console.log('[PWA] Running in iOS standalone mode');
            return true;
        }
        // Check if opened from homescreen on Android (TWA)
        if (document.referrer.includes('android-app://')) {
            console.log('[PWA] Running as Android TWA');
            return true;
        }
        // NOT in standalone mode = show install buttons
        console.log('[PWA] Not in standalone mode - buttons should be visible');
        return false;
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
        console.log('[PWA] Marked as installed');
    }

    // Re-validate stored install state (uninstall detection)
    async function resolveStoredInstalled() {
        const stored = getStoredInstalled();
        if (!stored) {
            return false;
        }

        // If running in standalone, it's installed
        if (checkIfInstalled()) {
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
        localStorage.removeItem('pwa-installed');
        console.log('[PWA] All PWA states cleared. Reload the page to see the install buttons.');
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
                console.log('[PWA] Install button clicked, deferredPrompt:', !!deferredPrompt);

                if (deferredPrompt) {
                    // Browser supports install prompt
                    deferredPrompt.prompt();
                    const { outcome } = await deferredPrompt.userChoice;
                    console.log('[PWA] Install button clicked - User choice:', outcome);

                    if (outcome === 'accepted') {
                        showInstalledBadge();
                        isInstalled = true;
                        setStoredInstalled(true);
                        hideFooterInstall();
                    }

                    deferredPrompt = null;
                    hideInstallBanner();
                } else if (isIOS()) {
                    // iOS - show instructions
                    showInstallBanner();
                } else {
                    // Android/Desktop but no prompt available yet
                    // Show manual instructions
                    showManualInstallInstructions();
                }
            });
        }
    }

    // Show manual install instructions when prompt is not available
    function showManualInstallInstructions() {
        const isChrome = /Chrome/.test(navigator.userAgent) && /Google Inc/.test(navigator.vendor);
        const isEdge = /Edg/.test(navigator.userAgent);
        const isFirefox = /Firefox/.test(navigator.userAgent);
        const isSamsung = /SamsungBrowser/.test(navigator.userAgent);

        let instructions = '';
        if (isChrome || isEdge) {
            instructions = '📱 لتثبيت التطبيق:\n\n1. اضغط على ⋮ (النقاط الثلاث) أعلى الشاشة\n2. اختر "تثبيت التطبيق" أو "Add to Home screen"';
        } else if (isSamsung) {
            instructions = '📱 لتثبيت التطبيق:\n\n1. اضغط على ☰ (القائمة)\n2. اختر "إضافة الصفحة إلى" ثم "الشاشة الرئيسية"';
        } else if (isFirefox) {
            instructions = '📱 لتثبيت التطبيق:\n\n1. اضغط على ⋮ (القائمة)\n2. اختر "تثبيت" أو "Install"';
        } else {
            instructions = '📱 لتثبيت التطبيق:\n\nاستخدم قائمة المتصفح واختر "إضافة إلى الشاشة الرئيسية"';
        }

        alert(instructions);
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
        console.log('[PWA] New version available - will update on next reload');
        // Don't show annoying popup - the user will get the new version on next visit
    }

    // Listen for beforeinstallprompt event
    window.addEventListener('beforeinstallprompt', (e) => {
        console.log('[PWA] beforeinstallprompt fired');
        e.preventDefault();
        deferredPrompt = e;
        // If install prompt is available, app is not installed
        setStoredInstalled(false);
        isInstalled = false;
        showInstallBanner();
        showFooterInstall(); // Also show footer install button
    });

    // Listen for app installed event
    window.addEventListener('appinstalled', (e) => {
        console.log('[PWA] App installed!');
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
        // Check if already installed (standalone or stored)
        const standaloneInstalled = checkIfInstalled();
        const storedInstalled = await resolveStoredInstalled();
        isInstalled = standaloneInstalled || storedInstalled;

        // Register service worker
        registerServiceWorker();

        // Show install buttons only if not installed
        if (!isInstalled) {
            console.log('[PWA] Not installed - showing install buttons');
            showFooterInstall();

            // For iOS: Also show banner after delay with instructions
            if (isIOS()) {
                setTimeout(() => {
                    showInstallBanner();
                }, 3000);
            }
        }

        console.log('[PWA] Initialized. Installed:', isInstalled);
    }
})();
