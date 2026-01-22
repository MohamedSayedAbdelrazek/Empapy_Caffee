<!-- Premium Footer -->
<footer class="footer-main">
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
                        <a href="#" class="social-link"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="social-link"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="social-link"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="social-link"><i class="bi bi-whatsapp"></i></a>
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
                            <span>القاهرة، مصر</span>
                        </li>
                        <li>
                            <i class="bi bi-telephone-fill"></i>
                            <span dir="ltr">+20 100 123 4567</span>
                        </li>
                        <li>
                            <i class="bi bi-envelope-fill"></i>
                            <span>info@empapy.com</span>
                        </li>
                        <li>
                            <i class="bi bi-clock-fill"></i>
                            <span>السبت - الخميس: 9 ص - 11 م</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

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
