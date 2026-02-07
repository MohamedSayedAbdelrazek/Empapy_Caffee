@extends('layouts.app')

@section('title', $product->name . ' - إمبابي كافيه')

@section('meta_description', Str::limit($product->description, 160))

@push('scripts')
    {{-- JSON-LD Structured Data for SEO --}}
    <script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Product",
    "name": "{{ $product->name }}",
    "description": "{{ Str::limit($product->description, 300) }}",
    "image": "{{ $product->image }}",
    "sku": "{{ $product->id }}",
    "brand": {
        "@type": "Brand",
        "name": "إمبابي كافيه"
    },
    "category": "{{ $product->category?->name }}",
    "offers": {
        "@type": "Offer",
        "url": "{{ url()->current() }}",
        "priceCurrency": "EGP",
        "price": "{{ $product->current_price }}",
        "availability": "{{ $product->in_stock ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}",
        "seller": {
            "@type": "Organization",
            "name": "إمبابي كافيه"
        }
    }
    {{-- DISABLED: Reviews/Rating - uncomment when ready
    @if(isset($product->reviews_avg_rating) && $product->reviews_count > 0)
    ,"aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "{{ number_format($product->reviews_avg_rating, 1) }}",
        "reviewCount": "{{ $product->reviews_count }}"
    }
    @endif
    --}}
}
</script>
@endpush

