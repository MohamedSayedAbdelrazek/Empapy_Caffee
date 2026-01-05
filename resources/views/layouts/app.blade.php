<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'إمبابي كافيه - قهوة فاخرة')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="{{ asset('logo.jpg') }}">
    <link rel="apple-touch-icon" href="{{ asset('logo.jpg') }}">

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

    @stack('styles')
</head>

<body>
    <!-- Announcement Bar -->
    <div class="announcement-bar">
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
                    <i class="bi bi-star-fill"></i>
                    قهوة طازجة محمصة يومياً
                </span>
                <span class="announcement-divider">☕</span>
                <span class="announcement-item">
                    <i class="bi bi-clock"></i>
                    توصيل سريع خلال 24 ساعة
                </span>
                <span class="announcement-divider">☕</span>
                <!-- Repeat for seamless loop -->
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
                    <i class="bi bi-star-fill"></i>
                    قهوة طازجة محمصة يومياً
                </span>
                <span class="announcement-divider">☕</span>
                <span class="announcement-item">
                    <i class="bi bi-clock"></i>
                    توصيل سريع خلال 24 ساعة
                </span>
                <span class="announcement-divider">☕</span>
            </div>
        </div>
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

    @stack('scripts')
</body>

</html>
