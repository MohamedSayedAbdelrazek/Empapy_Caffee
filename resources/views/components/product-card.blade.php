<!-- Enhanced Product Card Component -->
@props(['product'])

@php
    $inWishlist = \App\Models\Wishlist::hasProduct($product->id);
@endphp

<div class="product-card glass-card tilt-card" data-aos="fade-up" data-product-id="{{ $product->id }}"
    onclick="window.location.href='{{ route('shop.show', $product) }}'" style="cursor: pointer;">
    <!-- Image -->
    <div class="product-image" onclick="event.stopPropagation();">
        <!-- Badges -->
        @if ($product->is_on_sale)
            <span class="product-badge sale animate-pulse">
                <i class="bi bi-lightning-fill me-1"></i>
                خصم {{ $product->discount_percentage }}%
            </span>
        @endif
        @if ($product->is_featured)
            <span class="product-badge featured">
                <i class="bi bi-star-fill me-1"></i>
                مميز
            </span>
        @endif

        <!-- Product Image with Skeleton Loading -->
        <div class="product-image-wrapper">
            <div class="skeleton-placeholder skeleton" style="position: absolute; inset: 0; z-index: 1;"></div>
            <img src="{{ $product->image }}" alt="{{ $product->name_ar }}" loading="lazy"
                onload="this.previousElementSibling.style.display='none'; this.classList.add('loaded');">
        </div>

        <!-- Quick Actions -->
        <div class="product-actions">
            <button class="btn btn-action wishlist-btn ripple {{ $inWishlist ? 'active' : '' }}"
                onclick="event.stopPropagation(); toggleWishlist({{ $product->id }}, this)"
                title="{{ $inWishlist ? 'حذف من المفضلة' : 'أضف للمفضلة' }}"
                aria-label="{{ $inWishlist ? 'حذف من المفضلة' : 'أضف للمفضلة' }}">
                <i class="bi {{ $inWishlist ? 'bi-heart-fill' : 'bi-heart' }}"></i>
            </button>
            <a href="{{ route('shop.show', $product) }}" class="btn btn-action ripple" title="عرض التفاصيل"
                aria-label="عرض التفاصيل" onclick="event.stopPropagation();">
                <i class="bi bi-eye"></i>
            </a>
        </div>


    </div>

    <!-- Content -->
    <div class="product-content">
        <div class="product-category-wrapper">
            <span class="product-category">
                <i class="bi bi-tag me-1"></i>
                {{ $product->category?->name_ar }}
            </span>
        </div>

        <h3 class="product-title">
            <a href="{{ route('shop.show', $product) }}" class="animated-link">
                {{ $product->name_ar }}
            </a>
        </h3>

        <div class="product-meta">
            @if ($product->roast_level)
                <span class="meta-item">
                    <i class="bi bi-fire"></i>
                    @switch($product->roast_level)
                        @case('light')
                            فاتح
                        @break

                        @case('medium')
                            متوسط
                        @break

                        @case('dark')
                            داكن
                        @break
                    @endswitch
                </span>
            @endif
            @if ($product->weight)
                <span class="meta-item">
                    <i class="bi bi-box"></i>
                    {{ $product->weight }}
                </span>
            @endif
        </div>

        <div class="product-footer">
            <div class="product-price {{ $product->is_on_sale ? 'has-discount' : '' }}">
                @if ($product->has_options)
                    {{-- Products with options show price range --}}
                    @php
                        $minPrice = $product->min_price;
                        $maxPrice = $product->max_price;
                    @endphp
                    @if ($minPrice != $maxPrice)
                        <span class="price-range">{{ number_format($minPrice) }} – {{ number_format($maxPrice) }}
                            ج.م</span>
                    @else
                        <span class="price-current">{{ number_format($minPrice) }} ج.م</span>
                    @endif
                @elseif ($product->is_on_sale)
                    <span class="price-old">{{ number_format($product->price) }}</span>
                    <span class="price-current">{{ number_format($product->sale_price) }} ج.م</span>
                @else
                    <span class="price-current">{{ number_format($product->price) }} ج.م</span>
                @endif
            </div>

            <!-- Quick Shop Button -->
            <button class="btn-add-cart-main quick-shop-btn ripple" data-product-id="{{ $product->id }}"
                data-has-options="{{ $product->has_options ? 'true' : 'false' }}"
                onclick="event.stopPropagation(); openQuickShopModal({{ $product->id }}, {{ $product->has_options ? 'true' : 'false' }});"
                aria-label="تسوق سريعاً" title="تسوق سريعاً">
                <svg class="cart-icon-btn" xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 -960 960 960"
                    width="18px" fill="currentColor">
                    <path
                        d="M440-183v-274L200-596v274l240 139Zm80 0 240-139v-274L520-457v274Zm-80 92L160-252q-19-11-29.5-29T120-321v-318q0-22 10.5-40t29.5-29l280-161q19-11 40-11t40 11l280 161q19 11 29.5 29t10.5 40v318q0 22-10.5 40T800-252L520-91q-19 11-40 11t-40-11Zm200-528 77-44-237-137-78 45 238 136Zm-160 93 78-45-237-137-78 45 237 137Z" />
                </svg>
                <span class="btn-text">تسوق سريعاً</span>
            </button>
        </div>
    </div>
