<!-- Premium Footer -->
<footer class="footer-main" style="margin-top: 60px;">
    <!-- Wave Effect -->
    <div class="footer-wave">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M0 120L60 110C120 100 240 80 360 70C480 60 600 60 720 65C840 70 960 80 1080 85C1200 90 1320 90 1380 90L1440 90V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0V120Z"
                fill="#1a1a1a" />
        </svg>
    </div>


    <div class="footer-content">
        <div class="container">
            <div class="row g-5">
                <!-- About -->
                <div class="col-lg-4" data-aos="fade-up">
                    <div class="footer-brand mb-4">
                        <img src="{{ asset('logo.jpg') }}" alt="إمبابي كافيه" class="footer-logo"
                            style="height: 50px; width: auto; border-radius: 8px;">
                        <span class="brand-text">إمبابي كافيه</span>
                    </div>
                    <p class="footer-about">
                        نقدم لكم أجود أنواع القهوة من حول العالم. نحمص حبوبنا بعناية فائقة لنضمن لكم تجربة قهوة
                        استثنائية في كل كوب.
                    </p>
                    <div class="social-links">
                        <a href="https://www.facebook.com/profile.php?id=61559046937280" target="_blank"
                            class="social-link" title="فيسبوك"><i class="bi bi-facebook"></i></a>
                        <a href="https://wa.me/201151579225" target="_blank" class="social-link" title="واتساب"><i
                                class="bi bi-whatsapp"></i></a>
                        <a href="https://www.instagram.com/empapy_coffee" target="_blank" class="social-link"
                            title="انستجرام"><i class="bi bi-instagram"></i></a>
                        <a href="https://www.tiktok.com/@empapy_coffe" target="_blank" class="social-link"
                            title="تيك توك"><i class="bi bi-tiktok"></i></a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="col-lg-2 col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <h5 class="footer-title">روابط سريعة</h5>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}">الرئيسية</a></li>
                        <li><a href="{{ route('shop.index') }}">المتجر</a></li>
                        <li><a href="{{ route('about') }}">من نحن</a></li>
                        <li><a href="{{ route('contact') }}">تواصل معنا</a></li>
                    </ul>
                </div>

                <!-- Categories -->
                <div class="col-lg-2 col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <h5 class="footer-title">الأصناف</h5>
                    <ul class="footer-links">
                        @php
                            $footerCategories = \App\Models\Category::active()->orderBy('name')->limit(4)->get();
                        @endphp
                        @forelse($footerCategories as $category)
                            <li>
                                <a href="{{ route('shop.index', ['category' => $category->slug]) }}">
                                    {{ $category->name }}
                                </a>
                            </li>
                        @empty
                            <li><a href="{{ route('shop.index') }}">تصفح جميع المنتجات</a></li>
                        @endforelse
                    </ul>
                </div>

                <!-- Contact -->
                <div class="col-lg-4 col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <h5 class="footer-title">تواصل معنا</h5>
                    <ul class="footer-contact">
                        <li>
                            <i class="bi bi-geo-alt-fill"></i>
                            <a href="https://www.google.com/maps/place/%D8%A8%D9%86+%D8%A7%D9%85%D8%A8%D8%A7%D8%A8%D9%8A%E2%80%AD/@30.0879583,31.2502435,15.75z/data=!4m6!3m5!1s0x145841006b879969:0x1d2dfd8c57ec5e89!8m2!3d30.0887951!4d31.2528785!16s%2Fg%2F11lz66c2wf"
                                target="_blank" class="location-link"
                                style="color: inherit; text-decoration: none; transition: color 0.3s;">
                                القاهرة، مصر - بن امبابي
                            </a>
                        </li>
                        <li>
                            <i class="bi bi-telephone-fill"></i>
                            <a href="tel:+201151579225" style="color: inherit; text-decoration: none;">
                                <span dir="ltr">+20 1151579225</span>
                            </a>
                        </li>
                        <li>
                            <i class="bi bi-envelope-fill"></i>
                            <a href="mailto:info@empapy.com" style="color: inherit; text-decoration: none;">
                                <span>info@empapy.com</span>
                            </a>
                        </li>
                        <li>
                            <i class="bi bi-clock-fill"></i>
                            <span>طوال أيام الأسبوع: 9 ص - 2 ص</span>
                        </li>
                    </ul>


                </div>

            </div>
        </div>
    </div>
    <!-- Creative Map Section -->
    <div class="footer-map-section" data-aos="fade-up">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="map-card">
                        <!-- Map Header -->
                        <div class="map-header-compact">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="map-icon-badge">
                                        <i class="bi bi-geo-alt-fill"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0">موقع الكافيه</h5>
                                        <p class="mb-0 small opacity-75">بن امبابي - القاهرة</p>
                                    </div>
                                </div>
                                <a href="https://www.google.com/maps/place/%D8%A8%D9%86+%D8%A7%D9%85%D8%A8%D8%A7%D8%A8%D9%8A%E2%80%AD/@30.0879583,31.2502435,15.75z/data=!4m6!3m5!1s0x145841006b879969:0x1d2dfd8c57ec5e89!8m2!3d30.0887951!4d31.2528785!16s%2Fg%2F11lz66c2wf"
                                    target="_blank" class="btn btn-golden btn-sm d-none d-md-inline-flex">
                                    <i class="bi bi-arrow-up-right-circle me-1"></i>
                                    افتح الخريطة
                                </a>
                            </div>
                        </div>

                        <!-- Compact Map Container -->
                        <div class="compact-map-container">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3453.238326!2d31.2502435!3d30.0887951!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x145841006b879969%3A0x1d2dfd8c57ec5e89!2z2KjZhiDYp9mF2KjYp9io2Yo!5e0!3m2!1sar!2seg!4v1706624000000!5m2!1sar!2seg"
                                width="100%" height="100%" style="border:0; border-radius: 0 0 16px 16px;"
                                allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                            </iframe>

                            <!-- Floating Overlay Button for Mobile -->
                            <a href="https://www.google.com/maps/place/%D8%A8%D9%86+%D8%A7%D9%85%D8%A8%D8%A7%D8%A8%D9%8A%E2%80%AD/@30.0879583,31.2502435,15.75z/data=!4m6!3m5!1s0x145841006b879969:0x1d2dfd8c57ec5e89!8m2!3d30.0887951!4d31.2528785!16s%2Fg%2F11lz66c2wf"
                                target="_blank" class="floating-map-btn d-md-none">
                                <i class="bi bi-pin-map-fill"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Creative Map Section Styling */
        .footer-map-section {
            padding: 2rem 0 1.5rem;
            background: linear-gradient(180deg, transparent 0%, rgba(0, 0, 0, 0.1) 100%);
        }

        .map-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(201, 162, 39, 0.2);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .map-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 48px rgba(201, 162, 39, 0.2);
            border-color: rgba(201, 162, 39, 0.4);
        }

        .map-header-compact {
            padding: 1.25rem 1.5rem;
            background: linear-gradient(135deg, #2C1810 0%, #1a1009 100%);
            color: white;
        }

        .map-header-compact h5 {
            color: var(--gold);
            font-weight: 600;
            font-size: 1.1rem;
        }

        .map-icon-badge {
            width: 45px;
            height: 45px;
            background: var(--gradient-gold);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--espresso);
            font-size: 1.2rem;
            box-shadow: 0 4px 12px rgba(201, 162, 39, 0.3);
        }

        .compact-map-container {
            height: 220px;
            position: relative;
            overflow: hidden;
        }

        .compact-map-container iframe {
            filter: grayscale(0.15);
            transition: filter 0.3s ease;
        }

        .compact-map-container:hover iframe {
            filter: grayscale(0);
        }

        .floating-map-btn {
            position: absolute;
            bottom: 15px;
            right: 15px;
            width: 50px;
            height: 50px;
            background: var(--gradient-gold);
            color: var(--espresso);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.4);
            z-index: 10;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .floating-map-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(201, 162, 39, 0.5);
            color: var(--espresso);
        }

        /* Dark mode adjustments */
        [data-theme="dark"] .map-card {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(201, 162, 39, 0.3);
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .footer-map-section {
                padding: 2rem 0 1.5rem;
            }

            .compact-map-container {
                height: 200px;
            }

            .map-header-compact {
                padding: 1rem;
            }

            .map-header-compact h5 {
                font-size: 1rem;
            }

            .map-icon-badge {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }
        }
    </style>

    <!-- PWA Install Section (Hidden by default, shown via JS when installable) -->
    <div class="footer-install-section" id="footerInstallSection" style="display: none;">
        <div class="container">
            <div class="install-cta">
                <div class="install-content">
                    <i class="bi bi-phone-fill install-icon"></i>
                    <div class="install-text">
                        <strong>حمّل التطبيق مجاناً!</strong>
                        <span>تجربة أسرع وإشعارات فورية</span>
                    </div>
                </div>
                <button type="button" class="btn-install-app" id="footerInstallBtn">
                    <i class="bi bi-download"></i>
                    تثبيت التطبيق
                </button>
            </div>
        </div>
    </div>

    <!-- Copyright -->
    <div class="footer-bottom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">&copy; {{ date('Y') }} إمبابي كافيه. جميع الحقوق محفوظة</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <div class="payment-methods">
                        <img src="https://img.icons8.com/color/48/visa.png" alt="Visa" width="40">
                        <img src="https://img.icons8.com/color/48/mastercard.png" alt="Mastercard" width="40">
                        <img src="https://img.icons8.com/color/48/cash-in-hand.png" alt="Cash" width="40">
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
