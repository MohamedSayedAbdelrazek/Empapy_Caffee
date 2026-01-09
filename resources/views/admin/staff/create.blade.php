@extends('admin.layouts.app')

@section('title', 'إضافة موظف جديد')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">➕ إضافة موظف جديد</h2>
            <p class="text-muted mb-0">إضافة مدير أو كاشير جديد للنظام</p>
        </div>
        <a href="{{ route('admin.staff.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-right me-2"></i>رجوع
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.staff.store') }}" method="POST">
                @csrf

                <div class="row g-4">
                    {{-- Name --}}
                    <div class="col-md-6">
                        <label class="form-label">الاسم الكامل <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="col-md-6">
                        <label class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Phone --}}
                    <div class="col-md-6">
                        <label class="form-label">رقم الهاتف</label>
                        <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror"
                            value="{{ old('phone') }}" placeholder="01xxxxxxxxx">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Role --}}
                    <div class="col-md-6">
                        <label class="form-label">الدور <span class="text-danger">*</span></label>
                        <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                            <option value="">اختر الدور</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>
                                🛡️ مدير - صلاحيات كاملة
                            </option>
                            <option value="cashier" {{ old('role') === 'cashier' ? 'selected' : '' }}>
                                👤 كاشير - الطلبات والإشعارات فقط
                            </option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <strong>المدير:</strong> كل الصلاحيات (المنتجات، الفئات، العملاء، الإعدادات)<br>
                            <strong>الكاشير:</strong> الطلبات ولوحة الطلبات والإشعارات فقط
                        </div>
                    </div>

                    {{-- Password --}}
                    <div class="col-md-6">
                        <label class="form-label">كلمة المرور <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                            required minlength="8">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">8 أحرف على الأقل</div>
                    </div>

                    {{-- Password Confirmation --}}
                    <div class="col-md-6">
                        <label class="form-label">تأكيد كلمة المرور <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-2"></i>إضافة الموظف
                    </button>
                    <a href="{{ route('admin.staff.index') }}" class="btn btn-outline-secondary">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
@endsection
