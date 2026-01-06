/**
 * Empapy Caffe - Creative Premium Effects
 * Advanced animations for world-class coffee website experience
 */

document.addEventListener('DOMContentLoaded', function () {
    initCreativeEffects();
});

function initCreativeEffects() {
    initCoffeeCursor();
    initCoffeeLoader();
    initCoffeeScrollProgress();
    initSteamEffect();
    initTypingAnimation();
    initInteractiveParticles();
    initTimeBasedTheme();
    initAdvanced3DCards();
    initMagneticButtonsAdvanced();
}

/**
 * 1. Custom Coffee Cursor with Steam Trail
 * DISABLED - User preference
 */
function initCoffeeCursor() {
    // Disabled by user request
    return;
}

/**
 * 2. Coffee Loading Screen Animation
 * DISABLED - User preference (speeds up website)
 */
function initCoffeeLoader() {
    // Disabled by user request to speed up website
    return;
}

/**
 * 3. Scroll Progress as Coffee Cup Filling
 * DISABLED - User prefers the original top bar
 */
function initCoffeeScrollProgress() {
    // Disabled - keeping original top progress bar from enhancements.js
    return;
}

/**
 * 4. Steam Effect on Coffee Images
 */
function initSteamEffect() {
    // Add steam to hero image and product images
    const coffeeImages = document.querySelectorAll('.hero-image img, .product-image');

    coffeeImages.forEach((img, index) => {
        const steamContainer = document.createElement('div');
        steamContainer.className = 'steam-container';

        for (let i = 0; i < 5; i++) {
            const steam = document.createElement('div');
            steam.className = 'steam-particle';
            steam.style.animationDelay = `${i * 0.3} s`;
            steam.style.left = `${20 + Math.random() * 60}% `;
            steamContainer.appendChild(steam);
        }

        if (img.classList.contains('product-image')) {
            img.appendChild(steamContainer);
        } else {
            img.parentElement.appendChild(steamContainer);
        }
    });
}

/**
 * 5. Typing Animation for Headings
 */
function initTypingAnimation() {
    const heroTitle = document.querySelector('.hero-title');
    if (!heroTitle) return;

    // Store original HTML
    const originalHTML = heroTitle.innerHTML;

    // Get the text content parts
    const textParts = originalHTML.split(/<span>|<\/span>/);

    if (textParts.length >= 3) {
        const beforeSpan = textParts[0];
        const spanContent = textParts[1];
        const afterSpan = textParts[2] || '';

        heroTitle.innerHTML = '';
        heroTitle.style.opacity = '1';

        // Type the first part
        typeText(heroTitle, beforeSpan, 50, () => {
            // Add the colored span with typing
            const span = document.createElement('span');
            heroTitle.appendChild(span);
            typeText(span, spanContent, 50, () => {
                // Type the last part
                typeText(heroTitle, afterSpan, 50);
            });
        });
    }
}

function typeText(element, text, speed, callback) {
    let i = 0;
    const interval = setInterval(() => {
        if (text[i] === '<') {
            // Skip HTML tags
            const tagEnd = text.indexOf('>', i);
            element.innerHTML += text.substring(i, tagEnd + 1);
            i = tagEnd + 1;
        } else {
            element.innerHTML += text[i];
            i++;
        }

        if (i >= text.length) {
            clearInterval(interval);
            if (callback) callback();
        }
    }, speed);
}

/**
 * 6. Interactive Coffee Particles
 * OPTIMIZED: Uses IntersectionObserver to pause when not visible
 */
