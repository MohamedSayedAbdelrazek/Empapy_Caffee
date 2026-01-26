<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'إمبابي كافيه - قهوة فاخرة')</title>

    <script>
        // ✅ Service Worker Registration for PWA
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('✅ SW registered:', reg.scope))
                    .catch(err => console.log('❌ SW registration failed:', err));
            });
        }
    </script>

    <!-- Google Analytics 4 -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-JWP19DBNZE"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'G-JWP19DBNZE');
    </script>

    <!-- PWA Meta Tags -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#2C1810">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="إمبابي كافيه">

    <!-- Favicon & App Icons -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('icons/ios/120.png') }}">
    <link rel="apple-touch-icon" sizes="128x128" href="{{ asset('icons/ios/128.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('icons/ios/152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('icons/ios/180.png') }}">
    <link rel="apple-touch-icon" sizes="256x256" href="{{ asset('icons/ios/256.png') }}">

    <!-- SEO Meta Tags -->
    <meta name="description" content="@yield('meta_description', 'إمبابي كافيه - أجود أنواع القهوة الفاخرة من حول العالم. تسوق الآن واستمتع بتجربة قهوة استثنائية.')">
    <meta name="keywords" content="قهوة, كافيه, قهوة فاخرة, بن, إمبابي, espresso, coffee">
    <meta name="author" content="Empapy Caffe">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('og_title', 'إمبابي كافيه - قهوة فاخرة')">
    <meta property="og:description" content="@yield('meta_description', 'أجود أنواع القهوة الفاخرة من حول العالم')">
    <meta property="og:image" content="@yield('og_image', asset('images/og-image.jpg'))">
    <meta property="og:locale" content="ar_EG">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('og_title', 'إمبابي كافيه')">
    <meta name="twitter:description" content="@yield('meta_description', 'أجود أنواع القهوة الفاخرة')">

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Google Fonts - Cairo for Arabic -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Bootstrap 5 RTL -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">

    <!-- AOS - Animate On Scroll -->
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- UI Enhancements CSS -->
    <link rel="stylesheet" href="{{ asset('css/enhancements.css') }}">

    <!-- Creative Premium Effects CSS -->
    <link rel="stylesheet" href="{{ asset('css/creative-effects.css') }}">

    <!-- UX Enhancements CSS -->
    <link rel="stylesheet" href="{{ asset('css/ux-enhancements.css') }}">

    <!-- Loyalty System CSS -->
    <link rel="stylesheet" href="{{ asset('css/loyalty.css') }}">

    <!-- User Dropdown Menu CSS -->
    <link rel="stylesheet" href="{{ asset('css/user-dropdown.css') }}">

    <!-- PWA Install Prompt CSS -->
    <link rel="stylesheet" href="{{ asset('css/pwa-install.css') }}">

    <!-- Firebase Notifications CSS -->
    <link rel="stylesheet" href="{{ asset('css/firebase-notifications.css') }}">

    <!-- Product Card CSS (extracted for better caching) -->
    <link rel="stylesheet" href="{{ asset('css/product-card.css') }}">

    @stack('styles')
</head>

