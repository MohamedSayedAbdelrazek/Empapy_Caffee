@extends('admin.layouts.app')

@section('title', 'الملف الشخصي')

@push('styles')
    <style>
        /* Profile Hero Section */
        .profile-hero {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            border-radius: 24px;
            padding: 40px;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }

        .profile-hero::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(201, 162, 39, 0.15) 0%, transparent 70%);
            border-radius: 50%;
            transform: translate(30%, -30%);
        }

        .profile-hero::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(201, 162, 39, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            transform: translate(-30%, 30%);
        }

        /* Avatar Container */
        .avatar-container {
            position: relative;
            width: 150px;
            height: 150px;
            margin: 0 auto 20px;
        }

        .avatar-wrapper {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 4px solid rgba(201, 162, 39, 0.5);
            overflow: hidden;
            position: relative;
            background: linear-gradient(135deg, #2d2d44 0%, #1a1a2e 100%);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3), 0 0 0 4px rgba(201, 162, 39, 0.2);
            transition: all 0.3s ease;
        }

        .avatar-wrapper:hover {
            transform: scale(1.05);
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.4), 0 0 0 6px rgba(201, 162, 39, 0.3);
        }

        .avatar-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avatar-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 60px;
            color: #c9a227;
            background: linear-gradient(135deg, #2d2d44 0%, #1a1a2e 100%);
        }

        .avatar-upload-btn {
            position: absolute;
            bottom: 5px;
            right: 5px;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: linear-gradient(135deg, #c9a227 0%, #e8c547 100%);
            border: 3px solid #1a1a2e;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            color: #1a1a2e;
            font-size: 18px;
        }

        .avatar-upload-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 5px 20px rgba(201, 162, 39, 0.4);
        }

        /* Profile Info */
        .profile-name {
            font-size: 2rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 8px;
        }

        .profile-role {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 20px;
            background: linear-gradient(135deg, rgba(201, 162, 39, 0.2) 0%, rgba(201, 162, 39, 0.1) 100%);
            border: 1px solid rgba(201, 162, 39, 0.3);
            border-radius: 50px;
            color: #c9a227;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .profile-meta {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-top: 25px;
            flex-wrap: wrap;
        }

        .profile-meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
        }

        .profile-meta-item i {
            color: #c9a227;
        }

        /* Stats Cards */
        .stat-card {
            background: linear-gradient(145deg, #1e1e2e, #252536);
            border-radius: 16px;
            padding: 25px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            border-color: rgba(201, 162, 39, 0.2);
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin: 0 auto 15px;
        }

        .stat-icon.orders {
            background: rgba(34, 197, 94, 0.15);
            color: #22c55e;
        }

        .stat-icon.products {
            background: rgba(59, 130, 246, 0.15);
            color: #3b82f6;
        }

        .stat-icon.customers {
            background: rgba(168, 85, 247, 0.15);
            color: #a855f7;
        }

        .stat-icon.notifications {
            background: rgba(245, 158, 11, 0.15);
            color: #f59e0b;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 5px;
        }

        .stat-label {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.85rem;
        }

        /* Settings Cards */
        .settings-card {
            background: linear-gradient(145deg, #1e1e2e, #252536);
            border-radius: 20px;
            padding: 30px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            margin-bottom: 25px;
        }

        .settings-card-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .settings-card-icon {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            background: linear-gradient(135deg, rgba(201, 162, 39, 0.2) 0%, rgba(201, 162, 39, 0.1) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #c9a227;
            font-size: 22px;
        }

        .settings-card-title {
            color: #fff;
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
        }

        .settings-card-subtitle {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.85rem;
            margin: 0;
        }

        /* Form Styling */
        .form-label {
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
            margin-bottom: 8px;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 14px 18px;
            color: #fff;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: #c9a227;
            box-shadow: 0 0 0 3px rgba(201, 162, 39, 0.15);
            color: #fff;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.3);
        }

        /* Buttons */
        .btn-gold {
            background: linear-gradient(135deg, #c9a227 0%, #e8c547 100%);
            border: none;
            color: #1a1a2e;
            font-weight: 600;
            padding: 14px 28px;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .btn-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(201, 162, 39, 0.3);
            color: #1a1a2e;
        }

        .btn-outline-gold {
            background: transparent;
            border: 2px solid rgba(201, 162, 39, 0.5);
            color: #c9a227;
            font-weight: 600;
            padding: 12px 26px;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .btn-outline-gold:hover {
            background: rgba(201, 162, 39, 0.1);
            border-color: #c9a227;
            color: #c9a227;
        }

        /* Avatar Upload Dropzone */
        .avatar-dropzone {
            border: 2px dashed rgba(201, 162, 39, 0.3);
            border-radius: 16px;
            padding: 40px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            background: rgba(201, 162, 39, 0.05);
        }

        .avatar-dropzone:hover,
        .avatar-dropzone.dragover {
            border-color: #c9a227;
            background: rgba(201, 162, 39, 0.1);
        }

        .avatar-dropzone .upload-icon {
            font-size: 48px;
            color: #c9a227;
            margin-bottom: 15px;
        }

        .avatar-preview {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin: 0 auto 15px;
            overflow: hidden;
            display: none;
        }

        .avatar-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avatar-preview.show {
            display: block;
        }

        /* Password Strength Indicator */
        .password-strength {
            height: 4px;
            border-radius: 2px;
            margin-top: 8px;
            background: rgba(255, 255, 255, 0.1);
            overflow: hidden;
        }

        .password-strength-bar {
            height: 100%;
            border-radius: 2px;
            transition: all 0.3s ease;
            width: 0;
        }

        .password-strength-bar.weak {
            width: 33%;
            background: #ef4444;
        }

        .password-strength-bar.medium {
            width: 66%;
            background: #f59e0b;
        }

        .password-strength-bar.strong {
            width: 100%;
            background: #22c55e;
        }

        /* Success Animation */
        @keyframes successPulse {
            0% {
                box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.4);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(34, 197, 94, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(34, 197, 94, 0);
            }
        }

        .success-pulse {
            animation: successPulse 1s ease-out;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-4">
        <!-- Profile Hero Section -->
        <div class="profile-hero text-center">
            <div class="avatar-container">
                <div class="avatar-wrapper" id="mainAvatar">
                    @if ($admin->avatar)
                        <img src="{{ Storage::url($admin->avatar) }}" alt="{{ $admin->name }}" id="currentAvatar">
                    @else
                        <div class="avatar-placeholder">
                            <i class="bi bi-person-fill"></i>
                        </div>
                    @endif
                </div>
                <label for="avatarInput" class="avatar-upload-btn" title="تغيير الصورة">
                    <i class="bi bi-camera-fill"></i>
                </label>
                <input type="file" id="avatarInput" accept="image/*" style="display: none;">
            </div>

            <h1 class="profile-name">{{ $admin->name }}</h1>
            <div class="profile-role">
                <i class="bi bi-shield-check"></i>
                مدير النظام
            </div>

            <div class="profile-meta">
                <div class="profile-meta-item">
                    <i class="bi bi-envelope"></i>
                    {{ $admin->email }}
                </div>
                @if ($admin->phone)
                    <div class="profile-meta-item">
                        <i class="bi bi-telephone"></i>
                        {{ $admin->phone }}
                    </div>
                @endif
                <div class="profile-meta-item">
                    <i class="bi bi-calendar-check"></i>
                    انضم {{ $admin->created_at->format('Y/m/d') }}
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Profile Settings -->
            <div class="col-lg-6">
                <div class="settings-card">
                    <div class="settings-card-header">
                        <div class="settings-card-icon">
                            <i class="bi bi-person-gear"></i>
                        </div>
                        <div>
                            <h5 class="settings-card-title">البيانات الشخصية</h5>
                            <p class="settings-card-subtitle">تعديل الاسم والبريد الإلكتروني</p>
                        </div>
                    </div>

                    <form action="{{ route('admin.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="form-label">الاسم الكامل</label>
                            <input type="text" name="name" class="form-control"
                                value="{{ old('name', $admin->name) }}" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">البريد الإلكتروني</label>
                            <input type="email" name="email" class="form-control"
                                value="{{ old('email', $admin->email) }}" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">رقم الهاتف</label>
                            <input type="tel" name="phone" class="form-control"
                                value="{{ old('phone', $admin->phone) }}" placeholder="اختياري">
                        </div>

                        <button type="submit" class="btn btn-gold w-100">
                            <i class="bi bi-check-lg me-2"></i>حفظ التغييرات
                        </button>
                    </form>
                </div>
            </div>

            <!-- Password Settings -->
            <div class="col-lg-6">
                <div class="settings-card">
                    <div class="settings-card-header">
                        <div class="settings-card-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        <div>
                            <h5 class="settings-card-title">كلمة المرور</h5>
                            <p class="settings-card-subtitle">تغيير كلمة المرور الخاصة بك</p>
                        </div>
                    </div>

                    <form action="{{ route('admin.profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="form-label">كلمة المرور الحالية</label>
                            <div class="input-group">
                                <input type="password" name="current_password" class="form-control" required
                                    id="currentPassword">
                                <button type="button" class="btn btn-outline-gold toggle-password"
                                    data-target="currentPassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">كلمة المرور الجديدة</label>
                            <div class="input-group">
                                <input type="password" name="password" class="form-control" required id="newPassword"
                                    minlength="8">
                                <button type="button" class="btn btn-outline-gold toggle-password"
                                    data-target="newPassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <div class="password-strength">
                                <div class="password-strength-bar" id="passwordStrengthBar"></div>
                            </div>
                            <small class="text-muted mt-1 d-block">يجب أن تكون 8 أحرف على الأقل</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">تأكيد كلمة المرور</label>
                            <div class="input-group">
                                <input type="password" name="password_confirmation" class="form-control" required
                                    id="confirmPassword">
                                <button type="button" class="btn btn-outline-gold toggle-password"
                                    data-target="confirmPassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-gold w-100">
                            <i class="bi bi-lock me-2"></i>تغيير كلمة المرور
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden Avatar Upload Form -->
    <form id="avatarForm" action="{{ route('admin.profile.avatar') }}" method="POST" enctype="multipart/form-data"
        style="display: none;">
        @csrf
    </form>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Avatar Upload
            const avatarInput = document.getElementById('avatarInput');
            const avatarForm = document.getElementById('avatarForm');
            const mainAvatar = document.getElementById('mainAvatar');

            avatarInput.addEventListener('change', function(e) {
                if (this.files && this.files[0]) {
                    const file = this.files[0];

                    // Validate file size (5MB max)
                    if (file.size > 5 * 1024 * 1024) {
                        alert('حجم الصورة يجب أن لا يتجاوز 5MB');
                        return;
                    }

                    // Show preview immediately
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        mainAvatar.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                    };
                    reader.readAsDataURL(file);

                    // Upload via AJAX
                    const formData = new FormData();
                    formData.append('avatar', file);
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

                    fetch('{{ route('admin.profile.avatar') }}', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(err => Promise.reject(err));
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                mainAvatar.classList.add('success-pulse');
                                setTimeout(() => mainAvatar.classList.remove('success-pulse'), 1000);
                                // Update image with new URL
                                if (data.avatar_url) {
                                    mainAvatar.innerHTML = `<img src="${data.avatar_url}" alt="Avatar">`;
                                }
                            } else {
                                alert(data.message || 'حدث خطأ أثناء رفع الصورة');
                                location.reload();
                            }
                        })
                        .catch(error => {
                            console.error('Upload Error:', error);
                            const message = error.message || error.errors?.avatar?.[0] || 'حدث خطأ أثناء رفع الصورة';
                            alert(message);
                            location.reload();
                        });
                }
            });

            // Toggle Password Visibility
            document.querySelectorAll('.toggle-password').forEach(btn => {
                btn.addEventListener('click', function() {
                    const targetId = this.dataset.target;
                    const input = document.getElementById(targetId);
                    const icon = this.querySelector('i');

                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('bi-eye');
                        icon.classList.add('bi-eye-slash');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('bi-eye-slash');
                        icon.classList.add('bi-eye');
                    }
                });
            });

            // Password Strength Indicator
            const newPassword = document.getElementById('newPassword');
            const strengthBar = document.getElementById('passwordStrengthBar');

            newPassword.addEventListener('input', function() {
                const password = this.value;
                let strength = 0;

                if (password.length >= 8) strength++;
                if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
                if (password.match(/[0-9]/)) strength++;
                if (password.match(/[^a-zA-Z0-9]/)) strength++;

                strengthBar.className = 'password-strength-bar';
                if (password.length === 0) {
                    strengthBar.style.width = '0';
                } else if (strength <= 1) {
                    strengthBar.classList.add('weak');
                } else if (strength <= 3) {
                    strengthBar.classList.add('medium');
                } else {
                    strengthBar.classList.add('strong');
                }
            });
        });
    </script>
@endpush
