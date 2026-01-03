@php $tier = $tier ?? null; @endphp

<div class="row g-4">
    @if (!$tier)
        <div class="col-md-6">
            <label class="form-label">المعرف (Slug) <span class="text-danger">*</span></label>
            <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror"
                value="{{ old('slug') }}" placeholder="gold" required>
            @error('slug')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    @endif

    <div class="col-md-6">
        <label class="form-label">الاسم (إنجليزي) <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $tier?->name) }}" placeholder="Gold" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">الاسم (عربي) <span class="text-danger">*</span></label>
        <input type="text" name="name_ar" class="form-control @error('name_ar') is-invalid @enderror"
            value="{{ old('name_ar', $tier?->name_ar) }}" placeholder="ذهبي" required>
        @error('name_ar')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">الحد الأدنى للنقاط <span class="text-danger">*</span></label>
        <input type="number" name="min_points" min="0"
            class="form-control @error('min_points') is-invalid @enderror"
            value="{{ old('min_points', $tier?->min_points ?? 0) }}" required>
        @error('min_points')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">الحد الأقصى للنقاط</label>
        <input type="number" name="max_points" min="0"
            class="form-control @error('max_points') is-invalid @enderror"
            value="{{ old('max_points', $tier?->max_points) }}" placeholder="فارغ = غير محدود">
        @error('max_points')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">نسبة الخصم %</label>
        <input type="number" name="discount_percent" min="0" max="100"
            class="form-control @error('discount_percent') is-invalid @enderror"
            value="{{ old('discount_percent', $tier?->discount_percent ?? 0) }}">
        @error('discount_percent')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">مضاعف النقاط</label>
        <input type="number" name="points_multiplier" min="1" max="10" step="0.1"
            class="form-control @error('points_multiplier') is-invalid @enderror"
            value="{{ old('points_multiplier', $tier?->points_multiplier ?? 1) }}">
        @error('points_multiplier')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">ترتيب العرض</label>
        <input type="number" name="sort_order" min="0"
            class="form-control @error('sort_order') is-invalid @enderror"
            value="{{ old('sort_order', $tier?->sort_order ?? 0) }}">
    </div>

    <div class="col-md-6">
        <label class="form-label">الأيقونة (Emoji) <span class="text-danger">*</span></label>
        <input type="text" name="icon" class="form-control @error('icon') is-invalid @enderror"
            value="{{ old('icon', $tier?->icon ?? '🥇') }}" style="font-size: 1.5rem;" required>
        @error('icon')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">اللون <span class="text-danger">*</span></label>
        <input type="color" name="color"
            class="form-control form-control-color @error('color') is-invalid @enderror"
            value="{{ old('color', $tier?->color ?? '#FFD700') }}" style="width: 100px; height: 45px;" required>
        @error('color')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label class="form-label">الوصف (عربي)</label>
        <textarea name="description_ar" rows="2" class="form-control @error('description_ar') is-invalid @enderror"
            placeholder="وصف اختياري للمستوى">{{ old('description_ar', $tier?->description_ar) }}</textarea>
    </div>

    <div class="col-md-4">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="free_shipping" value="1" id="free_shipping"
                {{ old('free_shipping', $tier?->free_shipping) ? 'checked' : '' }}>
            <label class="form-check-label" for="free_shipping">شحن مجاني</label>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
                {{ old('is_active', $tier?->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">نشط</label>
        </div>
    </div>
</div>

<hr class="my-4">

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary">
        <i class="bi bi-check-lg me-1"></i> حفظ
    </button>
    <a href="{{ route('admin.loyalty.tiers') }}" class="btn btn-outline-secondary">إلغاء</a>
</div>
