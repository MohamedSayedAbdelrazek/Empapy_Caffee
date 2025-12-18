@extends('layouts.app')

@section('title', $product->name_ar . ' - إمبابي كافيه')

@section('content')
    <!-- Page Header -->
    <div class="page-header" style="padding: 140px 0 60px;">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('shop.index') }}">المتجر</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('shop.index', ['category' => $product->category?->slug]) }}">{{ $product->category?->name_ar }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ $product->name_ar }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="py-5">
        <div class="container">
            <div class="row g-5">
                <!-- Product Image -->
                <div class="col-lg-6" data-aos="fade-up">
                    <div class="glass-card p-3 position-sticky" style="top: 100px;">
                        <div class="product-main-image">
                            @if ($product->is_on_sale)
                                <span class="product-badge sale">خصم {{ $product->discount_percentage }}%</span>
                            @endif
                            <img src="{{ $product->image }}" alt="{{ $product->name_ar }}" class="w-100"
                                style="border-radius: var(--radius-md);">
                        </div>
                    </div>
                </div>

                <!-- Product Details -->
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="product-details">
                        <span class="badge bg-warning text-dark mb-3">{{ $product->category?->name_ar }}</span>

                        <h1 class="mb-3" style="font-size: 2.5rem; font-weight: 800;">{{ $product->name_ar }}</h1>
                        <h2 class="text-muted mb-4" style="font-size: 1.2rem; font-weight: 400;">{{ $product->name }}</h2>

                        <!-- Price -->
                        <div class="product-price-lg mb-4">
                            @if ($product->is_on_sale)
                                <span class="text-decoration-line-through text-muted me-2" style="font-size: 1.5rem;">
                                    {{ number_format($product->price) }} ج.م
                                </span>
                                <span style="font-size: 2.5rem; font-weight: 800; color: var(--espresso);">
                                    {{ number_format($product->sale_price) }} ج.م
                                </span>
                            @else
                                <span style="font-size: 2.5rem; font-weight: 800; color: var(--espresso);">
                                    {{ number_format($product->price) }} ج.م
                                </span>
                            @endif
                        </div>

                        <!-- Product Meta -->
                        <div class="row g-3 mb-4">
                            @if ($product->origin_ar)
                                <div class="col-6">
                                    <div class="glass-card p-3 text-center h-100">
                                        <i class="bi bi-geo-alt text-gold fs-4"></i>
                                        <p class="mb-0 mt-2 small text-muted">المصدر</p>
                                        <strong>{{ $product->origin_ar }}</strong>
                                    </div>
                                </div>
                            @endif
                            @if ($product->roast_level)
                                <div class="col-6">
                                    <div class="glass-card p-3 text-center h-100">
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
                            @if ($product->weight)
                                <div class="col-6">
                                    <div class="glass-card p-3 text-center h-100">
                                        <i class="bi bi-box-seam text-gold fs-4"></i>
                                        <p class="mb-0 mt-2 small text-muted">الوزن</p>
                                        <strong>{{ $product->weight }}</strong>
                                    </div>
                                </div>
                            @endif
                            <div class="col-6">
                                <div class="glass-card p-3 text-center h-100">
                                    <i class="bi bi-archive text-gold fs-4"></i>
                                    <p class="mb-0 mt-2 small text-muted">المخزون</p>
                                    <strong>
                                        @if ($product->stock > 0)
                                            <span class="text-success">متوفر ({{ $product->stock }})</span>
                                        @else
                                            <span class="text-danger">غير متوفر</span>
                                        @endif
                                    </strong>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <h5>الوصف</h5>
                            <p class="text-muted">{{ $product->description_ar }}</p>
                        </div>

                        <!-- Add to Cart -->
                        @if ($product->stock > 0)
                            <div class="d-flex gap-3 align-items-center mb-4">
                                <div class="quantity-input d-flex align-items-center glass-card">
                                    <button type="button" class="btn btn-minus px-3 py-2 border-0">
                                        <i class="bi bi-dash"></i>
                                    </button>
                                    <input type="number" id="quantity" value="1" min="1"
                                        max="{{ min($product->stock, 10) }}" class="form-control border-0 text-center"
                                        style="width: 60px;">
                                    <button type="button" class="btn btn-plus px-3 py-2 border-0">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </div>
                                <button class="btn btn-golden btn-lg flex-grow-1" id="addToCartBtn"
                                    data-product-id="{{ $product->id }}">
                                    <i class="bi bi-bag-plus me-2"></i>أضف للسلة
                                </button>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                هذا المنتج غير متوفر حالياً
                            </div>
                        @endif

                        <!-- Share -->
                        <div class="d-flex align-items-center gap-3">
                            <span class="text-muted">مشاركة:</span>
                            <a href="#" class="btn btn-icon btn-sm"><i class="bi bi-facebook"></i></a>
                            <a href="#" class="btn btn-icon btn-sm"><i class="bi bi-twitter-x"></i></a>
                            <a href="#" class="btn btn-icon btn-sm"><i class="bi bi-whatsapp"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Products -->
    @if ($relatedProducts->count())
        <section class="py-5 bg-white">
            <div class="container">
                <div class="section-title" data-aos="fade-up">
                    <h2>منتجات مشابهة</h2>
                </div>

                <div class="row g-4">
                    @foreach ($relatedProducts as $related)
                        <div class="col-6 col-md-4 col-lg-3">
                            @include('components.product-card', ['product' => $related])
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

        .quantity-input input::-webkit-inner-spin-button,
        .quantity-input input::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const quantityInput = document.getElementById('quantity');
            const btnMinus = document.querySelector('.btn-minus');
            const btnPlus = document.querySelector('.btn-plus');
            const addToCartBtn = document.getElementById('addToCartBtn');

            btnMinus?.addEventListener('click', () => {
                const current = parseInt(quantityInput.value) || 1;
                if (current > 1) quantityInput.value = current - 1;
            });

            btnPlus?.addEventListener('click', () => {
                const current = parseInt(quantityInput.value) || 1;
                const max = parseInt(quantityInput.max) || 10;
                if (current < max) quantityInput.value = current + 1;
            });

            addToCartBtn?.addEventListener('click', function() {
                const productId = this.dataset.productId;
                const quantity = parseInt(quantityInput.value) || 1;

                this.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>جاري الإضافة...';
                this.disabled = true;

                addToCart(productId, quantity);

                setTimeout(() => {
                    this.innerHTML = '<i class="bi bi-check-lg me-2"></i>تمت الإضافة';
                    setTimeout(() => {
                        this.innerHTML = '<i class="bi bi-bag-plus me-2"></i>أضف للسلة';
                        this.disabled = false;
                    }, 1500);
                }, 500);
            });
        });
    </script>
@endpush
