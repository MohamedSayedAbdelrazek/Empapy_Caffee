@extends('layouts.app')

@section('title', 'إمبابي كافيه - قهوة فاخرة لعشاق النكهات الأصيلة')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-bg">
            <picture>
                <source srcset="{{ asset('images/hero-bg.webp') }}" type="image/webp">
                <img src="{{ asset('images/hero-bg.jpg') }}" alt="Coffee Background" fetchpriority="high" decoding="async" width="1920" height="1080">
            </picture>
        </div>
        <div class="hero-overlay"></div>

        <!-- Floating Beans Container -->
        <div class="floating-beans"></div>

        <div class="container" style="position: relative; z-index: 100;">
            <div class="row align-items-center min-vh-100">
                <div class="col-lg-6" data-aos="fade-up">
                    <div class="hero-content" style="position: relative; z-index: 100;">
                        <span class="hero-badge">
                            <i class="bi bi-stars"></i>
                            قهوة فاخرة من حول العالم
                        </span>
                        <h1 class="hero-title">
                            استمتع بتجربة <span>قهوة استثنائية</span> في كل كوب
                        </h1>
                        <p class="hero-description">
                            نختار أجود حبوب البن من أفضل المزارع حول العالم، ونحمصها بعناية فائقة لنقدم لك تجربة قهوة لا
                            تُنسى.
                        </p>
                        <div class="hero-buttons">
                            <a href="{{ route('shop.index') }}" class="btn btn-golden btn-lg">
                                <svg class="cart-icon me-2" xmlns="http://www.w3.org/2000/svg" height="20px"
                                    viewBox="0 -960 960 960" width="20px" fill="currentColor">
                                    <path
                                        d="M280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM246-720l96 200h280l110-200H246Zm-38-80h590q23 0 35 20.5t1 41.5L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68-39.5t-2-78.5l54-98-144-304H40v-80h130l38 80Zm134 280h280-280Z" />
                                </svg>تسوق الآن
                            </a>
                            <a href="#featured" class="btn btn-outline-golden btn-lg">
                                <i class="bi bi-cup-hot me-2"></i>اكتشف المنتجات
                            </a>
                        </div>

                        <div class="hero-stats">

                        </div>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block" data-aos="fade-up" data-aos-delay="200">
                    <div class="hero-image text-center" style="position: relative; z-index: 100;">
                        <picture>
                            <source srcset="{{ asset('images/hero-coffee-new.webp') }}" type="image/webp">
                            <img src="{{ asset('images/hero-coffee-new.png') }}" alt="Premium Coffee" loading="lazy"
                                decoding="async" width="450" height="450"
                                style="border-radius: 30px; max-height: 450px; width: auto; object-fit: cover; box-shadow: 0 25px 60px rgba(0,0,0,0.4);">
                        </picture>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="categories-section py-5">
        <div class="container">
            <div class="section-title text-center mb-5" data-aos="fade-up">
                <h2>تصفح الأصناف</h2>
                <p>اختر من مجموعتنا المتنوعة من أفضل أنواع القهوة</p>
            </div>

            <div class="row g-4 justify-content-center">
                @foreach ($categories as $category)
                    <div class="col-lg-4 col-md-6 col-12" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <a href="{{ route('shop.index', ['category' => $category->slug]) }}" class="category-card-vertical">
                            <div class="card-image">
                                <x-optimized-image :src="$category->image" :alt="$category->name" loading="lazy" decoding="async" :width="400" :height="300" />
                                <div class="card-overlay"></div>
                                <div class="card-shine"></div>
                            </div>
                            <div class="card-content">
                                <h3>{{ $category->name }}</h3>
                                <p class="products-count">
                                    <i class="bi bi-cup-hot-fill"></i>
                                    {{ $category->products_count }} منتج
                                </p>
                                <span class="explore-btn">
                                    اكتشف المزيد
                                    <i class="bi bi-arrow-left"></i>
                                </span>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- 🎬 Cinematic Video Showcase Section -->
    <section class="cinema-video-section" data-aos="fade-up">
        <!-- Animated Background -->
        <div class="cinema-bg">
            <div class="cinema-gradient"></div>
            <div class="floating-particles" id="videoParticles"></div>
        </div>

        <div class="container position-relative">
            <!-- Cinematic Title -->
            <div class="cinema-header text-center" data-aos="fade-down">
                <div class="cinema-badge">
                    <i class="bi bi-film"></i>
                    <span>تجربة سينمائية</span>
                </div>
                <h2 class="cinema-title">
                    <span class="title-line">شاهد</span>
                    <span class="title-gold">قصتنا</span>
                </h2>
                <p class="cinema-subtitle">رحلة من حبة البن إلى فنجانك</p>
            </div>

            <!-- Cinema Frame -->
            <div class="cinema-frame" data-aos="zoom-in" data-aos-delay="200">
                <!-- Decorative Corners -->
                <div class="frame-corner top-left"></div>
                <div class="frame-corner top-right"></div>
                <div class="frame-corner bottom-left"></div>
                <div class="frame-corner bottom-right"></div>

                <!-- Glow Effect -->
                <div class="frame-glow"></div>

                <!-- Video Container -->
                <div class="cinema-video-wrapper">
                    <video id="empapyVideo" class="cinema-video" playsinline preload="metadata"
                        poster="{{ asset('images/video-poster.jpg') }}" aria-label="فيديو إمبابي كافيه - رحلة القهوة">
                        <source src="{{ asset('assets/videos/Empapy_video.mp4') }}" type="video/mp4">
                    </video>

                    <!-- Custom Play Button with Sound Waves -->
                    <div class="cinema-play-overlay" id="cinemaPlayBtn">
                        <!-- Sound Wave Animation -->
                        <div class="sound-waves">
                            <span class="wave"></span>
                            <span class="wave"></span>
                            <span class="wave"></span>
                        </div>

                        <!-- Play Button -->
                        <div class="cinema-play-btn">
                            <div class="play-ring"></div>
                            <div class="play-icon">
                                <i class="bi bi-play-fill"></i>
                            </div>
                        </div>

                        <span class="cinema-play-text">اضغط لتشغيل الفيديو</span>
                    </div>

                    <!-- Video Controls Overlay (appears on hover when playing) -->
                    <div class="cinema-controls" id="cinemaControls">
                        <button class="control-btn" id="cinemaPauseBtn">
                            <i class="bi bi-pause-fill"></i>
                        </button>
                        <div class="progress-bar">
                            <div class="progress-fill" id="progressFill"></div>
                        </div>
                        <button class="control-btn" id="cinemaFullscreen">
                            <i class="bi bi-fullscreen"></i>
                        </button>
                    </div>
                </div>

                <!-- Film Strip Decoration -->
                <div class="film-strip top"></div>
                <div class="film-strip bottom"></div>
            </div>

            <!-- Premium Slogan -->
            <div class="cinema-slogan" data-aos="fade-up" data-aos-delay="400">
                <div class="slogan-wrapper">
                    <div class="slogan-icon-box">
                        <i class="bi bi-cup-hot-fill"></i>
                    </div>
                    <div class="slogan-text-box">
                        <p class="slogan-main">
                            <span class="gold-text">كل رشفة</span> تحكي قصة شغف
                        </p>
                        <p class="slogan-sub">اكتشف طعم القهوة الأصيلة من إمبابي</p>
                    </div>
                </div>
                <div class="slogan-divider">
                    <span class="divider-line"></span>
                    <i class="bi bi-stars"></i>
                    <span class="divider-line"></span>
                </div>
            </div>
        </div>
    </section>

    {{-- Cinematic Video Showcase Section - COMMENTED OUT
    <section class="video-showcase-section" data-aos="fade-up">
        <!-- Image Background (replaced video) -->
        <div class="video-background">
            <img src="{{ asset('images/showcase-bg.jpg') }}" alt="Coffee Beans Background" class="showcase-bg-image">
            <!-- Gradient Overlays -->
            <div class="video-overlay-gradient top"></div>
            <div class="video-overlay-gradient bottom"></div>
            <div class="video-overlay-dark"></div>

            <!-- Steam Animation Removed for Performance -->
        </div>

        <!-- Decorative Frame -->
        <div class="video-frame">
            <div class="frame-corner top-right"></div>
            <div class="frame-corner top-left"></div>
            <div class="frame-corner bottom-right"></div>
            <div class="frame-corner bottom-left"></div>
        </div>

        <!-- Content Card -->
        <div class="container position-relative" style="z-index: 10;">
            <div class="video-content-wrapper">
                <div class="video-glass-card" data-aos="zoom-in" data-aos-delay="200">
                    <div class="card-glow"></div>
                    <div class="card-content">
                        <span class="video-badge">
                            <i class="bi bi-play-circle-fill"></i>
                            تجربة حسية فريدة
                        </span>
                        <h2 class="video-title">
                            اكتشف <span class="gold-text">رحلة البن</span> من المزرعة إلى فنجانك
                        </h2>
                        <p class="video-description">
                            نختار كل حبة بعناية فائقة من أرقى مزارع البن في العالم، ونحمصها بخبرة أجيال لنقدم لك تجربة قهوة
                            استثنائية تُحاكي حواسك
                        </p>
                        <div class="video-features">
                            <div class="feature-item">
                                <i class="bi bi-globe-americas"></i>
                                <span>من أفضل المزارع</span>
                            </div>
                            <div class="feature-item">
                                <i class="bi bi-fire"></i>
                                <span>تحميص طازج يومياً</span>
                            </div>
                            <div class="feature-item">
                                <i class="bi bi-heart-fill"></i>
                                <span>بحب وشغف</span>
                            </div>
                        </div>
                        <a href="{{ route('shop.index') }}" class="btn btn-video-cta">
                            <span>استكشف منتجاتنا</span>
                            <i class="bi bi-arrow-left"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    --}}

    <!-- Featured Products -->
    <section class="py-5 bg-white" id="featured">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>منتجات مميزة</h2>
                <p>اكتشف أفضل أنواع القهوة المختارة بعناية</p>
            </div>

            <div class="row g-4">
                @forelse($featuredProducts as $product)
                    <div class="col-6 col-md-4 col-lg-3">
                        @include('components.product-card', [
                            'product' => $product,
                            'wishlistIds' => $wishlistIds ?? [],
                        ])
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-cup display-1 text-muted"></i>
                        <p class="text-muted mt-3">لا توجد منتجات مميزة حالياً</p>
                    </div>
                @endforelse
            </div>

            <div class="text-center mt-5" data-aos="fade-up">
                <a href="{{ route('shop.index') }}" class="btn btn-golden btn-lg">
                    عرض جميع المنتجات
                    <i class="bi bi-arrow-left ms-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5 my-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="0">
                    <div class="glass-card text-center p-4 h-100">
                        <div class="mb-3">
                            <i class="bi bi-truck display-4 text-gold"></i>
                        </div>
                        <h5>توصيل سريع</h5>
                        <p class="text-muted mb-0">توصيل مجاني للطلبات أكثر من 500 ج.م</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="glass-card text-center p-4 h-100">
                        <div class="mb-3">
                            <i class="bi bi-patch-check display-4 text-gold"></i>
                        </div>
                        <h5>جودة مضمونة</h5>
                        <p class="text-muted mb-0">أجود أنواع البن من أفضل المزارع</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="glass-card text-center p-4 h-100">
                        <div class="mb-3">
                            <i class="bi bi-arrow-repeat display-4 text-gold"></i>
                        </div>
                        <h5>استبدال سهل</h5>
                        <p class="text-muted mb-0">استبدال المنتجات خلال 7 أيام</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="glass-card text-center p-4 h-100">
                        <div class="mb-3">
                            <i class="bi bi-headset display-4 text-gold"></i>
                        </div>
                        <h5>دعم متواصل</h5>
                        <p class="text-muted mb-0">فريق دعم جاهز لمساعدتك</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Latest Products -->
    @if ($latestProducts->count())
        <section class="py-5 bg-white">
            <div class="container">
                <div class="section-title" data-aos="fade-up">
                    <h2>وصل حديثاً</h2>
                    <p>أحدث إضافاتنا من القهوة الفاخرة</p>
                </div>

                <div class="row g-4">
                    @foreach ($latestProducts as $product)
                        <div class="col-6 col-md-4 col-lg-3">
                            @include('components.product-card', [
                                'product' => $product,
                                'wishlistIds' => $wishlistIds ?? [],
                            ])
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Schema.org JSON-LD -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "CoffeeShop",
      "name": "Empapy Caffe",
      "image": "{{ asset('images/logo.png') }}",
      "priceRange": "$$",
      "description": "استمتع بتجربة قهوة استثنائية مع أجود أنواع البن المحمص بعناية.",
      "address": {
        "@type": "PostalAddress",
        "addressLocality": "Cairo",
        "addressCountry": "EG"
      },
      "url": "{{ url('/') }}",
      "telephone": "+201000000000"
    }
    </script>
