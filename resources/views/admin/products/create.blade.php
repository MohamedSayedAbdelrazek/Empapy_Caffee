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
                    <h5 class="mb-4">التسعير</h5>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">السعر (ج.م) *</label>
                            <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                                value="{{ old('price') }}" step="0.01" min="0" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">سعر التخفيض</label>
                            <input type="number" name="sale_price" class="form-control" value="{{ old('sale_price') }}"
                                step="0.01" min="0">
                        </div>
                    </div>
                </div>

                <div class="admin-card mb-4">
                    <h5 class="mb-4">تفاصيل القهوة</h5>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">الوزن الافتراضي</label>
                                <input type="text" name="weight" class="form-control" value="{{ old('weight') }}"
                                    placeholder="250g">
                                <small class="text-muted">سيظهر إذا لم يكن للمنتج خيارات أوزان</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">درجة التحميص الافتراضية</label>
                                <select name="roast_level" class="form-select">
                                    <option value="">اختر...</option>
                                    <option value="light" {{ old('roast_level') === 'light' ? 'selected' : '' }}>تحميص
                                        فاتح
                                    </option>
                                    <option value="medium" {{ old('roast_level') === 'medium' ? 'selected' : '' }}>تحميص
                                        متوسط
                                    </option>
                                    <option value="dark" {{ old('roast_level') === 'dark' ? 'selected' : '' }}>تحميص داكن
                                    </option>
                                </select>
                            </div>
                    </div>
                </div>

                {{-- Product Options Section --}}
                <div class="admin-card mt-4">
                        <h5 class="mb-4">
                            <i class="bi bi-sliders me-2"></i>
                            خيارات المنتج (متعددة الأسعار)
                        </h5>
                        <p class="text-muted mb-4">فعّل الخيارات التي تريدها لهذا المنتج. <strong>الأوزان</strong> لها سعر
                            كامل لكل وزن، بينما <strong>التحميص والإضافات</strong> هي فروقات على السعر.</p>

                        {{-- Weight Options --}}
                        <div class="option-section mb-4 p-3 rounded"
                            style="background: rgba(201, 162, 39, 0.05); border: 1px solid rgba(201, 162, 39, 0.2);">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="has_weight_options"
                                    name="has_weight_options" value="1"
                                    {{ old('has_weight_options') ? 'checked' : '' }}
                                    onchange="toggleOptionSection('weight')">
                                <label class="form-check-label fw-bold" for="has_weight_options">
                                    <i class="bi bi-box-seam me-1"></i>
                                    هذا المنتج له أوزان متعددة
                                </label>
                            </div>

                            <div id="weight_options_container" class="option-values-container"
                                style="{{ old('has_weight_options') ? '' : 'display: none;' }}">
                                <table class="table table-dark table-hover mb-2">
                                    <thead>
                                        <tr>
                                            <th style="width: 35%">الوزن (عربي) *</th>
                                            <th style="width: 25%">الوزن (English)</th>
                                            <th style="width: 20%">السعر (ج.م) *</th>
                                            <th style="width: 10%">افتراضي</th>
                                            <th style="width: 10%"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="weight_values_body">
                                        @if (old('weight_values'))
                                            @foreach (old('weight_values') as $index => $value)
                                                <tr>
                                                    <td><input type="text"
                                                            name="weight_values[{{ $index }}][value_ar]"
                                                            class="form-control form-control-sm"
                                                            value="{{ $value['value_ar'] ?? '' }}"
                                                            placeholder="مثال: 125 جم"></td>
                                                    <td><input type="text"
                                                            name="weight_values[{{ $index }}][value]"
                                                            class="form-control form-control-sm"
                                                            value="{{ $value['value'] ?? '' }}" placeholder="e.g. 125g">
                                                    </td>
                                                    <td><input type="number"
                                                            name="weight_values[{{ $index }}][price_modifier]"
                                                            class="form-control form-control-sm"
                                                            value="{{ $value['price_modifier'] ?? 0 }}" step="0.01">
                                                    </td>
                                                    <td class="text-center"><input type="radio" name="weight_default"
                                                            value="{{ $index }}" class="form-check-input"
                                                            {{ $value['is_default'] ?? false ? 'checked' : '' }}
                                                            onchange="setDefaultValue('weight', {{ $index }})">
                                                    </td>
                                                    <td><button type="button" class="btn btn-sm btn-outline-danger"
                                                            onclick="removeOptionRow(this)"><i
                                                                class="bi bi-trash"></i></button></td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-sm btn-outline-warning"
                                    onclick="addOptionRow('weight')">
                                    <i class="bi bi-plus-lg me-1"></i>إضافة وزن
                                </button>
                            </div>
                        </div>

                        {{-- Roast Options --}}
                        <div class="option-section mb-4 p-3 rounded"
                            style="background: rgba(239, 68, 68, 0.05); border: 1px solid rgba(239, 68, 68, 0.2);">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="has_roast_options"
                                    name="has_roast_options" value="1"
                                    {{ old('has_roast_options') ? 'checked' : '' }}
                                    onchange="toggleOptionSection('roast')">
                                <label class="form-check-label fw-bold" for="has_roast_options">
                                    <i class="bi bi-fire me-1"></i>
                                    هذا المنتج له درجات تحميص متعددة
                                </label>
                            </div>

                            <div id="roast_options_container" class="option-values-container"
                                style="{{ old('has_roast_options') ? '' : 'display: none;' }}">
                                <table class="table table-dark table-hover mb-2">
                                    <thead>
                                        <tr>
                                            <th style="width: 35%">درجة التحميص (عربي) *</th>
                                            <th style="width: 25%">Roast Level (English)</th>
                                            <th style="width: 20%">السعر الإضافي (ج.م)</th>
                                            <th style="width: 10%">افتراضي</th>
                                            <th style="width: 10%"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="roast_values_body">
                                        @if (old('roast_values'))
                                            @foreach (old('roast_values') as $index => $value)
                                                <tr>
                                                    <td><input type="text"
                                                            name="roast_values[{{ $index }}][value_ar]"
                                                            class="form-control form-control-sm"
                                                            value="{{ $value['value_ar'] ?? '' }}"
                                                            placeholder="مثال: فاتح"></td>
                                                    <td><input type="text"
                                                            name="roast_values[{{ $index }}][value]"
                                                            class="form-control form-control-sm"
                                                            value="{{ $value['value'] ?? '' }}" placeholder="e.g. Light">
                                                    </td>
                                                    <td><input type="number"
                                                            name="roast_values[{{ $index }}][price_modifier]"
                                                            class="form-control form-control-sm"
                                                            value="{{ $value['price_modifier'] ?? 0 }}" step="0.01">
                                                    </td>
                                                    <td class="text-center"><input type="radio" name="roast_default"
                                                            value="{{ $index }}" class="form-check-input"
                                                            {{ $value['is_default'] ?? false ? 'checked' : '' }}
                                                            onchange="setDefaultValue('roast', {{ $index }})"></td>
                                                    <td><button type="button" class="btn btn-sm btn-outline-danger"
                                                            onclick="removeOptionRow(this)"><i
                                                                class="bi bi-trash"></i></button></td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                    onclick="addOptionRow('roast')">
                                    <i class="bi bi-plus-lg me-1"></i>إضافة درجة تحميص
                                </button>
                            </div>
                        </div>

                        {{-- Additive Options --}}
                        <div class="option-section p-3 rounded"
                            style="background: rgba(16, 185, 129, 0.05); border: 1px solid rgba(16, 185, 129, 0.2);">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="has_additive_options"
                                    name="has_additive_options" value="1"
                                    {{ old('has_additive_options') ? 'checked' : '' }}
                                    onchange="toggleOptionSection('additive')">
                                <label class="form-check-label fw-bold" for="has_additive_options">
                                    <i class="bi bi-plus-circle me-1"></i>
                                    هذا المنتج له إضافات
                                </label>
                            </div>

                            <div id="additive_options_container" class="option-values-container"
                                style="{{ old('has_additive_options') ? '' : 'display: none;' }}">
                                <table class="table table-dark table-hover mb-2">
                                    <thead>
                                        <tr>
                                            <th style="width: 35%">الإضافة (عربي) *</th>
                                            <th style="width: 25%">Additive (English)</th>
                                            <th style="width: 20%">السعر الإضافي (ج.م)</th>
                                            <th style="width: 10%">افتراضي</th>
                                            <th style="width: 10%"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="additive_values_body">
                                        @if (old('additive_values'))
                                            @foreach (old('additive_values') as $index => $value)
                                                <tr>
                                                    <td><input type="text"
                                                            name="additive_values[{{ $index }}][value_ar]"
                                                            class="form-control form-control-sm"
                                                            value="{{ $value['value_ar'] ?? '' }}"
                                                            placeholder="مثال: بالهيل"></td>
                                                    <td><input type="text"
                                                            name="additive_values[{{ $index }}][value]"
                                                            class="form-control form-control-sm"
                                                            value="{{ $value['value'] ?? '' }}"
                                                            placeholder="e.g. With Cardamom"></td>
                                                    <td><input type="number"
                                                            name="additive_values[{{ $index }}][price_modifier]"
                                                            class="form-control form-control-sm"
                                                            value="{{ $value['price_modifier'] ?? 0 }}" step="0.01">
                                                    </td>
                                                    <td class="text-center"><input type="radio" name="additive_default"
                                                            value="{{ $index }}" class="form-check-input"
                                                            {{ $value['is_default'] ?? false ? 'checked' : '' }}
                                                            onchange="setDefaultValue('additive', {{ $index }})">
                                                    </td>
                                                    <td><button type="button" class="btn btn-sm btn-outline-danger"
                                                            onclick="removeOptionRow(this)"><i
                                                                class="bi bi-trash"></i></button></td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-sm btn-outline-success"
                                    onclick="addOptionRow('additive')">
                                    <i class="bi bi-plus-lg me-1"></i>إضافة إضافة جديدة
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="admin-card mb-4">
                        <h5 class="mb-4">التصنيف</h5>

                        <label class="form-label">الصنف *</label>
                        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror"
                            required>
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
                            <input type="checkbox" name="is_active" class="form-check-input" id="is_active"
                                value="1" {{ old('is_active', true) ? 'checked' : '' }}>
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

        // ====== Product Options Management ======

        // Option row counters
        const optionCounters = {
            weight: 0,
            roast: 0,
            additive: 0
        };

        // Toggle option section visibility
        function toggleOptionSection(type) {
            const container = document.getElementById(`${type}_options_container`);
            const checkbox = document.getElementById(`has_${type}_options`);

            if (checkbox.checked) {
                container.style.display = 'block';
                // Add first row if empty
                const tbody = document.getElementById(`${type}_values_body`);
                if (tbody.children.length === 0) {
                    addOptionRow(type);
                }
            } else {
                container.style.display = 'none';
            }
        }

        // Add new option row
        function addOptionRow(type) {
            const tbody = document.getElementById(`${type}_values_body`);
            const index = optionCounters[type]++;

            const placeholders = {
                weight: {
                    ar: 'مثال: 125 جم',
                    en: 'e.g. 125g'
                },
                roast: {
                    ar: 'مثال: فاتح',
                    en: 'e.g. Light'
                },
                additive: {
                    ar: 'مثال: بالهيل',
                    en: 'e.g. With Cardamom'
                }
            };

            // Weight uses full price, others use modifier
            const priceLabel = type === 'weight' ? '' : '0'; // Weight has no default, modifiers default to 0
            const pricePlaceholder = type === 'weight' ? 'السعر الكامل' : 'فرق السعر';

            const row = document.createElement('tr');
            row.innerHTML = `
                <td><input type="text" name="${type}_values[${index}][value_ar]" class="form-control form-control-sm" placeholder="${placeholders[type].ar}" required></td>
                <td><input type="text" name="${type}_values[${index}][value]" class="form-control form-control-sm" placeholder="${placeholders[type].en}"></td>
                <td><input type="number" name="${type}_values[${index}][price_modifier]" class="form-control form-control-sm" value="${priceLabel}" step="0.01" placeholder="${pricePlaceholder}" ${type === 'weight' ? 'required' : ''}></td>
                <td class="text-center">
                    <input type="radio" name="${type}_default" value="${index}" class="form-check-input" onchange="setDefaultValue('${type}', ${index})">
                    <input type="hidden" name="${type}_values[${index}][is_default]" value="0" class="is-default-hidden">
                </td>
                <td><button type="button" class="btn btn-sm btn-outline-danger" onclick="removeOptionRow(this)"><i class="bi bi-trash"></i></button></td>
            `;

            tbody.appendChild(row);

            // If this is the first row, set it as default
            if (tbody.children.length === 1) {
                const radio = row.querySelector('input[type="radio"]');
                radio.checked = true;
                setDefaultValue(type, index);
            }

            // Re-index all rows
            reindexOptionRows(type);
        }

        // Remove option row
        function removeOptionRow(button) {
            const row = button.closest('tr');
            const tbody = row.closest('tbody');
            const type = tbody.id.replace('_values_body', '');

            row.remove();

            // Re-index remaining rows
            reindexOptionRows(type);

            // Ensure at least one default is set
            const radios = tbody.querySelectorAll('input[type="radio"]');
            const hasChecked = Array.from(radios).some(r => r.checked);
            if (!hasChecked && radios.length > 0) {
                radios[0].checked = true;
                const hiddenInput = radios[0].closest('td').querySelector('.is-default-hidden');
                if (hiddenInput) hiddenInput.value = '1';
            }
        }

        // Set default value
        function setDefaultValue(type, selectedIndex) {
            const tbody = document.getElementById(`${type}_values_body`);
            const rows = tbody.querySelectorAll('tr');

            rows.forEach((row, idx) => {
                const hiddenInput = row.querySelector('.is-default-hidden');
                if (hiddenInput) {
                    hiddenInput.value = '0';
                }
            });

            // Set the selected one as default
            const radios = tbody.querySelectorAll('input[type="radio"]');
            radios.forEach(radio => {
                const hiddenInput = radio.closest('td').querySelector('.is-default-hidden');
                if (radio.checked && hiddenInput) {
                    hiddenInput.value = '1';
                }
            });
        }

        // Re-index option rows after add/remove
        function reindexOptionRows(type) {
            const tbody = document.getElementById(`${type}_values_body`);
            const rows = tbody.querySelectorAll('tr');

            rows.forEach((row, index) => {
                const inputs = row.querySelectorAll('input');
                inputs.forEach(input => {
                    const name = input.getAttribute('name');
                    if (name) {
                        const newName = name.replace(/\[\d+\]/, `[${index}]`);
                        input.setAttribute('name', newName);
                    }

                    // Update radio value
                    if (input.type === 'radio') {
                        input.value = index;
                        input.setAttribute('onchange', `setDefaultValue('${type}', ${index})`);
                    }
                });
            });

            optionCounters[type] = rows.length;
        }

        // Make functions global
        window.toggleOptionSection = toggleOptionSection;
        window.addOptionRow = addOptionRow;
        window.removeOptionRow = removeOptionRow;
        window.setDefaultValue = setDefaultValue;
    </script>
@endpush
