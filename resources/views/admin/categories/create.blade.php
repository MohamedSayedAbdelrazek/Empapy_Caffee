@extends('admin.layouts.app')

@section('title', 'إضافة صنف')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="page-header-admin">
            <h1 class="page-title-admin">إضافة صنف جديد</h1>
        </div>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-light">
            <i class="bi bi-arrow-right me-2"></i>العودة للأصناف
        </a>
    </div>

    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="admin-card">
                    <h5 class="mb-4">معلومات الصنف</h5>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">الاسم (English) *</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">الاسم (عربي) *</label>
                            <input type="text" name="name_ar" class="form-control @error('name_ar') is-invalid @enderror"
                                value="{{ old('name_ar') }}" required>
                            @error('name_ar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">الوصف (English)</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">الوصف (عربي)</label>
                            <textarea name="description_ar" class="form-control" rows="3">{{ old('description_ar') }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">رابط الصورة</label>
                            <input type="url" name="image" class="form-control" value="{{ old('image') }}"
                                placeholder="https://...">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="admin-card mb-4">
                    <h5 class="mb-4">الحالة</h5>

                    <div class="form-check">
                        <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1"
                            {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">صنف نشط</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-admin-primary w-100 btn-lg">
                    <i class="bi bi-check-lg me-2"></i>حفظ الصنف
                </button>
            </div>
        </div>
    </form>
@endsection
