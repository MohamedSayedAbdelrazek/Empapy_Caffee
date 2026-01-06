/**
 * Admin Dashboard JavaScript
 */

document.addEventListener('DOMContentLoaded', function () {
    initSidebar();
});

/**
 * Sidebar Toggle
 */
function initSidebar() {
    const toggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('adminSidebar');

    if (toggle && sidebar) {
        toggle.addEventListener('click', function () {
            sidebar.classList.toggle('open');
        });

        // Close on outside click (mobile)
        document.addEventListener('click', function (e) {
            if (window.innerWidth < 992) {
                if (!sidebar.contains(e.target) && !toggle.contains(e.target)) {
                    sidebar.classList.remove('open');
                }
            }
        });
    }
}

/**
 * Initialize Charts
 */
function initCharts(data) {
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart');
    if (revenueCtx && data.ordersPerMonth) {
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

    // Orders Chart
    const ordersCtx = document.getElementById('ordersChart');
    if (ordersCtx && data.ordersPerMonth) {
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

    // Category Revenue Chart (Doughnut)
    const categoryCtx = document.getElementById('categoryChart');
    if (categoryCtx && data.revenueByCategory && data.revenueByCategory.length > 0) {
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

    // Order Status Chart
    const statusCtx = document.getElementById('statusChart');
    if (statusCtx && data.orderStatusCounts) {
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
