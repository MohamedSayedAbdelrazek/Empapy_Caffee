@extends('admin.layouts.app')

@section('title', 'تعديل الصنف')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="page-header-admin">
            <h1 class="page-title-admin">تعديل الصنف</h1>
            <p class="page-subtitle-admin">{{ $category->name_ar }}</p>
        </div>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-light">
            <i class="bi bi-arrow-right me-2"></i>العودة للأصناف
        </a>
    </div>

    <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="admin-card">
                    <h5 class="mb-4">معلومات الصنف</h5>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">الاسم (English) *</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $category->name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">الاسم (عربي) *</label>
                            <input type="text" name="name_ar" class="form-control @error('name_ar') is-invalid @enderror"
                                value="{{ old('name_ar', $category->name_ar) }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">الوصف (English)</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description', $category->description) }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">الوصف (عربي)</label>
                            <textarea name="description_ar" class="form-control" rows="3">{{ old('description_ar', $category->description_ar) }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">صورة الصنف</label>
                            @if ($category->image)
                                <div class="mb-3">
                                    <p class="text-muted small mb-2">الصورة الحالية:</p>
                                    <img src="{{ $category->image }}" alt="{{ $category->name_ar }}" class="img-thumbnail"
                                        style="max-height: 150px;">
                                </div>
                            @endif
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror"
                                accept="image/*" id="imageInput">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">اختر صورة جديدة لاستبدال الحالية (JPG, PNG, GIF - حد أقصى 2MB)</div>
                            <div id="imagePreview" class="mt-3" style="display: none;">
                                <p class="text-muted small mb-2">الصورة الجديدة:</p>
                                <img src="" alt="معاينة الصورة" class="img-thumbnail" style="max-height: 150px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="admin-card mb-4">
                    <h5 class="mb-4">الحالة</h5>

                    <div class="form-check">
                        <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1"
                            {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">صنف نشط</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-admin-primary w-100 btn-lg">
                    <i class="bi bi-check-lg me-2"></i>حفظ التعديلات
                </button>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        document.getElementById('imageInput').addEventListener('change', function(e) {
            const preview = document.getElementById('imagePreview');
            const previewImg = preview.querySelector('img');
            const file = e.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
            }
        });
    </script>
@endpush
