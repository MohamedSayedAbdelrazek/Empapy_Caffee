/**
 * Empapy Caffe - Premium UI Enhancements
 * Advanced animations, transitions, and visual effects
 */

document.addEventListener('DOMContentLoaded', function () {
    // Initialize all enhancements
    initScrollProgress();
    initDarkMode();
    init3DHoverEffect();
    initLazyLoading();
    initCustomCursor();
    initParallax();
    initMicroInteractions();
    initParticles();
    initQuickView();
    initAnimatedCounters();
    initScrollAnimations();
    initBackToTop();
    initPageTransitions();
    initMagneticButtons();
});

/**
 * 1. Scroll Progress Bar
 */
function initScrollProgress() {
    // Create progress bar element
    const progressBar = document.createElement('div');
    progressBar.className = 'scroll-progress';
    document.body.appendChild(progressBar);

    function updateProgress() {
        const scrollTop = window.scrollY;
        const docHeight = document.documentElement.scrollHeight - window.innerHeight;
        const scrollPercent = scrollTop / docHeight;
        progressBar.style.transform = `scaleX(${scrollPercent})`;
    }

    window.addEventListener('scroll', updateProgress, { passive: true });
    updateProgress();
}

/**
 * 2. Dark Mode Toggle
 */
function initDarkMode() {
    // Get the toggle button from navbar
    const toggleBtn = document.getElementById('themeToggleNavbar');
    if (!toggleBtn) return;

    // Check for saved preference
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', savedTheme);

    toggleBtn.addEventListener('click', () => {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);

        // Add animation
        toggleBtn.style.transform = 'scale(0.8) rotate(180deg)';
        setTimeout(() => {
            toggleBtn.style.transform = '';
        }, 300);
    });
}

/**
 * 3. 3D Hover Effect for Products
 */