@section('content')
    <!-- Page Header -->
    <div class="page-header" style="padding: 140px 0 60px;">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="animated-link">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('shop.index') }}" class="animated-link">المتجر</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('shop.index', ['category' => $product->category?->slug]) }}"
                            class="animated-link">{{ $product->category?->name }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ $product->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="py-5">
        <div class="container">
            <div class="row g-5">
                <!-- Product Gallery -->
                <div class="col-lg-6" data-aos="fade-up">
                    <div class="glass-card p-3 position-sticky" style="top: 100px;">
                        <!-- Main Product Gallery -->
                        <div class="product-gallery">
                            <div class="product-gallery-main" id="mainImageContainer">
                                @if ($product->is_on_sale)
                                    <span class="product-badge sale">
                                        <i class="bi bi-lightning-fill me-1"></i>
                                        خصم {{ $product->discount_percentage }}%
                                    </span>
                                @endif
                                @if ($product->is_featured)
                                    <span class="product-badge featured" style="top: auto; bottom: 20px; right: 20px;">
                                        <i class="bi bi-star-fill me-1"></i>
                                        مميز
                                    </span>
                                @endif
                                <x-optimized-image :src="$product->image" :alt="$product->name" id="gallery-main-image"
                                    class="main-product-image" />

                                @php
                                    $allImages = collect([$product->image]);
                                    if ($product->gallery && is_array($product->gallery)) {
                                        $allImages = $allImages->merge($product->gallery);
                                    }
                                @endphp

                                @if ($allImages->count() > 1)
                                    <button class="gallery-nav prev" aria-label="Previous">
                                        <i class="bi bi-chevron-right"></i>
                                    </button>
                                    <button class="gallery-nav next" aria-label="Next">
                                        <i class="bi bi-chevron-left"></i>
                                    </button>
                                    <div class="gallery-counter">
                                        <span id="gallery-current">1</span> / {{ $allImages->count() }}
                                    </div>
                                @endif

                                <!-- Zoom hint -->
                                <div class="zoom-hint">
                                    <i class="bi bi-zoom-in"></i>
                                    <span>اضغط للتكبير</span>
                                </div>
                            </div>

                            @if ($allImages->count() > 1)
                                <div class="gallery-thumbnails">
                                    @foreach ($allImages as $index => $image)
                                        <div class="gallery-thumb {{ $index === 0 ? 'active' : '' }}"
                                            data-index="{{ $index }}" data-image="{{ $image }}">
                                            <x-optimized-image :src="$image" alt="صورة {{ $index + 1 }}" />
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Product Details -->
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="product-details">
                        <!-- Category Badge -->
                        <span class="badge bg-warning text-dark mb-3 px-3 py-2">
                            <i class="bi bi-tag-fill me-1"></i>
                            {{ $product->category?->name }}
                        </span>

                        <!-- Product Title -->
                        <h1 class="mb-3" style="font-size: 2.5rem; font-weight: 800;">{{ $product->name }}</h1>
                        <h2 class="text-muted mb-4" style="font-size: 1.2rem; font-weight: 400;">{{ $product->name }}</h2>

                        {{-- DISABLED: Rating Stars - uncomment when ready
                        @if (isset($product->reviews_avg_rating))
                            <div class="product-rating mb-3">
                                <div class="stars">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= round($product->reviews_avg_rating))
                                            <i class="bi bi-star-fill text-warning"></i>
                                        @else
                                            <i class="bi bi-star text-muted"></i>
                                        @endif
                                    @endfor
                                </div>
                                <span class="text-muted me-2">({{ $product->reviews_count ?? 0 }} تقييم)</span>
                            </div>
                        @endif
                        --}}

                        <!-- Price Section -->
                        <div class="product-price-lg mb-4 p-4 glass-card">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    @if ($product->has_options)
                                        {{-- Product with options: show price range --}}
                                        @php
                                            $minPrice = $product->min_price;
                                            $maxPrice = $product->max_price;
                                        @endphp
                                        @if ($minPrice != $maxPrice)
                                            <span class="price-current price-range"
                                                style="font-size: 2.2rem; font-weight: 800; color: var(--espresso);">
                                                {{ number_format($minPrice) }} ج.م – {{ number_format($maxPrice) }} ج.م
                                            </span>
                                        @else
                                            <span class="price-current"
                                                style="font-size: 2.5rem; font-weight: 800; color: var(--espresso);">
                                                {{ number_format($minPrice) }} ج.م
                                            </span>
                                        @endif
                                    @elseif ($product->is_on_sale)
                                        {{-- Product on sale --}}
                                        <span class="text-decoration-line-through text-muted d-block"
                                            style="font-size: 1.3rem;">
                                            {{ number_format($product->price) }} ج.م
                                        </span>
                                        <span class="price-current"
                                            style="font-size: 2.5rem; font-weight: 800; color: var(--espresso);">
                                            {{ number_format($product->sale_price) }} ج.م
                                        </span>
                                    @else
                                        {{-- Regular product --}}
                                        <span class="price-current"
                                            style="font-size: 2.5rem; font-weight: 800; color: var(--espresso);">
                                            {{ number_format($product->price) }} ج.م
                                        </span>
                                    @endif
                                </div>
                                @if ($product->is_on_sale && !$product->has_weight_options)
                                    <div class="save-badge">
                                        <span class="badge bg-danger px-3 py-2" style="font-size: 1rem;">
                                            وفّر {{ number_format($product->price - $product->sale_price) }} ج.م
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Product Meta Info Cards -->
                        <div class="row g-3 mb-4">
                            @if ($product->roast_level && !$product->has_roast_options)
                                <div class="col-6">
                                    <div class="glass-card p-3 text-center h-100 meta-card">
                                        <i class="bi bi-fire text-gold fs-4"></i>
                                        <p class="mb-0 mt-2 small text-muted">التحميص</p>
                                        <strong>
                                            @switch($product->roast_level)
                                                @case('light')
                                                    تحميص فاتح
                                                @break

                                                @case('medium')
                                                    تحميص متوسط
                                                @break

                                                @case('dark')
                                                    تحميص داكن
                                                @break
                                            @endswitch
                                        </strong>
                                    </div>
                                </div>
                            @endif
                            @if ($product->weight && !$product->has_weight_options)
                                <div class="col-6">
                                    <div class="glass-card p-3 text-center h-100 meta-card">
                                        <i class="bi bi-box-seam text-gold fs-4"></i>
                                        <p class="mb-0 mt-2 small text-muted">الوزن</p>
                                        <strong>{{ $product->weight }}</strong>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Product Options Section (Weight, Roast, Additives) --}}
                        @if ($product->has_options)
                            <div class="product-options-section mb-4" id="productOptionsSection">
                                @php
                                    $weightValues = $product->weight_values;
                                    $roastValues = $product->roast_values;
                                    $additiveValues = $product->additive_values;
                                    $flavorValues = $product->flavor_values;
                                    $basePrice = $product->current_price;
                                @endphp

                                {{-- Weight Options --}}
                                @if ($product->has_weight_options && $weightValues->isNotEmpty())
                                    <div class="option-group glass-card p-3 mb-3">
                                        <h6 class="option-title mb-3">
                                            <i class="bi bi-box-seam text-gold me-2"></i>
                                            الوزن
                                        </h6>
                                        <div class="option-pills d-flex flex-wrap gap-2">
                                            @foreach ($weightValues as $value)
                                                <button type="button"
                                                    class="option-pill {{ $value->is_default ? 'active' : '' }}"
                                                    data-option-type="weight" data-option-id="{{ $value->id }}"
                                                    data-price-modifier="{{ $value->price_modifier }}"
                                                    data-value="{{ $value->value }}">
                                                    <span class="option-value">{{ $value->value }}</span>
                                                    {{-- Weight shows full price (not modifier) --}}
                                                    <span class="option-price-tag">
                                                        {{ number_format($value->price_modifier) }} ج.م
                                                    </span>
                                                </button>
                                            @endforeach
                                        </div>
                                        <input type="hidden" name="selected_weight" id="selected_weight"
                                            value="{{ $weightValues->firstWhere('is_default', true)?->id ?? $weightValues->first()?->id }}">
                                    </div>
                                @endif

                                {{-- Roast Options --}}
                                @if ($product->has_roast_options && $roastValues->isNotEmpty())
                                    <div class="option-group glass-card p-3 mb-3">
                                        <h6 class="option-title mb-3">
                                            <i class="bi bi-fire text-gold me-2"></i>
                                            درجة التحميص
                                        </h6>
                                        <div class="option-pills d-flex flex-wrap gap-2">
                                            @foreach ($roastValues as $value)
                                                <button type="button"
                                                    class="option-pill roast {{ $value->is_default ? 'active' : '' }}"
                                                    data-option-type="roast" data-option-id="{{ $value->id }}"
                                                    data-price-modifier="{{ $value->price_modifier }}"
                                                    data-value="{{ $value->value }}">
                                                    <span class="option-value">{{ $value->value }}</span>
                                                    @if ($value->price_modifier != 0)
                                                        <span
                                                            class="option-price-mod {{ $value->price_modifier > 0 ? 'positive' : 'negative' }}">
                                                            {{ $value->price_modifier > 0 ? '+' : '' }}{{ number_format($value->price_modifier) }}
                                                        </span>
                                                    @endif
                                                </button>
                                            @endforeach
                                        </div>
                                        <input type="hidden" name="selected_roast" id="selected_roast"
                                            value="{{ $roastValues->firstWhere('is_default', true)?->id ?? $roastValues->first()?->id }}">
                                    </div>
                                @endif

                                {{-- Additive Options --}}
                                @if ($product->has_additive_options && $additiveValues->isNotEmpty())
                                    <div class="option-group glass-card p-3 mb-3">
                                        <h6 class="option-title mb-3">
                                            <i class="bi bi-plus-circle text-gold me-2"></i>
                                            الإضافات
                                        </h6>
                                        <div class="option-pills d-flex flex-wrap gap-2">
                                            @foreach ($additiveValues as $value)
                                                <button type="button"
                                                    class="option-pill additive {{ $value->is_default ? 'active' : '' }}"
                                                    data-option-type="additive" data-option-id="{{ $value->id }}"
                                                    data-price-modifier="{{ $value->price_modifier }}"
                                                    data-value="{{ $value->value }}">
                                                    <span class="option-value">{{ $value->value }}</span>
                                                    @if ($value->price_modifier != 0)
                                                        <span
                                                            class="option-price-mod {{ $value->price_modifier > 0 ? 'positive' : 'negative' }}">
                                                            {{ $value->price_modifier > 0 ? '+' : '' }}{{ number_format($value->price_modifier) }}
                                                        </span>
                                                    @endif
                                                </button>
                                            @endforeach
                                        </div>
                                        <input type="hidden" name="selected_additive" id="selected_additive"
                                            value="{{ $additiveValues->firstWhere('is_default', true)?->id ?? $additiveValues->first()?->id }}">
                                    </div>
                                @endif

                                {{-- Flavor Options --}}
                                @if ($product->has_flavor_options && $flavorValues->isNotEmpty())
                                    <div class="option-group glass-card p-3 mb-3">
                                        <h6 class="option-title mb-3">
                                            <i class="bi bi-palette text-gold me-2"></i>
                                            النكهة
                                        </h6>
                                        <div class="option-pills d-flex flex-wrap gap-2">
                                            @foreach ($flavorValues as $value)
                                                <button type="button"
                                                    class="option-pill flavor {{ $value->is_default ? 'active' : '' }}"
                                                    data-option-type="flavor" data-option-id="{{ $value->id }}"
                                                    data-price-modifier="{{ $value->price_modifier }}"
                                                    data-value="{{ $value->value }}">
                                                    <span class="option-value">{{ $value->value }}</span>
                                                    {{-- Flavor shows full price (like weight) --}}
                                                    <span class="option-price-tag">
                                                        {{ number_format($value->price_modifier) }} ج.م
                                                    </span>
                                                </button>
                                            @endforeach
                                        </div>
                                        <input type="hidden" name="selected_flavor" id="selected_flavor"
                                            value="{{ $flavorValues->firstWhere('is_default', true)?->id ?? $flavorValues->first()?->id }}">
                                    </div>
                                @endif

                                {{-- Dynamic Price Display --}}
                                <div class="calculated-price glass-card p-3 text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-3">
                                        <span class="price-label">السعر الإجمالي:</span>
                                        <span class="dynamic-price" id="dynamicPrice"
                                            data-base-price="{{ $basePrice }}"
                                            style="font-size: 1.8rem; font-weight: 800; color: var(--espresso);">
                                            {{ number_format($product->starting_price) }} ج.م
                                        </span>
                                    </div>
                                    <small class="text-muted" id="priceBreakdown"></small>
                                </div>
                            </div>
                        @endif

                        <!-- Description -->
                        <div class="mb-4">
                            <h5 class="d-flex align-items-center gap-2">
                                <i class="bi bi-file-text text-gold"></i>
                                الوصف
                            </h5>
                            <p class="text-muted lead">{{ $product->description }}</p>
                        </div>

                        <!-- Add to Cart Section -->
                        <div class="quantity-selector">
                            <button type="button" class="qty-btn qty-minus" aria-label="Decrease">
                                <i class="bi bi-dash-lg"></i>
                            </button>
                            <input type="number" id="quantity" value="1" min="1" class="qty-input"
                                readonly>
                            <button type="button" class="qty-btn qty-plus" aria-label="Increase">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                        </div>
                        <button class="btn btn-golden btn-lg flex-grow-1 add-cart-btn ripple" id="addToCartBtn"
                            data-product-id="{{ $product->id }}">
                            <span class="btn-content">
                                <svg class="cart-icon-add me-2" xmlns="http://www.w3.org/2000/svg" height="20px"
                                    viewBox="0 -960 960 960" width="20px" fill="currentColor">
                                    <path
                                        d="M280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM246-720l96 200h280l110-200H246Zm-38-80h590q23 0 35 20.5t1 41.5L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68-39.5t-2-78.5l54-98-144-304H40v-80h130l38 80Zm134 280h280-280Z" />
                                </svg>
                                أضف للسلة
                            </span>
                            <span class="btn-loading d-none">
                                <span class="loading-dots">
                                    <span></span><span></span><span></span>
                                </span>
                            </span>
                            <span class="btn-success-state d-none">
                                <i class="bi bi-check-lg me-2"></i>
                                تمت الإضافة!
                            </span>
                        </button>
                    </div>

                    {{-- Buy Now Button --}}
                    <div class="mt-3">
                        <a href="{{ route('checkout.index') }}" class="btn btn-outline-golden btn-lg w-100 buy-now-btn">
                            <i class="bi bi-lightning-charge-fill me-2"></i>
                            اشتر الآن
                        </a>
                    </div>

                    <!-- Wishlist Button -->
                    @auth
                        <div class="mt-3">
                            <button class="btn btn-outline-secondary w-100 wishlist-btn"
                                data-product-id="{{ $product->id }}">
                                <i class="bi bi-heart me-2"></i>
                                أضف للمفضلة
                            </button>
                        </div>
                    @endauth
                </div>


                <!-- Product Features -->
                <div class="product-features mt-4">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="feature-item">
                                <i class="bi bi-truck text-gold"></i>
                                <span>توصيل سريع</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="feature-item">
                                <i class="bi bi-shield-check text-gold"></i>
                                <span>جودة مضمونة</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="feature-item">
                                <i class="bi bi-arrow-repeat text-gold"></i>
                                <span>استبدال سهل</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="feature-item">
                                <i class="bi bi-headset text-gold"></i>
                                <span>دعم متواصل</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Share Section -->
                <div class="share-section mt-4 pt-4 border-top">
                    <h6 class="mb-3">
                        <i class="bi bi-share text-gold me-2"></i>
                        مشاركة المنتج
                    </h6>
                    <div class="d-flex gap-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                            target="_blank" class="share-btn facebook">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($product->name) }}"
                            target="_blank" class="share-btn twitter">
                            <i class="bi bi-twitter-x"></i>
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($product->name . ' - ' . request()->url()) }}"
                            target="_blank" class="share-btn whatsapp">
                            <i class="bi bi-whatsapp"></i>
                        </a>
                        <button class="share-btn copy-link" onclick="copyProductLink()">
                            <i class="bi bi-link-45deg"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        </div>
        </div>
    </section>

    <!-- Lightbox Modal -->
    <div class="lightbox" id="imageLightbox">
        <button class="lightbox-close" aria-label="Close" onclick="closeLightbox()">
            <i class="bi bi-x-lg"></i>
        </button>
        <div class="lightbox-zoom-controls">
            <button class="zoom-btn" onclick="zoomIn()" aria-label="Zoom In">
                <i class="bi bi-zoom-in"></i>
            </button>
            <button class="zoom-btn" onclick="zoomOut()" aria-label="Zoom Out">
                <i class="bi bi-zoom-out"></i>
            </button>
            <button class="zoom-btn" onclick="zoomReset()" aria-label="Reset Zoom">
                <i class="bi bi-arrows-angle-contract"></i>
            </button>
        </div>
        <div class="lightbox-content">
            <div class="zoom-container" id="zoomContainer">
                <x-optimized-image :src="$product->image" :alt="$product->name" class="lightbox-image"
                    id="lightbox-image" />
            </div>
            @if ($allImages->count() > 1)
                <button class="lightbox-nav prev" onclick="lightboxPrev()" aria-label="Previous">
                    <i class="bi bi-chevron-right"></i>
                </button>
                <button class="lightbox-nav next" onclick="lightboxNext()" aria-label="Next">
                    <i class="bi bi-chevron-left"></i>
                </button>
            @endif
        </div>
        @if ($allImages->count() > 1)
            <div class="lightbox-thumbnails">
                @foreach ($allImages as $index => $image)
                    <div class="lightbox-thumb {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}"
                        onclick="lightboxGoTo({{ $index }})">
                        <x-optimized-image :src="$image" alt="Thumbnail {{ $index + 1 }}" />
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Related Products -->
    @if ($relatedProducts->count())
        <section class="py-5 bg-white">
            <div class="container">
                <div class="section-title" data-aos="fade-up">
                    <h2>منتجات مشابهة</h2>
                    <p>قد يعجبك أيضاً</p>
                </div>

                <div class="row g-4">
                    @foreach ($relatedProducts as $related)
                        <div class="col-6 col-md-4 col-lg-3" data-aos="fade-up"
                            data-aos-delay="{{ $loop->index * 100 }}">
                            @include('components.product-card', [
                                'product' => $related,
                                'wishlistIds' => $wishlistIds ?? [],
                            ])
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection

