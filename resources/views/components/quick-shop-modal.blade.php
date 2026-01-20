<!-- Quick Shop Modal Component -->
<div id="quickShopModal" class="quick-shop-modal" style="display: none;" role="dialog" aria-modal="true" aria-hidden="true"
    aria-labelledby="quickShopModalTitle">
    <!-- Static accessible title for screen readers -->
    <h2 id="quickShopModalTitle" class="visually-hidden">تسوق سريع</h2>
    <div class="modal-overlay" onclick="closeQuickShopModal()"></div>
    <div class="modal-container">
        <button class="modal-close" onclick="closeQuickShopModal()">
            <i class="bi bi-x-lg"></i>
        </button>

        <div class="modal-content" id="quickShopContent">
            <!-- Loading State -->
            <div class="loading-state" id="modalLoadingState">
                <div class="spinner-border text-golden" role="status">
                    <span class="visually-hidden">جاري التحميل...</span>
                </div>
                <p class="mt-3 text-muted">جاري تحميل البيانات...</p>
            </div>

            <!-- Product Content (loaded dynamically) -->
            <div id="modalProductContent" style="display: none;"></div>
        </div>
    </div>
</div>

<style>
    .quick-shop-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: fadeIn 0.15s ease;
    }

    .modal-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(8px);
    }

    .modal-container {
        position: relative;
        background: linear-gradient(135deg, #1a1410 0%, #2c1810 100%);
        border-radius: 24px;
        max-width: 600px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(201, 162, 39, 0.2);
        animation: slideUp 0.15s cubic-bezier(0.4, 0, 0.2, 1);
        scrollbar-width: thin;
        scrollbar-color: var(--gold) #2c1810;
    }

    .modal-container::-webkit-scrollbar {
        width: 8px;
    }

    .modal-container::-webkit-scrollbar-track {
        background: #2c1810;
    }

    .modal-container::-webkit-scrollbar-thumb {
        background: var(--gold);
        border-radius: 4px;
    }

    .modal-close {
        position: absolute;
        top: 20px;
        left: 20px;
        background: rgba(255, 255, 255, 0.1);
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 10;
        transition: all 0.3s ease;
        color: white;
    }

    .modal-close:hover {
        background: rgba(201, 162, 39, 0.2);
        transform: rotate(90deg);
        transition: all 0.2s ease;
    }

    .modal-content {
        padding: 30px;
    }

    .loading-state {
        text-align: center;
        padding: 60px 20px;
    }

    /* Product Content Styles */
    .modal-product-image {
        width: 100%;
        height: 250px;
        object-fit: cover;
        border-radius: 16px;
        margin-bottom: 20px;
    }

    .modal-product-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: white;
        margin-bottom: 10px;
    }

    .modal-product-category {
        display: inline-block;
        background: rgba(201, 162, 39, 0.2);
        color: var(--gold);
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 20px;
    }

    .modal-price-display {
        font-size: 2rem;
        font-weight: 700;
        color: var(--gold);
        margin-bottom: 30px;
    }

    .modal-option-group {
        margin-bottom: 25px;
    }

    .modal-option-label {
        display: block;
        font-size: 1rem;
        font-weight: 600;
        color: white;
        margin-bottom: 12px;
    }

    .modal-option-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .modal-option-pill {
        background: rgba(255, 255, 255, 0.05);
        border: 2px solid rgba(255, 255, 255, 0.1);
        color: rgba(255, 255, 255, 0.7);
        padding: 12px 20px;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
    }

    .modal-option-pill:hover {
        background: rgba(201, 162, 39, 0.1);
        border-color: var(--gold);
        color: white;
    }

    .modal-option-pill.active {
        background: var(--gradient-gold);
        border-color: var(--gold);
        color: var(--espresso);
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(201, 162, 39, 0.4);
    }

    .modal-option-price {
        font-size: 0.8rem;
        font-weight: 600;
    }

    .modal-quantity-selector {
        margin: 25px 0;
    }

    .modal-quantity-controls {
        display: flex;
        align-items: center;
        gap: 15px;
        justify-content: center;
    }

    .modal-quantity-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        border: 2px solid rgba(255, 255, 255, 0.2);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 1.1rem;
    }

    .modal-quantity-btn:hover {
        background: var(--gradient-gold);
        border-color: var(--gold);
        color: var(--espresso);
        transform: scale(1.1);
    }

    .modal-quantity-input {
        width: 60px;
        height: 40px;
        text-align: center;
        background: rgba(255, 255, 255, 0.05);
        border: 2px solid rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        color: white;
        font-size: 1.1rem;
        font-weight: 600;
        -moz-appearance: textfield;
        /* Firefox */
    }

    /* Hide spinner arrows in Chrome, Safari, Edge, Opera */
    .modal-quantity-input::-webkit-outer-spin-button,
    .modal-quantity-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }


    .modal-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-top: 30px;
    }

    .modal-btn {
        padding: 16px 24px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .modal-btn-add-cart {
        background: rgba(255, 255, 255, 0.1);
        border: 2px solid var(--gold);
        color: var(--gold);
    }

    .modal-btn-add-cart:hover {
        background: rgba(201, 162, 39, 0.2);
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(201, 162, 39, 0.3);
    }

    .modal-btn-buy-now {
        background: var(--gradient-gold);
        color: var(--espresso);
        box-shadow: 0 4px 15px rgba(201, 162, 39, 0.4);
    }

    .modal-btn-buy-now:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(201, 162, 39, 0.6);
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes slideUp {
        from {
            transform: translateY(50px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    @media (max-width: 576px) {
        .modal-container {
            width: 95%;
            max-height: 95vh;
        }

        .modal-content {
            padding: 20px;
        }

        .modal-actions {
            grid-template-columns: 1fr;
        }

        .modal-product-title {
            font-size: 1.4rem;
        }

        .modal-price-display {
            font-size: 1.5rem;
        }
    }
</style>

<script>
    let currentProductData = null;
    let selectedOptions = {};
    let currentQuantity = 1;
    let lastFocusedElement = null;
    let modalKeydownHandler = null;

    /**
     * SECURITY: HTML escape helper to prevent XSS
     */
    function escapeHtmlModal(text) {
        if (text === null || text === undefined) return '';
        const div = document.createElement('div');
        div.textContent = String(text);
        return div.innerHTML;
    }

    function getFocusableElements(modal) {
        return Array.from(modal.querySelectorAll(
            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
        )).filter(el => !el.hasAttribute('disabled') && !el.getAttribute('aria-hidden'));
    }

    function trapFocus(modal, e) {
        const focusable = getFocusableElements(modal);
        if (!focusable.length) return;
        const first = focusable[0];
        const last = focusable[focusable.length - 1];

        if (e.shiftKey && document.activeElement === first) {
            e.preventDefault();
            last.focus();
        } else if (!e.shiftKey && document.activeElement === last) {
            e.preventDefault();
            first.focus();
        }
    }

    // Open Quick Shop Modal
    function openQuickShopModal(productId, hasOptions) {
        const modal = document.getElementById('quickShopModal');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        modal.setAttribute('aria-hidden', 'false');
        lastFocusedElement = document.activeElement;

        // Reset state
        selectedOptions = {};
        currentQuantity = 1;

        // Show loading
        document.getElementById('modalLoadingState').style.display = 'block';
        document.getElementById('modalProductContent').style.display = 'none';

        // Focus management & ESC handling
        const closeBtn = modal.querySelector('.modal-close');
        if (closeBtn) closeBtn.focus();

        modalKeydownHandler = (e) => {
            if (e.key === 'Escape') {
                closeQuickShopModal();
                return;
            }
            if (e.key === 'Tab') {
                trapFocus(modal, e);
            }
        };
        document.addEventListener('keydown', modalKeydownHandler);

        // Fetch product details
        fetch(`/api/products/${productId}`)
            .then(response => response.json())
            .then(data => {
                currentProductData = data;
                renderProductContent(data);
            })
            .catch(error => {
                console.error('Error loading product:', error);
                closeQuickShopModal();
            });
    }

    // Close Modal
    function closeQuickShopModal() {
        const modal = document.getElementById('quickShopModal');
        modal.style.display = 'none';
        document.body.style.overflow = '';
        modal.setAttribute('aria-hidden', 'true');
        if (modalKeydownHandler) {
            document.removeEventListener('keydown', modalKeydownHandler);
            modalKeydownHandler = null;
        }
        if (lastFocusedElement && typeof lastFocusedElement.focus === 'function') {
            lastFocusedElement.focus();
            lastFocusedElement = null;
        }
    }

    // Render Product Content in Modal
    function renderProductContent(product) {
        const content = document.getElementById('modalProductContent');

        // SECURITY: Escape all user-provided content
        let html = `
            <img src="${escapeHtmlModal(product.image)}" alt="${escapeHtmlModal(product.name)}" class="modal-product-image">
            <h2 class="modal-product-title" id="quickShopTitle">${escapeHtmlModal(product.name)}</h2>
            ${product.category ? `<span class="modal-product-category"><i class="bi bi-tag me-1"></i>${escapeHtmlModal(product.category.name)}</span>` : ''}
            <div class="modal-price-display" id="modalCurrentPrice">${formatPrice(product.current_price)} ج.م</div>
        `;

        // Add options if they exist
        if (product.options && product.options.length > 0) {
            const optionsByType = groupOptionsByType(product.options);

            // Weight Options
            if (optionsByType.weight) {
                html += renderOptionGroup('weight', 'الوزن', optionsByType.weight, true);
            }

            // Roast Options
            if (optionsByType.roast) {
                html += renderOptionGroup('roast', 'التحميص', optionsByType.roast, false);
            }

            // Additive Options
            if (optionsByType.additive) {
                html += renderOptionGroup('additive', 'الإضافات', optionsByType.additive, false);
            }
        }

        // Quantity Selector
        html += `
            <div class="modal-quantity-selector">
                <label class="modal-option-label">الكمية</label>
                <div class="modal-quantity-controls">
                    <button class="modal-quantity-btn" onclick="changeModalQuantity(-1)">
                        <i class="bi bi-dash"></i>
                    </button>
                    <input type="number" class="modal-quantity-input" id="modalQuantity" value="1" min="1" max="10" readonly>
                    <button class="modal-quantity-btn" onclick="changeModalQuantity(1)">
                        <i class="bi bi-plus"></i>
                    </button>
                </div>
            </div>
        `;

        // Action Buttons
        html += `
            <div class="modal-actions">
                <button class="modal-btn modal-btn-add-cart" onclick="addToCartFromModal()">
                    <i class="bi bi-cart-plus"></i>
                    أضف للسلة
                </button>
                <button class="modal-btn modal-btn-buy-now" onclick="buyNowFromModal()">
                    <i class="bi bi-lightning-fill"></i>
                    اشتر الآن
                </button>
            </div>
        `;

        content.innerHTML = html;

        // Hide loading, show content
        document.getElementById('modalLoadingState').style.display = 'none';
        content.style.display = 'block';

        // Auto-select first option of each type
        if (product.options && product.options.length > 0) {
            const optionsByType = groupOptionsByType(product.options);
            Object.keys(optionsByType).forEach(type => {
                if (optionsByType[type] && optionsByType[type].length > 0) {
                    selectModalOption(type, optionsByType[type][0].id);
                }
            });
        }
    }

    // Render Option Group
    function renderOptionGroup(type, label, options, isWeight) {
        let html = `
            <div class="modal-option-group">
                <label class="modal-option-label">${label}</label>
                <div class="modal-option-pills">
        `;

        options.forEach(option => {
            const priceText = isWeight ?
                formatPrice(option.price_modifier) + ' ج.م' :
                (option.price_modifier >= 0 ? `+${formatPrice(option.price_modifier)}` : formatPrice(option
                    .price_modifier)) + ' ج.م';

            // SECURITY: Escape option values
            html += `
                <button class="modal-option-pill" 
                    data-type="${escapeHtmlModal(type)}" 
                    data-value-id="${parseInt(option.id)}"
                    onclick="selectModalOption('${escapeHtmlModal(type)}', ${parseInt(option.id)})">
                    <span>${escapeHtmlModal(option.value)}</span>
                    ${option.price_modifier != 0 || isWeight ? `<span class="modal-option-price">${escapeHtmlModal(priceText)}</span>` : ''}
                </button>
            `;
        });

        html += `
                </div>
            </div>
        `;

        return html;
    }

    // Group Options by Type
    function groupOptionsByType(options) {
        const grouped = {};
        options.forEach(option => {
            if (!grouped[option.type]) {
                grouped[option.type] = [];
            }
            grouped[option.type].push(option);
        });
        return grouped;
    }

    // Select Option
    function selectModalOption(type, valueId) {
        // Update selected options
        selectedOptions[type] = valueId;

        // Update UI
        document.querySelectorAll(`.modal-option-pill[data-type="${type}"]`).forEach(pill => {
            pill.classList.remove('active');
        });
        document.querySelector(`.modal-option-pill[data-type="${type}"][data-value-id="${valueId}"]`)?.classList.add(
            'active');

        // Update price
        updateModalPrice();
    }

    // Update Price Display
    function updateModalPrice() {
        if (!currentProductData) return;

        const optionValueIds = Object.values(selectedOptions);
        const basePrice = currentProductData.current_price;

        // Calculate price based on selected options
        let calculatedPrice = basePrice;

        // For weight options, the price_modifier is the absolute price
        if (selectedOptions.weight) {
            const weightOption = currentProductData.options.find(opt => opt.id === selectedOptions.weight);
            if (weightOption) {
                calculatedPrice = weightOption.price_modifier;
            }
        }

        // Add roast and additive modifiers
        ['roast', 'additive'].forEach(type => {
            if (selectedOptions[type]) {
                const option = currentProductData.options.find(opt => opt.id === selectedOptions[type]);
                if (option && option.price_modifier) {
                    calculatedPrice += parseFloat(option.price_modifier);
                }
            }
        });

        document.getElementById('modalCurrentPrice').textContent = formatPrice(calculatedPrice) + ' ج.م';
    }

    // Change Quantity
    function changeModalQuantity(delta) {
        const input = document.getElementById('modalQuantity');
        let newValue = currentQuantity + delta;
        if (newValue >= 1 && newValue <= 10) {
            currentQuantity = newValue;
            input.value = currentQuantity;
        }
    }

    // Add to Cart from Modal - OPTIMISTIC UI
    function addToCartFromModal() {
        const productId = currentProductData.id;
        const quantity = currentQuantity;
        const options = selectedOptions;

        // INSTANT: إغلاق فوري + تنبيه فوري
        closeQuickShopModal();
        if (window.Toast) window.Toast.cart('تمت الإضافة! 🎉', 'تمت إضافة المنتج للسلة');
        else showToast('تم إضافة المنتج للسلة بنجاح!', 'success');
        if (window.createConfetti) window.createConfetti();

        // تحديث Badge فوري
        const badge = document.getElementById('cartBadge');
        const oldCount = badge ? parseInt(badge.textContent) || 0 : 0;
        if (badge) {
            badge.textContent = oldCount + quantity;
            badge.classList.add('bounce');
            setTimeout(() => badge.classList.remove('bounce'), 500);
        }

        // Server request في الخلفية
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
                if (data.success) {
                    if (badge) badge.textContent = data.cartCount;
                    if (window.updateCartCount) window.updateCartCount();
                } else {
                    // ROLLBACK
                    if (badge) badge.textContent = oldCount;
                    if (window.Toast) window.Toast.error('خطأ', data.message || 'حدث خطأ');
                    else showToast(data.message || 'حدث خطأ', 'error');
                }
            })
            .catch(error => {
                // ROLLBACK
                if (badge) badge.textContent = oldCount;
                if (window.Toast) window.Toast.error('خطأ', 'حدث خطأ في الاتصال');
                else showToast('حدث خطأ أثناء الإضافة للسلة', 'error');
            });
    }

    // Buy Now from Modal
    function buyNowFromModal() {
        // First add to cart, then redirect to checkout
        const productId = currentProductData.id;
        const quantity = currentQuantity;
        const options = selectedOptions;

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
                if (data.success) {
                    // Redirect to checkout
                    window.location.href = '/checkout';
                } else {
                    showToast(data.message || 'حدث خطأ', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('حدث خطأ', 'error');
            });
    }

    // Format Price Helper
    function formatPrice(price) {
        return new Intl.NumberFormat('ar-EG').format(price);
    }

    // NOTE: ESC key handler moved to openQuickShopModal to avoid duplicates
</script>
