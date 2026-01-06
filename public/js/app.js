/**
 * Empapy Caffe - Main JavaScript
 * Premium Arabic Coffee E-commerce
 */

// Cart data cache to reduce API calls
let cartCache = {
    data: null,
    timestamp: 0,
    ttl: 5000 // 5 seconds cache
};

document.addEventListener('DOMContentLoaded', function () {
    // Initialize all components
    initNavbar();
    initCart();
    initProductCards();
    initFloatingBeans();
    initQuantityInputs(); // Fixed: was missing
    initSearch();
});

/**
 * Navbar Scroll Effect
 */
function initNavbar() {
    const navbar = document.getElementById('mainNavbar');
    if (!navbar) return;

    function updateNavbar() {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    }

    window.addEventListener('scroll', updateNavbar);
    updateNavbar();
}

/**
 * Cart Functionality
 */
function initCart() {
    const cartToggle = document.getElementById('cartToggle');
    const cartDrawer = document.getElementById('cartDrawer');
    const cartClose = document.getElementById('cartClose');
    const cartOverlay = document.querySelector('.cart-drawer-overlay');

    // Load cart count on page load
    updateCartBadge();

    // Toggle cart drawer
    if (cartToggle && cartDrawer) {
        cartToggle.addEventListener('click', function () {
            cartDrawer.classList.add('open');
            document.body.style.overflow = 'hidden';
            loadCartItems(); // Load items when opening
        });
    }

    // Close cart
    function closeCart() {
        if (cartDrawer) {
            cartDrawer.classList.remove('open');
            document.body.style.overflow = '';
        }
    }

    if (cartClose) cartClose.addEventListener('click', closeCart);
    if (cartOverlay) cartOverlay.addEventListener('click', closeCart);

    // Close on escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeCart();
    });
}

/**
 * Load cart items into drawer
 */
function loadCartItems() {
    const cartBody = document.getElementById('cartDrawerBody');
    const cartFooter = document.getElementById('cartDrawerFooter');
    const totalAmount = document.getElementById('cartTotalAmount');

    if (!cartBody) return;

    fetch('/cart/data', {
        headers: {
            'Accept': 'application/json',
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.items && data.items.length > 0) {
                // Build cart items HTML
                let html = '';
                data.items.forEach(item => {
                    html += `
                    <div class="cart-item d-flex gap-3 mb-3 pb-3 border-bottom border-secondary">
                        <img src="${item.image}" alt="${item.name_ar}" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                        <div class="flex-grow-1">
                            <h6 class="mb-1 small text-white">${item.name_ar}</h6>
                            ${item.options && item.options.length > 0
                            ? `<div class="mb-1">
                                        ${item.options.map(opt => `<span class="badge bg-secondary" style="font-size: 0.65rem;">${opt.label}: ${opt.value}</span>`).join(' ')}
                                    </div>`
                            : ''
                        }
                            <small class="text-white-50">الكمية: ${item.quantity}</small>
                            <div class="text-warning fw-bold">${item.subtotal.toLocaleString()} ج.م</div>
                        </div>
                        <button class="btn btn-sm btn-outline-danger" onclick="removeFromCartDrawer('${item.key}')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                `;
                });
                cartBody.innerHTML = html;

                // Show footer and update total
                if (cartFooter) cartFooter.style.display = 'block';
                if (totalAmount) totalAmount.textContent = data.cartTotal.toLocaleString() + ' ج.م';
            } else {
                // Show empty cart message
                cartBody.innerHTML = `
                <div class="cart-empty text-center py-5">
                    <i class="bi bi-bag-x display-1 text-muted"></i>
                    <p class="text-muted mt-3">سلتك فارغة</p>
                    <a href="/shop" class="btn btn-golden mt-3">تصفح المنتجات</a>
                </div>
            `;
                if (cartFooter) cartFooter.style.display = 'none';
            }
        })
        .catch(error => console.log('Error loading cart'));
}

/**
 * Remove item from cart drawer - OPTIMISTIC UI
 */