function init3DHoverEffect() {
    document.querySelectorAll('.product-card').forEach(card => {
        const wrapper = card.closest('.col-6, .col-md-4, .col-lg-3');
        if (wrapper) {
            wrapper.classList.add('product-card-3d');
        }

        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            const centerX = rect.width / 2;
            const centerY = rect.height / 2;

            const rotateX = (y - centerY) / 20;
            const rotateY = (centerX - x) / 20;

            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-10px)`;
        });

        card.addEventListener('mouseleave', () => {
            card.style.transform = '';
        });
    });
}

/**
 * 4. Image Lazy Loading with Blur Effect
 */
function initLazyLoading() {
    const images = document.querySelectorAll('img[data-src]');

    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.classList.add('lazy');

                // Create a new image to preload
                const preloadImg = new Image();
                preloadImg.src = img.dataset.src;
                preloadImg.onload = () => {
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    img.classList.add('loaded');
                    img.parentElement?.classList.add('loaded');
                };

                observer.unobserve(img);
            }
        });
    }, { rootMargin: '50px' });

    images.forEach(img => {
        img.parentElement?.classList.add('lazy-image');
        imageObserver.observe(img);
    });
}

/**
 * 5. Custom Cursor (DISABLED - keeping default cursor)
 */
function initCustomCursor() {
    // Disabled - keeping default browser cursor
    return;

    /*
    // Only on desktop
    if (window.innerWidth < 1024) return;

    const cursor = document.createElement('div');
    cursor.className = 'custom-cursor';
    
    const cursorDot = document.createElement('div');
    cursorDot.className = 'custom-cursor-dot';
    
    document.body.appendChild(cursor);
    document.body.appendChild(cursorDot);

    let mouseX = 0, mouseY = 0;
    let cursorX = 0, cursorY = 0;

    document.addEventListener('mousemove', (e) => {
        mouseX = e.clientX;
        mouseY = e.clientY;
        
        // Dot follows immediately
        cursorDot.style.left = `${mouseX - 3}px`;
        cursorDot.style.top = `${mouseY - 3}px`;
    });

    // Smooth cursor animation
    function animateCursor() {
        cursorX += (mouseX - cursorX) * 0.15;
        cursorY += (mouseY - cursorY) * 0.15;
        
        cursor.style.left = `${cursorX - 10}px`;
        cursor.style.top = `${cursorY - 10}px`;
        
        requestAnimationFrame(animateCursor);
    }
    animateCursor();

    // Hover effect on interactive elements
    const interactiveElements = document.querySelectorAll('a, button, .btn, .product-card, input, select, textarea');
    interactiveElements.forEach(el => {
        el.addEventListener('mouseenter', () => cursor.classList.add('hover'));
        el.addEventListener('mouseleave', () => cursor.classList.remove('hover'));
    });

    // Hide default cursor
    document.body.style.cursor = 'none';
    interactiveElements.forEach(el => el.style.cursor = 'none');
    */
}

/**
 * 6. Parallax Effect
 */
function initParallax() {
    const parallaxElements = document.querySelectorAll('.hero-bg img, .page-header::before');

    function updateParallax() {
        const scrolled = window.scrollY;

        parallaxElements.forEach(el => {
            const speed = 0.5;
            const yPos = -(scrolled * speed);
            el.style.transform = `translate3d(0, ${yPos}px, 0)`;
        });
    }

    window.addEventListener('scroll', updateParallax, { passive: true });
}

/**
 * 7. Micro-Interactions (Ripple Effect & Confetti)
 */
function initMicroInteractions() {
    // Ripple effect for buttons
    document.querySelectorAll('.btn-golden, .btn-outline-golden, .btn-action').forEach(btn => {
        btn.addEventListener('click', function (e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            const ripple = document.createElement('span');
            ripple.style.cssText = `
                position: absolute;
                background: rgba(255, 255, 255, 0.4);
                border-radius: 50%;
                pointer-events: none;
                transform: scale(0);
                animation: ripple 0.6s ease-out;
                left: ${x}px;
                top: ${y}px;
                width: 10px;
                height: 10px;
                margin-left: -5px;
                margin-top: -5px;
            `;

            this.appendChild(ripple);
            setTimeout(() => ripple.remove(), 600);
        });
    });

    // Add ripple animation to CSS
    const style = document.createElement('style');
    style.textContent = `
        @keyframes ripple {
            to {
                transform: scale(40);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);
}

/**
 * Confetti effect for add to cart
 */
function showConfetti(x, y) {
    const container = document.createElement('div');
    container.className = 'confetti-container';
    document.body.appendChild(container);

    const colors = ['#C9A227', '#E8C547', '#A88B1F', '#FFD700', '#FFA500'];

    for (let i = 0; i < 30; i++) {
        const confetti = document.createElement('div');
        confetti.className = 'confetti';
        confetti.style.cssText = `
            left: ${x}px;
            top: ${y}px;
            background: ${colors[Math.floor(Math.random() * colors.length)]};
            width: ${Math.random() * 10 + 5}px;
            height: ${Math.random() * 10 + 5}px;
            animation-delay: ${Math.random() * 0.3}s;
            animation-duration: ${Math.random() * 1 + 2}s;
            transform: translate(${(Math.random() - 0.5) * 200}px, 0) rotate(${Math.random() * 360}deg);
        `;
        container.appendChild(confetti);
    }

    setTimeout(() => container.remove(), 3000);
}

// Override the addToCart function to include confetti
const originalAddToCart = window.addToCart;
if (originalAddToCart) {
    window.addToCart = function (productId, quantity = 1) {
        // Get the button position for confetti
        const btn = document.querySelector(`[data-product-id="${productId}"]`);
        if (btn) {
            const rect = btn.getBoundingClientRect();
            showConfetti(rect.left + rect.width / 2, rect.top);
        }

        return originalAddToCart(productId, quantity);
    };
}

/**
 * 8. Particles Background
 */
function initParticles() {
    const hero = document.querySelector('.hero-section');
    if (!hero) return;

    const container = document.createElement('div');
    container.className = 'particles-container';
    hero.appendChild(container);

    for (let i = 0; i < 20; i++) {
        createParticle(container);
    }
}

function createParticle(container) {
    const particle = document.createElement('div');
    particle.className = 'particle';

    const size = Math.random() * 10 + 5;
    particle.style.cssText = `
        width: ${size}px;
        height: ${size}px;
        left: ${Math.random() * 100}%;
        top: ${Math.random() * 100}%;
        animation-duration: ${Math.random() * 10 + 10}s;
        animation-delay: ${Math.random() * 5}s;
    `;

    container.appendChild(particle);
}

/**
 * 9. Quick View Modal
 */
function initQuickView() {
    // Create modal structure
    const modal = document.createElement('div');
    modal.className = 'quick-view-modal';
    modal.id = 'quickViewModal';
    modal.innerHTML = `
        <div class="quick-view-overlay"></div>
        <div class="quick-view-content">
            <button class="quick-view-close"><i class="bi bi-x-lg"></i></button>
            <div class="quick-view-body">
                <div class="quick-view-image"></div>
                <div class="quick-view-details">
                    <span class="quick-view-category"></span>
                    <h2 class="quick-view-title"></h2>
                    <p class="quick-view-description"></p>
                    <div class="quick-view-price"></div>
                    <div class="quick-view-actions mt-4">
                        <button class="btn btn-golden btn-lg w-100 mb-3 quick-view-add-cart">
                            <i class="bi bi-bag-plus me-2"></i>أضف للسلة
                        </button>
                        <a href="#" class="btn btn-outline-golden w-100 quick-view-link">
                            عرض التفاصيل الكاملة
                        </a>
                    </div>
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(modal);

    // Close modal handlers
    modal.querySelector('.quick-view-overlay').addEventListener('click', closeQuickView);
    modal.querySelector('.quick-view-close').addEventListener('click', closeQuickView);
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeQuickView();
    });

    // Add quick view buttons to product cards
    document.querySelectorAll('.product-card').forEach(card => {
        const productActions = card.querySelector('.product-actions');
        if (productActions && !productActions.querySelector('.quick-view-btn')) {
            const quickViewBtn = document.createElement('button');
            quickViewBtn.className = 'btn-action quick-view-btn';
            quickViewBtn.innerHTML = '<i class="bi bi-eye"></i>';
            quickViewBtn.setAttribute('title', 'عرض سريع');

            // Get product data from card
            const productData = {
                id: card.querySelector('.add-to-cart-btn')?.dataset.productId,
                name: card.querySelector('.product-title a')?.textContent,
                category: card.querySelector('.product-category')?.textContent,
                price: card.querySelector('.price-current')?.textContent,
                oldPrice: card.querySelector('.price-old')?.textContent,
                image: card.querySelector('.product-image img')?.src,
                link: card.querySelector('.product-title a')?.href,
                description: 'قهوة فاخرة مختارة بعناية من أجود المزارع'
            };

            quickViewBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                openQuickView(productData);
            });

            productActions.insertBefore(quickViewBtn, productActions.firstChild);
        }
    });
}

function openQuickView(product) {
    const modal = document.getElementById('quickViewModal');
    if (!modal) return;

    modal.querySelector('.quick-view-image').style.backgroundImage = `url(${product.image})`;
    modal.querySelector('.quick-view-category').textContent = product.category || 'قهوة فاخرة';
    modal.querySelector('.quick-view-title').textContent = product.name;
    modal.querySelector('.quick-view-description').textContent = product.description;

    let priceHTML = `<span>${product.price}</span>`;
    if (product.oldPrice) {
        priceHTML = `<span class="old-price">${product.oldPrice}</span>${priceHTML}`;
    }
    modal.querySelector('.quick-view-price').innerHTML = priceHTML;

    modal.querySelector('.quick-view-link').href = product.link || '#';

    const addCartBtn = modal.querySelector('.quick-view-add-cart');
    addCartBtn.onclick = () => {
        if (product.id && window.addToCart) {
            window.addToCart(product.id);
            closeQuickView();
        }
    };

    modal.classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeQuickView() {
    const modal = document.getElementById('quickViewModal');
    if (modal) {
        modal.classList.remove('open');
        document.body.style.overflow = '';
    }
}

// Make function global
window.openQuickView = openQuickView;
window.closeQuickView = closeQuickView;

/**
 * 10. Animated Counters
 */
function initAnimatedCounters() {
    const counters = document.querySelectorAll('.stat-number');

    const observerOptions = {
        threshold: 0.5
    };

    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !entry.target.classList.contains('counted')) {
                animateCounter(entry.target);
                entry.target.classList.add('counted');
            }
        });
    }, observerOptions);

    counters.forEach(counter => {
        counter.classList.add('counter-animated');
        counterObserver.observe(counter);
    });
}

function animateCounter(element) {
    const text = element.textContent;
    const match = text.match(/(\d+)([K+★]?)/);
    if (!match) return;

    const target = parseInt(match[1]);
    const suffix = match[2] || '';
    const plusSign = text.includes('+') ? '+' : '';

    let current = 0;
    const increment = target / 50;
    const duration = 2000;
    const stepTime = duration / 50;

    const timer = setInterval(() => {
        current += increment;
        if (current >= target) {
            current = target;
            clearInterval(timer);
        }
        element.textContent = Math.floor(current) + suffix + plusSign;
    }, stepTime);
}

/**
 * 11. Scroll Animations
 */
function initScrollAnimations() {
    const animatedElements = document.querySelectorAll('[data-aos]');

    // Add custom scroll classes
    document.querySelectorAll('.glass-card, .category-card').forEach(el => {
        if (!el.hasAttribute('data-aos')) {
            el.classList.add('scroll-zoom');
        }
    });

    const scrollElements = document.querySelectorAll('.scroll-fade-up, .scroll-fade-left, .scroll-fade-right, .scroll-zoom');

    const scrollObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, { threshold: 0.1 });

    scrollElements.forEach(el => scrollObserver.observe(el));
}

/**
 * 12. Back to Top Button
 */
function initBackToTop() {
    const btn = document.createElement('button');
    btn.className = 'back-to-top';
    btn.innerHTML = '<i class="bi bi-chevron-up"></i>';
    btn.setAttribute('aria-label', 'Back to top');
    document.body.appendChild(btn);

    function toggleVisibility() {
        if (window.scrollY > 300) {
            btn.classList.add('visible');
        } else {
            btn.classList.remove('visible');
        }
    }

    window.addEventListener('scroll', toggleVisibility, { passive: true });

    btn.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}

/**
 * 13. Page Transitions
 * DISABLED - User prefers coffee loader only
 */
function initPageTransitions() {
    // Disabled - keeping only the coffee loader animation
    return;

    // Intercept link clicks for smooth transitions
    document.querySelectorAll('a[href^="/"], a[href^="' + window.location.origin + '"]').forEach(link => {
        // Skip certain links
        if (link.getAttribute('target') === '_blank' ||
            link.getAttribute('data-no-transition') ||
            link.getAttribute('href').includes('#')) {
            return;
        }

        link.addEventListener('click', function (e) {
            const href = this.getAttribute('href');

            // Don't transition for same page
            if (href === window.location.pathname) return;

            e.preventDefault();
            transition.classList.add('active');

            setTimeout(() => {
                window.location.href = href;
            }, 300);
        });
    });

    // Remove transition on page load
    window.addEventListener('load', () => {
        transition.classList.remove('active');
    });
}

/**
 * 14. Magnetic Buttons
 */
function initMagneticButtons() {
    if (window.innerWidth < 1024) return;

    document.querySelectorAll('.btn-golden, .btn-outline-golden').forEach(btn => {
        btn.classList.add('magnetic-btn');

        btn.addEventListener('mousemove', function (e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left - rect.width / 2;
            const y = e.clientY - rect.top - rect.height / 2;

            this.style.transform = `translate(${x * 0.2}px, ${y * 0.2}px)`;
        });

        btn.addEventListener('mouseleave', function () {
            this.style.transform = '';
        });
    });
}

/**
 * Utility: Show Confetti (globally accessible)
 */
window.showConfetti = showConfetti;
