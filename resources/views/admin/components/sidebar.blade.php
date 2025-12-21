<!-- Futuristic Admin Sidebar -->
<aside class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-header">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
            <img src="{{ asset('logo.jpg') }}" alt="إمبابي كافيه" class="sidebar-logo"
                style="height: 40px; width: auto; border-radius: 6px;">
            <span class="brand-text">إمبابي كافيه</span>
        </a>
    </div>

    <nav class="sidebar-nav">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                    href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>لوحة التحكم</span>
                </a>
            </li>

            <li class="nav-section">إدارة المتجر</li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}"
                    href="{{ route('admin.products.index') }}">
                    <i class="bi bi-box-seam"></i>
                    <span>المنتجات</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"
                    href="{{ route('admin.categories.index') }}">
                    <i class="bi bi-grid"></i>
                    <span>الأصناف</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"
                    href="{{ route('admin.orders.index') }}">
                    <i class="bi bi-receipt"></i>
                    <span>الطلبات</span>
                    @php $pendingCount = \App\Models\Order::pending()->count(); @endphp
                    @if ($pendingCount > 0)
                        <span class="badge bg-warning text-dark ms-auto">{{ $pendingCount }}</span>
                    @endif
                </a>
            </li>

            <li class="nav-section">التسويق</li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}"
                    href="{{ route('admin.coupons.index') }}">
                    <i class="bi bi-ticket-perforated"></i>
                    <span>الكوبونات</span>
                </a>
            </li>

            <li class="nav-section">إدارة المستخدمين</li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                    href="{{ route('admin.users.index') }}">
                    <i class="bi bi-people"></i>
                    <span>العملاء</span>
                </a>
            </li>
        </ul>
    </nav>

    <div class="sidebar-footer">
        <a href="{{ route('home') }}" target="_blank" class="btn btn-outline-light btn-sm w-100">
            <i class="bi bi-globe me-2"></i>زيارة الموقع
        </a>
    </div>
</aside>
