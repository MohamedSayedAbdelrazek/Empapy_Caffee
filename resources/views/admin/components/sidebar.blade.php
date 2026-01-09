<!-- Futuristic Admin Sidebar -->
<aside class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-header">
        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('admin.orders.index') }}"
            class="sidebar-brand">
            <img src="{{ asset('logo.jpg') }}" alt="إمبابي كافيه" class="sidebar-logo"
                style="height: 40px; width: auto; border-radius: 6px;">
            <span class="brand-text">إمبابي كافيه</span>
        </a>
    </div>

    <nav class="sidebar-nav">
        <ul class="nav flex-column">
            {{-- Dashboard - Admin Only --}}
            @if (auth()->user()->isAdmin())
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
            @endif

            {{-- Orders Section - All Staff --}}
            <li class="nav-section">الطلبات</li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.orders.index') ? 'active' : '' }}"
                    href="{{ route('admin.orders.index') }}">
                    <i class="bi bi-receipt"></i>
                    <span>الطلبات</span>
                    @php $pendingCount = \App\Models\Order::pending()->count(); @endphp
                    @if ($pendingCount > 0)
                        <span class="badge bg-warning text-dark ms-auto">{{ $pendingCount }}</span>
                    @endif
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.orders.kanban') ? 'active' : '' }}"
                    href="{{ route('admin.orders.kanban') }}">
                    <i class="bi bi-kanban"></i>
                    <span>لوحة الطلبات</span>
                    <span class="badge bg-primary ms-auto" style="font-size: 0.65rem; padding: 3px 6px;">NEW</span>
                </a>
            </li>

            {{-- Notifications - All Staff --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}"
                    href="{{ route('admin.notifications.index') }}">
                    <i class="bi bi-bell-fill"></i>
                    <span>الإشعارات</span>
                    @php $unreadCount = \App\Models\AdminNotification::unread()->count(); @endphp
                    @if ($unreadCount > 0)
                        <span class="badge bg-danger ms-auto">{{ $unreadCount }}</span>
                    @endif
                </a>
            </li>

            {{-- Marketing & Management - Admin Only --}}
            @if (auth()->user()->isAdmin())
                <li class="nav-section">التسويق</li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}"
                        href="{{ route('admin.coupons.index') }}">
                        <i class="bi bi-ticket-perforated"></i>
                        <span>الكوبونات</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.loyalty.*') ? 'active' : '' }}"
                        href="{{ route('admin.loyalty.dashboard') }}">
                        <i class="bi bi-award"></i>
                        <span>نظام الولاء</span>
                        <span class="badge bg-success ms-auto" style="font-size: 0.65rem; padding: 3px 6px;">NEW</span>
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

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}"
                        href="{{ route('admin.staff.index') }}">
                        <i class="bi bi-person-badge"></i>
                        <span>إدارة الفريق</span>
                    </a>
                </li>
            @endif
        </ul>
    </nav>

    <div class="sidebar-footer">
        <!-- Admin Profile Section -->
        <a href="{{ route('admin.profile.index') }}"
            class="admin-profile-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
            <div class="admin-avatar">
                @if (auth()->user()->avatar)
                    <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}">
                @else
                    <i class="bi bi-person-fill"></i>
                @endif
            </div>
            <div class="admin-info">
                <span class="admin-name">{{ auth()->user()->name }}</span>
                <span class="admin-role">
                    @if (auth()->user()->isAdmin())
                        مدير النظام
                    @else
                        كاشير
                    @endif
                </span>
            </div>
            <i class="bi bi-gear profile-settings-icon"></i>
        </a>

        <div class="footer-actions">
            <a href="{{ route('home') }}" target="_blank" class="btn btn-outline-light btn-sm flex-grow-1">
                <i class="bi bi-globe me-1"></i>الموقع
            </a>
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-box-arrow-left"></i>
                </button>
            </form>
        </div>
    </div>
</aside>