</div>

<style>
    /* Enhanced Product Card Styles */
    .product-card {
        position: relative;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
        border-radius: var(--radius-lg);
    }

    .product-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15), 0 0 0 1px rgba(201, 162, 39, 0.2);
    }

    /* Image wrapper with skeleton */
    .product-image-wrapper {
        position: relative;
        width: 100%;
        height: 100%;
    }

    .product-image-wrapper img {
        opacity: 0;
        transition: opacity 0.5s ease;
    }

    .product-image-wrapper img.loaded {
        opacity: 1;
    }

    .skeleton-placeholder {
        border-radius: 0;
    }

    /* Animate pulse for badges */
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.7;
        }
    }

    /* Quick Add Overlay */
    .quick-add-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 15px;
        background: linear-gradient(to top, rgba(44, 24, 16, 0.95), transparent);
        transform: translateY(100%);
        opacity: 0;
        transition: all 0.3s ease;
        display: flex;
        justify-content: center;
    }

    .product-card:hover .quick-add-overlay {
        transform: translateY(0);
        opacity: 1;
    }

    .quick-add-btn {
        background: var(--gradient-gold);
        border: none;
        padding: 12px 25px;
        border-radius: var(--radius-full);
        font-weight: 600;
        color: var(--espresso);
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }

    .quick-add-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 5px 20px rgba(201, 162, 39, 0.4);
    }

    .out-of-stock-msg {
        color: white;
        font-size: 0.9rem;
        opacity: 0.8;
    }

    /* Product Category */
    .product-category-wrapper {
        margin-bottom: 8px;
    }

    .product-category {
        font-size: 0.75rem;
        color: var(--gold);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        background: rgba(201, 162, 39, 0.1);
        padding: 4px 10px;
        border-radius: var(--radius-full);
    }

    /* Price Range for products with weight options */
    .price-range {
        font-size: 1rem;
        font-weight: 700;
        color: var(--gold);
    }

    /* Product Footer */
    .product-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid var(--gray-100);
        gap: 10px;
    }

    /* Main Add to Cart Button - Always Visible */
    .btn-add-cart-main {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 10px 16px;
        border-radius: var(--radius-full);
        background: var(--gradient-gold);
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        color: var(--espresso);
        font-size: 0.85rem;
        font-weight: 600;
        white-space: nowrap;
        box-shadow: 0 2px 10px rgba(201, 162, 39, 0.3);
    }

    .btn-add-cart-main:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(201, 162, 39, 0.5);
    }

    .btn-add-cart-main:active {
        transform: translateY(0);
    }

    .btn-add-cart-main .cart-icon-btn {
        width: 18px;
        height: 18px;
        flex-shrink: 0;
    }

    .btn-add-cart-main .btn-text {
        display: inline;
    }

    .btn-add-cart-main.loading {
        pointer-events: none;
        opacity: 0.7;
    }

    .btn-add-cart-main.success {
        background: #22c55e !important;
        color: white !important;
    }

    /* Out of Stock Label */
    .out-of-stock-label {
        display: flex;
        align-items: center;
        gap: 5px;
        padding: 8px 14px;
        border-radius: var(--radius-full);
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        font-size: 0.8rem;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .btn-add-cart-main .btn-text {
            display: none;
        }

        .btn-add-cart-main {
            padding: 10px;
            min-width: 40px;
            min-height: 40px;
        }

        .product-actions {
            opacity: 1 !important;
            transform: translateX(-50%) translateY(0) !important;
        }

        .quick-add-overlay {
            display: none;
        }
    }

    @media (max-width: 480px) {
        .product-footer {
            flex-direction: column;
            align-items: stretch;
            gap: 12px;
        }

        .btn-add-cart-main {
            width: 100%;
            justify-content: center;
        }

        .btn-add-cart-main .btn-text {
            display: inline;
        }
    }

    /* Low stock badge */
    .product-badge.low-stock {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        top: auto;
        bottom: 15px;
        right: 15px;
    }

    /* Out of stock badge */
    .product-badge.out-of-stock {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        top: 50px;
    }

    /* Wishlist active state */
    .wishlist-btn.active {
        background: #ff6b9d;
        color: white;
    }

    .wishlist-btn.active:hover {
        background: #ff4785;
    }

    /* Product actions hover effects */
    .btn-action {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .btn-action:hover {
        transform: scale(1.15) translateY(-2px);
    }

    /* Add loading state to cart buttons */
    .add-to-cart-btn.loading {
        pointer-events: none;
    }

    .add-to-cart-btn.loading i {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    .add-to-cart-btn.success {
        background: #22c55e !important;
        color: white !important;
    }

    .add-to-cart-btn.success i::before {
        content: "\F26B";
    }
</style>

<script>
    // Quick add to cart function
    function addToCartQuick(productId, button) {
        if (button.classList.contains('loading')) return;

        button.classList.add('loading');
        button.innerHTML = '<span class="loading-dots"><span></span><span></span><span></span></span>';

        fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: 1
                })
            })
            .then(response => response.json())
            .then(data => {
                button.classList.remove('loading');

                if (data.success) {
                    button.innerHTML = '<i class="bi bi-check-lg me-2"></i>تمت الإضافة!';
                    button.style.background = '#22c55e';
                    button.style.color = 'white';

                    // Show toast
                    if (window.Toast) {
                        window.Toast.cart('تمت الإضافة! 🎉', 'تمت إضافة المنتج إلى سلة التسوق');
                    }

                    // Confetti
                    if (window.createConfetti) {
                        window.createConfetti();
                    }

                    // Update cart count
                    if (typeof updateCartCount === 'function') {
                        updateCartCount();
                    }

                    // Reset button
                    setTimeout(() => {
                        button.innerHTML =
                            '<svg class="cart-icon-add me-2" xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 -960 960 960" width="18px" fill="currentColor"><path d="M280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM246-720l96 200h280l110-200H246Zm-38-80h590q23 0 35 20.5t1 41.5L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68-39.5t-2-78.5l54-98-144-304H40v-80h130l38 80Zm134 280h280-280Z"/></svg>إضافة سريعة';
                        button.style.background = '';
                        button.style.color = '';
                    }, 2000);
                } else {
                    button.innerHTML =
                        '<svg class="cart-icon-add me-2" xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 -960 960 960" width="18px" fill="currentColor"><path d="M280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM246-720l96 200h280l110-200H246Zm-38-80h590q23 0 35 20.5t1 41.5L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68-39.5t-2-78.5l54-98-144-304H40v-80h130l38 80Zm134 280h280-280Z"/></svg>إضافة سريعة';
                    if (window.Toast) {
                        window.Toast.error('خطأ', data.message || 'حدث خطأ');
                    }
                }
            })
            .catch(() => {
                button.classList.remove('loading');
                button.innerHTML =
                    '<svg class="cart-icon-add me-2" xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 -960 960 960" width="18px" fill="currentColor"><path d="M280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM246-720l96 200h280l110-200H246Zm-38-80h590q23 0 35 20.5t1 41.5L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68-39.5t-2-78.5l54-98-144-304H40v-80h130l38 80Zm134 280h280-280Z"/></svg>إضافة سريعة';
                if (window.Toast) {
                    window.Toast.error('خطأ', 'حدث خطأ في الاتصال');
                }
            });
    }
</script>
