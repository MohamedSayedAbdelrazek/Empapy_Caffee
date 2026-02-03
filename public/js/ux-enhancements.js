/**
 * Empapy Caffe - Ultimate UX Enhancements
 * Premium skeleton loaders, toast notifications, image gallery & zoom
 * Advanced micro-interactions and visual feedback
 */

(function () {
    'use strict';

    // ========================================
    // 1. TOAST NOTIFICATION SYSTEM
    // ========================================

    class ToastManager {
        constructor() {
            this.container = null;
            this.toasts = [];
            this.init();
        }

        init() {
            // Create toast container if it doesn't exist
            if (!document.querySelector('.toast-container')) {
                this.container = document.createElement('div');
                this.container.className = 'toast-container';
                document.body.appendChild(this.container);
            } else {
                this.container = document.querySelector('.toast-container');
            }
        }

        /**
         * Show a toast notification
         * @param {Object} options - Toast options
         */
        show(options = {}) {
            const {
                type = 'info', // success, error, warning, info, cart
                title = '',
                message = '',
                duration = 5000,
                action = null, // { text: 'Button Text', onClick: () => {} }
                icon = null,
                persistent = false
            } = options;

            // Create toast element
            const toast = document.createElement('div');
            toast.className = `toast-notification ${type}`;

            // Default icons based on type
            const defaultIcons = {
                success: 'bi-check-lg',
                error: 'bi-x-lg',
                warning: 'bi-exclamation-triangle',
                info: 'bi-info-lg',
                cart: 'bi-bag-check'
            };

            const iconClass = icon || defaultIcons[type] || defaultIcons.info;

            toast.innerHTML = `
                <div class="toast-icon">
                    <i class="bi ${iconClass}"></i>
                </div>
                <div class="toast-content">
                    ${title ? `<div class="toast-title">${title}</div>` : ''}
                    ${message ? `<div class="toast-message">${message}</div>` : ''}
                    ${action ? `
                        <div class="toast-action">
                            <button class="toast-action-btn">${action.text}</button>
                        </div>
                    ` : ''}
                </div>
                <button class="toast-close">
                    <i class="bi bi-x"></i>
                </button>
                ${!persistent ? '<div class="toast-progress"></div>' : ''}
            `;

            // Add to container
            this.container.appendChild(toast);

            // Trigger show animation
            requestAnimationFrame(() => {
                toast.classList.add('show');
            });

            // Play sound effect (optional)
            this.playSound(type);

            // Setup close button
            const closeBtn = toast.querySelector('.toast-close');
            closeBtn.addEventListener('click', () => this.hide(toast));

            // Setup action button
            if (action && action.onClick) {
                const actionBtn = toast.querySelector('.toast-action-btn');
                actionBtn.addEventListener('click', () => {
                    action.onClick();
                    this.hide(toast);
                });
            }

            // Auto-hide after duration
            if (!persistent && duration > 0) {
                setTimeout(() => this.hide(toast), duration);
            }

            // Track toast
            this.toasts.push(toast);

            return toast;
        }

        hide(toast) {
            if (!toast || !toast.parentNode) return;

            toast.classList.remove('show');
            toast.classList.add('hiding');

            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
                this.toasts = this.toasts.filter(t => t !== toast);
            }, 500);
        }

        hideAll() {
            this.toasts.forEach(toast => this.hide(toast));
        }

        // Quick methods
        success(title, message, options = {}) {
            return this.show({ type: 'success', title, message, ...options });
        }

        error(title, message, options = {}) {
            return this.show({ type: 'error', title, message, ...options });
        }

        warning(title, message, options = {}) {
            return this.show({ type: 'warning', title, message, ...options });
        }

        info(title, message, options = {}) {
            return this.show({ type: 'info', title, message, ...options });
        }

        cart(title, message, options = {}) {
            const toast = this.show({
                type: 'cart',
                title,
                message: message + '<div style="font-size:0.75rem;opacity:0.8;margin-top:4px;">اضغط لعرض السلة ←</div>',
                duration: 3000,
                ...options
            });
            // Make cart toast clickable - navigates to cart page
            if (toast) {
                toast.style.cursor = 'pointer';
                toast.addEventListener('click', (e) => {
                    if (!e.target.closest('.toast-close')) {
                        window.location.href = '/cart';
                    }
                });
            }
            return toast;
        }

        playSound(type) {
            // Optional: Add subtle sound effects
            // Uncomment if you want sound notifications
            /*
            const sounds = {
                success: '/sounds/success.mp3',
                error: '/sounds/error.mp3',
                cart: '/sounds/cart.mp3'
            };
            if (sounds[type]) {
                const audio = new Audio(sounds[type]);
                audio.volume = 0.3;
                audio.play().catch(() => {});
            }
            */
        }
    }

    // ========================================
    // 2. SKELETON LOADER SYSTEM
    // ========================================

    class SkeletonLoader {
        constructor() {
            this.templates = {
                productCard: this.createProductCardSkeleton.bind(this),
                categoryCard: this.createCategoryCardSkeleton.bind(this),
                statCard: this.createStatCardSkeleton.bind(this),
                tableRow: this.createTableRowSkeleton.bind(this),
                text: this.createTextSkeleton.bind(this)
            };
        }

        createProductCardSkeleton() {
            const div = document.createElement('div');
            div.className = 'skeleton-product-card';
            div.innerHTML = `
                <div class="skeleton-product-image skeleton"></div>
                <div class="skeleton-product-content">
                    <div class="skeleton skeleton-text" style="width: 40%;"></div>
                    <div class="skeleton skeleton-text title"></div>
                    <div class="skeleton skeleton-text subtitle"></div>
                    <div class="skeleton skeleton-text price"></div>
                </div>
            `;
            return div;
        }

        createCategoryCardSkeleton() {
            const div = document.createElement('div');
            div.className = 'skeleton skeleton-category-card';
            return div;
        }

        createStatCardSkeleton() {
            const div = document.createElement('div');
            div.className = 'skeleton-stat-card';
            div.innerHTML = `
                <div class="skeleton skeleton-stat-icon"></div>
                <div class="skeleton-stat-content">
                    <div class="skeleton skeleton-stat-value"></div>
                    <div class="skeleton skeleton-stat-label"></div>
                </div>
            `;
            return div;
        }

        createTableRowSkeleton(columns = 5) {
            const div = document.createElement('div');
            div.className = 'skeleton-table-row';
            for (let i = 0; i < columns; i++) {
                const cell = document.createElement('div');
                cell.className = 'skeleton skeleton-table-cell';
                cell.style.flex = i === 0 ? '2' : '1';
                div.appendChild(cell);
            }
            return div;
        }

        createTextSkeleton(width = '100%', height = '14px') {
            const div = document.createElement('div');
            div.className = 'skeleton skeleton-text';
            div.style.width = width;
            div.style.height = height;
            return div;
        }

        /**
         * Show skeleton loaders in a container
         * @param {HTMLElement|string} container - Container element or selector
         * @param {string} type - Type of skeleton
         * @param {number} count - Number of skeletons to show
         */
        show(container, type = 'productCard', count = 4) {
            const el = typeof container === 'string'
                ? document.querySelector(container)
                : container;

            if (!el || !this.templates[type]) return;

            // Store original content
            el.dataset.originalContent = el.innerHTML;
            el.innerHTML = '';

            const wrapper = document.createElement('div');
            wrapper.className = 'skeleton-wrapper row g-4';

            for (let i = 0; i < count; i++) {
                const col = document.createElement('div');
                col.className = type === 'categoryCard' ? 'col-6 col-lg-3' : 'col-6 col-md-4 col-lg-3';
                col.appendChild(this.templates[type]());
                wrapper.appendChild(col);
            }

            el.appendChild(wrapper);
        }

        /**
         * Hide skeleton loaders and restore content
         * @param {HTMLElement|string} container - Container element or selector
         */
        hide(container) {
            const el = typeof container === 'string'
                ? document.querySelector(container)
                : container;

            if (!el) return;

            const wrapper = el.querySelector('.skeleton-wrapper');
            if (wrapper) {
                wrapper.remove();
            }

            // Restore original content if available
            if (el.dataset.originalContent) {
                // Don't restore - usually content is replaced with new data
                delete el.dataset.originalContent;
            }
        }
    }

    // ========================================
    // 3. IMAGE GALLERY & LIGHTBOX
    // ========================================

    class ImageGallery {
        constructor(container, options = {}) {
            this.container = typeof container === 'string'
                ? document.querySelector(container)
                : container;

            if (!this.container) return;

            this.options = {
                images: [],
                enableZoom: true,
                enableLightbox: true,
                autoplay: false,
                autoplayDelay: 5000,
                ...options
            };

            this.currentIndex = 0;
            this.lightbox = null;
            this.zoomLevel = 1;
            this.maxZoom = 3;
            this.minZoom = 1;

            this.init();
        }

        init() {
            this.createGallery();
            this.bindEvents();

            if (this.options.autoplay) {
                this.startAutoplay();
            }
        }

        createGallery() {
            const images = this.options.images;
            if (!images.length) return;

            this.container.innerHTML = `
                <div class="product-gallery">
                    <div class="product-gallery-main">
                        <img src="${images[0]}" alt="Product Image" id="gallery-main-image">
                        ${images.length > 1 ? `
                            <button class="gallery-nav prev" aria-label="Previous">
                                <i class="bi bi-chevron-right"></i>
                            </button>
                            <button class="gallery-nav next" aria-label="Next">
                                <i class="bi bi-chevron-left"></i>
                            </button>
                            <div class="gallery-counter">
                                <span id="gallery-current">1</span> / ${images.length}
                            </div>
                        ` : ''}
                    </div>
                    ${images.length > 1 ? `
                        <div class="gallery-thumbnails">
                            ${images.map((img, i) => `
                                <div class="gallery-thumb ${i === 0 ? 'active' : ''}" data-index="${i}">
                                    <img src="${img}" alt="Thumbnail ${i + 1}">
                                </div>
                            `).join('')}
                        </div>
                    ` : ''}
                </div>
            `;

            // Create lightbox if enabled
            if (this.options.enableLightbox) {
                this.createLightbox();
            }
        }

        createLightbox() {
            if (document.querySelector('.lightbox')) return;

            const images = this.options.images;

            this.lightbox = document.createElement('div');
            this.lightbox.className = 'lightbox';
            this.lightbox.innerHTML = `
                <button class="lightbox-close" aria-label="Close">
                    <i class="bi bi-x-lg"></i>
                </button>
                <div class="lightbox-zoom-controls">
                    <button class="zoom-btn zoom-in" aria-label="Zoom In">
                        <i class="bi bi-zoom-in"></i>
                    </button>
                    <button class="zoom-btn zoom-out" aria-label="Zoom Out">
                        <i class="bi bi-zoom-out"></i>
                    </button>
                    <button class="zoom-btn zoom-reset" aria-label="Reset Zoom">
                        <i class="bi bi-arrows-angle-contract"></i>
                    </button>
                </div>
                <div class="lightbox-content">
                    <div class="zoom-container">
                        <img src="${images[0]}" alt="Product Image" class="lightbox-image" id="lightbox-image">
                    </div>
                    ${images.length > 1 ? `
                        <button class="lightbox-nav prev" aria-label="Previous">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                        <button class="lightbox-nav next" aria-label="Next">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                    ` : ''}
                </div>
                ${images.length > 1 ? `
                    <div class="lightbox-thumbnails">
                        ${images.map((img, i) => `
                            <div class="lightbox-thumb ${i === 0 ? 'active' : ''}" data-index="${i}">
                                <img src="${img}" alt="Thumbnail ${i + 1}">
                            </div>
                        `).join('')}
                    </div>
                ` : ''}
            `;

            document.body.appendChild(this.lightbox);
        }

        bindEvents() {
            const gallery = this.container.querySelector('.product-gallery');
            if (!gallery) return;

            const mainImage = gallery.querySelector('.product-gallery-main');
            const thumbs = gallery.querySelectorAll('.gallery-thumb');
            const prevBtn = gallery.querySelector('.gallery-nav.prev');
            const nextBtn = gallery.querySelector('.gallery-nav.next');

            // Click on main image to open lightbox
            if (mainImage && this.options.enableLightbox) {
                mainImage.addEventListener('click', () => this.openLightbox());
            }

            // Thumbnail clicks
            thumbs.forEach(thumb => {
                thumb.addEventListener('click', () => {
                    this.goTo(parseInt(thumb.dataset.index));
                });
            });

            // Navigation buttons
            if (prevBtn) prevBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                this.prev();
            });
            if (nextBtn) nextBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                this.next();
            });

            // Lightbox events
            if (this.lightbox) {
                this.lightbox.querySelector('.lightbox-close').addEventListener('click', () => this.closeLightbox());
                this.lightbox.addEventListener('click', (e) => {
                    if (e.target === this.lightbox) this.closeLightbox();
                });

                const lightboxPrev = this.lightbox.querySelector('.lightbox-nav.prev');
                const lightboxNext = this.lightbox.querySelector('.lightbox-nav.next');
                if (lightboxPrev) lightboxPrev.addEventListener('click', () => this.prev());
                if (lightboxNext) lightboxNext.addEventListener('click', () => this.next());

                this.lightbox.querySelectorAll('.lightbox-thumb').forEach(thumb => {
                    thumb.addEventListener('click', () => {
                        this.goTo(parseInt(thumb.dataset.index));
                    });
                });

                // Zoom controls
                this.lightbox.querySelector('.zoom-in').addEventListener('click', () => this.zoomIn());
                this.lightbox.querySelector('.zoom-out').addEventListener('click', () => this.zoomOut());
                this.lightbox.querySelector('.zoom-reset').addEventListener('click', () => this.zoomReset());

                // Keyboard navigation
                document.addEventListener('keydown', (e) => {
                    if (!this.lightbox.classList.contains('active')) return;

                    switch (e.key) {
                        case 'Escape': this.closeLightbox(); break;
                        case 'ArrowRight': this.prev(); break;
                        case 'ArrowLeft': this.next(); break;
                        case '+': this.zoomIn(); break;
                        case '-': this.zoomOut(); break;
                    }
                });

                // Mouse wheel zoom
                const zoomContainer = this.lightbox.querySelector('.zoom-container');
                zoomContainer.addEventListener('wheel', (e) => {
                    e.preventDefault();
                    if (e.deltaY < 0) this.zoomIn();
                    else this.zoomOut();
                });

                // Pan when zoomed
                this.setupPanControls();
            }

            // Touch/swipe support
            this.setupSwipeControls(gallery);
        }

        setupSwipeControls(element) {
            let startX = 0;
            let startY = 0;

            element.addEventListener('touchstart', (e) => {
                startX = e.touches[0].clientX;
                startY = e.touches[0].clientY;
            }, { passive: true });

            element.addEventListener('touchend', (e) => {
                const endX = e.changedTouches[0].clientX;
                const endY = e.changedTouches[0].clientY;
                const diffX = startX - endX;
                const diffY = startY - endY;

                if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 50) {
                    if (diffX > 0) this.next();
                    else this.prev();
                }
            }, { passive: true });
        }

        setupPanControls() {
            const zoomContainer = this.lightbox.querySelector('.zoom-container');
            const image = this.lightbox.querySelector('.lightbox-image');
            let isDragging = false;
            let startX, startY, translateX = 0, translateY = 0;

            zoomContainer.addEventListener('mousedown', (e) => {
                if (this.zoomLevel > 1) {
                    isDragging = true;
                    startX = e.clientX - translateX;
                    startY = e.clientY - translateY;
                    zoomContainer.classList.add('zoomed');
                }
            });

            document.addEventListener('mousemove', (e) => {
                if (!isDragging) return;
                translateX = e.clientX - startX;
                translateY = e.clientY - startY;
                image.style.transform = `scale(${this.zoomLevel}) translate(${translateX / this.zoomLevel}px, ${translateY / this.zoomLevel}px)`;
            });

            document.addEventListener('mouseup', () => {
                isDragging = false;
                zoomContainer.classList.remove('zoomed');
            });
        }

        goTo(index) {
            const images = this.options.images;
            this.currentIndex = index;

            // Update main image
            const mainImg = this.container.querySelector('#gallery-main-image');
            if (mainImg) {
                mainImg.style.opacity = '0';
                setTimeout(() => {
                    mainImg.src = images[index];
                    mainImg.style.opacity = '1';
                }, 200);
            }

            // Update counter
            const counter = this.container.querySelector('#gallery-current');
            if (counter) counter.textContent = index + 1;

            // Update thumbnails
            this.container.querySelectorAll('.gallery-thumb').forEach((thumb, i) => {
                thumb.classList.toggle('active', i === index);
            });

            // Update lightbox if open
            if (this.lightbox && this.lightbox.classList.contains('active')) {
                const lightboxImg = this.lightbox.querySelector('.lightbox-image');
                if (lightboxImg) lightboxImg.src = images[index];

                this.lightbox.querySelectorAll('.lightbox-thumb').forEach((thumb, i) => {
                    thumb.classList.toggle('active', i === index);
                });

                this.zoomReset();
            }
        }

        next() {
            const nextIndex = (this.currentIndex + 1) % this.options.images.length;
            this.goTo(nextIndex);
        }

        prev() {
            const prevIndex = (this.currentIndex - 1 + this.options.images.length) % this.options.images.length;
            this.goTo(prevIndex);
        }

        openLightbox() {
            if (!this.lightbox) return;

            this.lightbox.classList.add('active');
            document.body.style.overflow = 'hidden';

            // Update lightbox image to current
            const lightboxImg = this.lightbox.querySelector('.lightbox-image');
            if (lightboxImg) {
                lightboxImg.src = this.options.images[this.currentIndex];
            }
        }

        closeLightbox() {
            if (!this.lightbox) return;

            this.lightbox.classList.remove('active');
            document.body.style.overflow = '';
            this.zoomReset();
        }

        zoomIn() {
            if (this.zoomLevel < this.maxZoom) {
                this.zoomLevel = Math.min(this.maxZoom, this.zoomLevel + 0.5);
                this.updateZoom();
            }
        }

        zoomOut() {
            if (this.zoomLevel > this.minZoom) {
                this.zoomLevel = Math.max(this.minZoom, this.zoomLevel - 0.5);
                this.updateZoom();
            }
        }

        zoomReset() {
            this.zoomLevel = 1;
            this.updateZoom();
        }

        updateZoom() {
            const image = this.lightbox.querySelector('.lightbox-image');
            if (image) {
                image.style.transform = `scale(${this.zoomLevel})`;
            }
        }

        startAutoplay() {
            this.autoplayInterval = setInterval(() => this.next(), this.options.autoplayDelay);
        }

        stopAutoplay() {
            if (this.autoplayInterval) {
                clearInterval(this.autoplayInterval);
            }
        }
    }

    // ========================================
    // 4. PAGE LOADER (DISABLED)
    // ========================================

    class PageLoader {
        constructor() {
            // DISABLED: User preferred faster navigation without loading animation
            this.loader = null;
        }

        init() {
            // DISABLED - Coffee cup loader removed for faster navigation
        }

        show() {
            // DISABLED
        }

        hide() {
            // DISABLED
        }
    }

    // ========================================
    // 5. SCROLL ENHANCEMENTS
    // ========================================

    class ScrollEnhancements {
        constructor() {
            this.scrollIndicator = null;
            this.backToTop = null;
            this.init();
        }

        init() {
            this.createScrollIndicator();
            this.createBackToTop();
            this.bindEvents();
        }

        createScrollIndicator() {
            this.scrollIndicator = document.createElement('div');
            this.scrollIndicator.className = 'scroll-indicator';
            this.scrollIndicator.innerHTML = '<div class="scroll-indicator-bar"></div>';
            document.body.appendChild(this.scrollIndicator);
        }

        createBackToTop() {
            this.backToTop = document.createElement('button');
            this.backToTop.className = 'back-to-top';
            this.backToTop.setAttribute('aria-label', 'Back to top');
            this.backToTop.innerHTML = '<i class="bi bi-chevron-up"></i>';
            document.body.appendChild(this.backToTop);

            this.backToTop.addEventListener('click', () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }

        bindEvents() {
            window.addEventListener('scroll', () => {
                this.updateScrollIndicator();
                this.updateBackToTop();
            }, { passive: true });
        }

        updateScrollIndicator() {
            const scrollTop = window.scrollY;
            const docHeight = document.documentElement.scrollHeight - window.innerHeight;
            const progress = (scrollTop / docHeight) * 100;

            const bar = this.scrollIndicator.querySelector('.scroll-indicator-bar');
            if (bar) {
                bar.style.width = `${progress}%`;
            }
        }

        updateBackToTop() {
            if (window.scrollY > 400) {
                this.backToTop.classList.add('visible');
            } else {
                this.backToTop.classList.remove('visible');
            }
        }
    }

    // ========================================
    // 6. RIPPLE EFFECT
    // ========================================

    function initRippleEffect() {
        document.addEventListener('click', function (e) {
            const target = e.target.closest('.ripple, .btn-golden, .btn-action, .btn');
            if (!target) return;

            // Skip ripple on cart toggle to prevent hiding the badge
            if (target.classList.contains('cart-toggle') || target.id === 'cartToggle') {
                return;
            }

            const rect = target.getBoundingClientRect();
            const ripple = document.createElement('span');
            ripple.className = 'ripple-effect';

            const size = Math.max(rect.width, rect.height);
            ripple.style.width = ripple.style.height = `${size}px`;
            ripple.style.left = `${e.clientX - rect.left - size / 2}px`;
            ripple.style.top = `${e.clientY - rect.top - size / 2}px`;

            // Store original overflow value
            const originalOverflow = target.style.overflow;

            target.style.position = 'relative';
            target.style.overflow = 'hidden';
            target.appendChild(ripple);

            setTimeout(() => {
                ripple.remove();
                // Restore overflow after animation
                target.style.overflow = originalOverflow || '';
            }, 600);
        });
    }

    // ========================================
    // 7. CONFETTI EFFECT
    // ========================================

    function createConfetti() {
        // Use canvas-confetti library for smoother performance
        if (typeof confetti !== 'undefined') {
            confetti({
                particleCount: 50,
                spread: 70,
                origin: { y: 0.6 },
                colors: ['#C9A227', '#E8C547', '#A88B1F', '#FFD700', '#22C55E']
            });
        } else {
            // Fallback to simple animation if library not loaded
            const container = document.createElement('div');
            container.className = 'confetti';
            document.body.appendChild(container);

            const colors = ['#C9A227', '#E8C547', '#22C55E', '#3B82F6', '#F59E0B'];

            for (let i = 0; i < 50; i++) {
                const confettiPiece = document.createElement('div');
                confettiPiece.className = 'confetti-piece';
                confettiPiece.style.left = `${Math.random() * 100}vw`;
                confettiPiece.style.background = colors[Math.floor(Math.random() * colors.length)];
                confettiPiece.style.animationDelay = `${Math.random() * 2}s`;
                confettiPiece.style.transform = `rotate(${Math.random() * 360}deg)`;
                container.appendChild(confettiPiece);
            }

            setTimeout(() => container.remove(), 3500);
        }
    }

    // ========================================
    // 8. ANIMATED COUNTERS
    // ========================================

    function animateCounter(element, target, duration = 2000) {
        const start = 0;
        const increment = target / (duration / 16);
        let current = start;

        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                element.textContent = target.toLocaleString('ar-EG');
                clearInterval(timer);
            } else {
                element.textContent = Math.floor(current).toLocaleString('ar-EG');
            }
        }, 16);
    }

    // Initialize counters when they come into view
    function initCounters() {
        const counters = document.querySelectorAll('.count-up');

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !entry.target.classList.contains('counted')) {
                    const target = parseInt(entry.target.dataset.target) || 0;
                    animateCounter(entry.target, target);
                    entry.target.classList.add('counted');
                }
            });
        }, { threshold: 0.5 });

        counters.forEach(counter => observer.observe(counter));
    }

    // ========================================
    // 9. FORM VALIDATION UI
    // ========================================

    function initFormValidation() {
        document.querySelectorAll('form').forEach(form => {
            form.querySelectorAll('input, textarea, select').forEach(field => {
                field.addEventListener('blur', () => validateField(field));
                field.addEventListener('input', () => {
                    if (field.classList.contains('is-invalid')) {
                        validateField(field);
                    }
                });
            });
        });
    }

    function validateField(field) {
        const isValid = field.checkValidity();

        field.classList.remove('is-valid', 'is-invalid');
        field.classList.add(isValid ? 'is-valid' : 'is-invalid');

        if (!isValid) {
            field.classList.add('shake');
            setTimeout(() => field.classList.remove('shake'), 500);
        }
    }

    // ========================================
    // INITIALIZE ALL
    // ========================================

    // Global instances
    window.Toast = new ToastManager();
    window.Skeleton = new SkeletonLoader();
    window.PageLoader = new PageLoader();
    window.ScrollEnhancements = new ScrollEnhancements();

    // Initialize components
    document.addEventListener('DOMContentLoaded', function () {
        initRippleEffect();
        initCounters();
        initFormValidation();

        // Override existing cart toast messages
        overrideCartMessages();
    });

    // Override cart add messages to use new toast system
    function overrideCartMessages() {
        // Store original addToCart function if exists
        const originalAddToCart = window.addToCart;

        if (typeof originalAddToCart === 'function') {
            window.addToCart = function (productId, quantity = 1) {
                fetch('/cart/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ product_id: productId, quantity: quantity })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.Toast.cart('تمت الإضافة! 🎉', `تمت إضافة المنتج إلى سلة التسوق بنجاح`);
                            createConfetti();

                            // Update cart count
                            if (typeof updateCartCount === 'function') {
                                updateCartCount();
                            }

                            // Bounce cart badge
                            const cartBadge = document.querySelector('.cart-badge');
                            if (cartBadge) {
                                cartBadge.classList.add('bounce');
                                setTimeout(() => cartBadge.classList.remove('bounce'), 500);
                            }
                        } else {
                            window.Toast.error('خطأ', data.message || 'حدث خطأ أثناء إضافة المنتج');
                        }
                    })
                    .catch(() => {
                        window.Toast.error('خطأ في الاتصال', 'يرجى التحقق من اتصالك بالإنترنت');
                    });
            };
        }
    }

    // Expose ImageGallery class
    window.ImageGallery = ImageGallery;

    // Expose helper functions
    window.createConfetti = createConfetti;

})();
