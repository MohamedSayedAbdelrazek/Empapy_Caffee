@extends('admin.layouts.app')

@section('title', 'لوحة التحكم')

@section('content')
    <div class="page-header-admin">
        <h1 class="page-title-admin">لوحة التحكم</h1>
        <p class="page-subtitle-admin">مرحباً بك في لوحة تحكم إمبابي كافيه</p>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="admin-card stat-card">
                <div class="stat-icon primary">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div class="stat-value">{{ number_format($totalRevenue) }} ج.م</div>
                <div class="stat-label">إجمالي الإيرادات</div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="admin-card stat-card">
                <div class="stat-icon success">
                    <i class="bi bi-receipt"></i>
                </div>
                <div class="stat-value">{{ number_format($totalOrders) }}</div>
                <div class="stat-label">إجمالي الطلبات</div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="admin-card stat-card">
                <div class="stat-icon warning">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div class="stat-value">{{ number_format($pendingOrders) }}</div>
                <div class="stat-label">طلبات قيد الانتظار</div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="admin-card stat-card">
                <div class="stat-icon info">
                    <i class="bi bi-people"></i>
                </div>
                <div class="stat-value">{{ number_format($totalUsers) }}</div>
                <div class="stat-label">العملاء المسجلين</div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <div class="col-xl-8">
            <div class="admin-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">الإيرادات الشهرية</h5>
                    <span class="badge bg-success">آخر 6 أشهر</span>
                </div>
                <div class="chart-container">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="admin-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">حالة الطلبات</h5>
                </div>
                <div class="chart-container" style="height: 250px;">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-6">
            <div class="admin-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">الطلبات الشهرية</h5>
                </div>
                <div class="chart-container" style="height: 250px;">
                    <canvas id="ordersChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="admin-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">الإيرادات حسب الفئة</h5>
                </div>
                <div class="chart-container" style="height: 250px;">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Products Chart -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="admin-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0"><i class="bi bi-bar-chart-fill me-2"></i>أفضل 5 منتجات مبيعاً</h5>
                    <span class="badge bg-success">إحصائيات المبيعات</span>
                </div>
                <div class="chart-container" style="height: 300px;">
                    <canvas id="topProductsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders & Best Sellers -->
    <div class="row g-4">
        <div class="col-xl-8">
            <div class="admin-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">أحدث الطلبات</h5>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-light">عرض الكل</a>
                </div>
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>رقم الطلب</th>
                                <th>العميل</th>
                                <th>الإجمالي</th>
                                <th>الحالة</th>
                                <th>التاريخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                                <tr>
                                    <td><strong>{{ $order->order_number }}</strong></td>
                                    <td>{{ $order->customer_name }}</td>
                                    <td>{{ number_format($order->total) }} ج.م</td>
                                    <td>
                                        <span class="badge-status badge-{{ $order->status }}">
                                            {{ $order->status_ar }}
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">لا توجد طلبات حتى الآن</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="admin-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">الأكثر مبيعاً</h5>
                </div>
                @forelse($bestSellers as $product)
                    <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom border-secondary">
                        <x-optimized-image :src="$product->image" :alt="$product->name" class="rounded"
                            style="width: 50px; height: 50px; object-fit: cover;" />
                        <div class="flex-grow-1">
                            <h6 class="mb-1 small">{{ $product->name }}</h6>
                            <small class="text-muted">{{ number_format($product->price) }} ج.م</small>
                        </div>
                        <span class="badge bg-success">{{ $product->total_sold }} مبيعات</span>
                    </div>
                @empty
                    <p class="text-muted text-center py-4">لا توجد مبيعات بعد</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Month names in Arabic
            const monthNames = ['', 'يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو',
                'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'
            ];

            // Raw data from PHP
            const rawOrdersPerMonth = @json($ordersPerMonth);

            // Transform data with Arabic month names
            const ordersPerMonth = rawOrdersPerMonth.map(function(item) {
                return {
                    month: item.month,
                    monthLabel: monthNames[item.month] || item.month,
                    count: item.count,
                    revenue: item.revenue || 0
                };
            });

            const chartData = {
                ordersPerMonth: ordersPerMonth,
                revenueByCategory: @json($revenueByCategory),
                orderStatusCounts: @json($orderStatusCounts),
                bestSellers: @json($bestSellers)
            };

            initCharts(chartData);
            initTopProductsChart(chartData.bestSellers);
        });

        function initTopProductsChart(bestSellers) {
            const ctx = document.getElementById('topProductsChart');
            if (!ctx || !bestSellers || bestSellers.length === 0) return;

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: bestSellers.map(p => p.name),
                    datasets: [{
                        label: 'عدد المبيعات',
                        data: bestSellers.map(p => p.total_sold),
                        backgroundColor: [
                            'rgba(201, 162, 39, 0.9)',
                            'rgba(16, 185, 129, 0.9)',
                            'rgba(59, 130, 246, 0.9)',
                            'rgba(139, 92, 246, 0.9)',
                            'rgba(245, 158, 11, 0.9)'
                        ],
                        borderRadius: 8,
                        borderSkipped: false
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(255, 255, 255, 0.05)'
                            },
                            ticks: {
                                color: '#9ca3af'
                            }
                        },
                        y: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#9ca3af'
                            }
                        }
                    }
                }
            });
        }
    </script>
@endpush
