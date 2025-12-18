@extends('admin.layouts.app')

@section('title', 'الطلبات')

@section('content')
    <div class="page-header-admin mb-4">
        <h1 class="page-title-admin">الطلبات</h1>
        <p class="page-subtitle-admin">إدارة طلبات العملاء</p>
    </div>

    <!-- Filters -->
    <div class="admin-card mb-4">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="بحث برقم الطلب أو اسم العميل..."
                    value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">كل الحالات</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                    <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>قيد المعالجة
                    </option>
                    <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>تم الشحن</option>
                    <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>تم التسليم</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>ملغي</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="payment_status" class="form-select">
                    <option value="">حالة الدفع</option>
                    <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>في الانتظار
                    </option>
                    <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>مدفوع</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-light w-100">
                    <i class="bi bi-filter"></i> تصفية
                </button>
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="admin-card">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>رقم الطلب</th>
                        <th>العميل</th>
                        <th>الهاتف</th>
                        <th>الإجمالي</th>
                        <th>الحالة</th>
                        <th>الدفع</th>
                        <th>التاريخ</th>
                        <th style="width: 100px;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td><strong>{{ $order->order_number }}</strong></td>
                            <td>{{ $order->customer_name }}</td>
                            <td dir="ltr" class="text-end">{{ $order->customer_phone }}</td>
                            <td><strong>{{ number_format($order->total) }} ج.م</strong></td>
                            <td>
                                <span class="badge-status badge-{{ $order->status }}">
                                    {{ $order->status_ar }}
                                </span>
                            </td>
                            <td>
                                @if ($order->payment_status === 'paid')
                                    <span class="badge bg-success">مدفوع</span>
                                @else
                                    <span class="badge bg-warning text-dark">غير مدفوع</span>
                                @endif
                            </td>
                            <td>{{ $order->created_at->format('Y/m/d H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-light">
                                    <i class="bi bi-eye"></i> عرض
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="bi bi-receipt display-4 d-block mb-3"></i>
                                لا توجد طلبات
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($orders->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $orders->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection
