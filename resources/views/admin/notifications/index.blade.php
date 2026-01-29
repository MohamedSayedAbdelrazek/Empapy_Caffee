@extends('admin.layouts.app')

@section('title', 'جميع الإشعارات')

@section('content')
    <div class="page-header-admin">
        <h1 class="page-title-admin"><i class="bi bi-bell-fill me-2"></i>جميع الإشعارات</h1>
        <p class="page-subtitle-admin">عرض جميع الإشعارات والتنبيهات</p>
    </div>

    <div class="admin-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-primary fs-6">{{ $notifications->where('is_read', false)->count() }} غير مقروءة</span>
                <span class="text-muted">إجمالي: {{ $notifications->count() }} إشعار</span>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-light btn-sm" onclick="markAllAsRead()">
                    <i class="bi bi-check-all me-1"></i>تحديد الكل كمقروء
                </button>
                <button class="btn btn-outline-danger btn-sm" onclick="clearAllRead()">
                    <i class="bi bi-trash me-1"></i>حذف المقروءة
                </button>
            </div>
        </div>

        @if ($notifications->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-bell-slash display-1 text-muted opacity-25"></i>
                <h5 class="mt-3 text-muted">لا توجد إشعارات</h5>
                <p class="text-muted">ستظهر هنا الإشعارات عند وصول طلبات جديدة أو تنبيهات أخرى</p>
            </div>
        @else
            <div class="notification-list-page">
                @foreach ($notifications as $notification)
                    <div class="notification-item-page {{ $notification->is_read ? '' : 'unread' }}"
                        data-id="{{ $notification->id }}">
                        <div class="notification-icon-page {{ $notification->icon_color }}">
                            <i class="{{ $notification->icon }}"></i>
                        </div>
                        <div class="notification-content-page">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="notification-title-page mb-1">{{ $notification->title }}</h6>
                                    <p class="notification-message-page mb-1">{{ $notification->message }}</p>
                                    <small class="notification-time-page">
                                        <i class="bi bi-clock me-1"></i>{{ $notification->created_at->diffForHumans() }}
                                        <span class="badge bg-secondary ms-2">{{ $notification->type_label }}</span>
                                    </small>
                                </div>
                                <div class="d-flex gap-2">
                                    @if ($notification->action_url)
                                        <a href="{{ $notification->action_url }}" class="btn btn-sm btn-primary"
                                            onclick="markAsRead({{ $notification->id }})">
                                            <i class="bi bi-eye me-1"></i>عرض
                                        </a>
                                    @endif
                                    @if (!$notification->is_read)
                                        <button class="btn btn-sm btn-outline-success"
                                            onclick="markAsReadOnly({{ $notification->id }}, this)">
                                            <i class="bi bi-check"></i>
                                        </button>
                                    @endif
                                    <button class="btn btn-sm btn-outline-danger"
                                        onclick="deleteNotification({{ $notification->id }}, this)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection

@push('styles')
    <style>
        .notification-list-page {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .notification-item-page {
            padding: 16px 20px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            display: flex;
            gap: 16px;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .notification-item-page:hover {
            background: rgba(201, 162, 39, 0.05);
            border-color: rgba(201, 162, 39, 0.2);
        }

        .notification-item-page.unread {
            background: rgba(201, 162, 39, 0.08);
            border-right: 4px solid #c9a227;
        }

        .notification-icon-page {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }

        .notification-icon-page.success {
            background: rgba(16, 185, 129, 0.15);
            color: #10b981;
        }

        .notification-icon-page.warning {
            background: rgba(245, 158, 11, 0.15);
            color: #f59e0b;
        }

        .notification-icon-page.info {
            background: rgba(59, 130, 246, 0.15);
            color: #3b82f6;
        }

        .notification-icon-page.danger {
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
        }

        .notification-icon-page.primary {
            background: rgba(201, 162, 39, 0.15);
            color: #c9a227;
        }

        .notification-content-page {
            flex: 1;
        }

        .notification-title-page {
            font-weight: 600;
            font-size: 15px;
            color: #fff;
        }

        .notification-message-page {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.7);
        }

        .notification-time-page {
            font-size: 12px;
            color: rgba(201, 162, 39, 0.8);
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Mark Single items as Read (No Reload needed)
        async function markAsReadOnly(id, btn) {
            try {
                const response = await fetch(`/admin/notifications/${id}/read`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (response.ok) {
                    const item = btn.closest('.notification-item-page');
                    item.classList.remove('unread');
                    btn.remove();

                    NotificationSystem.showToast({
                        title: 'تم ✓',
                        message: 'تم تحديد الإشعار كمقروء',
                        type: 'info'
                    });
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }

        // Mark ALL as Read (Reloads Page)
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
                    window.location.reload();
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }

        // Delete Single Notification
        async function deleteNotification(id, btn) {
            if (!confirm('هل أنت متأكد من حذف هذا الإشعار؟')) return;

            try {
                const response = await fetch(`/admin/notifications/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (response.ok) {
                    const item = btn.closest('.notification-item-page');
                    item.style.animation = 'fadeOut 0.3s ease forwards';
                    setTimeout(() => item.remove(), 300);

                    NotificationSystem.showToast({
                        title: 'تم الحذف ✓',
                        message: 'تم حذف الإشعار بنجاح',
                        type: 'info'
                    });
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }

        // Clear All Read Notifications (Reloads Page)
        async function clearAllRead() {
            if (!confirm('هل أنت متأكد من حذف جميع الإشعارات المقروءة؟')) return;

            try {
                const response = await fetch('/admin/notifications/clear-all', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (response.ok) {
                    window.location.reload();
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }
    </script>
@endpush
