@extends('admin.layouts.app')

@section('title', 'تعديل المنتج')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="page-header-admin">
            <h1 class="page-title-admin">تعديل المنتج</h1>
            <p class="page-subtitle-admin">{{ $product->name }}</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-light">
            <i class="bi bi-arrow-right me-2"></i>العودة للمنتجات
        </a>
    </div>

    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="admin-card mb-4">
                    <h5 class="mb-4">معلومات المنتج</h5>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">اسم المنتج</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $product->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">وصف المنتج</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description', $product->description) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="admin-card mb-4">
                    <h5 class="mb-4">التسعير والمخزون</h5>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">السعر (ج.م) *</label>
                            <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                                value="{{ old('price', $product->price) }}" step="0.01" min="0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">سعر التخفيض</label>
                            <input type="number" name="sale_price" class="form-control"
                                value="{{ old('sale_price', $product->sale_price) }}" step="0.01" min="0">
                        </div>
                    </div>
                </div>

                <div class="admin-card">
                    <h5 class="mb-4">تفاصيل القهوة</h5>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">الوزن الافتراضي</label>
                            <input type="text" name="weight" class="form-control"
                                value="{{ old('weight', $product->weight) }}">
                            <small class="text-muted">سيظهر إذا لم يكن للمنتج خيارات أوزان</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">درجة التحميص الافتراضية</label>
                            <select name="roast_level" class="form-select">
                                <option value="">اختر درجة التحميص</option>
                                <option value="light"
                                    {{ old('roast_level', $product->roast_level) == 'light' ? 'selected' : '' }}>فاتح
                                </option>
                                <option value="medium"
                                    {{ old('roast_level', $product->roast_level) == 'medium' ? 'selected' : '' }}>متوسط
                                </option>
                                <option value="dark"
                                    {{ old('roast_level', $product->roast_level) == 'dark' ? 'selected' : '' }}>داكن
                                </option>
                            </select>
                            <small class="text-muted">سيظهر إذا لم يكن للمنتج خيارات تحميص</small>
                        </div>
                    </div>
                </div>

                {{-- Product Options Section --}}
                <div class="admin-card mt-4">
                    <h5 class="mb-4">
                        <i class="bi bi-sliders me-2"></i>
                        خيارات المنتج (متعددة الأسعار)
                    </h5>
                    <p class="text-muted mb-4">فعّل الخيارات التي تريدها لهذا المنتج. <strong>الأوزان</strong> لها سعر كامل
                        لكل وزن، بينما <strong>التحميص والإضافات</strong> هي فروقات على السعر.</p>

                    {{-- Weight Options --}}
                    <div class="option-section mb-4 p-3 rounded"
                        style="background: rgba(201, 162, 39, 0.05); border: 1px solid rgba(201, 162, 39, 0.2);">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="has_weight_options"
                                name="has_weight_options" value="1"
                                {{ old('has_weight_options', $product->has_weight_options) ? 'checked' : '' }}
                                onchange="toggleOptionSection('weight')">
                            <label class="form-check-label fw-bold" for="has_weight_options">
                                <i class="bi bi-box-seam me-1"></i>
                                هذا المنتج له أوزان متعددة
                            </label>
                        </div>

                        <div id="weight_options_container" class="option-values-container"
                            style="{{ old('has_weight_options', $product->has_weight_options) ? '' : 'display: none;' }}">
                            <table class="table table-dark table-hover mb-2">
                                <thead>
                                    <tr>
                                        <th style="width: 50%">الوزن</th>
                                        <th style="width: 30%">السعر (ج.م) *</th>
                                        <th style="width: 10%">افتراضي</th>
                                        <th style="width: 10%"></th>
                                    </tr>
                                </thead>
                                <tbody id="weight_values_body">
                                    @php $weightVals = old('weight_values') ?: ($weightValues ?? collect())->map(fn($v) => ['id' => $v->id, 'value' => $v->value, 'price_modifier' => $v->price_modifier, 'is_default' => $v->is_default])->toArray(); @endphp
                                    @foreach ($weightVals as $index => $value)
                                        <tr>
                                            <td>
                                                <input type="hidden" name="weight_values[{{ $index }}][id]"
                                                    value="{{ $value['id'] ?? '' }}">
                                                <input type="text" name="weight_values[{{ $index }}][value]"
                                                    class="form-control form-control-sm"
                                                    value="{{ $value['value'] ?? '' }}" placeholder="مثال: 125 جم">
                                            </td>
                                            <td><input type="number"
                                                    name="weight_values[{{ $index }}][price_modifier]"
                                                    class="form-control form-control-sm"
                                                    value="{{ $value['price_modifier'] ?? 0 }}" step="0.01"></td>
                                            <td class="text-center">
                                                <input type="radio" name="weight_default" value="{{ $index }}"
                                                    class="form-check-input"
                                                    {{ $value['is_default'] ?? false ? 'checked' : '' }}
                                                    onchange="setDefaultValue('weight', {{ $index }})">
                                                <input type="hidden"
                                                    name="weight_values[{{ $index }}][is_default]"
                                                    value="{{ $value['is_default'] ?? false ? '1' : '0' }}"
                                                    class="is-default-hidden">
                                            </td>
                                            <td><button type="button" class="btn btn-sm btn-outline-danger"
                                                    onclick="removeOptionRow(this)"><i class="bi bi-trash"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
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
                                {{ old('has_roast_options', $product->has_roast_options) ? 'checked' : '' }}
                                onchange="toggleOptionSection('roast')">
                            <label class="form-check-label fw-bold" for="has_roast_options">
                                <i class="bi bi-fire me-1"></i>
                                هذا المنتج له درجات تحميص متعددة
                            </label>
                        </div>

                        <div id="roast_options_container" class="option-values-container"
                            style="{{ old('has_roast_options', $product->has_roast_options) ? '' : 'display: none;' }}">
                            <table class="table table-dark table-hover mb-2">
                                <thead>
                                    <tr>
                                        <th style="width: 50%">درجة التحميص</th>
                                        <th style="width: 30%">السعر الإضافي (ج.م)</th>
                                        <th style="width: 10%">افتراضي</th>
                                        <th style="width: 10%"></th>
                                    </tr>
                                </thead>
                                <tbody id="roast_values_body">
                                    @php $roastVals = old('roast_values') ?: ($roastValues ?? collect())->map(fn($v) => ['id' => $v->id, 'value' => $v->value, 'price_modifier' => $v->price_modifier, 'is_default' => $v->is_default])->toArray(); @endphp
                                    @foreach ($roastVals as $index => $value)
                                        <tr>
                                            <td>
                                                <input type="hidden" name="roast_values[{{ $index }}][id]"
                                                    value="{{ $value['id'] ?? '' }}">
                                                <input type="text" name="roast_values[{{ $index }}][value]"
                                                    class="form-control form-control-sm"
                                                    value="{{ $value['value'] ?? '' }}" placeholder="مثال: فاتح">
                                            </td>
                                            <td><input type="number"
                                                    name="roast_values[{{ $index }}][price_modifier]"
                                                    class="form-control form-control-sm"
                                                    value="{{ $value['price_modifier'] ?? 0 }}" step="0.01"></td>
                                            <td class="text-center">
                                                <input type="radio" name="roast_default" value="{{ $index }}"
                                                    class="form-check-input"
                                                    {{ $value['is_default'] ?? false ? 'checked' : '' }}
                                                    onchange="setDefaultValue('roast', {{ $index }})">
                                                <input type="hidden"
                                                    name="roast_values[{{ $index }}][is_default]"
                                                    value="{{ $value['is_default'] ?? false ? '1' : '0' }}"
                                                    class="is-default-hidden">
                                            </td>
                                            <td><button type="button" class="btn btn-sm btn-outline-danger"
                                                    onclick="removeOptionRow(this)"><i class="bi bi-trash"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="addOptionRow('roast')">
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
                                {{ old('has_additive_options', $product->has_additive_options) ? 'checked' : '' }}
                                onchange="toggleOptionSection('additive')">
                            <label class="form-check-label fw-bold" for="has_additive_options">
                                <i class="bi bi-plus-circle me-1"></i>
                                هذا المنتج له إضافات
                            </label>
                        </div>

                        <div id="additive_options_container" class="option-values-container"
                            style="{{ old('has_additive_options', $product->has_additive_options) ? '' : 'display: none;' }}">
                            <table class="table table-dark table-hover mb-2">
                                <thead>
                                    <tr>
                                        <th style="width: 50%">الإضافة</th>
                                        <th style="width: 30%">السعر الإضافي (ج.م)</th>
                                        <th style="width: 10%">افتراضي</th>
                                        <th style="width: 10%"></th>
                                    </tr>
                                </thead>
                                <tbody id="additive_values_body">
                                    @php $additiveVals = old('additive_values') ?: ($additiveValues ?? collect())->map(fn($v) => ['id' => $v->id, 'value' => $v->value, 'price_modifier' => $v->price_modifier, 'is_default' => $v->is_default])->toArray(); @endphp
                                    @foreach ($additiveVals as $index => $value)
                                        <tr>
                                            <td>
                                                <input type="hidden" name="additive_values[{{ $index }}][id]"
                                                    value="{{ $value['id'] ?? '' }}">
                                                <input type="text" name="additive_values[{{ $index }}][value]"
                                                    class="form-control form-control-sm"
                                                    value="{{ $value['value'] ?? '' }}" placeholder="مثال: بالهيل">
                                            </td>
                                            <td><input type="number"
                                                    name="additive_values[{{ $index }}][price_modifier]"
                                                    class="form-control form-control-sm"
                                                    value="{{ $value['price_modifier'] ?? 0 }}" step="0.01"></td>
                                            <td class="text-center">
                                                <input type="radio" name="additive_default"
                                                    value="{{ $index }}" class="form-check-input"
                                                    {{ $value['is_default'] ?? false ? 'checked' : '' }}
                                                    onchange="setDefaultValue('additive', {{ $index }})">
                                                <input type="hidden"
                                                    name="additive_values[{{ $index }}][is_default]"
                                                    value="{{ $value['is_default'] ?? false ? '1' : '0' }}"
                                                    class="is-default-hidden">
                                            </td>
                                            <td><button type="button" class="btn btn-sm btn-outline-danger"
                                                    onclick="removeOptionRow(this)"><i class="bi bi-trash"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
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

            <div class="col-lg-4">
                <div class="admin-card mb-4">
                    <h5 class="mb-4">التصنيف</h5>

                    <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="admin-card mb-4">
                    <h5 class="mb-4">صورة المنتج الرئيسية</h5>

                    <div class="image-upload-area mb-3" id="mainImageUpload">
                        <input type="file" name="image" id="imageInput" accept="image/*" class="d-none">
                        @if ($product->image)
                            <div id="mainImagePreview" class="image-preview">
                                <img src="{{ $product->image }}" alt="{{ $product->name }}" id="previewImg">
                                <button type="button" class="btn btn-sm btn-danger remove-image"
                                    onclick="removeMainImage()">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                            <label for="imageInput" class="upload-label d-none">
                                <i class="bi bi-cloud-arrow-up display-4"></i>
                                <p class="mb-1">اسحب الصورة هنا أو انقر للاختيار</p>
                                <small class="text-light opacity-75">PNG, JPG حتى 2MB</small>
                            </label>
                        @else
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
                        @endif
                    </div>
                    <small class="text-muted">اترك فارغاً للاحتفاظ بالصورة الحالية</small>
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
                    <div id="galleryPreview" class="d-flex flex-wrap gap-2">
                        @if ($product->gallery)
                            @foreach ($product->gallery as $galleryImage)
                                <div class="gallery-item">
                                    <img src="{{ $galleryImage }}" alt="Gallery">
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="admin-card mb-4">
                    <h5 class="mb-4">الحالة</h5>

                    <div class="form-check mb-3">
                        <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1"
                            {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">منتج نشط</label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" name="is_featured" class="form-check-input" id="is_featured"
                            value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_featured">منتج مميز</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-admin-primary w-100 btn-lg">
                    <i class="bi bi-check-lg me-2"></i>حفظ التعديلات
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

        .image-upload-area:hover {
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
        }

        .gallery-item {
            width: 80px;
            height: 80px;
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        const imageInput = document.getElementById('imageInput');
        const mainImagePreview = document.getElementById('mainImagePreview');
        const previewImg = document.getElementById('previewImg');
        const uploadLabel = document.querySelector('#mainImageUpload .upload-label');

        imageInput.addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    mainImagePreview.classList.remove('d-none');
                    if (uploadLabel) uploadLabel.classList.add('d-none');
                };
                reader.readAsDataURL(e.target.files[0]);
            }
        });

        function removeMainImage() {
            imageInput.value = '';
            previewImg.src = '';
            mainImagePreview.classList.add('d-none');
            if (uploadLabel) uploadLabel.classList.remove('d-none');
        }

        const galleryInput = document.getElementById('galleryInput');
        const galleryPreview = document.getElementById('galleryPreview');

        galleryInput.addEventListener('change', function(e) {
            if (e.target.files) {
                Array.from(e.target.files).forEach((file) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'gallery-item';
                        div.innerHTML = `<img src="${e.target.result}" alt="New">`;
                        galleryPreview.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });
            }
        });

        // ====== Product Options Management ======

        // Option row counters - initialize with existing rows count
        const optionCounters = {
            weight: document.querySelectorAll('#weight_values_body tr').length,
            roast: document.querySelectorAll('#roast_values_body tr').length,
            additive: document.querySelectorAll('#additive_values_body tr').length
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
            const priceLabel = type === 'weight' ? '' : '0';
            const pricePlaceholder = type === 'weight' ? 'السعر الكامل' : 'فرق السعر';

            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <input type="hidden" name="${type}_values[${index}][id]" value="">
                    <input type="text" name="${type}_values[${index}][value]" class="form-control form-control-sm" placeholder="${placeholders[type].ar}" required>
                </td>
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
