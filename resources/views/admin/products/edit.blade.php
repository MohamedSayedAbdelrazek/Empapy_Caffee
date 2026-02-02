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
                {{-- Basic Product Information --}}
                <div class="admin-card mb-4">
                    <h5 class="mb-4">
                        <i class="bi bi-box-seam me-2 text-warning"></i>
                        معلومات المنتج الأساسية
                    </h5>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">اسم المنتج <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $product->name) }}" required placeholder="أدخل اسم المنتج">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">وصف المنتج</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="أضف وصفاً تفصيلياً للمنتج...">{{ old('description', $product->description) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Pricing Section --}}
                <div class="admin-card mb-4">
                    <h5 class="mb-4">
                        <i class="bi bi-currency-dollar me-2 text-success"></i>
                        التسعير
                    </h5>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">السعر (ج.م) <span class="text-danger">*</span></label>
                            <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                                value="{{ old('price', $product->price) }}" step="0.01" min="0" required>
                            <div class="form-text text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                في حالة وجود خيارات أوزان، ضع سعر أقل وزن هنا كـ "يبدأ من"
                            </div>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">سعر التخفيض (اختياري)</label>
                            <input type="number" name="sale_price" class="form-control @error('sale_price') is-invalid @enderror" 
                                value="{{ old('sale_price', $product->sale_price) }}" step="0.01" min="0" placeholder="اتركه فارغاً إذا لا يوجد خصم">
                            @error('sale_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Smart Product Configuration --}}
                <div class="admin-card mb-4">
                    <h5 class="mb-4">
                        <i class="bi bi-magic me-2 text-primary"></i>
                        تكوين المنتج الذكي
                    </h5>
                    <p class="text-muted mb-4">
                        <i class="bi bi-lightbulb me-1"></i>
                        اختر خصائص المنتج وسيتم عرض الحقول المناسبة تلقائياً
                    </p>

                    {{-- Product Type Selection Cards --}}
                    <div class="row g-3 mb-4">
                        {{-- Is Coffee Product --}}
                        <div class="col-md-6 col-lg-3">
                            <div class="product-type-card {{ old('is_coffee_product', $product->is_coffee_product ?? false) ? 'active' : '' }}" id="coffee_card" onclick="toggleProductType('coffee')">
                                <input type="checkbox" name="is_coffee_product" id="is_coffee_product" value="1" 
                                    {{ old('is_coffee_product', $product->is_coffee_product ?? false) ? 'checked' : '' }} class="d-none product-type-checkbox">
                                <div class="type-icon">
                                    <i class="bi bi-cup-hot"></i>
                                </div>
                                <h6 class="type-title">منتج قهوة / بن</h6>
                                <p class="type-desc">له درجات تحميص ووزن افتراضي</p>
                            </div>
                        </div>

                        {{-- Has Weight Options --}}
                        <div class="col-md-6 col-lg-3">
                            <div class="product-type-card {{ old('has_weight_options', $product->has_weight_options) ? 'active' : '' }}" id="weight_card" onclick="toggleProductType('weight')">
                                <input type="checkbox" name="has_weight_options" id="has_weight_options" value="1" 
                                    {{ old('has_weight_options', $product->has_weight_options) ? 'checked' : '' }} class="d-none product-type-checkbox">
                                <div class="type-icon type-icon-blue">
                                    <i class="bi bi-box-seam"></i>
                                </div>
                                <h6 class="type-title">أوزان متعددة</h6>
                                <p class="type-desc">متوفر بأوزان مختلفة بأسعار مختلفة</p>
                            </div>
                        </div>

                        {{-- Has Roast Options --}}
                        <div class="col-md-6 col-lg-3">
                            <div class="product-type-card {{ old('has_roast_options', $product->has_roast_options) ? 'active' : '' }}" id="roast_card" onclick="toggleProductType('roast')">
                                <input type="checkbox" name="has_roast_options" id="has_roast_options" value="1" 
                                    {{ old('has_roast_options', $product->has_roast_options) ? 'checked' : '' }} class="d-none product-type-checkbox">
                                <div class="type-icon type-icon-red">
                                    <i class="bi bi-fire"></i>
                                </div>
                                <h6 class="type-title">درجات تحميص</h6>
                                <p class="type-desc">متوفر بدرجات تحميص مختلفة</p>
                            </div>
                        </div>

                        {{-- Has Additives --}}
                        <div class="col-md-6 col-lg-3">
                            <div class="product-type-card {{ old('has_additive_options', $product->has_additive_options) ? 'active' : '' }}" id="additive_card" onclick="toggleProductType('additive')">
                                <input type="checkbox" name="has_additive_options" id="has_additive_options" value="1" 
                                    {{ old('has_additive_options', $product->has_additive_options) ? 'checked' : '' }} class="d-none product-type-checkbox">
                                <div class="type-icon type-icon-green">
                                    <i class="bi bi-plus-circle"></i>
                                </div>
                                <h6 class="type-title">إضافات</h6>
                                <p class="type-desc">يمكن إضافة نكهات أو مكونات إضافية</p>
                            </div>
                        </div>
                    </div>

                    {{-- Selected Options Summary --}}
                    <div class="selected-options-summary mb-4" id="options_summary" style="display: none;">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="badge bg-secondary">الخيارات المفعّلة:</span>
                            <span class="badge bg-warning text-dark option-badge" id="badge_coffee" style="display: none;">
                                <i class="bi bi-cup-hot me-1"></i>منتج قهوة
                            </span>
                            <span class="badge bg-primary option-badge" id="badge_weight" style="display: none;">
                                <i class="bi bi-box-seam me-1"></i>أوزان متعددة
                            </span>
                            <span class="badge bg-danger option-badge" id="badge_roast" style="display: none;">
                                <i class="bi bi-fire me-1"></i>درجات تحميص
                            </span>
                            <span class="badge bg-success option-badge" id="badge_additive" style="display: none;">
                                <i class="bi bi-plus-circle me-1"></i>إضافات
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Coffee Details Section (Conditional) --}}
                <div class="admin-card mb-4 conditional-section" id="coffee_details_section" style="display: none;">
                    <h5 class="mb-4">
                        <i class="bi bi-cup-hot me-2 text-warning"></i>
                        تفاصيل القهوة
                    </h5>

                    <div class="row g-3">
                        <div class="col-md-6" id="default_weight_field">
                            <label class="form-label">الوزن الافتراضي</label>
                            <input type="text" name="weight" class="form-control" value="{{ old('weight', $product->weight) }}"
                                placeholder="مثال: 250g">
                            <small class="text-muted">للمنتجات ذات الوزن الثابت</small>
                        </div>
                        <div class="col-md-6" id="default_roast_field">
                            <label class="form-label">درجة التحميص الافتراضية</label>
                            <select name="roast_level" class="form-select">
                                <option value="">اختر درجة التحميص...</option>
                                <option value="light" {{ old('roast_level', $product->roast_level) === 'light' ? 'selected' : '' }}>فاتح (Light)</option>
                                <option value="medium" {{ old('roast_level', $product->roast_level) === 'medium' ? 'selected' : '' }}>متوسط (Medium)</option>
                                <option value="dark" {{ old('roast_level', $product->roast_level) === 'dark' ? 'selected' : '' }}>داكن (Dark)</option>
                            </select>
                            <small class="text-muted">للمنتجات ذات درجة تحميص ثابتة</small>
                        </div>
                    </div>
                </div>

                {{-- Weight Options Section (Conditional) --}}
                <div class="admin-card mb-4 conditional-section" id="weight_section" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0">
                            <i class="bi bi-box-seam me-2 text-primary"></i>
                            خيارات الأوزان
                        </h5>
                        <button type="button" class="btn btn-sm btn-primary" onclick="addOptionRow('weight')">
                            <i class="bi bi-plus-lg me-1"></i>إضافة وزن
                        </button>
                    </div>
                    <p class="text-muted mb-3">
                        <i class="bi bi-info-circle me-1"></i>
                        <strong>الأوزان لها سعر كامل لكل وزن</strong> - مثال: 125 جم بـ 50 ج.م، 250 جم بـ 90 ج.م
                    </p>

                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 45%">الوزن</th>
                                    <th style="width: 35%">السعر (ج.م) <span class="text-danger">*</span></th>
                                    <th style="width: 10%">افتراضي</th>
                                    <th style="width: 10%"></th>
                                </tr>
                            </thead>
                            <tbody id="weight_values_body">
                                @php $weightVals = old('weight_values') ?: ($weightValues ?? collect())->map(fn($v) => ['id' => $v->id, 'value' => $v->value, 'price_modifier' => $v->price_modifier, 'is_default' => $v->is_default])->toArray(); @endphp
                                @foreach ($weightVals as $index => $value)
                                    <tr>
                                        <td>
                                            <input type="hidden" name="weight_values[{{ $index }}][id]" value="{{ $value['id'] ?? '' }}">
                                            <input type="text" name="weight_values[{{ $index }}][value]"
                                                class="form-control form-control-sm"
                                                value="{{ $value['value'] ?? '' }}" placeholder="مثال: 125 جم">
                                        </td>
                                        <td><input type="number" name="weight_values[{{ $index }}][price_modifier]"
                                                class="form-control form-control-sm"
                                                value="{{ $value['price_modifier'] ?? 0 }}" step="0.01" required></td>
                                        <td class="text-center">
                                            <input type="radio" name="weight_default" value="{{ $index }}" 
                                                class="form-check-input" {{ ($value['is_default'] ?? false) ? 'checked' : '' }}
                                                onchange="setDefaultValue('weight', {{ $index }})">
                                            <input type="hidden" name="weight_values[{{ $index }}][is_default]" 
                                                value="{{ ($value['is_default'] ?? false) ? '1' : '0' }}" class="is-default-hidden">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeOptionRow(this)">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Roast Options Section (Conditional) --}}
                <div class="admin-card mb-4 conditional-section" id="roast_section" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0">
                            <i class="bi bi-fire me-2 text-danger"></i>
                            درجات التحميص
                        </h5>
                        <button type="button" class="btn btn-sm btn-danger" onclick="addOptionRow('roast')">
                            <i class="bi bi-plus-lg me-1"></i>إضافة درجة تحميص
                        </button>
                    </div>
                    <p class="text-muted mb-3">
                        <i class="bi bi-info-circle me-1"></i>
                        <strong>درجات التحميص تُضاف كفرق سعر</strong> - مثال: فاتح +0 ج.م، متوسط +5 ج.م، داكن +10 ج.م
                    </p>

                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 45%">درجة التحميص</th>
                                    <th style="width: 35%">السعر الإضافي (ج.م)</th>
                                    <th style="width: 10%">افتراضي</th>
                                    <th style="width: 10%"></th>
                                </tr>
                            </thead>
                            <tbody id="roast_values_body">
                                @php $roastVals = old('roast_values') ?: ($roastValues ?? collect())->map(fn($v) => ['id' => $v->id, 'value' => $v->value, 'price_modifier' => $v->price_modifier, 'is_default' => $v->is_default])->toArray(); @endphp
                                @foreach ($roastVals as $index => $value)
                                    <tr>
                                        <td>
                                            <input type="hidden" name="roast_values[{{ $index }}][id]" value="{{ $value['id'] ?? '' }}">
                                            <input type="text" name="roast_values[{{ $index }}][value]"
                                                class="form-control form-control-sm"
                                                value="{{ $value['value'] ?? '' }}" placeholder="مثال: فاتح">
                                        </td>
                                        <td><input type="number" name="roast_values[{{ $index }}][price_modifier]"
                                                class="form-control form-control-sm"
                                                value="{{ $value['price_modifier'] ?? 0 }}" step="0.01"></td>
                                        <td class="text-center">
                                            <input type="radio" name="roast_default" value="{{ $index }}" 
                                                class="form-check-input" {{ ($value['is_default'] ?? false) ? 'checked' : '' }}
                                                onchange="setDefaultValue('roast', {{ $index }})">
                                            <input type="hidden" name="roast_values[{{ $index }}][is_default]" 
                                                value="{{ ($value['is_default'] ?? false) ? '1' : '0' }}" class="is-default-hidden">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeOptionRow(this)">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Additive Options Section (Conditional) --}}
                <div class="admin-card mb-4 conditional-section" id="additive_section" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0">
                            <i class="bi bi-plus-circle me-2 text-success"></i>
                            الإضافات
                        </h5>
                        <button type="button" class="btn btn-sm btn-success" onclick="addOptionRow('additive')">
                            <i class="bi bi-plus-lg me-1"></i>إضافة جديدة
                        </button>
                    </div>
                    
                    {{-- Notice about pricing matrix --}}
                    <div class="alert alert-info small mb-3" id="additive_price_notice" style="display: none;">
                        <i class="bi bi-info-circle me-1"></i>
                        <strong>ملاحظة:</strong> بما أنك فعّلت الأوزان المتعددة، سيتم تحديد سعر كل إضافة حسب الوزن في المصفوفة أدناه.
                    </div>

                    <p class="text-muted mb-3" id="additive_simple_notice">
                        <i class="bi bi-info-circle me-1"></i>
                        <strong>الإضافات تُضاف كفرق سعر</strong> - مثال: بالهيل +15 ج.م، سادة +0 ج.م
                    </p>

                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0" id="additive_table">
                            <thead>
                                <tr>
                                    <th style="width: 45%">الإضافة</th>
                                    <th style="width: 35%" id="additive_price_header">السعر الإضافي (ج.م)</th>
                                    <th style="width: 10%">افتراضي</th>
                                    <th style="width: 10%"></th>
                                </tr>
                            </thead>
                            <tbody id="additive_values_body">
                                @php $additiveVals = old('additive_values') ?: ($additiveValues ?? collect())->map(fn($v) => ['id' => $v->id, 'value' => $v->value, 'price_modifier' => $v->price_modifier, 'is_default' => $v->is_default])->toArray(); @endphp
                                @foreach ($additiveVals as $index => $value)
                                    <tr>
                                        <td>
                                            <input type="hidden" name="additive_values[{{ $index }}][id]" value="{{ $value['id'] ?? '' }}">
                                            <input type="text" name="additive_values[{{ $index }}][value]"
                                                class="form-control form-control-sm"
                                                value="{{ $value['value'] ?? '' }}" placeholder="مثال: بالهيل">
                                        </td>
                                        <td class="additive-price-cell"><input type="number" name="additive_values[{{ $index }}][price_modifier]"
                                                class="form-control form-control-sm"
                                                value="{{ $value['price_modifier'] ?? 0 }}" step="0.01"></td>
                                        <td class="text-center">
                                            <input type="radio" name="additive_default" value="{{ $index }}" 
                                                class="form-check-input" {{ ($value['is_default'] ?? false) ? 'checked' : '' }}
                                                onchange="setDefaultValue('additive', {{ $index }})">
                                            <input type="hidden" name="additive_values[{{ $index }}][is_default]" 
                                                value="{{ ($value['is_default'] ?? false) ? '1' : '0' }}" class="is-default-hidden">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeOptionRow(this)">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Additive Weight Pricing Matrix (Conditional - when both weights and additives) --}}
                <div class="admin-card mb-4 conditional-section" id="pricing_matrix_section" style="display: none;">
                    <h5 class="mb-4">
                        <i class="bi bi-grid-3x3-gap-fill me-2 text-purple"></i>
                        مصفوفة أسعار الإضافات حسب الوزن
                    </h5>
                    <p class="text-muted mb-3">
                        <i class="bi bi-lightbulb me-1"></i>
                        حدد سعر كل إضافة لكل وزن. مثلاً: "بالهيل" قد تكلف +15 ج.م للوزن 125 جم و +25 ج.م للوزن 250 جم
                    </p>

                    <div class="table-responsive">
                        <table class="table table-dark table-bordered text-center mb-0" id="pricing_matrix_table">
                            <thead id="pricing_matrix_head">
                                <tr>
                                    <th class="text-end" style="min-width: 150px;">الإضافة \ الوزن</th>
                                </tr>
                            </thead>
                            <tbody id="pricing_matrix_body">
                            </tbody>
                        </table>
                    </div>
                    <small class="text-muted d-block mt-2">
                        <i class="bi bi-info-circle me-1"></i>
                        اترك الخانة 0 إذا كانت الإضافة مجانية لهذا الوزن
                    </small>
                </div>
            </div>

            <div class="col-lg-4">
                {{-- Category Selection --}}
                <div class="admin-card mb-4">
                    <h5 class="mb-4">
                        <i class="bi bi-folder me-2 text-warning"></i>
                        التصنيف
                    </h5>

                    <label class="form-label">الصنف <span class="text-danger">*</span></label>
                    <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                        <option value="">اختر الصنف...</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Main Image Upload --}}
                <div class="admin-card mb-4">
                    <h5 class="mb-4">
                        <i class="bi bi-image me-2 text-info"></i>
                        صورة المنتج الرئيسية
                    </h5>

                    <div class="image-upload-area mb-3" id="mainImageUpload">
                        <input type="file" name="image" id="imageInput" accept="image/*" class="d-none">
                        @if ($product->image)
                            <div id="mainImagePreview" class="image-preview">
                                <img src="{{ $product->image }}" alt="{{ $product->name }}" id="previewImg">
                                <button type="button" class="btn btn-sm btn-danger remove-image" onclick="removeMainImage()">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                            <label for="imageInput" class="upload-label d-none">
                                <i class="bi bi-cloud-arrow-up display-4"></i>
                                <p class="mb-1">اسحب الصورة هنا أو انقر للاختيار</p>
                                <small class="text-light opacity-75">PNG, JPG, WebP حتى 2MB</small>
                            </label>
                        @else
                            <label for="imageInput" class="upload-label">
                                <i class="bi bi-cloud-arrow-up display-4"></i>
                                <p class="mb-1">اسحب الصورة هنا أو انقر للاختيار</p>
                                <small class="text-light opacity-75">PNG, JPG, WebP حتى 2MB</small>
                            </label>
                            <div id="mainImagePreview" class="image-preview d-none">
                                <img src="" alt="Preview" id="previewImg">
                                <button type="button" class="btn btn-sm btn-danger remove-image" onclick="removeMainImage()">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                        @endif
                    </div>
                    <small class="text-muted">اترك فارغاً للاحتفاظ بالصورة الحالية</small>
                    @error('image')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Gallery Upload --}}
                <div class="admin-card mb-4">
                    <h5 class="mb-4">
                        <i class="bi bi-images me-2 text-secondary"></i>
                        صور إضافية (اختياري)
                    </h5>

                    <div class="image-upload-area mb-3" id="galleryUpload">
                        <input type="file" name="gallery[]" id="galleryInput" accept="image/*" class="d-none" multiple>
                        <label for="galleryInput" class="upload-label">
                            <i class="bi bi-images display-4"></i>
                            <p class="mb-1">اختر صور متعددة</p>
                            <small class="text-light opacity-75">PNG, JPG, WebP حتى 2MB لكل صورة</small>
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

                {{-- Status --}}
                <div class="admin-card mb-4">
                    <h5 class="mb-4">
                        <i class="bi bi-toggle-on me-2 text-success"></i>
                        الحالة
                    </h5>

                    <div class="form-check form-switch mb-3">
                        <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1"
                            {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            <i class="bi bi-check-circle me-1"></i>منتج نشط
                        </label>
                    </div>

                    <div class="form-check form-switch">
                        <input type="checkbox" name="is_featured" class="form-check-input" id="is_featured" value="1"
                            {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_featured">
                            <i class="bi bi-star me-1"></i>منتج مميز
                        </label>
                    </div>
                </div>

                {{-- Submit Button --}}
                <button type="submit" class="btn btn-admin-primary w-100 btn-lg">
                    <i class="bi bi-check-lg me-2"></i>حفظ التعديلات
                </button>
            </div>
        </div>
    </form>
@endsection

@push('styles')
    <style>
        /* Product Type Cards */
        .product-type-card {
            background: rgba(255, 255, 255, 0.03);
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 20px 15px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            height: 100%;
        }

        .product-type-card:hover {
            border-color: rgba(201, 162, 39, 0.5);
            background: rgba(201, 162, 39, 0.05);
            transform: translateY(-2px);
        }

        .product-type-card.active {
            border-color: var(--admin-primary);
            background: rgba(201, 162, 39, 0.15);
            box-shadow: 0 0 20px rgba(201, 162, 39, 0.2);
        }

        .product-type-card .type-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: rgba(201, 162, 39, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            font-size: 1.5rem;
            color: var(--admin-primary);
            transition: all 0.3s ease;
        }

        .product-type-card.active .type-icon {
            background: var(--admin-primary);
            color: #000;
            transform: scale(1.1);
        }

        .product-type-card .type-icon-blue {
            background: rgba(59, 130, 246, 0.2);
            color: #3b82f6;
        }

        .product-type-card.active .type-icon-blue {
            background: #3b82f6;
            color: #fff;
        }

        .product-type-card .type-icon-red {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }

        .product-type-card.active .type-icon-red {
            background: #ef4444;
            color: #fff;
        }

        .product-type-card .type-icon-green {
            background: rgba(16, 185, 129, 0.2);
            color: #10b981;
        }

        .product-type-card.active .type-icon-green {
            background: #10b981;
            color: #fff;
        }

        .product-type-card .type-title {
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 5px;
            color: #fff;
        }

        .product-type-card .type-desc {
            font-size: 0.75rem;
            color: #9ca3af;
            margin-bottom: 0;
            line-height: 1.4;
        }

        /* Selected Options Summary */
        .selected-options-summary {
            background: rgba(201, 162, 39, 0.1);
            border: 1px solid rgba(201, 162, 39, 0.3);
            border-radius: 8px;
            padding: 12px 15px;
        }

        .option-badge {
            font-size: 0.8rem;
            padding: 5px 10px;
        }

        /* Conditional Sections Animation */
        .conditional-section {
            animation: fadeSlideIn 0.3s ease;
        }

        @keyframes fadeSlideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Image Upload */
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

        /* Purple color for matrix */
        .text-purple {
            color: #8b5cf6 !important;
        }

        /* Hide additive price column when matrix is active */
        .hide-price-column .additive-price-cell,
        .hide-price-column #additive_price_header {
            display: none !important;
        }

        /* Pricing Matrix Styles */
        #pricing_matrix_table input {
            width: 80px;
            margin: 0 auto;
        }

        #pricing_matrix_table th,
        #pricing_matrix_table td {
            vertical-align: middle;
        }

        /* Alert info style */
        .alert-info {
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.3);
            color: #93c5fd;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Prepare existing matrix data
        @php
            $matrixPrices = [];
            if ($product->has_additive_options && $product->has_weight_options) {
                $additiveIds = ($additiveValues ?? collect())->pluck('id')->filter();
                $prices = \App\Models\AdditiveWeightPrice::whereIn('additive_option_value_id', $additiveIds)->get();
                foreach ($prices as $price) {
                    $matrixPrices[$price->additive_option_value_id][$price->weight_option_value_id] = $price->price_modifier;
                }
            }
        @endphp

        const existingMatrixPrices = @json($matrixPrices);

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Update all sections based on current state
            updateAllSections();
        });

        // Toggle product type
        function toggleProductType(type) {
            const card = document.getElementById(`${type}_card`);
            const checkbox = document.getElementById(type === 'coffee' ? 'is_coffee_product' : `has_${type}_options`);
            
            checkbox.checked = !checkbox.checked;
            card.classList.toggle('active', checkbox.checked);
            
            updateAllSections();
        }

        // Update all sections based on selected types
        function updateAllSections() {
            const isCoffee = document.getElementById('is_coffee_product').checked;
            const hasWeights = document.getElementById('has_weight_options').checked;
            const hasRoast = document.getElementById('has_roast_options').checked;
            const hasAdditives = document.getElementById('has_additive_options').checked;

            // Update badges
            document.getElementById('badge_coffee').style.display = isCoffee ? 'inline-block' : 'none';
            document.getElementById('badge_weight').style.display = hasWeights ? 'inline-block' : 'none';
            document.getElementById('badge_roast').style.display = hasRoast ? 'inline-block' : 'none';
            document.getElementById('badge_additive').style.display = hasAdditives ? 'inline-block' : 'none';

            // Show/hide summary
            const hasAnyOption = isCoffee || hasWeights || hasRoast || hasAdditives;
            document.getElementById('options_summary').style.display = hasAnyOption ? 'block' : 'none';

            // Coffee details section - show only relevant fields
            const coffeeSection = document.getElementById('coffee_details_section');
            const defaultWeightField = document.getElementById('default_weight_field');
            const defaultRoastField = document.getElementById('default_roast_field');
            
            // Hide weight field if multiple weights enabled
            defaultWeightField.style.display = hasWeights ? 'none' : 'block';
            // Hide roast field if multiple roasts enabled
            defaultRoastField.style.display = hasRoast ? 'none' : 'block';
            
            // Show coffee section only if: is coffee AND at least one field is visible
            const hasVisibleFields = !hasWeights || !hasRoast;
            coffeeSection.style.display = (isCoffee && hasVisibleFields) ? 'block' : 'none';

            // Weight section
            const weightSection = document.getElementById('weight_section');
            if (hasWeights) {
                weightSection.style.display = 'block';
                const tbody = document.getElementById('weight_values_body');
                if (tbody.children.length === 0) {
                    addOptionRow('weight');
                }
            } else {
                weightSection.style.display = 'none';
            }

            // Roast section
            const roastSection = document.getElementById('roast_section');
            if (hasRoast) {
                roastSection.style.display = 'block';
                const tbody = document.getElementById('roast_values_body');
                if (tbody.children.length === 0) {
                    addOptionRow('roast');
                }
            } else {
                roastSection.style.display = 'none';
            }

            // Additive section
            const additiveSection = document.getElementById('additive_section');
            if (hasAdditives) {
                additiveSection.style.display = 'block';
                const tbody = document.getElementById('additive_values_body');
                if (tbody.children.length === 0) {
                    addOptionRow('additive');
                }
            } else {
                additiveSection.style.display = 'none';
            }

            // Pricing matrix (when both weights and additives)
            const matrixSection = document.getElementById('pricing_matrix_section');
            const additivePriceNotice = document.getElementById('additive_price_notice');
            const additiveSimpleNotice = document.getElementById('additive_simple_notice');
            const additiveTable = document.getElementById('additive_table');

            if (hasWeights && hasAdditives) {
                matrixSection.style.display = 'block';
                additivePriceNotice.style.display = 'block';
                additiveSimpleNotice.style.display = 'none';
                // Hide price column in additive table
                additiveTable.classList.add('hide-price-column');
                updatePricingMatrix();
            } else {
                matrixSection.style.display = 'none';
                additivePriceNotice.style.display = 'none';
                additiveSimpleNotice.style.display = 'block';
                additiveTable.classList.remove('hide-price-column');
            }
        }

        // Option row counters
        const optionCounters = {
            weight: document.querySelectorAll('#weight_values_body tr').length,
            roast: document.querySelectorAll('#roast_values_body tr').length,
            additive: document.querySelectorAll('#additive_values_body tr').length
        };

        // Add new option row
        function addOptionRow(type) {
            const tbody = document.getElementById(`${type}_values_body`);
            const index = optionCounters[type]++;

            const placeholders = {
                weight: 'مثال: 125 جم',
                roast: 'مثال: فاتح',
                additive: 'مثال: بالهيل'
            };

            const isFirstRow = tbody.children.length === 0;
            const hasWeights = document.getElementById('has_weight_options').checked;

            let priceCellContent = '';
            if (type === 'additive' && hasWeights) {
                priceCellContent = `<span class="text-muted small">حسب المصفوفة</span>
                    <input type="hidden" name="additive_values[${index}][price_modifier]" value="0">`;
            } else {
                const priceRequired = type === 'weight' ? 'required' : '';
                const priceValue = type === 'weight' ? '' : '0';
                priceCellContent = `<input type="number" name="${type}_values[${index}][price_modifier]"
                    class="form-control form-control-sm" value="${priceValue}" step="0.01" ${priceRequired}>`;
            }

            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <input type="hidden" name="${type}_values[${index}][id]" value="">
                    <input type="text" name="${type}_values[${index}][value]"
                        class="form-control form-control-sm" placeholder="${placeholders[type]}" required>
                </td>
                <td class="additive-price-cell">${priceCellContent}</td>
                <td class="text-center">
                    <input type="radio" name="${type}_default" value="${index}" 
                        class="form-check-input" ${isFirstRow ? 'checked' : ''}
                        onchange="setDefaultValue('${type}', ${index})">
                    <input type="hidden" name="${type}_values[${index}][is_default]" 
                        value="${isFirstRow ? '1' : '0'}" class="is-default-hidden">
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeOptionRow(this)">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            `;

            tbody.appendChild(row);

            // Update matrix if applicable
            if (type === 'weight' || type === 'additive') {
                updatePricingMatrix();
            }
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

            // Update matrix if applicable
            if (type === 'weight' || type === 'additive') {
                updatePricingMatrix();
            }
        }

        // Set default value
        function setDefaultValue(type, selectedIndex) {
            const tbody = document.getElementById(`${type}_values_body`);
            
            // Reset all
            tbody.querySelectorAll('.is-default-hidden').forEach(input => {
                input.value = '0';
            });

            // Set selected
            const selectedRadio = tbody.querySelector(`input[type="radio"][value="${selectedIndex}"]`);
            if (selectedRadio) {
                const hiddenInput = selectedRadio.closest('td').querySelector('.is-default-hidden');
                if (hiddenInput) hiddenInput.value = '1';
            }
        }

        // Re-index option rows
        function reindexOptionRows(type) {
            const tbody = document.getElementById(`${type}_values_body`);
            const rows = tbody.querySelectorAll('tr');

            rows.forEach((row, index) => {
                row.querySelectorAll('input').forEach(input => {
                    const name = input.getAttribute('name');
                    if (name) {
                        input.setAttribute('name', name.replace(/\[\d+\]/, `[${index}]`));
                    }
                    if (input.type === 'radio') {
                        input.value = index;
                        input.setAttribute('onchange', `setDefaultValue('${type}', ${index})`);
                    }
                });
            });

            optionCounters[type] = rows.length;
        }

        // Update pricing matrix
        function updatePricingMatrix() {
            const hasWeights = document.getElementById('has_weight_options').checked;
            const hasAdditives = document.getElementById('has_additive_options').checked;
            
            if (!hasWeights || !hasAdditives) return;

            const weights = getOptionValues('weight');
            const additives = getOptionValues('additive');

            if (weights.length === 0 || additives.length === 0) return;

            // Build header
            let headerHtml = '<tr><th class="text-end" style="min-width: 150px;">الإضافة \\ الوزن</th>';
            weights.forEach((weight, wIdx) => {
                headerHtml += `<th style="min-width: 100px;">${weight.name || 'الوزن ' + (wIdx + 1)}</th>`;
            });
            headerHtml += '</tr>';
            document.getElementById('pricing_matrix_head').innerHTML = headerHtml;

            // Build body
            let bodyHtml = '';
            additives.forEach((additive, aIdx) => {
                bodyHtml += `<tr><td class="text-end fw-bold">${additive.name || 'الإضافة ' + (aIdx + 1)}</td>`;
                weights.forEach((weight, wIdx) => {
                    const inputName = `additive_weight_prices[${aIdx}][${wIdx}]`;
                    const existingValue = getExistingMatrixValue(aIdx, wIdx, additive.id, weight.id);
                    bodyHtml += `<td>
                        <input type="number" name="${inputName}" 
                            class="form-control form-control-sm text-center" 
                            value="${existingValue}" step="0.01" placeholder="0">
                    </td>`;
                });
                bodyHtml += '</tr>';
            });
            document.getElementById('pricing_matrix_body').innerHTML = bodyHtml;
        }

        // Get option values (including IDs for existing items)
        function getOptionValues(type) {
            const tbody = document.getElementById(`${type}_values_body`);
            const rows = tbody.querySelectorAll('tr');
            const values = [];

            rows.forEach((row, index) => {
                const nameInput = row.querySelector('input[type="text"]');
                const idInput = row.querySelector('input[name$="[id]"]');
                values.push({
                    index: index,
                    name: nameInput ? nameInput.value : '',
                    id: idInput ? idInput.value : null
                });
            });

            return values;
        }

        // Get existing matrix value
        function getExistingMatrixValue(additiveIdx, weightIdx, additiveId, weightId) {
            // Check for dirty value in DOM first
            const input = document.querySelector(`input[name="additive_weight_prices[${additiveIdx}][${weightIdx}]"]`);
            if (input) return input.value;

            // Check in backend data using IDs
            if (additiveId && weightId && existingMatrixPrices[additiveId] && existingMatrixPrices[additiveId][weightId] !== undefined) {
                return existingMatrixPrices[additiveId][weightId];
            }

            return '0';
        }

        // Listen for changes to update matrix labels
        document.addEventListener('input', function(e) {
            if (e.target.matches('#weight_values_body input[type="text"], #additive_values_body input[type="text"]')) {
                updatePricingMatrix();
            }
        });

        // ====== Image Upload ======
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
                    if (uploadLabel) uploadLabel.classList.add('d-none');
                };
                reader.readAsDataURL(e.target.files[0]);
            }
        });

        // Drag and drop
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            mainImageUpload.addEventListener(eventName, e => {
                e.preventDefault();
                e.stopPropagation();
            }, false);
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            mainImageUpload.addEventListener(eventName, () => mainImageUpload.classList.add('dragover'), false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            mainImageUpload.addEventListener(eventName, () => mainImageUpload.classList.remove('dragover'), false);
        });

        mainImageUpload.addEventListener('drop', function(e) {
            const files = e.dataTransfer.files;
            if (files.length) {
                imageInput.files = files;
                imageInput.dispatchEvent(new Event('change'));
            }
        }, false);

        function removeMainImage() {
            imageInput.value = '';
            previewImg.src = '';
            mainImagePreview.classList.add('d-none');
            if (uploadLabel) uploadLabel.classList.remove('d-none');
        }

        // Gallery upload
        const galleryInput = document.getElementById('galleryInput');
        const galleryPreview = document.getElementById('galleryPreview');

        galleryInput.addEventListener('change', function(e) {
            if (e.target.files) {
                Array.from(e.target.files).forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'gallery-item';
                        div.innerHTML = `<img src="${e.target.result}" alt="New Gallery">`;
                        galleryPreview.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });
            }
        });

        // Make functions global
        window.toggleProductType = toggleProductType;
        window.addOptionRow = addOptionRow;
        window.removeOptionRow = removeOptionRow;
        window.setDefaultValue = setDefaultValue;
        window.removeMainImage = removeMainImage;
    </script>
@endpush
