<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة التحكم') - إمبابي كافيه</title>

    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="{{ asset('logo.jpg') }}">
    <link rel="apple-touch-icon" href="{{ asset('logo.jpg') }}">

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
    <link rel="stylesheet" href="{{ asset_version('css/admin.css') }}">

    <!-- Notification Styles -->
    <style>
        /* Notification Bell */
        .notification-bell {
            position: relative;
            cursor: pointer;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            min-width: 20px;
            height: 20px;
            padding: 0 6px;
            font-size: 11px;
            font-weight: 700;
            line-height: 20px;
            text-align: center;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            border-radius: 10px;
            animation: pulse-badge 2s infinite;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.4);
        }

        @keyframes pulse-badge {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }
        }

        /* Notification Dropdown */
        .notification-dropdown {
            width: min(380px, calc(100vw - 24px));
            max-height: 500px;
            overflow: hidden;
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            background: linear-gradient(145deg, #1e1e2e, #252536);
        }

        .notification-header {
            padding: 16px 20px;
            background: linear-gradient(135deg, rgba(201, 162, 39, 0.1), rgba(201, 162, 39, 0.05));
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .notification-header h6 {
            margin: 0;
            font-weight: 700;
            color: #fff;
        }

        .notification-header .badge {
            background: linear-gradient(135deg, #c9a227, #b8941f);
            font-size: 11px;
        }

        .notification-list {
            max-height: 350px;
            overflow-y: auto;
        }

        .notification-list::-webkit-scrollbar {
            width: 6px;
        }

        .notification-list::-webkit-scrollbar-thumb {
            background: rgba(201, 162, 39, 0.3);
            border-radius: 3px;
        }

        .notification-item {
            padding: 14px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            gap: 14px;
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
        }

        .notification-item:hover {
            background: rgba(201, 162, 39, 0.1);
        }

        .notification-item.unread {
            background: rgba(201, 162, 39, 0.05);
            border-right: 3px solid #c9a227;
        }

        .notification-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .notification-icon.success {
            background: rgba(16, 185, 129, 0.15);
            color: #10b981;
        }

        .notification-icon.warning {
            background: rgba(245, 158, 11, 0.15);
            color: #f59e0b;
        }

        .notification-icon.info {
            background: rgba(59, 130, 246, 0.15);
            color: #3b82f6;
        }

        .notification-icon.danger {
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
        }

        .notification-icon.primary {
            background: rgba(201, 162, 39, 0.15);
            color: #c9a227;
        }

        .notification-content {
            flex: 1;
            min-width: 0;
        }

        .notification-title {
            font-weight: 600;
            font-size: 14px;
            color: #fff;
            margin-bottom: 4px;
        }

        .notification-message {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 4px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .notification-time {
            font-size: 11px;
            color: rgba(201, 162, 39, 0.8);
        }

        .notification-footer {
            padding: 12px 20px;
            background: rgba(0, 0, 0, 0.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .notification-footer a,
        .notification-footer button {
            font-size: 13px;
            color: #c9a227;
            text-decoration: none;
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
            transition: color 0.3s;
        }

        .notification-footer a:hover,
        .notification-footer button:hover {
            color: #d4af37;
        }

        .notification-empty {
            padding: 40px 20px;
            text-align: center;
            color: rgba(255, 255, 255, 0.5);
        }

        .notification-empty i {
            font-size: 48px;
            margin-bottom: 12px;
            opacity: 0.3;
        }

        /* Toast Notification */
        .toast-container {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .toast-notification {
            min-width: 350px;
            max-width: 420px;
            padding: 16px 20px;
            background: linear-gradient(145deg, #1e1e2e, #252536);
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.4);
            border-right: 4px solid #10b981;
            display: flex;
            gap: 14px;
            animation: slideIn 0.4s ease, fadeOut 0.4s ease 4.6s forwards;
            cursor: pointer;
        }

        .toast-notification.order {
            border-right-color: #10b981;
        }

        .toast-notification.warning {
            border-right-color: #f59e0b;
        }

        .toast-notification.info {
            border-right-color: #3b82f6;
        }

        @keyframes slideIn {
            from {
                transform: translateX(-100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes fadeOut {
            to {
                opacity: 0;
                transform: translateX(-20px);
            }
        }

        .toast-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            background: rgba(16, 185, 129, 0.15);
            color: #10b981;
        }

        .toast-content {
            flex: 1;
        }

        .toast-title {
            font-weight: 700;
            font-size: 15px;
            color: #fff;
            margin-bottom: 4px;
        }

        .toast-message {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.7);
        }

        .toast-close {
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.5);
            font-size: 18px;
            cursor: pointer;
            padding: 0;
            transition: color 0.3s;
        }

        .toast-close:hover {
            color: #fff;
        }

        /* Sound Toggle Button */
        .sound-toggle {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            border: none;
            background: rgba(255, 255, 255, 0.1);
            color: #c9a227;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sound-toggle:hover {
            background: rgba(201, 162, 39, 0.2);
        }

        .sound-toggle.muted {
            color: rgba(255, 255, 255, 0.4);
        }
    </style>

    @stack('styles')

<script>
    {{-- نستخدم config() وليس env() حتى يعمل المفتاح بعد تشغيل config:cache في الإنتاج --}}
    window.firebaseVapidKey = "{{ config('firebase.fcm.vapid_key') }}";
</script>

</head>

<body class="admin-body">
    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Sidebar Backdrop (Mobile) -->
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

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
                    <!-- Sound Toggle -->
                    <button class="sound-toggle" id="soundToggle" title="تشغيل/إيقاف الصوت">
                        <i class="bi bi-volume-up-fill"></i>
                    </button>

                    <!-- PWA Install Button (Hidden by default, shown via pwa.js) -->
                    <button class="btn btn-icon navbar-install-btn" id="adminNavbarInstallBtn" style="display: none;"
                        title="تثبيت التطبيق">
                        <i class="bi bi-download"></i>
                    </button>

                    <!-- Notification Dropdown -->
                    <div class="dropdown">
                        <button class="btn btn-icon notification-bell position-relative" data-bs-toggle="dropdown"
                            id="notificationBell">
                            <i class="bi bi-bell-fill"></i>
                            <span class="notification-badge" id="notificationBadge" style="display: none;">0</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-start notification-dropdown p-0">
                            <div class="notification-header">
                                <h6><i class="bi bi-bell-fill me-2"></i>الإشعارات</h6>
                                <span class="badge" id="notificationCount">0 جديد</span>
                            </div>
                            <div class="notification-list" id="notificationList">
                                <div class="notification-empty">
                                    <i class="bi bi-bell-slash d-block"></i>
                                    <p>لا توجد إشعارات جديدة</p>
                                </div>
                            </div>
                            <div class="notification-footer">
                                <a href="{{ route('admin.notifications.index') }}">عرض الكل</a>
                                <button onclick="markAllAsRead()">تحديد الكل كمقروء</button>
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
                {{-- Fancy Welcome Overlay --}}
                @if (session('welcome'))
                    <div class="welcome-overlay" id="welcomeOverlay">
                        <div class="welcome-content">
                            <div class="welcome-avatar">
                                @if (auth()->user()->avatar)
                                    <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="Avatar">
                                @else
                                    <i class="bi bi-person-fill"></i>
                                @endif
                            </div>
                            <h1 class="welcome-title">{{ session('welcome') }}</h1>
                            <p class="welcome-subtitle">نتمنى لك يوماً موفقاً ☕</p>
                            <div class="welcome-confetti"></div>
                        </div>
                    </div>
                    <style>
                        .welcome-overlay {
                            position: fixed;
                            top: 0;
                            left: 0;
                            right: 0;
                            bottom: 0;
                            background: linear-gradient(135deg, rgba(26, 26, 46, 0.98) 0%, rgba(15, 52, 96, 0.98) 100%);
                            z-index: 9999;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            animation: welcomeFadeIn 0.5s ease-out;
                        }

                        @keyframes welcomeFadeIn {
                            from {
                                opacity: 0;
                            }

                            to {
                                opacity: 1;
                            }
                        }

                        .welcome-content {
                            text-align: center;
                            animation: welcomeSlideUp 0.6s ease-out 0.2s both;
                        }

                        @keyframes welcomeSlideUp {
                            from {
                                opacity: 0;
                                transform: translateY(30px);
                            }

                            to {
                                opacity: 1;
                                transform: translateY(0);
                            }
                        }

                        .welcome-avatar {
                            width: 120px;
                            height: 120px;
                            border-radius: 50%;
                            border: 4px solid #c9a227;
                            margin: 0 auto 25px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            background: linear-gradient(135deg, #2d2d44 0%, #1a1a2e 100%);
                            overflow: hidden;
                            box-shadow: 0 0 40px rgba(201, 162, 39, 0.3);
                            animation: avatarPulse 2s ease-in-out infinite;
                        }

                        @keyframes avatarPulse {

                            0%,
                            100% {
                                box-shadow: 0 0 40px rgba(201, 162, 39, 0.3);
                            }

                            50% {
                                box-shadow: 0 0 60px rgba(201, 162, 39, 0.5);
                            }
                        }

                        .welcome-avatar img {
                            width: 100%;
                            height: 100%;
                            object-fit: cover;
                        }

                        .welcome-avatar i {
                            font-size: 60px;
                            color: #c9a227;
                        }

                        .welcome-title {
                            font-size: 2.5rem;
                            font-weight: 700;
                            color: #fff;
                            margin-bottom: 10px;
                            text-shadow: 0 0 30px rgba(201, 162, 39, 0.3);
                        }

                        .welcome-subtitle {
                            font-size: 1.2rem;
                            color: rgba(255, 255, 255, 0.7);
                        }

                        .welcome-overlay.fade-out {
                            animation: welcomeFadeOut 0.5s ease-out forwards;
                        }

                        @keyframes welcomeFadeOut {
                            from {
                                opacity: 1;
                            }

                            to {
                                opacity: 0;
                                pointer-events: none;
                            }
                        }

                        /* Mobile responsive for welcome overlay */
                        @media (max-width: 768px) {
                            .welcome-overlay {
                                overflow: hidden;
                            }

                            .welcome-content {
                                padding: 20px;
                                max-width: 100vw;
                            }

                            .welcome-avatar {
                                width: 80px;
                                height: 80px;
                            }

                            .welcome-avatar i {
                                font-size: 40px;
                            }

                            .welcome-title {
                                font-size: 1.5rem;
                            }

                            .welcome-subtitle {
                                font-size: 1rem;
                            }
                        }
                    </style>
                    <script>
                        setTimeout(function() {
                            const overlay = document.getElementById('welcomeOverlay');
                            if (overlay) {
                                overlay.classList.add('fade-out');
                                setTimeout(() => overlay.remove(), 500);
                            }
                        }, 4000);
                    </script>
                @endif

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

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>يوجد أخطاء في البيانات المدخلة:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <!-- Notification Sounds 🔊 -->
    <!-- Cash Register Sound for New Orders 💰 -->
    <audio id="soundNewOrder" preload="auto">
        <source src="https://assets.mixkit.co/active_storage/sfx/2058/2058-preview.mp3" type="audio/mpeg">
    </audio>

    <!-- Welcome Chime for New Customers 👤 -->
    <audio id="soundNewCustomer" preload="auto">
        <source src="https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3" type="audio/mpeg">
    </audio>

    <!-- Star/Success Sound for New Reviews ⭐ -->
    <audio id="soundNewReview" preload="auto">
        <source src="https://assets.mixkit.co/active_storage/sfx/2018/2018-preview.mp3" type="audio/mpeg">
    </audio>

    <!-- Alert Sound for Low Stock ⚠️ -->
    <audio id="soundLowStock" preload="auto">
        <source src="https://assets.mixkit.co/active_storage/sfx/2570/2570-preview.mp3" type="audio/mpeg">
    </audio>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Admin JS -->
    <script src="{{ asset_version('js/admin.js') }}"></script>

    <!-- Firebase Push Notifications -->
    <link rel="stylesheet" href="{{ asset_version('css/firebase-notifications.css') }}">
    <script src="{{ asset_version('js/firebase-notifications.js') }}"></script>

    <!-- PWA Install -->
    <link rel="stylesheet" href="{{ asset_version('css/pwa-install.css') }}">
    <script src="{{ asset_version('js/pwa.js') }}"></script>

    <!-- Notification System JS -->
    <script>
        // Notification Configuration
        // Notification Configuration (FCM-based)
        const NotificationSystem = {
            soundEnabled: localStorage.getItem('notificationSound') !== 'muted',

            // Different sounds for different notification types 🔊
            sounds: {
                new_order: document.getElementById('soundNewOrder'), // 💰 Cash register
                new_customer: document.getElementById('soundNewCustomer'), // 👤 Welcome chime  
                new_review: document.getElementById('soundNewReview'), // ⭐ Success sound
                low_stock: document.getElementById('soundLowStock'), // ⚠️ Alert sound
                default: document.getElementById('soundNewOrder') // Default fallback
            },

            init() {
                this.updateSoundButton();
                this.bindEvents();
            },



            bindEvents() {
                // Sound toggle
                document.getElementById('soundToggle').addEventListener('click', () => {
                    this.toggleSound();
                });

                // Click outside to close dropdown
                document.addEventListener('click', (e) => {
                    if (!e.target.closest('.notification-dropdown') && !e.target.closest('#notificationBell')) {
                        // Dropdown closes automatically with Bootstrap
                    }
                });
            },

            toggleSound() {
                this.soundEnabled = !this.soundEnabled;
                localStorage.setItem('notificationSound', this.soundEnabled ? 'enabled' : 'muted');
                this.updateSoundButton();

                if (this.soundEnabled) {
                    this.showToast({
                        title: 'تم تفعيل الصوت 🔊',
                        message: 'ستسمع أصوات مختلفة حسب نوع الإشعار!',
                        type: 'info'
                    });
                    // Play a sample sound
                    this.playSound('new_order');
                }
            },

            updateSoundButton() {
                const btn = document.getElementById('soundToggle');
                const icon = btn.querySelector('i');

                if (this.soundEnabled) {
                    btn.classList.remove('muted');
                    icon.className = 'bi bi-volume-up-fill';
                } else {
                    btn.classList.add('muted');
                    icon.className = 'bi bi-volume-mute-fill';
                }
            },

            // Play sound based on notification type
            playSound(notificationType = 'default') {
                if (!this.soundEnabled) return;

                const sound = this.sounds[notificationType] || this.sounds.default;
                if (sound) {
                    sound.currentTime = 0;
                    sound.play().catch(e => console.log('Audio play failed:', e));
                }
            },


            // Update notification UI (called by FCM handler)
            updateNotificationUI(data) {
                const badge = document.getElementById('notificationBadge');
                const countSpan = document.getElementById('notificationCount');
                const list = document.getElementById('notificationList');

                // Update badge
                if (data.unread_count > 0) {
                    badge.style.display = 'block';
                    badge.textContent = data.unread_count > 99 ? '99+' : data.unread_count;
                    countSpan.textContent = `${data.unread_count} جديد`;
                } else {
                    badge.style.display = 'none';
                    countSpan.textContent = 'لا يوجد جديد';
                }

                // Build notification list (add new at top, don't duplicate)
                if (data.notifications && data.notifications.length > 0) {
                    const existingItems = list.querySelectorAll('.notification-item');
                    const existingIds = new Set();
                    existingItems.forEach(item => existingIds.add(item.dataset.id));

                    let html = '';
                    data.notifications.forEach(n => {
                        if (!existingIds.has(String(n.id))) {
                            html += this.renderNotificationItem(n);
                        }
                    });

                    if (html) {
                        if (list.querySelector('.notification-empty')) {
                            list.innerHTML = html;
                        } else {
                            list.insertAdjacentHTML('afterbegin', html);
                        }
                    }
                } else if (!list.querySelector('.notification-item')) {
                    // Show empty state only if no notifications at all
                    list.innerHTML = `
                        <div class="notification-empty">
                            <i class="bi bi-bell-slash d-block"></i>
                            <p>لا توجد إشعارات جديدة</p>
                        </div>
                    `;
                }
            },

            renderNotificationItem(notification) {
                const iconColorClass = notification.icon_color || 'primary';
                const unreadClass = notification.is_read ? '' : 'unread';

                return `
                    <a href="${notification.action_url || '#'}" 
                       class="notification-item ${unreadClass}" 
                       data-id="${notification.id}"
                       onclick="markAsRead(${notification.id})">
                        <div class="notification-icon ${iconColorClass}">
                            <i class="${notification.icon}"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">${notification.title}</div>
                            <div class="notification-message">${notification.message}</div>
                            <div class="notification-time">${notification.time_ago}</div>
                        </div>
                    </a>
                `;
            },

            showToast(options) {
                const container = document.getElementById('toastContainer');
                const toast = document.createElement('div');
                toast.className = `toast-notification ${options.type || 'order'}`;

                const iconClass = options.type === 'order' ? 'bi-cart-check-fill' :
                    options.type === 'warning' ? 'bi-exclamation-triangle-fill' :
                    'bi-info-circle-fill';

                const iconBg = options.type === 'order' ? 'rgba(16, 185, 129, 0.15)' :
                    options.type === 'warning' ? 'rgba(245, 158, 11, 0.15)' :
                    'rgba(59, 130, 246, 0.15)';

                const iconColor = options.type === 'order' ? '#10b981' :
                    options.type === 'warning' ? '#f59e0b' :
                    '#3b82f6';

                toast.innerHTML = `
                    <div class="toast-icon" style="background: ${iconBg}; color: ${iconColor};">
                        <i class="bi ${iconClass}"></i>
                    </div>
                    <div class="toast-content">
                        <div class="toast-title">${options.title}</div>
                        <div class="toast-message">${options.message}</div>
                    </div>
                    <button class="toast-close" onclick="this.parentElement.remove()">
                        <i class="bi bi-x"></i>
                    </button>
                `;

                if (options.url) {
                    toast.style.cursor = 'pointer';
                    toast.addEventListener('click', (e) => {
                        if (!e.target.closest('.toast-close')) {
                            window.location.href = options.url;
                        }
                    });
                }

                container.appendChild(toast);

                // Auto remove after 5 seconds
                setTimeout(() => {
                    if (toast.parentElement) {
                        toast.remove();
                    }
                }, 5000);
            },


        };

        // Initialize when DOM is ready
        document.addEventListener('DOMContentLoaded', () => {
            NotificationSystem.init();

            // Listen for Firebase foreground messages and update dashboard UI
            // Define global handler that firebase-notifications.js will call
            window.handleFirebaseMessage = function(payload) {
                const {
                    notification,
                    data
                } = payload;

                // Play sound
                NotificationSystem.playSound(data?.type || 'new_order');

                // Show toast in admin dashboard
                NotificationSystem.showToast({
                    title: notification?.title || 'إشعار جديد',
                    message: notification?.body || '',
                    type: data?.type === 'new_order' ? 'order' : 'info'
                });

                // Update badge count (increment by 1)
                const badge = document.getElementById('notificationBadge');
                const countSpan = document.getElementById('notificationCount');
                if (badge) {
                    let currentCount = parseInt(badge.textContent) || 0;
                    currentCount++;
                    badge.style.display = 'block';
                    badge.textContent = currentCount > 99 ? '99+' : currentCount;
                    if (countSpan) {
                        countSpan.textContent = `${currentCount} جديد`;
                    }
                }

                // Add notification to the list
                const list = document.getElementById('notificationList');
                if (list) {
                    // Remove empty state if exists
                    const emptyState = list.querySelector('.notification-empty');
                    if (emptyState) {
                        emptyState.remove();
                    }

                    // Create notification item for dashboard
                    const typeIcons = {
                        'new_order': 'bi-cart-check-fill',
                        'order_status_change': 'bi-truck',
                        'new_contact': 'bi-envelope-fill',
                        'order_cancelled': 'bi-x-circle-fill',
                        'new_user': 'bi-person-plus-fill'
                    };
                    const typeColors = {
                        'new_order': 'success',
                        'order_status_change': 'info',
                        'new_contact': 'warning',
                        'order_cancelled': 'danger',
                        'new_user': 'primary'
                    };

                    const icon = typeIcons[data?.type] || 'bi-bell-fill';
                    const color = typeColors[data?.type] || 'primary';
                    const url = data?.url || '#';
                    const now = new Date();
                    const timeAgo = 'الآن';

                    const notificationHtml = `
                            <a href="${url}" 
                               class="notification-item unread" 
                               data-id="fcm-${Date.now()}"
                               style="animation: slideInRight 0.3s ease-out;">
                                <div class="notification-icon ${color}">
                                    <i class="${icon}"></i>
                                </div>
                                <div class="notification-content">
                                    <div class="notification-title">${notification?.title || 'إشعار'}</div>
                                    <div class="notification-message">${notification?.body || ''}</div>
                                    <div class="notification-time">${timeAgo}</div>
                                </div>
                            </a>
                        `;

                    list.insertAdjacentHTML('afterbegin', notificationHtml);
                }
            };
        });

        // Global functions for onclick handlers
        async function markAsRead(id) {
            try {
                await fetch(`/admin/notifications/${id}/read`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
            } catch (error) {
                console.error('Error marking as read:', error);
            }
        }

        async function markAllAsRead() {
            try {
                const response = await fetch('/admin/notifications/mark-all-read', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (response.ok) {
                    // Update UI
                    document.getElementById('notificationBadge').style.display = 'none';
                    document.getElementById('notificationCount').textContent = 'لا يوجد جديد';
                    document.querySelectorAll('.notification-item.unread').forEach(item => {
                        item.classList.remove('unread');
                    });

                    NotificationSystem.showToast({
                        title: 'تم بنجاح ✓',
                        message: 'تم تحديد جميع الإشعارات كمقروءة',
                        type: 'info'
                    });
                }
            } catch (error) {
                console.error('Error marking all as read:', error);
            }
        }
    </script>

    @stack('scripts')
</body>

</html>
