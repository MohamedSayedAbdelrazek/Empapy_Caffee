@extends('layouts.app')

@section('title', 'إمبابي كافيه - قهوة فاخرة لعشاق النكهات الأصيلة')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-bg">
            <img src="https://images.unsplash.com/photo-1447933601403-0c6688de566e?w=1920&q=80" alt="Coffee Background"
                loading="lazy" decoding="async">
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
                        <img src="https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=420&h=500&fit=crop"
                            alt="Premium Coffee" loading="lazy" decoding="async"
                            style="border-radius: 30px; max-height: 450px; width: auto; object-fit: cover;">
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
                                <img src="{{ $category->image }}" alt="{{ $category->name }}" loading="lazy"
                                    decoding="async">
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

    <!-- Cinematic Video Showcase Section -->
    <section class="video-showcase-section" data-aos="fade-up">
        <!-- Video Background -->
        <div class="video-background">
            <video autoplay muted loop playsinline preload="metadata" loading="lazy" class="d-none d-md-block"
                id="showcaseVideo">
                <source src="{{ asset('assets/videos/2675509-hd_1920_1080_24fps.mp4') }}" type="video/mp4">
            </video>
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

        <!-- Video Controls -->
        <button class="video-control-btn" id="videoToggle" aria-label="Toggle video playback">
            <i class="bi bi-pause-fill" id="playPauseIcon"></i>
        </button>
    </section>

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
                        @include('components.product-card', ['product' => $product])
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
                            @include('components.product-card', ['product' => $product])
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- CTA Section -->
    <section class="py-5 my-5">
        <div class="container">
            <div class="glass-dark p-5 text-center" style="border-radius: 30px;" data-aos="fade-up">
                <h2 class="text-white mb-3">انضم إلى عائلة إمبابي كافيه</h2>
                <p class="text-white-50 mb-4 mx-auto" style="max-width: 500px;">
                    اشترك في نشرتنا الإخبارية واحصل على خصم 10% على طلبك الأول
                </p>
                <form class="d-flex gap-3 flex-wrap justify-content-center" style="max-width: 500px; margin: 0 auto;">
                    <input type="email" class="form-control" placeholder="بريدك الإلكتروني"
                        style="flex: 1; min-width: 250px;">
                    <button type="submit" class="btn btn-golden">اشترك الآن</button>
                </form>
            </div>
        </div>
    </section>
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
            height: 200px;
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
                height: 160px;
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
            overflow: hidden;
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
        }

        .video-background video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transform: scale(1.05);
            transition: transform 8s ease;
        }

        .video-showcase-section:hover .video-background video {
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
    </style>
@endpush

@push('scripts')
    <script>
        // Video Play/Pause Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const video = document.getElementById('showcaseVideo');
            const toggleBtn = document.getElementById('videoToggle');
            const playPauseIcon = document.getElementById('playPauseIcon');

            if (video && toggleBtn && playPauseIcon) {
                toggleBtn.addEventListener('click', function() {
                    if (video.paused) {
                        video.play();
                        playPauseIcon.classList.remove('bi-play-fill');
                        playPauseIcon.classList.add('bi-pause-fill');
                    } else {
                        video.pause();
                        playPauseIcon.classList.remove('bi-pause-fill');
                        playPauseIcon.classList.add('bi-play-fill');
                    }
                });
            }

            // Parallax effect on scroll
            window.addEventListener('scroll', function() {
                const section = document.querySelector('.video-showcase-section');
                if (section && video) {
                    const rect = section.getBoundingClientRect();
                    const scrollPercent = (window.innerHeight - rect.top) / (window.innerHeight + rect
                        .height);
                    if (scrollPercent > 0 && scrollPercent < 1) {
                        const scale = 1.05 + (scrollPercent * 0.08);
                        video.style.transform = `scale(${scale})`;
                    }
                }
            });
        });
    </script>
@endpush
