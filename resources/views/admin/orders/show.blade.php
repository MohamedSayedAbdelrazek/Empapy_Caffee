@extends('admin.layouts.app')

@section('title', 'تفاصيل الطلب ' . $order->order_number)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="page-header-admin">
            <h1 class="page-title-admin">تفاصيل الطلب</h1>
            <p class="page-subtitle-admin">{{ $order->order_number }}</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-light">
            <i class="bi bi-arrow-right me-2"></i>العودة للطلبات
        </a>
    </div>

    <div class="row g-4">
        <!-- Order Details -->
        <div class="col-lg-8">
            <div class="admin-card mb-4">
                <h5 class="mb-4">عناصر الطلب</h5>
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>المنتج</th>
                                <th>السعر</th>
                                <th>الكمية</th>
                                <th>المجموع</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            @if ($item->product)
                                                <img src="{{ $item->product->image }}" class="rounded"
                                                    style="width: 45px; height: 45px; object-fit: cover;">
                                            @endif
                                            <div>
                                                <strong>{{ $item->product_name_ar }}</strong>
                                                <br><small class="text-muted">{{ $item->product_name }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ number_format($item->price) }} ج.م</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td><strong>{{ number_format($item->total) }} ج.م</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="border-top">
                            <tr>
                                <td colspan="3" class="text-start">المجموع الفرعي</td>
                                <td>{{ number_format($order->subtotal) }} ج.م</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-start">التوصيل</td>
                                <td>{{ $order->shipping == 0 ? 'مجاني' : number_format($order->shipping) . ' ج.م' }}</td>
                            </tr>
                            @if ($order->discount > 0 || $order->coupon_code)
                                <tr class="text-success">
                                    <td colspan="3" class="text-start">
                                        <i class="bi bi-tag me-1"></i>الخصم
                                        @if ($order->coupon_code)
                                            <span class="badge bg-success ms-2">{{ $order->coupon_code }}</span>
                                        @endif
                                    </td>
                                    <td>- {{ number_format($order->discount) }} ج.م</td>
                                </tr>
                            @endif
                            <tr>
                                <td colspan="3" class="text-start"><strong>الإجمالي</strong></td>
                                <td><strong
                                        style="color: var(--admin-primary); font-size: 1.2rem;">{{ number_format($order->total) }}
                                        ج.م</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="admin-card">
                <h5 class="mb-4">معلومات العميل</h5>
                <div class="row g-4">
                    <div class="col-md-6">
                        <p class="text-muted mb-1">الاسم</p>
                        <p class="mb-3"><strong>{{ $order->customer_name }}</strong></p>

                        <p class="text-muted mb-1">البريد الإلكتروني</p>
                        <p class="mb-3"><strong>{{ $order->customer_email }}</strong></p>

                        <p class="text-muted mb-1">الهاتف</p>
                        <p dir="ltr" class="text-end mb-0"><strong>{{ $order->customer_phone }}</strong></p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1">العنوان</p>
                        <p class="mb-3"><strong>{{ $order->shipping_address }}</strong></p>

                        <p class="text-muted mb-1">المدينة / المحافظة</p>
                        <p class="mb-3"><strong>{{ $order->city }}
                                {{ $order->governorate ? '/ ' . $order->governorate : '' }}</strong></p>

                        @if ($order->notes)
                            <p class="text-muted mb-1">ملاحظات</p>
                            <p class="mb-0"><strong>{{ $order->notes }}</strong></p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Status -->
        <div class="col-lg-4">
            <div class="admin-card mb-4">
                <h5 class="mb-4">حالة الطلب</h5>

                <div class="mb-4">
                    <p class="text-muted mb-2">الحالة الحالية</p>
                    <span class="badge-status badge-{{ $order->status }} fs-6">
                        {{ $order->status_ar }}
                    </span>
                </div>

                <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="mb-4">
                    @csrf
                    @method('PATCH')
                    <label class="form-label">تغيير الحالة</label>
                    <select name="status" class="form-select mb-3">
                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                        <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>قيد المعالجة
                        </option>
                        <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>تم الشحن</option>
                        <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>تم التسليم
                        </option>
                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>ملغي</option>
                    </select>
                    <button type="submit" class="btn btn-admin-primary w-100">
                        <i class="bi bi-check-lg me-2"></i>تحديث الحالة
                    </button>
                </form>

                <hr class="border-secondary">

                <div class="mb-4">
                    <p class="text-muted mb-2">حالة الدفع</p>
                    @if ($order->payment_status === 'paid')
                        <span class="badge bg-success fs-6">مدفوع</span>
                    @else
                        <span class="badge bg-warning text-dark fs-6">غير مدفوع</span>
                    @endif
                </div>

                <p class="text-muted mb-2">طريقة الدفع</p>
                <p><strong>{{ $order->payment_method === 'cash_on_delivery' ? 'الدفع عند الاستلام' : $order->payment_method }}</strong>
                </p>
            </div>

            @if ($order->status !== 'cancelled')
                <div class="admin-card">
                    <h5 class="mb-4 text-danger">منطقة الخطر</h5>
                    <form action="{{ route('admin.orders.cancel', $order) }}" method="POST"
                        onsubmit="return confirm('هل أنت متأكد من إلغاء هذا الطلب؟ سيتم إرجاع المخزون.')">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="bi bi-x-circle me-2"></i>إلغاء الطلب
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection
