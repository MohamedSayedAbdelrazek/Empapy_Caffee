@extends('layouts.app')

@section('title', 'تعديل الملف الشخصي - إمبابي كافيه')

@section('content')
    <!-- Page Header -->
    <div class="page-header" style="padding: 120px 0 50px; background: linear-gradient(135deg, #2C1810 0%, #3D2317 100%);">
        <div class="container">
            <h1 class="page-title text-white" data-aos="fade-up">⚙️ الملف الشخصي</h1>
            <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('account.index') }}">حسابي</a></li>
                    <li class="breadcrumb-item active text-white-50">الملف الشخصي</li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="py-5" style="background: var(--cream); min-height: 60vh;">
        <div class="container">
            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>
                    @foreach ($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row g-4">
                <!-- Avatar Section -->
                <div class="col-lg-4" data-aos="fade-up">
                    <div class="glass-card p-4 text-center">
                        <h5 class="mb-4"><i class="bi bi-camera me-2" style="color: var(--gold);"></i>الصورة الشخصية</h5>

                        <div class="profile-avatar-upload mb-4">
                            <div class="avatar-preview-container" id="avatarPreviewContainer">
                                @if ($user->avatar)
                                    <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}"
                                        id="avatarPreview">
                                @else
                                    <i class="bi bi-person-fill" id="avatarPlaceholder"></i>
                                @endif
                            </div>
                            <label for="avatarInput" class="avatar-upload-btn">
                                <i class="bi bi-camera-fill"></i>
                            </label>
                        </div>

                        <form id="avatarForm" action="{{ route('account.avatar') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="file" id="avatarInput" name="avatar" accept="image/*" style="display: none;">
                        </form>

                        <div class="avatar-dropzone" id="dropzone">
                            <i class="bi bi-cloud-arrow-up"></i>
                            <p class="mb-0">اسحب الصورة هنا أو انقر للاختيار</p>
                            <small class="text-muted">الحد الأقصى 5MB | JPG, PNG, GIF</small>
                        </div>

                        @if ($user->avatar)
                            <form action="{{ route('account.avatar.remove') }}" method="POST" class="mt-3">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash me-1"></i>حذف الصورة
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Profile & Password -->
                <div class="col-lg-8">
                    <!-- Profile Info -->
                    <div class="glass-card p-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                        <h5 class="mb-4"><i class="bi bi-person me-2" style="color: var(--gold);"></i>البيانات الشخصية
                        </h5>

                        <form action="{{ route('account.profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">الاسم الكامل *</label>
                                    <input type="text" name="name" class="form-control"
                                        value="{{ old('name', $user->name) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">البريد الإلكتروني *</label>
                                    <input type="email" name="email" class="form-control"
                                        value="{{ old('email', $user->email) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">رقم الهاتف</label>
                                    <input type="tel" name="phone" class="form-control"
                                        value="{{ old('phone', $user->phone) }}" placeholder="01xxxxxxxxx">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">المحافظة</label>
                                    <select name="governorate" class="form-select">
                                        <option value="">اختر المحافظة</option>
                                        @foreach (['القاهرة', 'الجيزة', 'الإسكندرية', 'الدقهلية', 'الشرقية', 'المنوفية', 'القليوبية', 'البحيرة', 'الغربية', 'كفر الشيخ', 'دمياط', 'بورسعيد', 'الإسماعيلية', 'السويس', 'الفيوم', 'بني سويف', 'المنيا', 'أسيوط', 'سوهاج', 'قنا', 'الأقصر', 'أسوان', 'البحر الأحمر', 'شمال سيناء', 'جنوب سيناء', 'مطروح', 'الوادي الجديد'] as $gov)
                                            <option value="{{ $gov }}"
                                                {{ old('governorate', $user->governorate) == $gov ? 'selected' : '' }}>
                                                {{ $gov }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">المدينة</label>
                                    <input type="text" name="city" class="form-control"
                                        value="{{ old('city', $user->city) }}" placeholder="مثال: مدينة نصر">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">العنوان التفصيلي</label>
                                    <textarea name="address" class="form-control" rows="2" placeholder="الشارع، رقم المبنى، الشقة...">{{ old('address', $user->address) }}</textarea>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-golden mt-4">
                                <i class="bi bi-check-lg me-2"></i>حفظ التغييرات
                            </button>
                        </form>
                    </div>

                    <!-- Change Password -->
                    <div class="glass-card p-4" data-aos="fade-up" data-aos-delay="200">
                        <h5 class="mb-4"><i class="bi bi-shield-lock me-2" style="color: var(--gold);"></i>تغيير كلمة
                            المرور</h5>

                        <form action="{{ route('account.password') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label">كلمة المرور الحالية</label>
                                    <div class="input-group">
                                        <input type="password" name="current_password" class="form-control"
                                            id="currentPassword" required>
                                        <button type="button" class="btn btn-outline-secondary toggle-password"
                                            data-target="currentPassword">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">كلمة المرور الجديدة</label>
                                    <div class="input-group">
                                        <input type="password" name="password" class="form-control" id="newPassword"
                                            minlength="8" required>
                                        <button type="button" class="btn btn-outline-secondary toggle-password"
                                            data-target="newPassword">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                    <div class="password-strength mt-2">
                                        <div class="password-strength-bar" id="passwordStrengthBar"></div>
                                    </div>
                                    <small class="text-muted">8 أحرف على الأقل</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">تأكيد كلمة المرور</label>
                                    <div class="input-group">
                                        <input type="password" name="password_confirmation" class="form-control"
                                            id="confirmPassword" required>
                                        <button type="button" class="btn btn-outline-secondary toggle-password"
                                            data-target="confirmPassword">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-outline-golden mt-4">
                                <i class="bi bi-lock me-2"></i>تغيير كلمة المرور
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <style>
        .profile-avatar-upload {
            position: relative;
            width: 150px;
            height: 150px;
            margin: 0 auto;
        }

        .avatar-preview-container {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--medium-roast) 0%, var(--dark-roast) 100%);
            border: 4px solid rgba(201, 162, 39, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .avatar-preview-container:hover {
            border-color: var(--gold);
            transform: scale(1.02);
        }

        .avatar-preview-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avatar-preview-container i {
            font-size: 64px;
            color: #c9a227;
        }

        .profile-avatar-upload .avatar-upload-btn {
            position: absolute;
            bottom: 5px;
            right: 5px;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: linear-gradient(135deg, #c9a227 0%, #e8c547 100%);
            border: 3px solid white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            color: #1a1a2e;
            font-size: 18px;
        }

        .profile-avatar-upload .avatar-upload-btn:hover {
            transform: scale(1.1);
        }

        .avatar-dropzone {
            border: 2px dashed rgba(201, 162, 39, 0.3);
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            margin-top: 20px;
        }

        .avatar-dropzone:hover,
        .avatar-dropzone.dragover {
            border-color: #c9a227;
            background: rgba(201, 162, 39, 0.05);
        }

        .avatar-dropzone i {
            font-size: 32px;
            color: #c9a227;
            display: block;
            margin-bottom: 10px;
        }

        .password-strength {
            height: 4px;
            border-radius: 2px;
            background: #e5e5e5;
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
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const avatarInput = document.getElementById('avatarInput');
            const avatarForm = document.getElementById('avatarForm');
            const previewContainer = document.getElementById('avatarPreviewContainer');
            const dropzone = document.getElementById('dropzone');

            // File input change
            avatarInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    handleFileUpload(this.files[0]);
                }
            });

            // Dropzone click
            dropzone.addEventListener('click', () => avatarInput.click());

            // Drag and drop
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                dropzone.addEventListener(eventName, () => dropzone.classList.add('dragover'));
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, () => dropzone.classList.remove('dragover'));
            });

            dropzone.addEventListener('drop', function(e) {
                const files = e.dataTransfer.files;
                if (files && files[0]) {
                    handleFileUpload(files[0]);
                }
            });

            function handleFileUpload(file) {
                if (file.size > 5 * 1024 * 1024) {
                    alert('حجم الصورة يجب أن لا يتجاوز 5MB');
                    return;
                }

                // Show preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewContainer.innerHTML =
                        `<img src="${e.target.result}" alt="Preview" id="avatarPreview">`;
                };
                reader.readAsDataURL(file);

                // Upload
                const formData = new FormData();
                formData.append('avatar', file);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

                fetch('{{ route('account.avatar') }}', {
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
                            dropzone.innerHTML =
                                '<i class="bi bi-check-circle text-success"></i><p class="mb-0 text-success">تم رفع الصورة بنجاح!</p>';
                            if (data.avatar_url) {
                                previewContainer.innerHTML =
                                    `<img src="${data.avatar_url}" alt="Avatar" id="avatarPreview">`;
                            }
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            alert(data.message || 'حدث خطأ أثناء رفع الصورة');
                            location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Upload Error:', error);
                        const message = error.message || error.errors?.avatar?.[0] ||
                        'حدث خطأ أثناء رفع الصورة';
                        alert(message);
                        location.reload();
                    });
            }

            // Toggle password visibility
            document.querySelectorAll('.toggle-password').forEach(btn => {
                btn.addEventListener('click', function() {
                    const input = document.getElementById(this.dataset.target);
                    const icon = this.querySelector('i');

                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.replace('bi-eye', 'bi-eye-slash');
                    } else {
                        input.type = 'password';
                        icon.classList.replace('bi-eye-slash', 'bi-eye');
                    }
                });
            });

            // Password strength
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