function initInteractiveParticles() {
    const hero = document.querySelector('.hero-section');
    if (!hero) return;

    const canvas = document.createElement('canvas');
    canvas.className = 'particles-canvas';
    canvas.style.cssText = `
        position: absolute;
        inset: 0;
        z-index: 5;
        pointer-events: none;
    `;
    hero.appendChild(canvas);

    const ctx = canvas.getContext('2d');
    let particles = [];
    let mouseX = 0, mouseY = 0;
    let animationId = null;
    let isVisible = false;

    function resize() {
        canvas.width = hero.offsetWidth;
        canvas.height = hero.offsetHeight;
    }
    resize();
    window.addEventListener('resize', resize);

    // Create coffee bean particles
    class Particle {
        constructor() {
            this.reset();
        }

        reset() {
            this.x = Math.random() * canvas.width;
            this.y = Math.random() * canvas.height;
            this.size = Math.random() * 8 + 4;
            this.speedX = (Math.random() - 0.5) * 0.5;
            this.speedY = Math.random() * 0.5 + 0.2;
            this.rotation = Math.random() * Math.PI * 2;
            this.rotationSpeed = (Math.random() - 0.5) * 0.02;
            this.opacity = Math.random() * 0.5 + 0.3;
        }

        update() {
            this.x += this.speedX;
            this.y += this.speedY;
            this.rotation += this.rotationSpeed;

            // Mouse interaction
            const dx = mouseX - this.x;
            const dy = mouseY - this.y;
            const distance = Math.sqrt(dx * dx + dy * dy);
            if (distance < 100) {
                const force = (100 - distance) / 100;
                this.x -= dx * force * 0.02;
                this.y -= dy * force * 0.02;
            }

            // Reset if out of bounds
            if (this.y > canvas.height) {
                this.reset();
                this.y = -this.size;
            }
        }

        draw() {
            ctx.save();
            ctx.translate(this.x, this.y);
            ctx.rotate(this.rotation);
            ctx.globalAlpha = this.opacity;
            ctx.fillStyle = '#C9A227';

            // Draw coffee bean shape
            ctx.beginPath();
            ctx.ellipse(0, 0, this.size, this.size * 0.6, 0, 0, Math.PI * 2);
            ctx.fill();

            // Draw center line
            ctx.strokeStyle = '#8B5A2B';
            ctx.lineWidth = 1;
            ctx.beginPath();
            ctx.moveTo(-this.size * 0.7, 0);
            ctx.quadraticCurveTo(0, -this.size * 0.3, this.size * 0.7, 0);
            ctx.stroke();

            ctx.restore();
        }
    }

    // Create particles (reduced from 30 to 20)
    for (let i = 0; i < 20; i++) {
        particles.push(new Particle());
    }

    // Track mouse
    hero.addEventListener('mousemove', (e) => {
        const rect = hero.getBoundingClientRect();
        mouseX = e.clientX - rect.left;
        mouseY = e.clientY - rect.top;
    });

    // Animation loop with visibility check
    function animate() {
        if (!isVisible) {
            animationId = null;
            return;
        }

        ctx.clearRect(0, 0, canvas.width, canvas.height);

        particles.forEach(particle => {
            particle.update();
            particle.draw();
        });

        animationId = requestAnimationFrame(animate);
    }

    // Use IntersectionObserver to pause when not visible
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            isVisible = entry.isIntersecting;
            if (isVisible && !animationId) {
                animate();
            }
        });
    }, { threshold: 0.1 });

    observer.observe(hero);
}

/**
 * 7. Time-Based Auto Theme
 */
function initTimeBasedTheme() {
    const hour = new Date().getHours();

    // Night mode: 8 PM - 6 AM
    const isNight = hour >= 20 || hour < 6;

    // Check if user has manual preference
    const savedTheme = localStorage.getItem('theme');
    if (!savedTheme) {
        // Auto-set based on time
        document.documentElement.setAttribute('data-theme', isNight ? 'dark' : 'light');

        // Add time indicator
        const timeIndicator = document.createElement('div');
        timeIndicator.className = 'time-theme-indicator';
        timeIndicator.innerHTML = isNight ?
            '<i class="bi bi-moon-stars"></i> الوضع المسائي' :
            '<i class="bi bi-sun"></i> الوضع النهاري';
        document.body.appendChild(timeIndicator);

        setTimeout(() => {
            timeIndicator.classList.add('visible');
            setTimeout(() => {
                timeIndicator.classList.remove('visible');
                setTimeout(() => timeIndicator.remove(), 500);
            }, 3000);
        }, 2000);
    }
}

