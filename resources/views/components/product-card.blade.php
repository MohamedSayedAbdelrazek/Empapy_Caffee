<!-- Enhanced Product Card Component -->
@props(['product', 'wishlistIds' => []])

@php
    $inWishlist = in_array($product->id, $wishlistIds ?? []);
@endphp

<div class="product-card glass-card tilt-card" data-product-id="{{ $product->id }}"
    onclick="window.location.href='{{ route('shop.show', $product) }}'" style="cursor: pointer;">
    <!-- Image -->
    <div class="product-image">
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
            <img src="{{ $product->image }}" alt="{{ $product->name }}" loading="lazy"
                onload="this.previousElementSibling.style.display='none'; this.classList.add('loaded');">
        </div>

        <!-- Quick Actions -->



    </div>

    <!-- Content -->
    <div class="product-content">
        <div class="product-category-wrapper">
            <span class="product-category">
                <i class="bi bi-tag me-1"></i>
                {{ $product->category?->name }}
            </span>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-2">
            <h3 class="product-title m-0">
                <a href="{{ route('shop.show', $product) }}" class="animated-link">
                    {{ $product->name }}
                </a>
            </h3>
            <button class="wishlist-btn-inline {{ $inWishlist ? 'active' : '' }}"
                onclick="event.preventDefault(); event.stopPropagation(); toggleWishlist({{ $product->id }}, this)"
                title="{{ $inWishlist ? 'حذف من المفضلة' : 'أضف للمفضلة' }}"
                aria-label="{{ $inWishlist ? 'حذف من المفضلة' : 'أضف للمفضلة' }}"
                style="position: relative; z-index: 10;">
                <i class="bi {{ $inWishlist ? 'bi-heart-fill' : 'bi-heart' }}"></i>
            </button>
        </div>

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

{{-- 
    CSS and JS are now in external files:
    - /css/product-card.css
    - /js/product-card.js
    These are included in layouts/app.blade.php for better caching
--}}
