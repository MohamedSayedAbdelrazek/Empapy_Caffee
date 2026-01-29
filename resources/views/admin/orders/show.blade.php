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
                                <tr
                                    @if ($item->is_reward_item) class="table-success" style="background: rgba(16, 185, 129, 0.15);" @endif>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            @if ($item->product)
                                                <img src="{{ $item->product->image }}" class="rounded"
                                                    style="width: 45px; height: 45px; object-fit: cover;">
                                            @endif
                                            <div>
                                                <strong>{{ $item->product_name }}</strong>
                                                @if ($item->is_reward_item)
                                                    <span class="badge bg-success ms-2">
                                                        <i class="bi bi-gift me-1"></i>مكافأة مجانية
                                                    </span>
                                                @endif
                                                @if ($item->reward_note)
                                                    <br><small class="text-success"><i
                                                            class="bi bi-star-fill me-1"></i>{{ $item->reward_note }}</small>
                                                @endif
                                                @if ($item->selectedOptions->count() > 0)
                                                    <div class="mt-1">
                                                        @foreach ($item->selectedOptions as $option)
                                                            <span class="badge bg-light text-dark border me-1">
                                                                {{ $option->option_name }}: {{ $option->value_name }}
                                                                @if ($option->price_modifier != 0)
                                                                    ({{ $option->formatted_price_modifier }})
                                                                @endif
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($item->is_reward_item)
                                            <span class="text-success fw-bold">مجاني</span>
                                        @else
                                            {{ number_format($item->price) }} ج.م
                                        @endif
                                    </td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>
                                        @if ($item->is_reward_item)
                                            <strong class="text-success">0 ج.م</strong>
                                        @else
                                            <strong>{{ number_format($item->total) }} ج.م</strong>
                                        @endif
                                    </td>
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
                            {{-- Free Product Reward Alert --}}
                            @if (isset($rewardRedemption) &&
                                    $rewardRedemption &&
                                    $rewardRedemption->reward &&
                                    $rewardRedemption->reward->reward_type === 'free_product')
                                <tr class="{{ $rewardRedemption->gift_fulfilled ? 'table-success' : 'table-warning' }}"
                                    style="background: rgba({{ $rewardRedemption->gift_fulfilled ? '16, 185, 129' : '245, 158, 11' }}, 0.15);">
                                    <td colspan="4" class="text-start">
                                        <div class="py-2">
                                            <div class="d-flex align-items-center gap-3 mb-2">
                                                <div
                                                    class="bg-{{ $rewardRedemption->gift_fulfilled ? 'success' : 'warning' }} bg-opacity-25 rounded-circle p-2">
                                                    <i class="bi bi-gift text-{{ $rewardRedemption->gift_fulfilled ? 'success' : 'warning' }}"
                                                        style="font-size: 1.5rem;"></i>
                                                </div>
                                                <div>
                                                    @if ($rewardRedemption->gift_fulfilled)
                                                        <strong class="text-success d-block">
                                                            <i class="bi bi-check-circle me-1"></i>
                                                            تم تجهيز الهدية المجانية ✓
                                                        </strong>
                                                    @else
                                                        <strong class="text-warning d-block">
                                                            <i class="bi bi-exclamation-triangle me-1"></i>
                                                            مطلوب تجهيز هدية مجانية - مكافأة الولاء
                                                        </strong>
                                                    @endif
                                                    <small class="text-muted">كود المكافأة:
                                                        {{ $rewardRedemption->redemption_code }}</small>
                                                </div>
                                            </div>

                                            @if ($rewardRedemption->gift_fulfilled && $rewardRedemption->gift_note)
                                                {{-- Show the gift note --}}
                                                <div class="alert alert-success mb-0 py-2">
                                                    <i class="bi bi-box-seam me-1"></i>
                                                    <strong>الهدية:</strong> {{ $rewardRedemption->gift_note }}
                                                </div>
                                            @else
                                                {{-- Show gift note input form --}}
                                                <form action="{{ route('admin.orders.gift-note', $order) }}" method="POST"
                                                    class="mt-2">
                                                    @csrf
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-warning text-dark">
                                                            <i class="bi bi-pencil"></i>
                                                        </span>
                                                        <input type="text" name="gift_note" class="form-control"
                                                            placeholder="اكتب اسم الهدية المجانية (مثال: كيس قهوة 100 جرام)"
                                                            required value="{{ $rewardRedemption->gift_note }}">
                                                        <button type="submit" class="btn btn-success">
                                                            <i class="bi bi-check-lg me-1"></i>
                                                            تم التجهيز
                                                        </button>
                                                    </div>
                                                    <small class="text-muted mt-1 d-block">
                                                        💡 اكتب اسم المنتج الذي ستضيفه كهدية مجانية للعميل
                                                    </small>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
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
