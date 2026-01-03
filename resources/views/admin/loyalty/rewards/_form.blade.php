@php $reward = $reward ?? null; @endphp

<div class="row g-4">
    <div class="col-md-6">
        <label class="form-label">الاسم (إنجليزي) <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $reward?->name) }}" placeholder="10% Discount" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">الاسم (عربي) <span class="text-danger">*</span></label>
        <input type="text" name="name_ar" class="form-control @error('name_ar') is-invalid @enderror"
            value="{{ old('name_ar', $reward?->name_ar) }}" placeholder="خصم 10%" required>
        @error('name_ar')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">نوع المكافأة <span class="text-danger">*</span></label>
        <select name="reward_type" class="form-select @error('reward_type') is-invalid @enderror" required>
            <option value="discount_fixed"
                {{ old('reward_type', $reward?->reward_type) === 'discount_fixed' ? 'selected' : '' }}>خصم ثابت (ج.م)
            </option>
            <option value="discount_percent"
                {{ old('reward_type', $reward?->reward_type) === 'discount_percent' ? 'selected' : '' }}>خصم نسبة (%)
            </option>
            <option value="free_shipping"
                {{ old('reward_type', $reward?->reward_type) === 'free_shipping' ? 'selected' : '' }}>شحن مجاني</option>
            <option value="free_product"
                {{ old('reward_type', $reward?->reward_type) === 'free_product' ? 'selected' : '' }}>منتج مجاني</option>
        </select>
        @error('reward_type')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">قيمة المكافأة</label>
        <input type="number" name="reward_value" min="0" step="0.01"
            class="form-control @error('reward_value') is-invalid @enderror"
            value="{{ old('reward_value', $reward?->reward_value) }}" placeholder="10">
        @error('reward_value')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="text-muted">للخصم الثابت: القيمة بالجنيه. للنسبة: النسبة المئوية.</small>
    </div>

    <div class="col-md-6">
        <label class="form-label">النقاط المطلوبة <span class="text-danger">*</span></label>
        <input type="number" name="points_required" min="1"
            class="form-control @error('points_required') is-invalid @enderror"
            value="{{ old('points_required', $reward?->points_required) }}" placeholder="100" required>
        @error('points_required')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">الأيقونة (Emoji)</label>
        <input type="text" name="icon" class="form-control @error('icon') is-invalid @enderror"
            value="{{ old('icon', $reward?->icon ?? '🎁') }}" style="font-size: 1.5rem;">
        @error('icon')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">المخزون</label>
        <input type="number" name="stock" min="0" class="form-control @error('stock') is-invalid @enderror"
            value="{{ old('stock', $reward?->stock) }}" placeholder="فارغ = غير محدود">
        @error('stock')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">الحد لكل مستخدم</label>
        <input type="number" name="max_per_user" min="1"
            class="form-control @error('max_per_user') is-invalid @enderror"
            value="{{ old('max_per_user', $reward?->max_per_user) }}" placeholder="فارغ = غير محدود">
        @error('max_per_user')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">ترتيب العرض</label>
        <input type="number" name="sort_order" min="0"
            class="form-control @error('sort_order') is-invalid @enderror"
            value="{{ old('sort_order', $reward?->sort_order ?? 0) }}">
    </div>

    @if (isset($tiers) && $tiers->count() > 0)
        <div class="col-md-6">
            <label class="form-label">المستوى المطلوب</label>
            <select name="tier_required" class="form-select @error('tier_required') is-invalid @enderror">
                <option value="">بدون شرط</option>
                @foreach ($tiers as $tier)
                    <option value="{{ $tier->slug }}"
                        {{ old('tier_required', $reward?->tier_required) === $tier->slug ? 'selected' : '' }}>
                        {{ $tier->icon }} {{ $tier->name_ar }}
                    </option>
                @endforeach
            </select>
            @error('tier_required')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    @endif

    <div class="col-12">
        <label class="form-label">الوصف (عربي)</label>
        <textarea name="description_ar" rows="2" class="form-control @error('description_ar') is-invalid @enderror"
            placeholder="وصف اختياري للمكافأة">{{ old('description_ar', $reward?->description_ar) }}</textarea>
    </div>

    <div class="col-md-4">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
                {{ old('is_active', $reward?->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">نشط</label>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured"
                {{ old('is_featured', $reward?->is_featured) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_featured">مميز</label>
        </div>
    </div>
</div>

<hr class="my-4">

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary">
        <i class="bi bi-check-lg me-1"></i> حفظ
    </button>
    <a href="{{ route('admin.loyalty.rewards') }}" class="btn btn-outline-secondary">إلغاء</a>
</div>