function removeFromCartDrawer(key) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    const cartItems = document.querySelectorAll('.cart-item');
    let itemToRemove = null;
    cartItems.forEach(item => { if (item.querySelector(`button[onclick*="${key}"]`)) itemToRemove = item; });
    if (!itemToRemove) return;

    // INSTANT: Fade out immediately
    itemToRemove.style.transition = 'opacity 0.2s, transform 0.2s';
    itemToRemove.style.opacity = '0';
    itemToRemove.style.transform = 'translateX(-20px)';
    if (window.Toast) window.Toast.success('تم الحذف', 'تم حذف المنتج من السلة');

    const badge = document.getElementById('cartBadge');
    const oldCount = badge ? parseInt(badge.textContent) || 0 : 0;
    if (badge) { badge.textContent = Math.max(0, oldCount - 1); badge.classList.add('bounce'); setTimeout(() => badge.classList.remove('bounce'), 500); }

    setTimeout(() => {
        const oldHTML = itemToRemove.outerHTML;
        const parent = itemToRemove.parentElement;
        itemToRemove.remove();

        // Background server request
        fetch('/cart/remove', { method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }, body: JSON.stringify({ key }) })
            .then(r => r.json()).then(d => { if (d.success) { loadCartItems(); updateCartBadge(true); } else { if (parent) parent.insertAdjacentHTML('beforeend', oldHTML); if (badge) badge.textContent = oldCount; } })
            .catch(() => { if (parent) parent.insertAdjacentHTML('beforeend', oldHTML); if (badge) badge.textContent = oldCount; });
    }, 200);
}

/**
 * Update cart badge - optimized with cache
 */
function updateCartBadge(forceRefresh = false) {
    const now = Date.now();

    // Use cache if valid and not forcing refresh
    if (!forceRefresh && cartCache.data && (now - cartCache.timestamp < cartCache.ttl)) {
        updateBadgeUI(cartCache.data.cartCount);
        return;
    }

    fetch('/cart/data', {
        headers: {
            'Accept': 'application/json',
        }
    })
        .then(response => response.json())
        .then(data => {
            // Update cache
            cartCache.data = data;
            cartCache.timestamp = now;
            updateBadgeUI(data.cartCount);
        })
        .catch(error => console.log('Cart not loaded'));
}

function updateBadgeUI(count) {
    const badge = document.getElementById('cartBadge');
    if (badge) {
        badge.textContent = count || 0;
        badge.classList.add('bounce');
        setTimeout(() => badge.classList.remove('bounce'), 500);
    }
}

/**
 * Add to cart function - OPTIMISTIC UI
 */
function addToCart(productId, quantity = 1) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    const badge = document.getElementById('cartBadge');
    const oldCount = badge ? parseInt(badge.textContent) || 0 : 0;

    //INSTANT: Update UI immediately
    if (window.Toast) window.Toast.cart('تمت الإضافة! 🎉', 'تمت إضافة المنتج للسلة');
    if (window.createConfetti) window.createConfetti();
    if (badge) { badge.textContent = oldCount + quantity; badge.classList.add('bounce'); setTimeout(() => badge.classList.remove('bounce'), 500); }

    // Background server request
    fetch('/cart/add', { method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }, body: JSON.stringify({ product_id: productId, quantity }) })
        .then(r => r.json())
        .then(d => {
            if (d.success) {
                // Update with real count from server
                if (badge) badge.textContent = d.cartCount;
                updateCartBadge(true);
            } else {
                // ROLLBACK on error
                if (badge) badge.textContent = oldCount;
                if (window.Toast) window.Toast.error('خطأ', d.message || 'حدث خطأ');
            }
        })
        .catch(() => {
            // ROLLBACK on network error
            if (badge) badge.textContent = oldCount;
            if (window.Toast) window.Toast.error('خطأ', 'حدث خطأ في الاتصال');
        });
}

// Global updateCartCount function
window.updateCartCount = function () {
    updateCartBadge(true);
};

/**
 * Initialize product cards
 */
function initProductCards() {
    document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const productId = this.dataset.productId;

            // Add loading state
            const originalContent = this.innerHTML;
            this.innerHTML = '<i class="bi bi-hourglass-split"></i>';
            this.disabled = true;

            addToCart(productId);

            // Reset button after animation
            setTimeout(() => {
                this.innerHTML = '<i class="bi bi-check-lg"></i>';
                setTimeout(() => {
                    this.innerHTML = originalContent;
                    this.disabled = false;
                }, 1000);
            }, 500);
        });
    });
}

/**
 * Show toast notification - Uses new Toast system if available
 */
