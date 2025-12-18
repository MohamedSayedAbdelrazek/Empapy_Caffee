<!-- Cart Drawer (Slide-in) -->
<div class="cart-drawer" id="cartDrawer">
    <div class="cart-drawer-overlay"></div>
    <div class="cart-drawer-content">
        <div class="cart-drawer-header">
            <h5><i class="bi bi-bag me-2"></i>سلة التسوق</h5>
            <button class="btn-close btn-close-white" id="cartClose"></button>
        </div>

        <div class="cart-drawer-body" id="cartDrawerBody">
            <!-- Cart items will be loaded via AJAX -->
            <div class="cart-empty text-center py-5">
                <i class="bi bi-bag-x display-1 text-muted"></i>
                <p class="text-muted mt-3">سلتك فارغة</p>
                <a href="{{ route('shop.index') }}" class="btn btn-golden mt-3">تصفح المنتجات</a>
            </div>
        </div>

        <div class="cart-drawer-footer" id="cartDrawerFooter" style="display: none;">
            <div class="cart-total d-flex justify-content-between mb-3">
                <span>المجموع:</span>
                <span class="cart-total-amount" id="cartTotalAmount">0 ج.م</span>
            </div>
            <a href="{{ route('cart.index') }}" class="btn btn-outline-light w-100 mb-2">
                عرض السلة
            </a>
            <a href="{{ route('checkout.index') }}" class="btn btn-golden w-100">
                إتمام الطلب
            </a>
        </div>
    </div>
</div>