@endsection

@push('styles')
    <style>
        .text-gold {
            color: var(--gold) !important;
        }

        /* Categories Section Styles */
        .categories-section {
            background: linear-gradient(135deg, #faf8f5 0%, #f5f0e8 100%);
        }

        .section-badge {
            display: inline-block;
            background: linear-gradient(135deg, var(--gold), #e6c547);
            color: var(--espresso);
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Vertical Card Styles */
        .category-card-vertical {
            display: block;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            text-decoration: none;
            color: inherit;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%;
        }

        .category-card-vertical:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px rgba(201, 162, 39, 0.2);
        }

        .card-image {
            position: relative;
            height: 350px;
            overflow: hidden;
        }

        .card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .category-card-vertical:hover .card-image img {
            transform: scale(1.1);
        }

        .card-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(44, 24, 16, 0.7) 0%, transparent 50%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .category-card-vertical:hover .card-overlay {
            opacity: 1;
        }

        .card-shine {
            position: absolute;
            top: 0;
            left: -100%;
            width: 50%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transform: skewX(-25deg);
            transition: left 0.6s ease;
        }

        .category-card-vertical:hover .card-shine {
            left: 150%;
        }

        .card-content {
            padding: 25px;
            text-align: center;
        }

        .card-content h3 {
            font-size: 1.3rem;
            font-weight: 700;
            margin: 0 0 10px 0;
            color: var(--espresso, #2c1810);
            transition: color 0.3s ease;
        }

        .category-card-vertical:hover .card-content h3 {
            color: var(--gold, #c9a227);
        }

        .card-content .products-count {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 0.9rem;
            color: #888;
            margin-bottom: 15px;
        }

        .card-content .products-count i {
            color: var(--gold, #c9a227);
            font-size: 1rem;
        }

        .explore-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, var(--gold, #c9a227), #d4a84b);
            color: white;
            padding: 10px 25px;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .explore-btn i {
            transition: transform 0.3s ease;
        }

        .category-card-vertical:hover .explore-btn {
            background: linear-gradient(135deg, var(--espresso, #2c1810), #3d2419);
            transform: scale(1.05);
        }

        .category-card-vertical:hover .explore-btn i {
            transform: translateX(-5px);
        }

        @media (max-width: 768px) {
            .card-image {
                height: 250px;
            }

            .card-content {
                padding: 20px;
            }

            .card-content h3 {
                font-size: 1.1rem;
            }

            .explore-btn {
                padding: 8px 20px;
                font-size: 0.85rem;
            }
        }

        /* =========================================
                                                                                           CINEMATIC VIDEO SHOWCASE SECTION STYLES
                                                                                           ========================================= */
        .video-showcase-section {
            position: relative;
            min-height: 70vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        /* Video Background */
        .video-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            pointer-events: none;
            /* Allow scroll to pass through */
        }

        .video-background .showcase-bg-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transform: scale(1.05);
            transition: transform 8s ease;
        }

        .video-showcase-section:hover .video-background .showcase-bg-image {
            transform: scale(1.1);
        }

        /* Gradient Overlays */
        .video-overlay-gradient {
            position: absolute;
            left: 0;
            right: 0;
            height: 200px;
            z-index: 2;
            pointer-events: none;
        }

        .video-overlay-gradient.top {
            top: 0;
            background: linear-gradient(to bottom, rgba(250, 248, 245, 0.95) 0%, transparent 100%);
        }

        .video-overlay-gradient.bottom {
            bottom: 0;
            background: linear-gradient(to top, rgba(255, 255, 255, 0.95) 0%, transparent 100%);
        }

        .video-overlay-dark {
            position: absolute;
            inset: 0;
            background: rgba(44, 24, 16, 0.45);
            z-index: 3;
            pointer-events: none;
            /* Allow scroll to pass through */
        }

        /* Steam Particle Animation */
        .steam-particles {
            position: absolute;
            inset: 0;
            z-index: 4;
            pointer-events: none;
            overflow: hidden;
        }

        .steam {
            position: absolute;
            width: 100px;
            height: 100px;
            background: radial-gradient(ellipse at center, rgba(255, 255, 255, 0.15) 0%, transparent 70%);
            border-radius: 50%;
            filter: blur(20px);
            animation: steamFloat 8s ease-in-out infinite;
        }

        .steam-1 {
            left: 15%;
            bottom: 10%;
            animation-delay: 0s;
        }

        .steam-2 {
            left: 35%;
            bottom: 5%;
            animation-delay: 2s;
            animation-duration: 10s;
        }

        .steam-3 {
            right: 25%;
            bottom: 15%;
            animation-delay: 4s;
            animation-duration: 7s;
        }

        .steam-4 {
            right: 10%;
            bottom: 8%;
            animation-delay: 1s;
            animation-duration: 9s;
        }

        @keyframes steamFloat {

            0%,
            100% {
                transform: translateY(0) scale(1);
                opacity: 0;
            }

            10% {
                opacity: 0.7;
            }

            50% {
                transform: translateY(-150px) scale(1.5);
                opacity: 0.5;
            }

            90% {
                opacity: 0;
            }
        }

        /* Decorative Frame */
        .video-frame {
            position: absolute;
            inset: 40px;
            z-index: 5;
            pointer-events: none;
        }

        .frame-corner {
            position: absolute;
            width: 80px;
            height: 80px;
            border-color: var(--gold, #c9a227);
            border-style: solid;
            opacity: 0.6;
            transition: all 0.5s ease;
        }

        .video-showcase-section:hover .frame-corner {
            opacity: 1;
            width: 100px;
            height: 100px;
        }

        .frame-corner.top-right {
            top: 0;
            right: 0;
            border-width: 3px 3px 0 0;
        }

        .frame-corner.top-left {
            top: 0;
            left: 0;
            border-width: 3px 0 0 3px;
        }

        .frame-corner.bottom-right {
            bottom: 0;
            right: 0;
            border-width: 0 3px 3px 0;
        }

        .frame-corner.bottom-left {
            bottom: 0;
            left: 0;
            border-width: 0 0 3px 3px;
        }

        /* Content Wrapper */
        .video-content-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 50vh;
        }

        /* Glassmorphism Card */
        .video-glass-card {
            position: relative;
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 30px;
            padding: 50px;
            max-width: 650px;
            text-align: center;
            box-shadow:
                0 25px 80px rgba(0, 0, 0, 0.3),
                inset 0 0 60px rgba(255, 255, 255, 0.05);
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }

        .video-glass-card:hover {
            transform: translateY(-10px);
            box-shadow:
                0 35px 100px rgba(0, 0, 0, 0.4),
                inset 0 0 80px rgba(255, 255, 255, 0.08);
        }

        .video-glass-card .card-glow {
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at center, rgba(201, 162, 39, 0.15) 0%, transparent 50%);
            animation: glowPulse 4s ease-in-out infinite;
            pointer-events: none;
        }

        @keyframes glowPulse {

            0%,
            100% {
                opacity: 0.5;
                transform: scale(1);
            }

            50% {
                opacity: 1;
                transform: scale(1.1);
            }
        }

        .video-glass-card .card-content {
            position: relative;
            z-index: 2;
        }

        /* Video Badge */
        .video-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, var(--gold, #c9a227), #d4a84b);
            color: var(--espresso, #2c1810);
            padding: 10px 25px;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 700;
            margin-bottom: 25px;
            box-shadow: 0 8px 25px rgba(201, 162, 39, 0.4);
            animation: badgePulse 2s ease-in-out infinite;
        }

        @keyframes badgePulse {

            0%,
            100% {
                box-shadow: 0 8px 25px rgba(201, 162, 39, 0.4);
            }

            50% {
                box-shadow: 0 8px 40px rgba(201, 162, 39, 0.6);
            }
        }

        .video-badge i {
            font-size: 1.1rem;
        }

        /* Video Title */
        .video-title {
            font-size: 2.2rem;
            font-weight: 800;
            color: white;
            margin-bottom: 20px;
            line-height: 1.4;
            text-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        .video-title .gold-text {
            color: var(--gold, #c9a227);
            position: relative;
            display: inline-block;
        }

        .video-title .gold-text::after {
            content: '';
            position: absolute;
            bottom: 5px;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, transparent, var(--gold, #c9a227), transparent);
        }

        /* Video Description */
        .video-description {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.8;
            margin-bottom: 30px;
        }

        /* Video Features */
        .video-features {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 35px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.1);
            padding: 12px 20px;
            border-radius: 50px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            transition: all 0.3s ease;
        }

        .feature-item:hover {
            background: rgba(201, 162, 39, 0.2);
            border-color: var(--gold, #c9a227);
            transform: translateY(-3px);
        }

        .feature-item i {
            color: var(--gold, #c9a227);
            font-size: 1.2rem;
        }

        .feature-item span {
            color: white;
            font-size: 0.95rem;
            font-weight: 600;
        }

        /* CTA Button */
        .btn-video-cta {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            background: linear-gradient(135deg, var(--gold, #c9a227), #d4a84b);
            color: var(--espresso, #2c1810);
            padding: 18px 40px;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 10px 35px rgba(201, 162, 39, 0.4);
            position: relative;
            overflow: hidden;
        }

        .btn-video-cta::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.5s ease;
        }

        .btn-video-cta:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 20px 50px rgba(201, 162, 39, 0.5);
            color: var(--espresso, #2c1810);
        }

        .btn-video-cta:hover::before {
            left: 100%;
        }

        .btn-video-cta i {
            transition: transform 0.3s ease;
        }

        .btn-video-cta:hover i {
            transform: translateX(-8px);
        }

        /* Video Control Button */
        .video-control-btn {
            position: absolute;
            bottom: 30px;
            left: 30px;
            width: 55px;
            height: 55px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            color: white;
            font-size: 1.4rem;
            cursor: pointer;
            z-index: 20;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .video-control-btn:hover {
            background: var(--gold, #c9a227);
            border-color: var(--gold, #c9a227);
            color: var(--espresso, #2c1810);
            transform: scale(1.1);
        }

        /* Responsive Styles */
        @media (max-width: 992px) {
            .video-showcase-section {
                min-height: 60vh;
            }

            .video-glass-card {
                padding: 40px 30px;
                margin: 0 20px;
            }

            .video-title {
                font-size: 1.8rem;
            }

            .video-frame {
                inset: 20px;
            }

            .frame-corner {
                width: 50px;
                height: 50px;
            }
        }

        @media (max-width: 768px) {
            .video-showcase-section {
                min-height: 80vh;
            }

            .video-glass-card {
                padding: 30px 25px;
            }

            .video-title {
                font-size: 1.5rem;
            }

            .video-description {
                font-size: 1rem;
            }

            .video-features {
                gap: 10px;
            }

            .feature-item {
                padding: 10px 15px;
                font-size: 0.85rem;
            }

            .btn-video-cta {
                padding: 15px 30px;
                font-size: 1rem;
            }

            .video-control-btn {
                width: 45px;
                height: 45px;
                font-size: 1.2rem;
                bottom: 20px;
                left: 20px;
            }

            .video-frame {
                display: none;
            }
        }

        /* =========================================
                           🎬 CINEMATIC VIDEO SECTION STYLES
                   ========================================= */
        .cinema-video-section {
            position: relative;
            padding: 80px 0;
            overflow: hidden;
            background: linear-gradient(180deg, #0d0d0d 0%, #1a1208 50%, #0d0d0d 100%);
        }

        /* Animated Background */
        .cinema-bg {
            position: absolute;
            inset: 0;
            pointer-events: none;
        }

        .cinema-gradient {
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse at 50% 0%, rgba(201, 162, 39, 0.15) 0%, transparent 50%),
                radial-gradient(ellipse at 20% 80%, rgba(201, 162, 39, 0.08) 0%, transparent 40%),
                radial-gradient(ellipse at 80% 80%, rgba(201, 162, 39, 0.08) 0%, transparent 40%);
        }

        /* Floating Particles */
        .floating-particles {
            position: absolute;
            inset: 0;
            overflow: hidden;
        }

        .floating-particles .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: radial-gradient(circle, rgba(201, 162, 39, 0.8), transparent);
            border-radius: 50%;
            animation: floatParticle 8s ease-in-out infinite;
        }

        @keyframes floatParticle {

            0%,
            100% {
                transform: translateY(0) scale(1);
                opacity: 0;
            }

            10% {
                opacity: 1;
            }

            90% {
                opacity: 1;
            }

            100% {
                transform: translateY(-100vh) scale(0.5);
                opacity: 0;
            }
        }

        /* Cinema Header */
        .cinema-header {
            margin-bottom: 50px;
        }

        .cinema-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, rgba(201, 162, 39, 0.2), rgba(201, 162, 39, 0.05));
            border: 1px solid rgba(201, 162, 39, 0.3);
            padding: 8px 20px;
            border-radius: 50px;
            margin-bottom: 20px;
            backdrop-filter: blur(10px);
        }

        .cinema-badge i {
            color: #c9a227;
            font-size: 1rem;
        }

        .cinema-badge span {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.9rem;
            font-weight: 500;
            letter-spacing: 1px;
        }

        .cinema-title {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 15px;
            line-height: 1.3;
        }

        .cinema-title .title-line {
            color: #ffffff;
            display: block;
        }

        .cinema-title .title-gold {
            display: inline-block;
            background: linear-gradient(135deg, #c9a227 0%, #f5e6a3 50%, #c9a227 100%);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: goldShimmer 3s ease-in-out infinite;
        }

        @keyframes goldShimmer {

            0%,
            100% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }
        }

        .cinema-subtitle {
            color: rgba(255, 255, 255, 0.7);
            font-size: 1.2rem;
            margin: 0;
        }

        /* Cinema Frame */
        .cinema-frame {
            position: relative;
            max-width: 1200px;
            /* Increased from 1000px */
            width: 95%;
            /* Take up more space on smaller screens */
            margin: 0 auto;
            padding: 15px;
            background: linear-gradient(135deg, rgba(201, 162, 39, 0.1), rgba(0, 0, 0, 0.3));
            border-radius: 24px;
            border: 2px solid rgba(201, 162, 39, 0.3);
        }

        /* Decorative Corners */
        .frame-corner {
            position: absolute;
            width: 40px;
            height: 40px;
            border-color: #c9a227;
            border-style: solid;
            z-index: 5;
            transition: all 0.4s ease;
        }

        .frame-corner.top-left {
            top: -2px;
            left: -2px;
            border-width: 4px 0 0 4px;
            border-radius: 20px 0 0 0;
        }

        .frame-corner.top-right {
            top: -2px;
            right: -2px;
            border-width: 4px 4px 0 0;
            border-radius: 0 20px 0 0;
        }

        .frame-corner.bottom-left {
            bottom: -2px;
            left: -2px;
            border-width: 0 0 4px 4px;
            border-radius: 0 0 0 20px;
        }

        .frame-corner.bottom-right {
            bottom: -2px;
            right: -2px;
            border-width: 0 4px 4px 0;
            border-radius: 0 0 20px 0;
        }

        .cinema-frame:hover .frame-corner {
            width: 60px;
            height: 60px;
            filter: drop-shadow(0 0 10px rgba(201, 162, 39, 0.5));
        }

        /* Frame Glow */
        .frame-glow {
            position: absolute;
            inset: -20px;
            background: radial-gradient(ellipse at center, rgba(201, 162, 39, 0.1), transparent 70%);
            border-radius: 30px;
            opacity: 0;
            transition: opacity 0.5s ease;
            pointer-events: none;
            z-index: 0;
        }

        .cinema-frame:hover .frame-glow {
            opacity: 1;
        }

        /* Film Strip Decoration */
        .film-strip {
            position: absolute;
            left: 0;
            right: 0;
            height: 8px;
            background: repeating-linear-gradient(90deg,
                    rgba(201, 162, 39, 0.3) 0px,
                    rgba(201, 162, 39, 0.3) 20px,
                    transparent 20px,
                    transparent 30px);
            z-index: 5;
        }

        .film-strip.top {
            top: 5px;
            border-radius: 20px 20px 0 0;
        }

        .film-strip.bottom {
            bottom: 5px;
            border-radius: 0 0 20px 20px;
        }

        /* Video Wrapper */
        .cinema-video-wrapper {
            position: relative;
            border-radius: 16px;
            overflow: hidden;
            background: #000;
            box-shadow:
                0 30px 60px rgba(0, 0, 0, 0.5),
                inset 0 0 0 1px rgba(255, 255, 255, 0.05);
        }

        .cinema-video {
            width: 100%;
            height: auto;
            display: block;
            aspect-ratio: 16 / 9;
            object-fit: cover;
        }

        /* Play Overlay */
        .cinema-play-overlay {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 20px;
            background: rgba(0, 0, 0, 0.5);
            cursor: pointer;
            transition: all 0.4s ease;
            z-index: 10;
        }

        .cinema-play-overlay:hover {
            background: rgba(0, 0, 0, 0.4);
        }

        .cinema-play-overlay.hidden {
            opacity: 0;
            pointer-events: none;
        }

        /* Sound Waves Animation */
        .sound-waves {
            position: absolute;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .sound-waves .wave {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 2px solid rgba(201, 162, 39, 0.3);
            position: absolute;
            animation: waveExpand 2s ease-out infinite;
        }

        .sound-waves .wave:nth-child(2) {
            animation-delay: 0.5s;
        }

        .sound-waves .wave:nth-child(3) {
            animation-delay: 1s;
        }

        @keyframes waveExpand {
            0% {
                transform: scale(0.8);
                opacity: 1;
            }

            100% {
                transform: scale(2);
                opacity: 0;
            }
        }

        /* Play Button */
        .cinema-play-btn {
            position: relative;
            z-index: 2;
        }

        .play-ring {
            position: absolute;
            inset: -15px;
            border-radius: 50%;
            border: 2px solid rgba(201, 162, 39, 0.5);
            animation: ringPulse 2s ease-in-out infinite;
        }

        @keyframes ringPulse {

            0%,
            100% {
                transform: scale(1);
                opacity: 0.5;
            }

            50% {
                transform: scale(1.1);
                opacity: 1;
            }
        }

        .play-icon {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: linear-gradient(135deg, #c9a227, #d4a84b);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow:
                0 10px 40px rgba(201, 162, 39, 0.5),
                0 0 30px rgba(201, 162, 39, 0.3),
                inset 0 2px 0 rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }

        .cinema-play-overlay:hover .play-icon {
            transform: scale(1.1);
            box-shadow:
                0 15px 50px rgba(201, 162, 39, 0.7),
                0 0 40px rgba(201, 162, 39, 0.5);
        }

        .play-icon i {
            font-size: 2.5rem;
            color: #1a1208;
            margin-left: 5px;
        }

        .cinema-play-text {
            color: #ffffff;
            font-size: 1rem;
            font-weight: 600;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.8);
            letter-spacing: 1px;
        }

        /* Custom Video Controls */
        .cinema-controls {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px 20px;
            background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 15;
        }

        .cinema-video-wrapper:hover .cinema-controls,
        .cinema-controls.visible {
            opacity: 1;
        }

        .control-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: none;
            background: rgba(201, 162, 39, 0.2);
            color: #c9a227;
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .control-btn:hover {
            background: #c9a227;
            color: #1a1208;
            transform: scale(1.1);
        }

        .progress-bar {
            flex: 1;
            height: 6px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
            overflow: hidden;
            cursor: pointer;
        }

        .progress-fill {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, #c9a227, #f5e6a3);
            border-radius: 3px;
            transition: width 0.1s linear;
        }

        /* Cinema Slogan */
        .cinema-slogan {
            margin-top: 50px;
            text-align: center;
        }

        .slogan-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .slogan-icon-box {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(201, 162, 39, 0.2), rgba(201, 162, 39, 0.05));
            border: 2px solid rgba(201, 162, 39, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            animation: iconFloat 3s ease-in-out infinite;
        }

        .slogan-icon-box i {
            font-size: 2rem;
            background: linear-gradient(135deg, #c9a227, #f5e6a3);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        @keyframes iconFloat {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-8px);
            }
        }

        .slogan-text-box {
            text-align: right;
        }

        .slogan-main {
            font-size: 1.8rem;
            font-weight: 700;
            color: #ffffff;
            margin: 0 0 5px 0;
        }

        .slogan-main .gold-text {
            background: linear-gradient(135deg, #c9a227, #f5e6a3);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .slogan-sub {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.7);
            margin: 0;
        }

        .slogan-divider {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin-top: 30px;
        }

        .divider-line {
            width: 80px;
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(201, 162, 39, 0.5), transparent);
        }

        .slogan-divider i {
            color: #c9a227;
            font-size: 1.2rem;
            animation: starPulse 2s ease-in-out infinite;
        }

        @keyframes starPulse {

            0%,
            100% {
                opacity: 0.6;
                transform: scale(1);
            }

            50% {
                opacity: 1;
                transform: scale(1.2);
            }
        }

        /* Responsive Styles */
        @media (max-width: 992px) {
            .cinema-video-section {
                padding: 60px 0;
            }

            .cinema-title {
                font-size: 2.5rem;
            }

            .cinema-frame {
                padding: 12px;
            }

            .play-icon {
                width: 75px;
                height: 75px;
            }

            .play-icon i {
                font-size: 2rem;
            }

            .slogan-main {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .cinema-video-section {
                padding: 50px 0;
            }

            .cinema-header {
                margin-bottom: 35px;
            }

            .cinema-title {
                font-size: 2rem;
            }

            .cinema-subtitle {
                font-size: 1rem;
            }

            .cinema-frame {
                padding: 10px;
                border-radius: 16px;
            }

            .frame-corner {
                width: 25px;
                height: 25px;
            }

            .film-strip {
                display: none;
            }

            .play-icon {
                width: 65px;
                height: 65px;
            }

            .play-icon i {
                font-size: 1.8rem;
            }

            .cinema-play-text {
                font-size: 0.9rem;
            }

            .slogan-wrapper {
                flex-direction: column;
                gap: 15px;
            }

            .slogan-text-box {
                text-align: center;
            }

            .slogan-icon-box {
                width: 60px;
                height: 60px;
            }

            .slogan-main {
                font-size: 1.3rem;
            }

            .slogan-sub {
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            .cinema-title {
                font-size: 1.6rem;
            }

            .cinema-badge {
                padding: 6px 15px;
            }

            .cinema-badge span {
                font-size: 0.8rem;
            }

            .play-icon {
                width: 55px;
                height: 55px;
            }

            .play-icon i {
                font-size: 1.5rem;
            }

            .sound-waves .wave {
                width: 80px;
                height: 80px;
            }

            .cinema-play-text {
                font-size: 0.8rem;
            }

            .divider-line {
                width: 50px;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        // 🎬 Cinematic Video Section Controller
        document.addEventListener('DOMContentLoaded', function() {

            // ========== Floating Particles ==========
            const particlesContainer = document.getElementById('videoParticles');
            if (particlesContainer) {
                const particleCount = 20;

                for (let i = 0; i < particleCount; i++) {
                    const particle = document.createElement('span');
                    particle.className = 'particle';
                    particle.style.left = Math.random() * 100 + '%';
                    particle.style.top = Math.random() * 100 + '%';
                    particle.style.animationDelay = Math.random() * 8 + 's';
                    particle.style.animationDuration = (6 + Math.random() * 4) + 's';
                    particlesContainer.appendChild(particle);
                }
            }

            // ========== Video Controls ==========
            const video = document.getElementById('empapyVideo');
            const playOverlay = document.getElementById('cinemaPlayBtn');
            const controls = document.getElementById('cinemaControls');
            const pauseBtn = document.getElementById('cinemaPauseBtn');
            const fullscreenBtn = document.getElementById('cinemaFullscreen');
            const progressFill = document.getElementById('progressFill');
            const progressBar = document.querySelector('.progress-bar');

            if (video && playOverlay) {
                // Play button click
                playOverlay.addEventListener('click', function() {
                    video.play();
                    playOverlay.classList.add('hidden');
                    if (controls) controls.classList.add('visible');
                });

                // Pause button click
                if (pauseBtn) {
                    pauseBtn.addEventListener('click', function() {
                        togglePlay();
                    });
                }

                // Click on video to toggle play/pause
                video.addEventListener('click', function() {
                    togglePlay();
                });

                function togglePlay() {
                    if (video.paused) {
                        video.play();
                        if (pauseBtn) pauseBtn.innerHTML = '<i class="bi bi-pause-fill"></i>';
                    } else {
                        video.pause();
                        if (pauseBtn) pauseBtn.innerHTML = '<i class="bi bi-play-fill"></i>';
                    }
                }

                // Fullscreen button
                if (fullscreenBtn) {
                    fullscreenBtn.addEventListener('click', function() {
                        if (video.requestFullscreen) {
                            video.requestFullscreen();
                        } else if (video.webkitRequestFullscreen) {
                            video.webkitRequestFullscreen();
                        } else if (video.msRequestFullscreen) {
                            video.msRequestFullscreen();
                        }
                    });
                }

                // Progress bar update
                video.addEventListener('timeupdate', function() {
                    if (progressFill && video.duration) {
                        const progress = (video.currentTime / video.duration) * 100;
                        progressFill.style.width = progress + '%';
                    }
                });

                // Click on progress bar to seek
                if (progressBar) {
                    progressBar.addEventListener('click', function(e) {
                        const rect = progressBar.getBoundingClientRect();
                        const clickPosition = (e.clientX - rect.left) / rect.width;
                        video.currentTime = clickPosition * video.duration;
                    });
                }

                // Video events
                video.addEventListener('pause', function() {
                    if (pauseBtn) pauseBtn.innerHTML = '<i class="bi bi-play-fill"></i>';
                });

                video.addEventListener('play', function() {
                    playOverlay.classList.add('hidden');
                    if (pauseBtn) pauseBtn.innerHTML = '<i class="bi bi-pause-fill"></i>';
                });

                video.addEventListener('ended', function() {
                    playOverlay.classList.remove('hidden');
                    if (controls) controls.classList.remove('visible');
                    if (progressFill) progressFill.style.width = '0%';
                });

                // Double click to fullscreen
                video.addEventListener('dblclick', function() {
                    if (video.requestFullscreen) {
                        video.requestFullscreen();
                    }
                });
            }

            // ========== Legacy Video Toggle (for other sections) ==========
            const showcaseVideo = document.getElementById('showcaseVideo');
            const toggleBtn = document.getElementById('videoToggle');
            const playPauseIcon = document.getElementById('playPauseIcon');

            if (showcaseVideo && toggleBtn && playPauseIcon) {
                toggleBtn.addEventListener('click', function() {
                    if (showcaseVideo.paused) {
                        showcaseVideo.play();
                        playPauseIcon.classList.remove('bi-play-fill');
                        playPauseIcon.classList.add('bi-pause-fill');
                    } else {
                        showcaseVideo.pause();
                        playPauseIcon.classList.remove('bi-pause-fill');
                        playPauseIcon.classList.add('bi-play-fill');
                    }
                });
            }

            // ========== Parallax Effect ==========
            window.addEventListener('scroll', function() {
                const section = document.querySelector('.cinema-video-section');
                if (section) {
                    const rect = section.getBoundingClientRect();
                    const scrollPercent = (window.innerHeight - rect.top) / (window.innerHeight + rect
                        .height);

                    // Subtle glow intensity on scroll
                    const frameGlow = section.querySelector('.frame-glow');
                    if (frameGlow && scrollPercent > 0.2 && scrollPercent < 0.8) {
                        frameGlow.style.opacity = Math.min(1, (scrollPercent - 0.2) * 2);
                    }
                }
            });
        });
    </script>
@endpush
