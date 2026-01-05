<!-- Premium Sticky Navbar -->
<nav class="navbar navbar-expand-lg fixed-top navbar-main" id="mainNavbar">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand" href="{{ route('home') }}">
            <img src="{{ asset('logo.jpg') }}" alt="إمبابي كافيه" class="navbar-logo"
                style="height: 45px; width: auto; border-radius: 8px;">
            <span class="brand-text">إمبابي كافيه</span>
        </a>

        <!-- Mobile Toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Nav Links -->
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        الرئيسية
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('shop.*') ? 'active' : '' }}"
                        href="{{ route('shop.index') }}">
                        المتجر
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">
                        من نحن
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}"
                        href="{{ route('contact') }}">
                        تواصل معنا
                    </a>
                </li>
            </ul>

            <!-- Right Side -->
            <div class="navbar-actions d-flex align-items-center gap-3">


                <!-- Search -->
                <button class="btn btn-icon" data-bs-toggle="modal" data-bs-target="#searchModal">
                    <i class="bi bi-search"></i>
                </button>

                <!-- Cart -->
                <button class="btn btn-icon cart-toggle position-relative" id="cartToggle">
                    <svg class="cart-icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                        width="24px" fill="currentColor">
                        <path
                            d="M280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM246-720l96 200h280l110-200H246Zm-38-80h590q23 0 35 20.5t1 41.5L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68-39.5t-2-78.5l54-98-144-304H40v-80h130l38 80Zm134 280h280-280Z" />
                    </svg>
                    <span class="cart-badge" id="cartBadge">0</span>
                </button>

                <!-- User -->
                @auth
                    <div class="dropdown user-dropdown">
                        <button class="btn btn-icon dropdown-toggle user-icon-btn" data-bs-toggle="dropdown"
                            aria-label="User menu">
                            <i class="bi bi-person-circle"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end user-dropdown-menu shadow-lg">
                            @if (auth()->user()->isAdmin())
                                <li>
                                    <a class="dropdown-item premium-item" href="{{ route('admin.dashboard') }}">
                                        <div class="item-icon admin-icon">
                                            <i class="bi bi-speedometer2"></i>
                                        </div>
                                        <span>لوحة التحكم</span>
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                            @endif
                            <li>
                                <a class="dropdown-item premium-item" href="{{ route('orders.my-orders') }}">
                                    <div class="item-icon orders-icon">
                                        <i class="bi bi-bag-check"></i>
                                    </div>
                                    <span>طلباتي</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item premium-item" href="{{ route('wishlist.index') }}">
                                    <div class="item-icon wishlist-icon">
                                        <i class="bi bi-heart"></i>
                                    </div>
                                    <span>المفضلة</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item premium-item" href="{{ route('orders.track') }}">
                                    <div class="item-icon track-icon">
                                        <i class="bi bi-geo-alt"></i>
                                    </div>
                                    <span>تتبع طلب</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item premium-item" href="{{ route('loyalty.index') }}">
                                    <div class="item-icon points-icon">
                                        <i class="bi bi-award"></i>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between flex-grow-1">
                                        <span>نقاطي</span>
                                        @if (auth()->user()->loyaltyPoints)
                                            <span
                                                class="points-badge">{{ number_format(auth()->user()->loyaltyPoints->available_points) }}</span>
                                        @endif
                                    </div>
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item premium-item logout-item">
                                        <div class="item-icon logout-icon">
                                            <i class="bi bi-box-arrow-right"></i>
                                        </div>
                                        <span>تسجيل الخروج</span>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-golden">
                        تسجيل الدخول
                    </a>
                @endauth
                <!-- Theme Toggle -->
                <button class="btn btn-icon theme-toggle-navbar" id="themeToggleNavbar" aria-label="Toggle dark mode">
                    <i class="bi bi-sun-fill sun-icon"></i>
                    <i class="bi bi-moon-fill moon-icon"></i>
                </button>
            </div>
        </div>
    </div>
</nav>

<!-- Search Modal -->
<div class="modal fade" id="searchModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content glass-card">
            <div class="modal-body p-4">
                <form action="{{ route('shop.index') }}" method="GET">
                    <div class="input-group input-group-lg">
                        <input type="text" name="search" class="form-control"
                            placeholder="ابحث عن قهوتك المفضلة..." autofocus>
                        <button type="submit" class="btn btn-golden">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