function showToast(message, type = 'success') {
    // Use new Toast system if available
    if (window.Toast) {
        switch (type) {
            case 'success':
                window.Toast.success('نجاح', message);
                break;
            case 'error':
                window.Toast.error('خطأ', message);
                break;
            case 'warning':
                window.Toast.warning('تنبيه', message);
                break;
            case 'cart':
                window.Toast.cart('السلة', message);
                break;
            default:
                window.Toast.info('معلومة', message);
        }
        return;
    }

    // Fallback to old toast system
    document.querySelectorAll('.toast-custom').forEach(t => t.remove());

    const toast = document.createElement('div');
    toast.className = `toast-custom ${type}`;
    toast.innerHTML = `
        <i class="bi bi-${type === 'success' ? 'check-circle-fill' : 'x-circle-fill'}"></i>
        <span>${message}</span>
    `;

    // Create container if not exists
    let container = document.querySelector('.toast-container-legacy');
    if (!container) {
        container = document.createElement('div');
        container.className = 'toast-container-legacy';
        container.style.cssText = 'position: fixed; top: 100px; left: 20px; z-index: 9999;';
        document.body.appendChild(container);
    }

    container.appendChild(toast);

    // Remove after 3 seconds
    setTimeout(() => {
        toast.style.animation = 'slideIn 0.3s ease reverse';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

/**
 * Floating coffee beans animation for hero
 */
function initFloatingBeans() {
    const container = document.querySelector('.floating-beans');
    if (!container) return;

    const beanIcons = ['☕', '🫘', '☕'];

    function createBean() {
        const bean = document.createElement('span');
        bean.className = 'bean';
        bean.textContent = beanIcons[Math.floor(Math.random() * beanIcons.length)];
        bean.style.left = `${Math.random() * 100}%`;
        bean.style.animationDuration = `${15 + Math.random() * 10}s`;
        bean.style.animationDelay = `${Math.random() * 5}s`;

        container.appendChild(bean);

        // Remove after animation
        setTimeout(() => bean.remove(), 25000);
    }

    // Create initial beans
    for (let i = 0; i < 10; i++) {
        setTimeout(createBean, i * 500);
    }

    // Continue creating beans
    setInterval(createBean, 2000);
}

/**
 * Quantity Input Handler (for product detail page)
 */
function initQuantityInputs() {
    document.querySelectorAll('.quantity-input').forEach(wrapper => {
        const input = wrapper.querySelector('input');
        const btnMinus = wrapper.querySelector('.btn-minus');
        const btnPlus = wrapper.querySelector('.btn-plus');

        if (btnMinus) {
            btnMinus.addEventListener('click', () => {
                const current = parseInt(input.value) || 1;
                if (current > 1) input.value = current - 1;
            });
        }

        if (btnPlus) {
            btnPlus.addEventListener('click', () => {
                const current = parseInt(input.value) || 1;
                const max = parseInt(input.max) || 10;
                if (current < max) input.value = current + 1;
            });
        }
    });
}

/**
 * Initialize Search with debounce
 */
function initSearch() {
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');

    if (!searchInput) return;

    let debounceTimer;

    searchInput.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        const query = this.value.trim();

        if (query.length < 2) {
            if (searchResults) searchResults.innerHTML = '';
            return;
        }

        debounceTimer = setTimeout(() => {
            performSearch(query);
        }, 300);
    });

    // Close on click outside
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.search-wrapper') && searchResults) {
            searchResults.innerHTML = '';
        }
    });
}

/**
 * Perform AJAX search
 */
function performSearch(query) {
    const searchResults = document.getElementById('searchResults');
    if (!searchResults) return;

    fetch(`/shop?search=${encodeURIComponent(query)}`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.products && data.products.length > 0) {
                let html = '<div class="search-dropdown">';
                data.products.forEach(product => {
                    html += `
                    <a href="/shop/${product.slug}" class="search-item">
                        <img src="${product.image}" alt="${product.name_ar}">
                        <div>
                            <span class="name">${product.name_ar}</span>
                            <span class="price">${product.price} ج.م</span>
                        </div>
                    </a>
                `;
                });
                html += '</div>';
                searchResults.innerHTML = html;
            } else {
                searchResults.innerHTML = '<div class="search-dropdown"><p class="p-3 text-muted">لا توجد نتائج</p></div>';
            }
        })
        .catch(() => {
            searchResults.innerHTML = '';
        });
}

/**
 * Wishlist Functions
 */
function toggleWishlist(productId, button) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    fetch('/wishlist/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ product_id: productId })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const icon = button.querySelector('i');
                if (data.added) {
                    icon.classList.remove('bi-heart');
                    icon.classList.add('bi-heart-fill', 'text-danger');
                    showToast('تمت الإضافة للمفضلة', 'success');
                } else {
                    icon.classList.remove('bi-heart-fill', 'text-danger');
                    icon.classList.add('bi-heart');
                    showToast('تم الحذف من المفضلة', 'success');
                }
                updateWishlistCount();
            }
        })
        .catch(() => showToast('حدث خطأ', 'error'));
}

function updateWishlistCount() {
    fetch('/wishlist/count', {
        headers: { 'Accept': 'application/json' }
    })
        .then(response => response.json())
        .then(data => {
            const badge = document.getElementById('wishlistBadge');
            if (badge) {
                badge.textContent = data.count || 0;
                if (data.count > 0) {
                    badge.style.display = 'flex';
                } else {
                    badge.style.display = 'none';
                }
            }
        });
}

