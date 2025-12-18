<!-- Product Card Component -->
@props(['product'])

@php
    $inWishlist = \App\Models\Wishlist::hasProduct($product->id);
@endphp

<div class="product-card glass-card" data-aos="fade-up">
    <!-- Image -->
    <div class="product-image">
        @if ($product->is_on_sale)
            <span class="product-badge sale">خصم {{ $product->discount_percentage }}%</span>
        @endif
        @if ($product->is_featured)
            <span class="product-badge featured">مميز</span>
        @endif

        <!-- Stock Status -->
        @if ($product->stock <= 0)
            <span class="product-badge out-of-stock">نفذ المخزون</span>
        @elseif($product->stock <= 5)
            <span class="product-badge low-stock">متبقي {{ $product->stock }} فقط</span>
        @endif

        <img src="{{ $product->image }}" alt="{{ $product->name_ar }}" loading="lazy">

        <!-- Quick Actions -->
        <div class="product-actions">
            <button class="btn btn-action wishlist-btn {{ $inWishlist ? 'active' : '' }}"
                onclick="toggleWishlist({{ $product->id }}, this)"
                title="{{ $inWishlist ? 'حذف من المفضلة' : 'أضف للمفضلة' }}">
                <i class="bi {{ $inWishlist ? 'bi-heart-fill text-danger' : 'bi-heart' }}"></i>
            </button>
            @if ($product->stock > 0)
                <button class="btn btn-action add-to-cart-btn" data-product-id="{{ $product->id }}" title="أضف للسلة">
                    <i class="bi bi-bag-plus"></i>
                </button>
            @endif
            <a href="{{ route('shop.show', $product) }}" class="btn btn-action" title="عرض التفاصيل">
                <i class="bi bi-eye"></i>
            </a>
        </div>
    </div>

    <!-- Content -->
    <div class="product-content">
        <span class="product-category">{{ $product->category?->name_ar }}</span>
        <h3 class="product-title">
            <a href="{{ route('shop.show', $product) }}">{{ $product->name_ar }}</a>
        </h3>

        <div class="product-meta">
            @if ($product->origin_ar)
                <span class="meta-item"><i class="bi bi-geo-alt"></i> {{ $product->origin_ar }}</span>
            @endif
            @if ($product->roast_level)
                <span class="meta-item"><i class="bi bi-fire"></i>
                    {{ $product->roast_level === 'light' ? 'تحميص فاتح' : ($product->roast_level === 'medium' ? 'تحميص متوسط' : 'تحميص داكن') }}</span>
            @endif
        </div>

        <div class="product-price">
            @if ($product->is_on_sale)
                <span class="price-old">{{ number_format($product->price) }} ج.م</span>
                <span class="price-current">{{ number_format($product->sale_price) }} ج.م</span>
            @else
                <span class="price-current">{{ number_format($product->price) }} ج.م</span>
            @endif
        </div>
    </div>
</div>