/**
 * 8. Advanced 3D Product Cards
 */
function initAdvanced3DCards() {
    document.querySelectorAll('.product-card').forEach(card => {
        // Add shine effect layer
        const shine = document.createElement('div');
        shine.className = 'card-shine';
        card.appendChild(shine);

        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = ((e.clientX - rect.left) / rect.width) * 100;
            const y = ((e.clientY - rect.top) / rect.height) * 100;

            shine.style.background = `radial - gradient(circle at ${x} % ${y} %, rgba(255, 255, 255, 0.3) 0 %, transparent 50 %)`;
        });

        card.addEventListener('mouseleave', () => {
            shine.style.background = 'none';
        });
    });
}

/**
 * 9. Advanced Magnetic Buttons
 */
function initMagneticButtonsAdvanced() {
    if (window.innerWidth < 1024) return;

    document.querySelectorAll('.btn-golden, .btn-outline-golden, .hero-badge').forEach(btn => {
        const inner = btn.innerHTML;
        btn.innerHTML = `<span class="btn-inner">${inner}</span>`;
        const span = btn.querySelector('.btn-inner');

        btn.addEventListener('mousemove', function (e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left - rect.width / 2;
            const y = e.clientY - rect.top - rect.height / 2;

            this.style.transform = `translate(${x * 0.3}px, ${y * 0.3}px)`;
            span.style.transform = `translate(${x * 0.1}px, ${y * 0.1}px)`;
        });

        btn.addEventListener('mouseleave', function () {
            this.style.transform = '';
            span.style.transform = '';
        });
    });
}

/**
 * Expose functions globally
 */
window.CreativeEffects = {
    initCoffeeCursor,
    initCoffeeLoader,
    initSteamEffect,
    initTypingAnimation,
    initInteractiveParticles
};

/**
 * 10. Animated Gradient Text
 */
function initAnimatedGradientText() {
    const heroTitle = document.querySelector('.hero-title span');
    if (!heroTitle) return;

    heroTitle.classList.add('gradient-text-animated');
}

// Add gradient text animation on load
document.addEventListener('DOMContentLoaded', initAnimatedGradientText);

/**
 * 11. Smooth Scroll Reveal Effects
 */
function initSmoothReveal() {
    const revealElements = document.querySelectorAll('.glass-card, .category-card, .product-card, .section-title');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
                entry.target.style.transitionDelay = `${Math.random() * 0.3} s`;
            }
        });
    }, { threshold: 0.1, rootMargin: '50px' });

    revealElements.forEach(el => {
        el.classList.add('reveal-element');
        observer.observe(el);
    });
}

document.addEventListener('DOMContentLoaded', initSmoothReveal);

/**
 * 12. Sound Effects (Optional - disabled by default)
 */
const SoundManager = {
    enabled: false,
    sounds: {},

    init() {
        // Sounds are disabled by default
        // User can enable via: SoundManager.enable()
    },

    enable() {
        this.enabled = true;
    },

    disable() {
        this.enabled = false;
    },

    play(soundName) {
        if (!this.enabled) return;
        // Sound implementation would go here
    }
};

window.SoundManager = SoundManager;

/**
 * 13. Easter Egg - Konami Code
 */
function initEasterEgg() {
    const konamiCode = [38, 38, 40, 40, 37, 39, 37, 39, 66, 65];
    let konamiIndex = 0;

    document.addEventListener('keydown', (e) => {
        if (e.keyCode === konamiCode[konamiIndex]) {
            konamiIndex++;
            if (konamiIndex === konamiCode.length) {
                activateEasterEgg();
                konamiIndex = 0;
            }
        } else {
            konamiIndex = 0;
        }
    });
}

