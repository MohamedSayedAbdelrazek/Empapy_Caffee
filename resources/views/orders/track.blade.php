@extends('layouts.app')

@section('title', 'تتبع طلبك - إمبابي كافيه')
@section('meta_description', 'تتبع حالة طلبك في إمبابي كافيه')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1 class="page-title" data-aos="fade-up">تتبع طلبك</h1>
            <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item active">تتبع الطلب</li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <!-- Search Form -->
                    <div class="glass-card p-4 mb-4" data-aos="fade-up">
                        <h5 class="mb-3"><i class="bi bi-search me-2"></i>ابحث عن طلبك</h5>
                        <form action="{{ route('orders.search') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <input type="text" name="order_number" class="form-control form-control-lg"
                                    placeholder="أدخل رقم الطلب (مثال: EMP-XXXXX)"
                                    value="{{ old('order_number', request('order_number')) }}" required>
                            </div>
                            <div class="input-group">
                                <input type="text" name="verification" class="form-control form-control-lg"
                                    placeholder="البريد الإلكتروني أو رقم الهاتف المسجل بالطلب"
                                    value="{{ old('verification') }}" required>
                                <button type="submit" class="btn btn-golden btn-lg">
                                    <i class="bi bi-search me-2"></i>بحث
                                </button>
                            </div>
                            <small class="text-muted d-block mt-2">
                                <i class="bi bi-shield-lock me-1"></i>لحماية خصوصيتك، أدخل البريد الإلكتروني أو رقم الهاتف المستخدم عند إتمام الطلب
                            </small>
                        </form>

                        @if (session('error'))
                            <div class="alert alert-danger mt-3 mb-0">
                                <i class="bi bi-x-circle me-2"></i>{{ session('error') }}
                            </div>
                        @endif
                    </div>

                    @isset($order)
                        <!-- Order Found -->
                        <div class="glass-card p-4" data-aos="fade-up">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div>
                                    <h4 class="mb-1">طلب رقم: {{ $order->order_number }}</h4>
                                    <small class="text-muted">{{ $order->created_at->format('Y/m/d - h:i A') }}</small>
                                </div>
                                <span class="badge-status badge-{{ $order->status }} fs-6">
                                    {{ $order->status_ar }}
                                </span>
                            </div>

                            <!-- Order Timeline -->
                            <div class="order-timeline mb-4">
                                @php
                                    $statuses = ['pending', 'processing', 'shipped', 'delivered'];
                                    $currentIndex = array_search($order->status, $statuses);
                                    $isCancelled = $order->status === 'cancelled';
                                @endphp

                                @if ($isCancelled)
                                    <div class="alert alert-danger">
                                        <i class="bi bi-x-circle me-2"></i>تم إلغاء هذا الطلب
                                    </div>
                                @else
                                    <div class="timeline-track">
                                        @foreach ($statuses as $index => $status)
                                            @php
                                                $isActive = $index <= $currentIndex;
                                                $isCurrent = $index === $currentIndex;
                                                $statusLabels = [
                                                    'pending' => 'قيد الانتظار',
                                                    'processing' => 'قيد التجهيز',
                                                    'shipped' => 'تم الشحن',
                                                    'delivered' => 'تم التسليم',
                                                ];
                                                $statusIcons = [
                                                    'pending' => 'bi-clock',
                                                    'processing' => 'bi-gear',
                                                    'shipped' => 'bi-truck',
                                                    'delivered' => 'bi-check-circle',
                                                ];
                                            @endphp
                                            <div
                                                class="timeline-step {{ $isActive ? 'active' : '' }} {{ $isCurrent ? 'current' : '' }}">
                                                <div class="step-icon">
                                                    <i class="bi {{ $statusIcons[$status] }}"></i>
                                                </div>
                                                <div class="step-label">{{ $statusLabels[$status] }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <hr>

                            <!-- Order Items -->
                            <h5 class="mb-3">عناصر الطلب</h5>
                            @foreach ($order->items as $item)
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    @if ($item->product)
                                        <x-optimized-image :src="$item->product->image" :alt="$item->product_name" class="rounded"
                                            style="width: 60px; height: 60px; object-fit: cover;" />
                                    @endif
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">{{ $item->product_name }}</h6>
                                        <small class="text-muted">الكمية: {{ $item->quantity }} ×
                                            {{ number_format($item->price) }} ج.م</small>
                                    </div>
                                    <strong>{{ number_format($item->total) }} ج.م</strong>
                                </div>
                            @endforeach

                            <hr>

                            <!-- Order Summary -->
                            <div class="d-flex justify-content-between mb-2">
                                <span>المجموع الفرعي</span>
                                <span>{{ number_format($order->subtotal) }} ج.م</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>التوصيل</span>
                                <span>{{ $order->shipping == 0 ? 'مجاني' : number_format($order->shipping) . ' ج.م' }}</span>
                            </div>
                            @if ($order->discount > 0)
                                <div class="d-flex justify-content-between mb-2 text-success">
                                    <span><i class="bi bi-tag me-1"></i>الخصم</span>
                                    <span>- {{ number_format($order->discount) }} ج.م</span>
                                </div>
                            @endif
                            <div class="d-flex justify-content-between">
                                <strong class="fs-5">الإجمالي</strong>
                                <strong class="fs-5" style="color: var(--gold);">{{ number_format($order->total) }}
                                    ج.م</strong>
                            </div>
                        </div>
                    @endisset
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <style>
        .order-timeline {
            padding: 20px 0;
        }

        .timeline-track {
            display: flex;
            justify-content: space-between;
            position: relative;
        }

        .timeline-track::before {
            content: '';
            position: absolute;
            top: 25px;
            left: 50px;
            right: 50px;
            height: 4px;
            background: var(--gray-300);
            z-index: 0;
        }

        .timeline-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 1;
            flex: 1;
        }

        .step-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: var(--gray-500);
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }

        .timeline-step.active .step-icon {
            background: var(--success);
            color: white;
        }

        .timeline-step.current .step-icon {
            background: var(--gold);
            color: var(--espresso);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }
        }

        .step-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--gray-500);
            text-align: center;
        }

        .timeline-step.active .step-label {
            color: var(--espresso);
        }

        @media (max-width: 576px) {
            .step-label {
                font-size: 0.7rem;
            }

            .step-icon {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }
        }
    </style>
@endpush