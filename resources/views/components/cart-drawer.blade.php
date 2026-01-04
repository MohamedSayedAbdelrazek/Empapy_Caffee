<!-- Cart Drawer (Slide-in) -->
<div class="cart-drawer" id="cartDrawer">
    <div class="cart-drawer-overlay"></div>
    <div class="cart-drawer-content">
        <div class="cart-drawer-header">
            <h5><svg class="cart-icon me-2" xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="currentColor"><path d="M280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM246-720l96 200h280l110-200H246Zm-38-80h590q23 0 35 20.5t1 41.5L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68-39.5t-2-78.5l54-98-144-304H40v-80h130l38 80Zm134 280h280-280Z"/></svg>سلة التسوق</h5>
            <button class="btn-close btn-close-white" id="cartClose"></button>
        </div>

        <div class="cart-drawer-body" id="cartDrawerBody">
            <!-- Cart items will be loaded via AJAX -->
            <div class="cart-empty text-center py-5">
                <svg class="cart-icon-empty" xmlns="http://www.w3.org/2000/svg" height="80px" viewBox="0 -960 960 960" width="80px" fill="currentColor" style="opacity: 0.5;"><path d="M280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM246-720l96 200h280l110-200H246Zm-38-80h590q23 0 35 20.5t1 41.5L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68-39.5t-2-78.5l54-98-144-304H40v-80h130l38 80Zm134 280h280-280Z"/></svg>
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
