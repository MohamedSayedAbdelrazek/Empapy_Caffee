/**
 * Empapy Caffe - Product Card Scripts
 * Extracted from product-card.blade.php for better caching
 */

/**
 * Quick add to cart function
 * @param {number} productId - Product ID
 * @param {HTMLElement} button - Button element
 */
function addToCartQuick(productId, button) {
    if (button.classList.contains('loading')) return;

    button.classList.add('loading');
    button.innerHTML = '<span class="loading-dots"><span></span><span></span><span></span></span>';

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
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
                    resetQuickAddButton(button);
                }, 2000);
            } else {
                resetQuickAddButton(button);
                if (window.Toast) {
                    window.Toast.error('خطأ', data.message || 'حدث خطأ');
                }
            }
        })
        .catch(() => {
            button.classList.remove('loading');
            resetQuickAddButton(button);
            if (window.Toast) {
                window.Toast.error('خطأ', 'حدث خطأ في الاتصال');
            }
        });
}

/**
 * Reset quick add button to default state
 */
function resetQuickAddButton(button) {
    button.innerHTML = `
        <svg class="cart-icon-add me-2" xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 -960 960 960" width="18px" fill="currentColor">
            <path d="M280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM246-720l96 200h280l110-200H246Zm-38-80h590q23 0 35 20.5t1 41.5L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68-39.5t-2-78.5l54-98-144-304H40v-80h130l38 80Zm134 280h280-280Z"/>
        </svg>إضافة سريعة`;
    button.style.background = '';
    button.style.color = '';
}

/**
 * Toggle wishlist status
 * @param {number} productId - Product ID
 * @param {HTMLElement} button - Button element
 */
function toggleWishlist(productId, button) {
    const isActive = button.classList.contains('active');
    const icon = button.querySelector('i');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    // Optimistic UI update
    button.classList.toggle('active');
    if (icon) {
        icon.className = isActive ? 'bi bi-heart' : 'bi bi-heart-fill';
    }

    fetch('/wishlist/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ product_id: productId })
    })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                // Rollback on error
                button.classList.toggle('active');
                if (icon) {
                    icon.className = isActive ? 'bi bi-heart-fill' : 'bi bi-heart';
                }
            } else {
                // Show toast
                if (window.Toast) {
                    if (data.added) {
                        window.Toast.success('❤️ تمت الإضافة', 'تمت إضافة المنتج للمفضلة');
                    } else {
                        window.Toast.info('تم الحذف', 'تم حذف المنتج من المفضلة');
                    }
                }
            }
        })
        .catch(() => {
            // Rollback on error
            button.classList.toggle('active');
            if (icon) {
                icon.className = isActive ? 'bi bi-heart-fill' : 'bi bi-heart';
            }
        });
}
