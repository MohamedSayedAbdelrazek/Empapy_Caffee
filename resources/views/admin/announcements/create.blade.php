@extends('admin.layouts.app')

@section('title', 'إضافة إعلان جديد')

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mt-4">إضافة إعلان جديد</h1>
            <a href="{{ route('admin.announcements.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-right me-2"></i>رجوع
            </a>
        </div>

        <div class="card shadow">
            <div class="card-body">
                <form action="{{ route('admin.announcements.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="message_ar" class="form-label">رسالة الإعلان <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('message_ar') is-invalid @enderror" id="message_ar" name="message_ar"
                            rows="3" required>{{ old('message_ar') }}</textarea>
                        @error('message_ar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">يمكنك إضافة رموز (emoji) في بداية الرسالة مثل: 🚚 📦 ⭐ ⏰</small>
                    </div>

                    <!-- Hidden icon field with default value -->
                    <input type="hidden" name="icon" value="bi-star-fill">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="order" class="form-label">الترتيب <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('order') is-invalid @enderror"
                                    id="order" name="order" value="{{ old('order', 0) }}" min="0" required>
                                @error('order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">ترتيب ظهور الإعلان (الأقل رقماً يظهر أولاً)</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1"
                            {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            نشط (يظهر في الموقع)
                        </label>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>حفظ الإعلان
                        </button>
                        <a href="{{ route('admin.announcements.index') }}" class="btn btn-secondary">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
