<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة التحكم') - إمبابي كافيه</title>

    <!-- Google Fonts - Cairo -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Bootstrap 5 RTL -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">

    <!-- Admin CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    @stack('styles')
</head>

<body class="admin-body">
    <div class="admin-wrapper">
        <!-- Sidebar -->
        @include('admin.components.sidebar')

        <!-- Main Content -->
        <div class="admin-main">
            <!-- Top Navbar -->
            <nav class="admin-topbar">
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-icon sidebar-toggle" id="sidebarToggle">
                        <i class="bi bi-list"></i>
                    </button>
                    <div class="search-box d-none d-md-flex">
                        <i class="bi bi-search"></i>
                        <input type="text" placeholder="بحث...">
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <!-- Notifications -->
                    <div class="dropdown">
                        <button class="btn btn-icon position-relative" data-bs-toggle="dropdown">
                            <i class="bi bi-bell"></i>
                            <span class="notification-dot"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-start p-0" style="min-width: 300px;">
                            <div class="p-3 border-bottom">
                                <strong>الإشعارات</strong>
                            </div>
                            <div class="p-3 text-center text-muted">
                                لا توجد إشعارات جديدة
                            </div>
                        </div>
                    </div>

                    <!-- User Menu -->
                    <div class="dropdown">
                        <button class="btn user-menu-btn" data-bs-toggle="dropdown">
                            <div class="user-avatar">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
                            <i class="bi bi-chevron-down"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-start">
                            <li>
                                <a class="dropdown-item" href="{{ route('home') }}" target="_blank">
                                    <i class="bi bi-globe me-2"></i>زيارة الموقع
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>تسجيل الخروج
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <div class="admin-content">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="bi bi-x-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Admin JS -->
    <script src="{{ asset('js/admin.js') }}"></script>

    @stack('scripts')
</body>

</html>
