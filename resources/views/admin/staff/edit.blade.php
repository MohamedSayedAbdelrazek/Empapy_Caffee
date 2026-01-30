@extends('admin.layouts.app')

@section('title', 'تعديل بيانات الموظف')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">✏️ تعديل بيانات الموظف</h2>
            <p class="text-muted mb-0">{{ $staff->name }}</p>
        </div>
        <a href="{{ route('admin.staff.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-right me-2"></i>رجوع
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.staff.update', $staff) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    {{-- Name --}}
                    <div class="col-md-6">
                        <label class="form-label">الاسم الكامل <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $staff->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="col-md-6">
                        <label class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $staff->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Phone --}}
                    <div class="col-md-6">
                        <label class="form-label">رقم الهاتف</label>
                        <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror"
                            value="{{ old('phone', $staff->phone) }}" placeholder="01xxxxxxxxx">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Role --}}
                    <div class="col-md-6">
                        <label class="form-label">الدور <span class="text-danger">*</span></label>
                        <select name="role" id="roleSelect" class="form-select @error('role') is-invalid @enderror" required
                            @if ($staff->id === auth()->id()) disabled @endif>
                            <option value="admin" {{ old('role', $staff->role) === 'admin' ? 'selected' : '' }}>
                                🛡️ مدير - صلاحيات كاملة
                            </option>
                            <option value="cashier" {{ old('role', $staff->role) === 'cashier' ? 'selected' : '' }}>
                                👤 موظف - صلاحيات مخصصة
                            </option>
                        </select>
                        @if ($staff->id === auth()->id())
                            <input type="hidden" name="role" value="{{ $staff->role }}">
                            <div class="form-text text-warning">
                                <i class="bi bi-exclamation-triangle me-1"></i>
                                لا يمكنك تغيير دورك الخاص
                            </div>
                        @else
                            <div class="form-text" id="roleHint">
                                <span id="adminHint" style="display: none;">
                                    <i class="bi bi-info-circle text-primary me-1"></i>
                                    المدير لديه جميع الصلاحيات تلقائياً
                                </span>
                                <span id="staffHint">
                                    اختر الصلاحيات المخصصة من القسم أدناه
                                </span>
                            </div>
                        @endif
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="col-md-6">
                        <label class="form-label">كلمة المرور الجديدة</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                            minlength="8">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">اتركه فارغاً إذا لم ترد تغيير كلمة المرور</div>
                    </div>

                    {{-- Password Confirmation --}}
                    <div class="col-md-6">
                        <label class="form-label">تأكيد كلمة المرور</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                </div>

                {{-- Permissions Section --}}
                @include('admin.staff._permissions')
        </div>

        <hr class="my-4">

        <div class="d-flex justify-content-between">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-2"></i>حفظ التغييرات
                </button>
                <a href="{{ route('admin.staff.index') }}" class="btn btn-outline-secondary">إلغاء</a>
            </div>

            @if ($staff->id !== auth()->id())
                <form action="{{ route('admin.staff.destroy', $staff) }}" method="POST"
                    onsubmit="return confirm('هل أنت متأكد من حذف هذا الموظف؟')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="bi bi-trash me-2"></i>حذف الموظف
                    </button>
                </form>
            @endif
        </div>
        </form>
    </div>
    </div>

    {{-- Staff Info Card --}}
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>معلومات الحساب</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <small class="text-muted d-block">تاريخ الإنشاء</small>
                    <span>{{ $staff->created_at->format('Y/m/d h:i A') }}</span>
                </div>
                <div class="col-md-4">
                    <small class="text-muted d-block">آخر تحديث</small>
                    <span>{{ $staff->updated_at->format('Y/m/d h:i A') }}</span>
                </div>
                <div class="col-md-4">
                    <small class="text-muted d-block">ID</small>
                    <span>#{{ $staff->id }}</span>
                </div>
            </div>
        </div>
    </div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('roleSelect');
    const permissionsSection = document.getElementById('permissionsSection');
    const adminHint = document.getElementById('adminHint');
    const staffHint = document.getElementById('staffHint');

    if (!roleSelect || !permissionsSection) return;

    function togglePermissions() {
        if (roleSelect.value === 'admin') {
            permissionsSection.style.display = 'none';
            if (adminHint) adminHint.style.display = 'inline';
            if (staffHint) staffHint.style.display = 'none';
        } else {
            permissionsSection.style.display = 'block';
            if (adminHint) adminHint.style.display = 'none';
            if (staffHint) staffHint.style.display = 'inline';
        }
    }

    roleSelect.addEventListener('change', togglePermissions);
    togglePermissions(); // Run on page load
});
</script>
@endpush
@endsection
