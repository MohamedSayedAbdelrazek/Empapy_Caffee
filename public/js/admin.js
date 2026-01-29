/**
 * Admin Dashboard JavaScript
 */

document.addEventListener('DOMContentLoaded', function () {
    initSidebar();
});

/**
 * Sidebar Toggle with Mobile Support
 */
function initSidebar() {
    const toggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('adminSidebar');
    const backdrop = document.getElementById('sidebarBackdrop');

    if (toggle && sidebar) {
        // Toggle sidebar on button click
        toggle.addEventListener('click', function (e) {
            e.stopPropagation();
            toggleSidebar();
        });

        // Close sidebar when clicking backdrop
        if (backdrop) {
            backdrop.addEventListener('click', function () {
                closeSidebar();
            });
        }

        // Close on outside click (mobile)
        document.addEventListener('click', function (e) {
            if (window.innerWidth < 992) {
                if (!sidebar.contains(e.target) && !toggle.contains(e.target) && sidebar.classList.contains('open')) {
                    closeSidebar();
                }
            }
        });

        // Close on ESC key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && sidebar.classList.contains('open')) {
                closeSidebar();
            }
        });
    }

    function toggleSidebar() {
        const isOpen = sidebar.classList.contains('open');
        if (isOpen) {
            closeSidebar();
        } else {
            openSidebar();
        }
    }

    function openSidebar() {
        sidebar.classList.add('open');
        if (backdrop) backdrop.classList.add('show');
        document.body.classList.add('sidebar-open');
    }

    function closeSidebar() {
        sidebar.classList.remove('open');
        if (backdrop) backdrop.classList.remove('show');
        document.body.classList.remove('sidebar-open');
    }
}

/**
 * Show empty state message for charts
 */
function showEmptyState(canvas, message = 'لا توجد بيانات متاحة') {
    const parent = canvas.parentElement;
    parent.innerHTML = `
        <div class="d-flex flex-column align-items-center justify-content-center h-100 text-muted">
            <i class="bi bi-bar-chart-line" style="font-size: 3rem; opacity: 0.3;"></i>
            <p class="mt-2 mb-0">${message}</p>
        </div>
    `;
}

/**
 * Initialize Charts
 */
function initCharts(data) {
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart');
    if (revenueCtx) {
        if (!data.ordersPerMonth || data.ordersPerMonth.length === 0) {
            showEmptyState(revenueCtx, 'لا توجد إيرادات مسجلة');
        } else {
            new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: data.ordersPerMonth.map(d => d.monthLabel),
                    datasets: [{
                        label: 'الإيرادات (ج.م)',
                        data: data.ordersPerMonth.map(d => d.revenue),
                        borderColor: '#c9a227',
                        backgroundColor: 'rgba(201, 162, 39, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#c9a227',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(255, 255, 255, 0.05)'
                            },
                            ticks: {
                                color: '#9ca3af'
                            }
                        },
                        x: {
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
    }

    // Orders Chart
    const ordersCtx = document.getElementById('ordersChart');
    if (ordersCtx) {
        if (!data.ordersPerMonth || data.ordersPerMonth.length === 0) {
            showEmptyState(ordersCtx, 'لا توجد طلبات مسجلة');
        } else {
            new Chart(ordersCtx, {
                type: 'bar',
                data: {
                    labels: data.ordersPerMonth.map(d => d.monthLabel),
                    datasets: [{
                        label: 'عدد الطلبات',
                        data: data.ordersPerMonth.map(d => d.count),
                        backgroundColor: 'rgba(201, 162, 39, 0.8)',
                        borderRadius: 8,
                        borderSkipped: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(255, 255, 255, 0.05)'
                            },
                            ticks: {
                                color: '#9ca3af'
                            }
                        },
                        x: {
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
    }

    // Category Revenue Chart (Doughnut)
    const categoryCtx = document.getElementById('categoryChart');
    if (categoryCtx) {
        if (!data.revenueByCategory || data.revenueByCategory.length === 0) {
            showEmptyState(categoryCtx, 'لا توجد بيانات عن الفئات');
        } else {
            new Chart(categoryCtx, {
                type: 'doughnut',
                data: {
                    labels: data.revenueByCategory.map(d => d.name),
                    datasets: [{
                        data: data.revenueByCategory.map(d => d.revenue),
                        backgroundColor: [
                            '#c9a227',
                            '#10b981',
                            '#3b82f6',
                            '#8b5cf6',
                            '#f59e0b'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#9ca3af',
                                padding: 20
                            }
                        }
                    },
                    cutout: '70%'
                }
            });
        }
    }

    // Order Status Chart
    const statusCtx = document.getElementById('statusChart');
    if (statusCtx) {
        if (!data.orderStatusCounts || Object.keys(data.orderStatusCounts).length === 0) {
            showEmptyState(statusCtx, 'لا توجد بيانات عن حالة الطلبات');
        } else {
            const statusLabels = {
                'pending': 'قيد الانتظار',
                'processing': 'قيد المعالجة',
                'shipped': 'تم الشحن',
                'delivered': 'تم التسليم',
                'cancelled': 'ملغي'
            };
            const statusColors = {
                'pending': '#f59e0b',
                'processing': '#3b82f6',
                'shipped': '#8b5cf6',
                'delivered': '#10b981',
                'cancelled': '#ef4444'
            };

            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(data.orderStatusCounts).map(k => statusLabels[k] || k),
                    datasets: [{
                        data: Object.values(data.orderStatusCounts),
                        backgroundColor: Object.keys(data.orderStatusCounts).map(k => statusColors[k] || '#6b7280'),
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#9ca3af',
                                padding: 15
                            }
                        }
                    },
                    cutout: '65%'
                }
            });
        }
    }
}
