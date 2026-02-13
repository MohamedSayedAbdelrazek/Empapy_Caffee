/**
 * Creative Effects - Performance Optimized
 * Only essential effects, lazy-loaded, IntersectionObserver-gated
 */
(function() {
    'use strict';

    var isMobile = window.innerWidth < 1024;
    var prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    function initCreativeEffects() {
        if (prefersReducedMotion) return;

        initCoffeeLoader();
        initSmoothReveal();
        initTimeBasedTheme();

        // Desktop-only effects
        if (!isMobile) {
            initCoffeeScrollProgress();
            initInteractiveParticles();
        }
    }

    function initCoffeeLoader() {
        // Loader is now CSS-only, this just removes it
        var loader = document.querySelector('.coffee-loader');
        if (loader) {
            window.addEventListener('load', function() {
                setTimeout(function() {
                    loader.classList.add('loaded');
                    setTimeout(function() { loader.remove(); }, 600);
                }, 300);
            });
        }
    }

    function initCoffeeScrollProgress() {
        // Minimal scroll progress - no heavy DOM
    }

    function initInteractiveParticles() {
        var hero = document.querySelector('.hero-section');
        if (!hero) return;

        var canvas = document.createElement('canvas');
        canvas.className = 'particles-canvas';
        canvas.style.cssText = 'position:absolute;inset:0;z-index:5;pointer-events:none;';
        hero.appendChild(canvas);

        var ctx = canvas.getContext('2d');
        var particles = [];
        var isVisible = false;
        var rafId = null;

        function resize() {
            canvas.width = hero.offsetWidth;
            canvas.height = hero.offsetHeight;
        }
        resize();
        window.addEventListener('resize', resize, { passive: true });

        // Only 12 particles (reduced from 20)
        for (var i = 0; i < 12; i++) {
            particles.push({
                x: Math.random() * canvas.width,
                y: Math.random() * canvas.height,
                size: Math.random() * 6 + 3,
                speedX: (Math.random() - 0.5) * 0.3,
                speedY: Math.random() * 0.3 + 0.1,
                rotation: Math.random() * Math.PI * 2,
                rotSpeed: (Math.random() - 0.5) * 0.01,
                opacity: Math.random() * 0.3 + 0.2
            });
        }

        function draw() {
            if (!isVisible) { rafId = null; return; }
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            for (var i = 0; i < particles.length; i++) {
                var p = particles[i];
                p.x += p.speedX;
                p.y += p.speedY;
                p.rotation += p.rotSpeed;
                if (p.y > canvas.height) { p.y = -p.size; p.x = Math.random() * canvas.width; }

                ctx.save();
                ctx.translate(p.x, p.y);
                ctx.rotate(p.rotation);
                ctx.globalAlpha = p.opacity;
                ctx.fillStyle = '#C9A227';
                ctx.beginPath();
                ctx.ellipse(0, 0, p.size, p.size * 0.6, 0, 0, Math.PI * 2);
                ctx.fill();
                ctx.restore();
            }
            rafId = requestAnimationFrame(draw);
        }

        new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                isVisible = entry.isIntersecting;
                if (isVisible && !rafId) draw();
            });
        }, { threshold: 0.1 }).observe(hero);
    }

    function initTimeBasedTheme() {
        var hour = new Date().getHours();
        var isNight = hour >= 20 || hour < 6;
        if (!localStorage.getItem('theme')) {
            document.documentElement.setAttribute('data-theme', isNight ? 'dark' : 'light');
        }
    }

    function initSmoothReveal() {
        var elements = document.querySelectorAll('.glass-card, .category-card, .product-card, .section-title');
        if (!elements.length) return;

        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '50px' });

        elements.forEach(function(el) {
            el.classList.add('reveal-element');
            observer.observe(el);
        });
    }

    // Initialize on DOMContentLoaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCreativeEffects);
    } else {
        initCreativeEffects();
    }

    // Expose for other scripts
    window.CreativeEffects = { initInteractiveParticles: initInteractiveParticles };

    // Sound Manager stub
    window.SoundManager = { enabled: false, init: function(){}, enable: function(){}, disable: function(){}, play: function(){} };
})();