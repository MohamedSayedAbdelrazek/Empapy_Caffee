<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'إمبابي كافيه - قهوة فاخرة')</title>

    <!-- Google Search Console Verification -->
    <meta name="google-site-verification" content="1-K9hEmL-oDOLLF4eGYd2TsRlKdmvbEfSGwx79k-Tus" />

    <!-- Preconnect for Google services -->
    <link rel="preconnect" href="https://www.googletagmanager.com">
    <link rel="dns-prefetch" href="https://www.googletagmanager.com">

    <!-- Google Analytics 4 (async - non-blocking) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-JWP19DBNZE"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());
        gtag('config', 'G-JWP19DBNZE');
    </script>

    <script>
        // ✅ Service Worker Registration for PWA (deferred to after load)
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function () {
                // Delay SW registration to prioritize critical resources
                setTimeout(function () {
                    navigator.serviceWorker.register('/sw.js')
                        .then(reg => console.log('✅ SW registered:', reg.scope))
                        .catch(err => console.log('❌ SW registration failed:', err));
                }, 2000);
            });
        }
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

    <!-- ====== SEO Meta Tags - Enhanced for Google Ranking ====== -->
    <meta name="description"
        content="@yield('meta_description', 'إمبابي كافيه | Empapy Coffee - أجود أنواع البن والقهوة الفاخرة في مصر. قهوة امبابي، بن امبابي، توصيل سريع. تسوق الآن!')">

    <!-- All keyword variations for search -->
    <meta name="keywords"
        content="إمبابي كافيه, امبابي كافيه, بن امبابي, قهوة امبابي, إمبابي قهوة, امبابي قهوة, empapy coffee, empapy cafe, empapy caffe, embaby coffee, embaby cafe, embaby caffe, embabay coffee, embabay caffe, قهوة فاخرة, بن فاخر, قهوة مصر, بن مصري, coffee egypt, premium coffee, قهوة تركي, قهوة عربي, espresso">

    <meta name="author" content="Empapy Caffe | إمبابي كافيه">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="googlebot" content="index, follow">

    <!-- Site name for Google -->
    <meta name="application-name" content="إمبابي كافيه - Empapy Caffe">
    <meta name="apple-mobile-web-app-title" content="إمبابي كافيه">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="إمبابي كافيه - Empapy Caffe">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('og_title', 'إمبابي كافيه | Empapy Coffee - قهوة وبن فاخر')">
    <meta property="og:description"
        content="@yield('meta_description', 'أجود أنواع البن والقهوة الفاخرة في مصر. قهوة امبابي، بن امبابي. توصيل سريع لجميع المحافظات.')">
    <meta property="og:image" content="@yield('og_image', asset('images/og-image.jpg'))">
    <meta property="og:locale" content="ar_EG">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@empapycaffe">
    <meta name="twitter:title" content="@yield('og_title', 'إمبابي كافيه | Empapy Coffee')">
    <meta name="twitter:description" content="@yield('meta_description', 'أجود أنواع البن والقهوة الفاخرة في مصر')">

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Alternate language versions (helps with different spellings) -->
    <link rel="alternate" hreflang="ar" href="{{ url()->current() }}">
    <link rel="alternate" hreflang="ar-EG" href="{{ url()->current() }}">

    <!-- JSON-LD Structured Data for Google -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "CoffeeStore",
        "name": "إمبابي كافيه - Empapy Caffe",
        "alternateName": ["Empapy Coffee", "Embaby Caffe", "قهوة امبابي", "بن امبابي", "امبابي كافيه"],
        "url": "{{ config('app.url') }}",
        "logo": "{{ asset('images/logo.png') }}",
        "image": "{{ asset('images/og-image.jpg') }}",
        "description": "أجود أنواع البن والقهوة الفاخرة في مصر. قهوة امبابي، بن امبابي، توصيل سريع.",
        "telephone": "+201151579225",
        "address": {
            "@type": "PostalAddress",
            "addressCountry": "EG",
            "addressLocality": "Egypt"
        },
        "priceRange": "$$",
        "servesCuisine": "Coffee",
        "sameAs": [
            "https://www.facebook.com/profile.php?id=61559046937280",
            "https://www.instagram.com/empapy_coffee",
            "https://www.tiktok.com/@empapy_coffe"
        ]
    }
    </script>

    <!-- Preconnect to external domains for faster loading -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://unpkg.com" crossorigin>
    <link rel="preconnect" href="https://www.gstatic.com" crossorigin>

    <!-- Preload Critical LCP Font (Cairo) -->
    <link rel="preload" href="https://fonts.gstatic.com/s/cairo/v28/SLXgc1nY6HkvangtZmpQdkhzfH5lkSs2SgRjCAGMQ1z0hD45W1TOQlPmYw.woff2" 
          as="font" type="font/woff2" crossorigin>

    <!-- Preload LCP Image (Hero Background) - Only on Home Page -->
    @if(request()->routeIs('home'))
    <link rel="preload" href="{{ asset('images/hero-bg.webp') }}" as="image" type="image/webp" fetchpriority="high">
    @endif

    <!-- Google Fonts - Cairo (optimized weights, display swap) -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">

    <!-- Critical CSS (Inlined for faster FCP) -->
    <style>
        :root {
            --espresso: #2C1810;
            --dark-roast: #3D2317;
            --gold: #C9A227;
            --cream: #FFF8E7;
            --font-primary: 'Cairo', sans-serif
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box
        }

        body {
            font-family: var(--font-primary);
            background-color: var(--cream);
            color: var(--espresso);
            line-height: 1.7;
            overflow-x: hidden;
            margin: 0
        }

        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, var(--espresso) 0%, var(--dark-roast) 100%)
        }

        .hero-bg {
            position: absolute;
            inset: 0;
            z-index: 0
        }

        .hero-bg img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.4
        }

        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(44, 24, 16, 0.95) 0%, rgba(61, 35, 23, 0.9) 100%);
            z-index: 1
        }

        .hero-content {
            position: relative;
            z-index: 10;
            color: #fff;
            padding-top: 100px
        }

        .btn-golden {
            background: linear-gradient(135deg, var(--gold) 0%, #E8C547 50%, var(--gold) 100%);
            color: var(--espresso);
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600
        }
    </style>

    <!-- Bootstrap 5 RTL (Critical CSS) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">

    <!-- Bootstrap Icons (Deferred - Non-blocking) -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css"
        as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    </noscript>

    <!-- AOS - Animate On Scroll (Deferred) -->
    <link rel="preload" href="https://unpkg.com/aos@2.3.1/dist/aos.css" as="style"
        onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
    </noscript>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- UI Enhancements CSS -->
    <link rel="stylesheet" href="{{ asset('css/enhancements.css') }}">

    <!-- Creative Premium Effects CSS -->
    <link rel="stylesheet" href="{{ asset('css/creative-effects.css') }}">

    <!-- UX Enhancements CSS -->
    <link rel="stylesheet" href="{{ asset('css/ux-enhancements.css') }}">

    <!-- Announcement Bar CSS (v10 - direction fixed) -->
    <link rel="stylesheet" href="{{ asset('css/announcement-bar.css') }}?v=10">

    <!-- Loyalty System CSS (Deferred - loaded after page render) -->
    <link rel="preload" href="{{ asset('css/loyalty.css') }}" as="style"
        onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="{{ asset('css/loyalty.css') }}">
    </noscript>

    <!-- User Dropdown Menu CSS -->
    <link rel="stylesheet" href="{{ asset('css/user-dropdown.css') }}">

    <!-- PWA Install Prompt CSS (Deferred) -->
    <link rel="preload" href="{{ asset('css/pwa-install.css') }}" as="style"
        onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="{{ asset('css/pwa-install.css') }}">
    </noscript>

    <!-- Firebase Notifications CSS (Deferred) -->
    <link rel="preload" href="{{ asset('css/firebase-notifications.css') }}" as="style"
        onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="{{ asset('css/firebase-notifications.css') }}">
    </noscript>

    <!-- Product Card CSS (extracted for better caching) -->
    <link rel="stylesheet" href="{{ asset('css/product-card.css') }}">

    @stack('styles')
    <script>
        // بنسحب المفتاح من السيرفر ونحطه في شباك المتصفح عشان الجافاسكريبت تشوفه
        window.firebaseVapidKey = "{{ env('VITE_FIREBASE_VAPID_KEY') }}";
    </script>

</head>

<body>
    <!-- ☕ Premium Announcement Bar -->
    @php
        $announcements = \App\Models\Announcement::active()->ordered()->get();
    @endphp

    @if ($announcements->isNotEmpty())
        <div class="announcement-bar" id="announcementBar">
            <div class="announcement-wrapper">
                <div class="announcement-track">
                    {{-- First set of announcements --}}
                    @foreach ($announcements as $announcement)
                        <span class="announcement-item">
                            <i class="bi bi-stars"></i>
                            {{ $announcement->message_ar }}
                        </span>
                        <span class="announcement-divider">✦</span>
                    @endforeach
                    {{-- Duplicate for seamless loop --}}
                    @foreach ($announcements as $announcement)
                        <span class="announcement-item">
                            <i class="bi bi-stars"></i>
                            {{ $announcement->message_ar }}
                        </span>
                        <span class="announcement-divider">✦</span>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

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

    <!-- Bootstrap JS (Deferred - not render-blocking) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" defer></script>

    <!-- AOS JS (Deferred) -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js" defer></script>

    <!-- Custom JS (Deferred) -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- UI Enhancements JS (Deferred) -->
    <script src="{{ asset('js/enhancements.js') }}" defer></script>

    <script>
        // Initialize AOS after deferred script loads
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof AOS !== 'undefined') {
                AOS.init({
                    duration: 800,
                    easing: 'ease-out-cubic',
                    once: true,
                    offset: 50
                });
            }
        });
        // Fallback for when AOS loads after DOMContentLoaded
        window.addEventListener('load', function () {
            if (typeof AOS !== 'undefined' && !window.aosInitialized) {
                AOS.init({
                    duration: 800,
                    easing: 'ease-out-cubic',
                    once: true,
                    offset: 50
                });
                window.aosInitialized = true;
            }
        });
    </script>

    <!-- Creative Premium Effects JS (Deferred) -->
    <script src="{{ asset('js/creative-effects.js') }}" defer></script>

    <!-- UX Enhancements JS (Deferred) -->
    <script src="{{ asset('js/ux-enhancements.js') }}" defer></script>

    <!-- Product Card JS (Deferred) -->
    <script src="{{ asset('js/product-card.js') }}" defer></script>

    <!-- PWA Service Worker & Install Prompt (Deferred) -->
    <script src="{{ asset('js/pwa.js') }}" defer></script>

    <!-- Firebase SDKs (Deferred - loaded after main content) -->
    <script src="https://www.gstatic.com/firebasejs/10.7.1/firebase-app-compat.js" defer></script>
    <script src="https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging-compat.js" defer></script>

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

        window.handleFirebaseMessage = function (payload) {
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