@push('styles')
    <style>
        .text-gold {
            color: var(--gold) !important;
        }

        /* Enhanced Product Gallery Styles */
        .product-gallery-main {
            position: relative;
            border-radius: var(--radius-lg);
            overflow: hidden;
            background: var(--cream);
            aspect-ratio: 1;
            cursor: pointer;
        }

        .product-gallery-main img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.5s ease;
        }

        .product-gallery-main:hover img {
            transform: scale(1.03);
        }

        .zoom-hint {
            position: absolute;
            bottom: 15px;
            left: 15px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 8px 16px;
            border-radius: var(--radius-full);
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 8px;
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .product-gallery-main:hover .zoom-hint {
            opacity: 1;
            transform: translateY(0);
        }

        /* Quantity Selector */
        .quantity-selector {
            display: flex;
            align-items: center;
            background: var(--white);
            border-radius: var(--radius-full);
            border: 2px solid var(--gray-200);
            overflow: hidden;
        }

        .qty-btn {
            width: 50px;
            height: 50px;
            border: none;
            background: transparent;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: var(--espresso);
            transition: all 0.2s ease;
        }

        .qty-btn:hover {
            background: var(--gold);
            color: var(--espresso);
        }

        .qty-btn:active {
            transform: scale(0.95);
        }

        .qty-input {
            width: 60px;
            height: 50px;
            border: none;
            text-align: center;
            font-size: 1.2rem;
            font-weight: 700;
            background: transparent;
            color: var(--espresso);
        }

        .qty-input::-webkit-inner-spin-button,
        .qty-input::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Add to Cart Button States */
        .add-cart-btn {
            position: relative;
            min-width: 200px;
            overflow: hidden;
        }

        .add-cart-btn .btn-loading,
        .add-cart-btn .btn-success-state {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .add-cart-btn.loading .btn-content {
            opacity: 0;
        }

        .add-cart-btn.loading .btn-loading {
            display: flex !important;
        }

        .add-cart-btn.success .btn-content {
            opacity: 0;
        }

        .add-cart-btn.success .btn-success-state {
            display: flex !important;
            background: #22c55e;
        }

        /* Meta Card Hover */
        .meta-card {
            transition: all 0.3s ease;
        }

        .meta-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .meta-card i {
            transition: transform 0.3s ease;
        }

        .meta-card:hover i {
            transform: scale(1.2);
        }

        /* Product Options Styles */
        .product-options-section {
            margin-top: 1.5rem;
        }

        .option-group {
            border: 1px solid rgba(201, 162, 39, 0.2);
        }

        .option-title {
            font-weight: 700;
            color: var(--espresso);
            display: flex;
            align-items: center;
        }

        .option-pills {
            margin-bottom: 0;
        }

        .option-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            background: var(--white);
            border: 2px solid rgba(201, 162, 39, 0.3);
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            color: var(--espresso);
            position: relative;
            overflow: hidden;
        }

        .option-pill::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 0;
        }

        .option-pill:hover {
            border-color: var(--gold);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(201, 162, 39, 0.3);
        }

        .option-pill.active {
            border-color: var(--gold);
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            color: var(--white);
            box-shadow: 0 4px 20px rgba(201, 162, 39, 0.4);
        }

        .option-pill.active .option-value,
        .option-pill.active .option-price-mod {
            position: relative;
            z-index: 1;
        }

        .option-value {
            position: relative;
            z-index: 1;
        }

        .option-price-mod {
            position: relative;
            z-index: 1;
            font-size: 0.85rem;
            padding: 2px 8px;
            border-radius: 20px;
            font-weight: 700;
        }

        .option-price-mod.positive {
            background: rgba(34, 197, 94, 0.15);
            color: #16a34a;
        }

        .option-price-mod.negative {
            background: rgba(239, 68, 68, 0.15);
            color: #dc2626;
        }

        .option-pill.active .option-price-mod.positive {
            background: rgba(255, 255, 255, 0.25);
            color: var(--white);
        }

        .option-pill.active .option-price-mod.negative {
            background: rgba(255, 255, 255, 0.25);
            color: var(--white);
        }

        /* Weight price tag (shows full price, not modifier) */
        .option-price-tag {
            font-size: 0.85rem;
            padding: 2px 8px;
            border-radius: 20px;
            font-weight: 700;
            background: rgba(201, 162, 39, 0.15);
            color: var(--gold-dark);
        }

        .option-pill.active .option-price-tag {
            background: rgba(255, 255, 255, 0.25);
            color: var(--white);
        }

        /* Roast level specific colors */
        .option-pill.roast.active {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        /* Additive specific colors */
        .option-pill.additive.active {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .calculated-price {
            background: linear-gradient(135deg, rgba(201, 162, 39, 0.1), rgba(201, 162, 39, 0.05));
            border: 2px solid var(--gold);
            margin-top: 1rem;
        }

        .price-label {
            font-size: 1rem;
            color: var(--gray-600);
            font-weight: 500;
        }

        .dynamic-price {
            transition: all 0.3s ease;
        }

        .dynamic-price.updating {
            transform: scale(1.1);
            color: var(--gold) !important;
        }

        #priceBreakdown {
            display: block;
            margin-top: 0.5rem;
            font-size: 0.85rem;
        }

        /* Product Features */
        .feature-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 15px;
            background: var(--gray-100);
            border-radius: var(--radius-md);
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .feature-item:hover {
            background: var(--cream);
            transform: translateX(-5px);
        }

        .feature-item i {
            font-size: 1.2rem;
        }

        /* Share Buttons */
        .share-btn {
            width: 45px;
            height: 45px;
            border-radius: var(--radius-full);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .share-btn.facebook {
            background: #1877f2;
            color: white;
        }

        .share-btn.twitter {
            background: #000;
            color: white;
        }

        .share-btn.whatsapp {
            background: #25d366;
            color: white;
        }

        .share-btn.copy-link {
            background: var(--gray-200);
            color: var(--espresso);
        }

        .share-btn:hover {
            transform: translateY(-3px) scale(1.1);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        }

        /* Gallery Thumbnails Enhanced */
        .gallery-thumbnails {
            display: flex;
            gap: 12px;
            margin-top: 15px;
            overflow-x: auto;
            padding: 5px;
            scrollbar-width: none;
        }

        .gallery-thumbnails::-webkit-scrollbar {
            display: none;
        }

        .gallery-thumb {
            width: 80px;
            height: 80px;
            border-radius: var(--radius-md);
            overflow: hidden;
            cursor: pointer;
            border: 3px solid transparent;
            transition: all 0.3s ease;
            flex-shrink: 0;
            position: relative;
        }

        .gallery-thumb::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(201, 162, 39, 0);
            transition: background 0.3s ease;
            z-index: 1;
        }

        .gallery-thumb:hover::before {
            background: rgba(201, 162, 39, 0.2);
        }

        .gallery-thumb.active {
            border-color: var(--gold);
            box-shadow: 0 4px 15px rgba(201, 162, 39, 0.4);
        }

        .gallery-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Wishlist Button */
        .wishlist-btn {
            transition: all 0.3s ease;
        }

        .wishlist-btn:hover {
            background: #ff6b9d;
            border-color: #ff6b9d;
            color: white;
        }

        .wishlist-btn.active {
            background: #ff6b9d;
            border-color: #ff6b9d;
            color: white;
        }

        .wishlist-btn.active i {
            fill: currentColor;
        }

        /* Add to cart section */
        .add-to-cart-section {
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid var(--gold);
        }

        /* Product rating */
        .product-rating {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .product-rating .stars {
            display: flex;
            gap: 3px;
        }

        .product-rating .stars i {
            font-size: 1.1rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .quantity-selector {
                width: 100%;
                justify-content: center;
            }

            .add-cart-btn {
                width: 100%;
            }

            .gallery-thumb {
                width: 60px;
                height: 60px;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gallery Images Data
            const galleryImages = @json($allImages);
            let currentIndex = 0;
            let zoomLevel = 1;
            const maxZoom = 3;
            const minZoom = 1;

            // Quantity Controls
            const quantityInput = document.getElementById('quantity');
            const btnMinus = document.querySelector('.qty-minus');
            const btnPlus = document.querySelector('.qty-plus');
            const addToCartBtn = document.getElementById('addToCartBtn');

            btnMinus?.addEventListener('click', () => {
                const current = parseInt(quantityInput.value) || 1;
                if (current > 1) {
                    quantityInput.value = current - 1;
                    animateQuantityChange('decrease');
                }
            });

            btnPlus?.addEventListener('click', () => {
                const current = parseInt(quantityInput.value) || 1;
                const max = parseInt(quantityInput.max) || 10;
                if (current < max) {
                    quantityInput.value = current + 1;
                    animateQuantityChange('increase');
                }
            });

            function animateQuantityChange(direction) {
                quantityInput.style.transform = direction === 'increase' ? 'scale(1.2)' : 'scale(0.8)';
                setTimeout(() => {
                    quantityInput.style.transform = 'scale(1)';
                }, 150);
            }

            // Add to Cart
            addToCartBtn?.addEventListener('click', function() {
                const productId = this.dataset.productId;
                const quantity = parseInt(quantityInput.value) || 1;

                // Get options from dataset (populated by separate listener)
                let options = {};
                try {
                    options = JSON.parse(this.dataset.selectedOptions || '{}');
                } catch (e) {
                    console.error('Error parsing options', e);
                }

                // Show loading state
                this.classList.add('loading');
                this.disabled = true;

                // Make AJAX request
                fetch('/cart/add', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            quantity: quantity,
                            options: options
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        this.classList.remove('loading');

                        if (data.success) {
                            // Show success state
                            this.classList.add('success');

                            // Show toast
                            if (window.Toast) {
                                window.Toast.cart('تمت الإضافة! 🎉',
                                    `تمت إضافة ${quantity} من المنتج إلى سلة التسوق`);
                            }

                            // Create confetti
                            if (window.createConfetti) {
                                window.createConfetti();
                            }

                            // Update cart count
                            if (typeof updateCartCount === 'function') {
                                updateCartCount();
                            }

                            // Reset button after delay
                            setTimeout(() => {
                                this.classList.remove('success');
                                this.disabled = false;
                            }, 2000);
                        } else {
                            this.disabled = false;
                            if (window.Toast) {
                                window.Toast.error('خطأ', data.message || 'حدث خطأ أثناء الإضافة');
                            }
                        }
                    })
                    .catch(error => {
                        this.classList.remove('loading');
                        this.disabled = false;
                        if (window.Toast) {
                            window.Toast.error('خطأ في الاتصال', 'يرجى المحاولة مرة أخرى');
                        }
                    });
            });

            // Gallery Thumbnail Clicks
            document.querySelectorAll('.gallery-thumb').forEach(thumb => {
                thumb.addEventListener('click', () => {
                    const index = parseInt(thumb.dataset.index);
                    goToImage(index);
                });
            });

            // Gallery Navigation
            document.querySelector('.gallery-nav.prev')?.addEventListener('click', (e) => {
                e.stopPropagation();
                prevImage();
            });

            document.querySelector('.gallery-nav.next')?.addEventListener('click', (e) => {
                e.stopPropagation();
                nextImage();
            });

            // Open Lightbox on Main Image Click
            document.getElementById('mainImageContainer')?.addEventListener('click', (e) => {
                if (!e.target.closest('.gallery-nav')) {
                    openLightbox();
                }
            });

            // Gallery Functions
            window.goToImage = function(index) {
                currentIndex = index;
                const mainImg = document.getElementById('gallery-main-image');
                const counter = document.getElementById('gallery-current');

                // Animate image change
                mainImg.style.opacity = '0';
                mainImg.style.transform = 'scale(0.95)';

                setTimeout(() => {
                    mainImg.src = galleryImages[index];
                    mainImg.style.opacity = '1';
                    mainImg.style.transform = 'scale(1)';
                }, 200);

                if (counter) counter.textContent = index + 1;

                // Update thumbnails
                document.querySelectorAll('.gallery-thumb').forEach((t, i) => {
                    t.classList.toggle('active', i === index);
                });

                // Update lightbox if open
                const lightboxImg = document.getElementById('lightbox-image');
                if (lightboxImg) lightboxImg.src = galleryImages[index];

                document.querySelectorAll('.lightbox-thumb').forEach((t, i) => {
                    t.classList.toggle('active', i === index);
                });
            }

            window.nextImage = function() {
                const nextIndex = (currentIndex + 1) % galleryImages.length;
                goToImage(nextIndex);
            }

            window.prevImage = function() {
                const prevIndex = (currentIndex - 1 + galleryImages.length) % galleryImages.length;
                goToImage(prevIndex);
            }

            // Lightbox Functions
            window.openLightbox = function() {
                const lightbox = document.getElementById('imageLightbox');
                const lightboxImg = document.getElementById('lightbox-image');
                lightboxImg.src = galleryImages[currentIndex];
                lightbox.classList.add('active');
                document.body.style.overflow = 'hidden';
            }

            window.closeLightbox = function() {
                const lightbox = document.getElementById('imageLightbox');
                lightbox.classList.remove('active');
                document.body.style.overflow = '';
                zoomReset();
            }

            window.lightboxNext = function() {
                nextImage();
            }

            window.lightboxPrev = function() {
                prevImage();
            }

            window.lightboxGoTo = function(index) {
                goToImage(index);
            }

            // Zoom Functions
            window.zoomIn = function() {
                if (zoomLevel < maxZoom) {
                    zoomLevel = Math.min(maxZoom, zoomLevel + 0.5);
                    updateZoom();
                }
            }

            window.zoomOut = function() {
                if (zoomLevel > minZoom) {
                    zoomLevel = Math.max(minZoom, zoomLevel - 0.5);
                    updateZoom();
                }
            }

            window.zoomReset = function() {
                zoomLevel = 1;
                updateZoom();
            }

            function updateZoom() {
                const lightboxImg = document.getElementById('lightbox-image');
                if (lightboxImg) {
                    lightboxImg.style.transform = `scale(${zoomLevel})`;
                }
            }

            // Keyboard Navigation
            document.addEventListener('keydown', (e) => {
                const lightbox = document.getElementById('imageLightbox');
                if (!lightbox.classList.contains('active')) return;

                switch (e.key) {
                    case 'Escape':
                        closeLightbox();
                        break;
                    case 'ArrowRight':
                        prevImage();
                        break;
                    case 'ArrowLeft':
                        nextImage();
                        break;
                    case '+':
                    case '=':
                        zoomIn();
                        break;
                    case '-':
                        zoomOut();
                        break;
                }
            });

            // Close lightbox on overlay click
            document.getElementById('imageLightbox')?.addEventListener('click', (e) => {
                if (e.target.id === 'imageLightbox') {
                    closeLightbox();
                }
            });

            // Mouse wheel zoom in lightbox
            document.getElementById('zoomContainer')?.addEventListener('wheel', (e) => {
                e.preventDefault();
                if (e.deltaY < 0) zoomIn();
                else zoomOut();
            });

            // Touch swipe for gallery
            let touchStartX = 0;
            const mainImageContainer = document.getElementById('mainImageContainer');

            mainImageContainer?.addEventListener('touchstart', (e) => {
                touchStartX = e.touches[0].clientX;
            }, {
                passive: true
            });

            mainImageContainer?.addEventListener('touchend', (e) => {
                const touchEndX = e.changedTouches[0].clientX;
                const diff = touchStartX - touchEndX;

                if (Math.abs(diff) > 50) {
                    if (diff > 0) nextImage();
                    else prevImage();
                }
            }, {
                passive: true
            });

            // Copy Link Function
            window.copyProductLink = function() {
                navigator.clipboard.writeText(window.location.href).then(() => {
                    if (window.Toast) {
                        window.Toast.success('تم النسخ!', 'تم نسخ رابط المنتج بنجاح');
                    }
                });
            }

            // ====== Product Options Dynamic Pricing ======
            const optionsSection = document.getElementById('productOptionsSection');

            // Inject Pricing Matrix Data
            @php
                $pricingMatrix = [];
                if (isset($product) && $product->has_additive_options && $product->has_weight_options) {
                    $additives = $product->additive_values->pluck('id');
                    $prices = \App\Models\AdditiveWeightPrice::whereIn('additive_option_value_id', $additives)->get();
                    foreach ($prices as $price) {
                        $pricingMatrix[$price->additive_option_value_id][$price->weight_option_value_id] = $price->price_modifier;
                    }
                }
            @endphp
            const pricingMatrix = @json($pricingMatrix);

            if (optionsSection) {
                const optionPills = document.querySelectorAll('.option-pill');
                const dynamicPriceEl = document.getElementById('dynamicPrice');
                const priceBreakdownEl = document.getElementById('priceBreakdown');
                const basePrice = parseFloat(dynamicPriceEl?.dataset.basePrice || 0);

                // Handle option selection
                optionPills.forEach(pill => {
                    pill.addEventListener('click', function() {
                        const type = this.dataset.optionType;
                        const optionId = this.dataset.optionId;

                        // Remove active from same type pills
                        document.querySelectorAll(`.option-pill[data-option-type="${type}"]`)
                            .forEach(p => {
                                p.classList.remove('active');
                            });

                        // Add active to clicked pill
                        this.classList.add('active');

                        // Update hidden input
                        const hiddenInput = document.getElementById(`selected_${type}`);
                        if (hiddenInput) {
                            hiddenInput.value = optionId;
                        }

                        // Recalculate price
                        updateDynamicPrice();
                    });
                });

                // Calculate and update dynamic price
                function updateDynamicPrice() {
                    let finalPrice = basePrice; // Default to product base
                    let additionalModifiers = 0;
                    const breakdown = [];

                    // 1. Find selected weight first (weight overrides base price)
                    const activeWeightPill = document.querySelector(
                        '.option-pill.active[data-option-type="weight"]');
                    let selectedWeightId = null;

                    if (activeWeightPill) {
                        // Weight: the price_modifier IS the full price
                        finalPrice = parseFloat(activeWeightPill.dataset.priceModifier || 0);
                        selectedWeightId = activeWeightPill.dataset.optionId;
                        breakdown.push(`${activeWeightPill.dataset.value}: ${formatNumber(finalPrice)} ج.م`);
                    }

                    // 2. Find selected flavor (flavor also overrides base price like weight)
                    const activeFlavorPill = document.querySelector(
                        '.option-pill.active[data-option-type="flavor"]');

                    if (activeFlavorPill) {
                        // Flavor: the price_modifier IS the full price (like weight)
                        finalPrice = parseFloat(activeFlavorPill.dataset.priceModifier || 0);
                        breakdown.push(`${activeFlavorPill.dataset.value}: ${formatNumber(finalPrice)} ج.م`);
                    }

                    // 3. Process other options (roast, additive - these ADD to the price)
                    document.querySelectorAll('.option-pill.active').forEach(pill => {
                        const type = pill.dataset.optionType;
                        if (type === 'weight' || type === 'flavor') return; // Handled above

                        let modifier = parseFloat(pill.dataset.priceModifier || 0);
                        const value = pill.dataset.value;
                        const optionId = pill.dataset.optionId;

                        // Check for matrix price override for additives
                        if (type === 'additive' && selectedWeightId &&
                            pricingMatrix[optionId] &&
                            pricingMatrix[optionId][selectedWeightId] !== undefined) {

                            modifier = parseFloat(pricingMatrix[optionId][selectedWeightId]);
                        }

                        additionalModifiers += modifier;
                        if (modifier !== 0) {
                            const sign = modifier > 0 ? '+' : '';
                            breakdown.push(`${value}: ${sign}${formatNumber(modifier)}`);
                        }
                    });

                    finalPrice = finalPrice + additionalModifiers;

                    // Animate price change
                    if (dynamicPriceEl) {
                        dynamicPriceEl.classList.add('updating');
                        dynamicPriceEl.textContent = formatNumber(finalPrice) + ' ج.م';

                        setTimeout(() => {
                            dynamicPriceEl.classList.remove('updating');
                        }, 300);
                    }

                    // Show breakdown only if there are modifiers
                    if (priceBreakdownEl && additionalModifiers !== 0) {
                        priceBreakdownEl.textContent = `(${breakdown.join(' | ')})`;
                    } else if (priceBreakdownEl) {
                        priceBreakdownEl.textContent = '';
                    }
                }

                // Format number with Arabic-style thousands separator
                function formatNumber(num) {
                    return new Intl.NumberFormat('ar-EG').format(Math.round(num));
                }

                // Initial price calculation
                updateDynamicPrice();

                // Update add to cart to include options
                const addToCartBtn = document.getElementById('addToCartBtn');
                if (addToCartBtn) {
                    const originalClickHandler = addToCartBtn.onclick;

                    addToCartBtn.addEventListener('click', function(e) {
                        // Collect selected options before cart action
                        const selectedOptions = {};

                        ['weight', 'roast', 'additive'].forEach(type => {
                            const input = document.getElementById(`selected_${type}`);
                            if (input && input.value) {
                                selectedOptions[type] = input.value;
                            }
                        });

                        // Store in data attribute for cart handler
                        this.dataset.selectedOptions = JSON.stringify(selectedOptions);
                    });
                }
            }
            // ====== Wishlist Button Handler ======
            const wishlistBtn = document.querySelector('.wishlist-btn');
            if (wishlistBtn) {
                wishlistBtn.addEventListener('click', function() {
                    const productId = this.dataset.productId;
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                    const icon = this.querySelector('i');

                    fetch('/wishlist/toggle', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({
                                product_id: productId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                if (data.added) {
                                    icon.classList.remove('bi-heart');
                                    icon.classList.add('bi-heart-fill', 'text-danger');
                                    this.innerHTML =
                                        `<i class="bi bi-heart-fill text-danger me-2"></i>في المفضلة`;
                                    if (window.Toast) window.Toast.success('تمت الإضافة!',
                                        'تمت إضافة المنتج للمفضلة');
                                } else {
                                    icon.classList.remove('bi-heart-fill', 'text-danger');
                                    icon.classList.add('bi-heart');
                                    this.innerHTML = `<i class="bi bi-heart me-2"></i>أضف للمفضلة`;
                                    if (window.Toast) window.Toast.success('تم الحذف!',
                                        'تم حذف المنتج من المفضلة');
                                }
                                // Update wishlist badge
                                if (typeof updateWishlistCount === 'function') updateWishlistCount();
                            }
                        })
                        .catch(() => {
                            if (window.Toast) window.Toast.error('خطأ', 'حدث خطأ أثناء العملية');
                        });
                });
            }
        });
    </script>
@endpush
