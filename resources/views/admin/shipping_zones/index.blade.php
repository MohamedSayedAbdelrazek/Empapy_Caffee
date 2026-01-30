@extends('admin.layouts.app')

@section('title', 'مناطق الشحن')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">مناطق الشحن وتكلفتها</h1>
        <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-right me-1"></i> العودة للإعدادات
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">أسعار التوصيل للمحافظات</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>المحافظة</th>
                            <th>تكلفة الشحن (ج.م)</th>
                            <th>الحالة</th>
                            <th>إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($zones as $zone)
                            <tr>
                                <form action="{{ route('admin.shipping-zones.update', $zone) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <td class="align-middle fw-bold text-gray-900">{{ $zone->name }}</td>
                                    <td>
                                        <input type="number" name="fee"
                                            class="form-control bg-light border-secondary text-dark"
                                            value="{{ $zone->fee }}" min="0" step="1" required
                                            style="width: 120px; font-weight: bold;">
                                    </td>
                                    <td class="align-middle">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                                id="active_{{ $zone->id }}" {{ $zone->is_active ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold text-dark"
                                                for="active_{{ $zone->id }}">مفعل</label>
                                        </div>
                                    </td>
                                    <td>
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="bi bi-save me-1"></i> حفظ
                                        </button>
                                    </td>
                                </form>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