function activateEasterEgg() {
    // Rain coffee beans!
    const container = document.createElement('div');
    container.className = 'coffee-rain';
    container.style.cssText = `
    position: fixed;
    inset: 0;
    z - index: 999999;
    pointer - events: none;
    overflow: hidden;
    `;

    for (let i = 0; i < 50; i++) {
        const bean = document.createElement('div');
        bean.innerHTML = '☕';
        bean.style.cssText = `
    position: absolute;
    font - size: ${20 + Math.random() * 30} px;
    left: ${Math.random() * 100}%;
    top: -50px;
    animation: rainFall ${2 + Math.random() * 3}s linear forwards;
    animation - delay: ${Math.random() * 2} s;
    `;
        container.appendChild(bean);
    }

    document.body.appendChild(container);

    // Add rain animation
    const style = document.createElement('style');
    style.textContent = `
    @keyframes rainFall {
            to {
            transform: translateY(120vh) rotate(720deg);
        }
    }
    `;
    document.head.appendChild(style);

    setTimeout(() => {
        container.remove();
        style.remove();
    }, 5000);

    // Show secret message
    const message = document.createElement('div');
    message.style.cssText = `
    position: fixed;
    top: 50 %;
    left: 50 %;
    transform: translate(-50 %, -50 %);
    background: rgba(44, 24, 16, 0.95);
    color: #C9A227;
    padding: 30px 50px;
    border - radius: 20px;
    font - size: 1.5rem;
    font - weight: bold;
    z - index: 9999999;
    text - align: center;
    animation: popIn 0.5s ease;
    `;
    message.innerHTML = '☕ أنت من عشاق القهوة الحقيقيين! ☕<br><small style="color: rgba(255,255,255,0.7)">خصم 10% - استخدم كود: COFFEE10</small>';
    document.body.appendChild(message);

    const popStyle = document.createElement('style');
    popStyle.textContent = `
    @keyframes popIn {
        0 % { transform: translate(-50 %, -50 %) scale(0); }
        50 % { transform: translate(-50 %, -50 %) scale(1.1); }
        100 % { transform: translate(-50 %, -50 %) scale(1); }
    }
    `;
    document.head.appendChild(popStyle);

    setTimeout(() => {
        message.style.animation = 'popIn 0.3s ease reverse';
        setTimeout(() => {
            message.remove();
            popStyle.remove();
        }, 300);
    }, 3000);
}

document.addEventListener('DOMContentLoaded', initEasterEgg);

/**
 * 14. Smooth Menu Icon Animation
 */
function initMenuAnimation() {
    const navToggler = document.querySelector('.navbar-toggler');
    if (!navToggler) return;

    navToggler.addEventListener('click', function () {
        this.classList.toggle('active');
    });
}

document.addEventListener('DOMContentLoaded', initMenuAnimation);

/**
 * 15. Image Hover Zoom with Lens Effect
 */
function initImageLens() {
    document.querySelectorAll('.product-image').forEach(container => {
        const img = container.querySelector('img');
        if (!img) return;

        // Create lens element
        const lens = document.createElement('div');
        lens.className = 'image-lens';
        lens.style.cssText = `
    display: none;
    position: absolute;
    width: 100px;
    height: 100px;
    border - radius: 50 %;
    border: 3px solid #C9A227;
    pointer - events: none;
    z - index: 10;
    background - size: 300 %;
    box - shadow: 0 0 20px rgba(201, 162, 39, 0.5);
    `;
        container.appendChild(lens);

        container.addEventListener('mouseenter', () => {
            lens.style.display = 'block';
            lens.style.backgroundImage = `url(${img.src})`;
        });

        container.addEventListener('mouseleave', () => {
            lens.style.display = 'none';
        });

        container.addEventListener('mousemove', (e) => {
            const rect = container.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            const lensX = x - 50;
            const lensY = y - 50;

            lens.style.left = `${lensX} px`;
            lens.style.top = `${lensY} px`;

            // Calculate background position
            const bgX = (x / rect.width) * 100;
            const bgY = (y / rect.height) * 100;
            lens.style.backgroundPosition = `${bgX}% ${bgY}% `;
        });
    });
}

// Disabled by default - can be enabled via: window.initImageLens()
window.initImageLens = initImageLens;