<body>
    <!-- Announcement Bar -->
    <div class="announcement-bar" id="announcementBar">
        <div class="announcement-track">
            <div class="announcement-content">
                <span class="announcement-item">
                    <i class="bi bi-truck"></i>
                    توصيل مجاني للطلبات أكثر من 500 ج.م
                </span>
                <span class="announcement-divider">☕</span>
                <span class="announcement-item">
                    <i class="bi bi-gift"></i>
                    خصم 15% على طلبك الأول - كود: WELCOME15
                </span>
                <span class="announcement-divider">☕</span>
                <span class="announcement-item">
                    <span class="announcement-item">
                        <i class="bi bi-star-fill"></i>
                        قهوة طازجة محمصة يومياً
                    </span>
                    <span class="announcement-divider">☕</span>
                    <span class="announcement-item">
                        <i class="bi bi-clock"></i>
                        توصيل سريع خلال 24 ساعة
                    </span>
                    <span class="announcement-divider">☕</span>
                    <span class="announcement-item">
                        <i class="bi bi-truck"></i>
                        توصيل مجاني للطلبات أكثر من 500 ج.م
                    </span>
                    <span class="announcement-divider">☕</span>
                    <span class="announcement-item">
                        <i class="bi bi-gift"></i>
                        خصم 15% على طلبك الأول - كود: WELCOME15
                    </span>
                    <span class="announcement-divider">☕</span>

                    <!-- DUPLICATE SET FOR SEAMLESS LOOP -->
                    <span class="announcement-item">
                        <i class="bi bi-star-fill"></i>
                        قهوة طازجة محمصة يومياً
                    </span>
                    <span class="announcement-divider">☕</span>
                    <span class="announcement-item">
                        <i class="bi bi-clock"></i>
                        توصيل سريع خلال 24 ساعة
                    </span>
                    <span class="announcement-divider">☕</span>
                    <span class="announcement-item">
                        <i class="bi bi-truck"></i>
                        توصيل مجاني للطلبات أكثر من 500 ج.م
                    </span>
                    <span class="announcement-divider">☕</span>
                    <span class="announcement-item">
                        <i class="bi bi-gift"></i>
                        خصم 15% على طلبك الأول - كود: WELCOME15
                    </span>
                    <span class="announcement-divider">☕</span>
            </div>
        </div>
        <button class="announcement-close" id="announcementCloseBtn" aria-label="إخفاء شريط الإعلانات">
            <i class="bi bi-x"></i>
        </button>
    </div>

    <!-- Navbar -->
    @include('components.navbar')

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    @include('components.footer')

    <!-- Cart Drawer -->
    @include('components.cart-drawer')

    <!-- Quick Shop Modal -->
    @include('components.quick-shop-modal')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <!-- Custom JS -->
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- UI Enhancements JS -->
    <script src="{{ asset('js/enhancements.js') }}"></script>

    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-out-cubic',
            once: true,
            offset: 50
        });
    </script>

    <!-- Creative Premium Effects JS -->
    <script src="{{ asset('js/creative-effects.js') }}"></script>

    <!-- UX Enhancements JS -->
    <script src="{{ asset('js/ux-enhancements.js') }}"></script>

    <!-- Product Card JS (extracted for better caching) -->
    <script src="{{ asset('js/product-card.js') }}"></script>

    <!-- PWA Service Worker & Install Prompt -->
    <script src="{{ asset('js/pwa.js') }}"></script>

    <!-- Firebase SDKs -->
    <script src="https://www.gstatic.com/firebasejs/10.7.1/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging-compat.js"></script>

    <!-- Firebase Push Notifications -->
    <script>
        // User Notification Logic
        // User Notification Logic

        // SECURITY: HTML escape helper to prevent XSS
        function escapeHtml(text) {
            if (text === null || text === undefined) return '';
            const div = document.createElement('div');
            div.textContent = String(text);
            return div.innerHTML;
        }

        window.handleFirebaseMessage = function(payload) {
            console.log('[User Layout] Received Firebase Message:', payload);
            const {
                notification,
                data
            } = payload;

            // Extract data first - available for all blocks
            const title = notification?.title || data?.title || 'إشعار جديد';
            const body = notification?.body || data?.body || '';
            const url = data?.url || data?.click_action || '#';
            const icon = notification?.icon || data?.icon || '/icons/android/android-launchericon-96-96.png';
            const time = new Date().toLocaleTimeString('ar-EG', {
                hour: '2-digit',
                minute: '2-digit'
            });

            // 1. Update Badge
            const badge = document.getElementById('userNotificationBadge');
            if (badge) {
                let count = parseInt(badge.textContent) || 0;
                count++;
                badge.textContent = count > 99 ? '99+' : count;
                badge.style.display = 'block';
                // Animate badge
                badge.classList.add('animate__animated', 'animate__heartBeat');
                setTimeout(() => badge.classList.remove('animate__animated', 'animate__heartBeat'), 1000);
            }

            // 2. Add to List - ESCAPED to prevent XSS
            const list = document.getElementById('userNotificationList');
            if (list) {
                const emptyMsg = list.querySelector('.notification-empty');
                if (emptyMsg) emptyMsg.remove();

                const itemHtml = `
                    <a href="${escapeHtml(url)}" class="dropdown-item p-2 border-bottom notification-item unread" 
                       style="background: rgba(var(--bs-primary-rgb), 0.05);">
                        <div class="d-flex align-items-start gap-2">
                            <img src="${escapeHtml(icon)}" class="rounded-circle" width="40" height="40" alt="icon">
                            <div class="flex-grow-1">
                                <h6 class="mb-1 small fw-bold">${escapeHtml(title)}</h6>
                                <p class="mb-1 small text-muted text-truncate" style="max-width: 200px;">${escapeHtml(body)}</p>
                                <small class="text-secondary" style="font-size: 0.7rem;">${escapeHtml(time)}</small>
                            </div>
                            <span class="badge bg-primary rounded-pill p-1" style="font-size: 0.5rem;">جديد</span>
                        </div>
                    </a>
                `;

                list.insertAdjacentHTML('afterbegin', itemHtml);
            }

            // 3. Play Sound manually
            const audio = new Audio('/sounds/notification.mp3');
            audio.play().catch(e => console.log('Audio play failed', e));

            // Show Toast manually
            if (window.FCM && window.FCM.showToast) {
                window.FCM.showToast({
                    title: title,
                    body: body,
                    icon: icon,
                    url: url
                });
            }
        };


        // Placeholder for mark all read
        function markAllUserNotificationsRead() {
            // TODO: Implement backend call
            const badge = document.getElementById('userNotificationBadge');
            if (badge) {
                badge.style.display = 'none';
                badge.textContent = '0';
            }
            // Remove unread styling
            document.querySelectorAll('.notification-item.unread').forEach(el => {
                el.style.background = 'transparent';
                el.querySelector('.badge')?.remove();
                el.classList.remove('unread');
            });
        }
    </script>
    <script src="{{ asset('js/firebase-notifications.js') }}"></script>

    @stack('scripts')
</body>

</html>
