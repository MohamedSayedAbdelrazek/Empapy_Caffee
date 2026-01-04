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
                                <svg class="cart-icon me-2" xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="currentColor"><path d="M280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM246-720l96 200h280l110-200H246Zm-38-80h590q23 0 35 20.5t1 41.5L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68-39.5t-2-78.5l54-98-144-304H40v-80h130l38 80Zm134 280h280-280Z"/></svg>تسوق الآن
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
                            alt="Premium Coffee" style="border-radius: 30px; max-height: 450px; width: auto; object-fit: cover;">
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
                                <img src="{{ $category->image }}" alt="{{ $category->name_ar }}">
                                <div class="card-overlay"></div>
                                <div class="card-shine"></div>
                            </div>
                            <div class="card-content">
                                <h3>{{ $category->name_ar }}</h3>
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
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
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
    </style>
@endpush
