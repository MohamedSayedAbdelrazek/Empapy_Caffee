@extends('admin.layouts.app')

@section('title', 'إعدادات الموقع')

@section('content')
    <div class="container-fluid px-4">
        <div class="mb-4">
            <h1 class="mt-4">⚙️ إعدادات التوصيل</h1>
            <p class="text-muted">تحكم في شروط وأسعار التوصيل</p>
        </div>

        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Shipping Settings --}}
            @if (isset($settings['shipping']))
                <div class="card shadow mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-truck me-2"></i>إعدادات التوصيل
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="shipping_free_threshold" class="form-label">
                                        الحد الأدنى للتوصيل المجاني (ج.م) <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" class="form-control" id="shipping_free_threshold"
                                        name="settings[shipping][shipping_free_threshold]"
                                        value="{{ $settings['shipping']['shipping_free_threshold'] ?? 500 }}" min="0"
                                        step="1" required>
                                    <small class="form-text text-muted">
                                        الطلبات الأكثر من هذا المبلغ سيكون التوصيل مجاناً
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="shipping_fee" class="form-label">
                                        سعر التوصيل (ج.م) <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" class="form-control" id="shipping_fee"
                                        name="settings[shipping][shipping_fee]"
                                        value="{{ $settings['shipping']['shipping_fee'] ?? 50 }}" min="0"
                                        step="1" required>
                                    <small class="form-text text-muted">
                                        سعر التوصيل للطلبات الأقل من الحد الأدنى
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Shipping Zones Management Link --}}
            <div class="card shadow mb-4 border-start-lg border-start-info">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="fw-bold text-info mb-1">
                                <i class="bi bi-geo-alt me-2"></i>أسعار الشحن حسب المحافظة
                            </h5>
                            <p class="text-muted mb-0 small">يمكنك تخصيص سعر الشحن لكل محافظة على حدة (القاهرة، الإسكندرية، إلخ)</p>
                        </div>
                        <a href="{{ route('admin.shipping-zones.index') }}" class="btn btn-info text-white">
                            إدارة المناطق والأسعار <i class="bi bi-arrow-left ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Save Button --}}
            <div class="d-flex justify-content-end gap-2 mb-4">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-save me-2"></i>حفظ جميع الإعدادات
                </button>
            </div>
        </form>
    </div>
@endsection
