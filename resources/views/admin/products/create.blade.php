@extends('admin.layouts.app')

@section('title', 'إضافة منتج')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="page-header-admin">
            <h1 class="page-title-admin">إضافة منتج جديد</h1>
        </div>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-light">
            <i class="bi bi-arrow-right me-2"></i>العودة للمنتجات
        </a>
    </div>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="admin-card mb-4">
                    <h5 class="mb-4">معلومات المنتج</h5>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">الاسم (English)</label>
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
                    </div>
                </div>

                <div class="admin-card mb-4">
                    <h5 class="mb-4">التسعير والمخزون</h5>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">السعر (ج.م) *</label>
                            <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                                value="{{ old('price') }}" step="0.01" min="0" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">سعر التخفيض</label>
                            <input type="number" name="sale_price" class="form-control" value="{{ old('sale_price') }}"
                                step="0.01" min="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">المخزون *</label>
                            <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror"
                                value="{{ old('stock', 0) }}" min="0" required>
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="admin-card">
                    <h5 class="mb-4">تفاصيل القهوة</h5>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">الوزن</label>
                            <input type="text" name="weight" class="form-control" value="{{ old('weight') }}"
                                placeholder="250g">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">درجة التحميص</label>
                            <select name="roast_level" class="form-select">
                                <option value="">اختر...</option>
                                <option value="light" {{ old('roast_level') === 'light' ? 'selected' : '' }}>تحميص فاتح
                                </option>
                                <option value="medium" {{ old('roast_level') === 'medium' ? 'selected' : '' }}>تحميص متوسط
                                </option>
                                <option value="dark" {{ old('roast_level') === 'dark' ? 'selected' : '' }}>تحميص داكن
                                </option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">المصدر (English)</label>
                            <input type="text" name="origin" class="form-control" value="{{ old('origin') }}"
                                placeholder="Ethiopia">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">المصدر (عربي)</label>
                            <input type="text" name="origin_ar" class="form-control" value="{{ old('origin_ar') }}"
                                placeholder="إثيوبيا">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="admin-card mb-4">
                    <h5 class="mb-4">التصنيف</h5>

                    <label class="form-label">الصنف *</label>
                    <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                        <option value="">اختر الصنف...</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name_ar }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="admin-card mb-4">
                    <h5 class="mb-4">صورة المنتج الرئيسية</h5>

                    <div class="image-upload-area mb-3" id="mainImageUpload">
                        <input type="file" name="image" id="imageInput" accept="image/*" class="d-none">
                        <label for="imageInput" class="upload-label">
                            <i class="bi bi-cloud-arrow-up display-4"></i>
                            <p class="mb-1">اسحب الصورة هنا أو انقر للاختيار</p>
                            <small class="text-light opacity-75">PNG, JPG حتى 2MB</small>
                        </label>
                        <div id="mainImagePreview" class="image-preview d-none">
                            <img src="" alt="Preview" id="previewImg">
                            <button type="button" class="btn btn-sm btn-danger remove-image"
                                onclick="removeMainImage()">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    </div>
                    @error('image')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="admin-card mb-4">
                    <h5 class="mb-4">صور إضافية (اختياري)</h5>

                    <div class="image-upload-area mb-3" id="galleryUpload">
                        <input type="file" name="gallery[]" id="galleryInput" accept="image/*" class="d-none"
                            multiple>
                        <label for="galleryInput" class="upload-label">
                            <i class="bi bi-images display-4"></i>
                            <p class="mb-1">اختر صور متعددة</p>
                            <small class="text-light opacity-75">PNG, JPG حتى 2MB لكل صورة</small>
                        </label>
                    </div>
                    <div id="galleryPreview" class="d-flex flex-wrap gap-2"></div>
                </div>

                <div class="admin-card mb-4">
                    <h5 class="mb-4">الحالة</h5>

                    <div class="form-check mb-3">
                        <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1"
                            {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">منتج نشط</label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" name="is_featured" class="form-check-input" id="is_featured"
                            value="1" {{ old('is_featured') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_featured">منتج مميز</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-admin-primary w-100 btn-lg">
                    <i class="bi bi-check-lg me-2"></i>حفظ المنتج
                </button>
            </div>
        </div>
    </form>
@endsection

@push('styles')
    <style>
        .image-upload-area {
            border: 2px dashed rgba(201, 162, 39, 0.5);
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .image-upload-area:hover,
        .image-upload-area.dragover {
            border-color: var(--admin-primary);
            background: rgba(201, 162, 39, 0.1);
        }

        .upload-label {
            cursor: pointer;
            display: flex;
            flex-direction: column;
            align-items: center;
            color: #9ca3af;
        }

        .upload-label i {
            color: var(--admin-primary);
            margin-bottom: 10px;
        }

        .image-preview {
            position: relative;
            display: inline-block;
        }

        .image-preview img {
            max-width: 100%;
            max-height: 200px;
            border-radius: 8px;
            object-fit: cover;
        }

        .remove-image {
            position: absolute;
            top: -10px;
            right: -10px;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .gallery-item {
            position: relative;
            width: 80px;
            height: 80px;
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
        }

        .gallery-item .remove-gallery {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 20px;
            height: 20px;
            font-size: 10px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Main image upload
        const imageInput = document.getElementById('imageInput');
        const mainImagePreview = document.getElementById('mainImagePreview');
        const previewImg = document.getElementById('previewImg');
        const uploadLabel = document.querySelector('#mainImageUpload .upload-label');
        const mainImageUpload = document.getElementById('mainImageUpload');

        imageInput.addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    mainImagePreview.classList.remove('d-none');
                    uploadLabel.classList.add('d-none');
                };
                reader.readAsDataURL(e.target.files[0]);
            }
        });

        // Drag and drop for main image
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            mainImageUpload.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            mainImageUpload.addEventListener(eventName, () => mainImageUpload.classList.add('dragover'), false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            mainImageUpload.addEventListener(eventName, () => mainImageUpload.classList.remove('dragover'), false);
        });

        mainImageUpload.addEventListener('drop', function(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files.length) {
                imageInput.files = files;
                imageInput.dispatchEvent(new Event('change'));
            }
        }, false);

        function removeMainImage() {
            imageInput.value = '';
            previewImg.src = '';
            mainImagePreview.classList.add('d-none');
            uploadLabel.classList.remove('d-none');
        }

        // Gallery upload
        const galleryInput = document.getElementById('galleryInput');
        const galleryPreview = document.getElementById('galleryPreview');

        galleryInput.addEventListener('change', function(e) {
            galleryPreview.innerHTML = '';
            if (e.target.files) {
                Array.from(e.target.files).forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'gallery-item';
                        div.innerHTML = `
                        <img src="${e.target.result}" alt="Gallery ${index + 1}">
                    `;
                        galleryPreview.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });
            }
        });
    </script>
@endpush
