@extends('layouts.app')

@section('title', 'إمبابي كافيه - قهوة فاخرة لعشاق النكهات الأصيلة')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-bg">
            <img src="https://images.unsplash.com/photo-1447933601403-0c6688de566e?w=1920&q=80" alt="Coffee Background">
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
                                <i class="bi bi-bag me-2"></i>تسوق الآن
                            </a>
                            <a href="#featured" class="btn btn-outline-golden btn-lg">
                                <i class="bi bi-cup-hot me-2"></i>اكتشف المنتجات
                            </a>
                        </div>

                        <div class="hero-stats">
                            <div class="stat-item" data-aos="fade-up" data-aos-delay="100">
                                <span class="stat-number">25+</span>
                                <span class="stat-label">نوع قهوة</span>
                            </div>
                            <div class="stat-item" data-aos="fade-up" data-aos-delay="200">
                                <span class="stat-number">15K+</span>
                                <span class="stat-label">عميل سعيد</span>
                            </div>
                            <div class="stat-item" data-aos="fade-up" data-aos-delay="300">
                                <span class="stat-number">5★</span>
                                <span class="stat-label">تقييم العملاء</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block" data-aos="fade-up" data-aos-delay="200">
                    <div class="hero-image text-center" style="position: relative; z-index: 100;">
                        <img src="https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=600&h=800&fit=crop"
                            alt="Premium Coffee" style="border-radius: 30px;">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-5 my-5">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>تصفح الأصناف</h2>
                <p>اختر من مجموعتنا المتنوعة من أفضل أنواع القهوة</p>
            </div>

            <div class="row g-4">
                @foreach ($categories as $category)
                    <div class="col-6 col-lg-{{ 12 / min(count($categories), 5) }}" data-aos="fade-up"
                        data-aos-delay="{{ $loop->index * 100 }}">
                        <a href="{{ route('shop.index', ['category' => $category->slug]) }}" class="category-card d-block">
                            <img src="{{ $category->image }}" alt="{{ $category->name_ar }}">
                            <div class="category-overlay">
                                <h3 class="category-title">{{ $category->name_ar }}</h3>
                                <span class="category-count">{{ $category->products_count }} منتج</span>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
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
    </style>
@endpush
