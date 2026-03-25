<div wire:ignore>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Chart --}}
        <div class="lg:col-span-2 glass-card p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-black text-text-main tracking-tight">Hoạt động theo tháng</h3>
                <div class="flex items-center gap-4 text-xs font-bold">
                    <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-primary"></span> Sản
                        phẩm</span>
                    <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-success"></span> Bài
                        viết</span>
                    <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-accent"></span> Người
                        dùng</span>
                </div>
            </div>
            <div id="revenueChart" class="w-full h-80"></div>
        </div>

        {{-- Distribution Chart --}}
        <div class="glass-card p-6">
            <h3 class="text-lg font-black text-text-main tracking-tight mb-4">Phân bổ nội dung</h3>
            <div id="distributionChart" class="w-full h-64 flex items-center justify-center"></div>
            <div class="mt-4 space-y-2">
                <div
                    class="flex items-center justify-between text-xs font-bold uppercase tracking-widest text-text-muted">
                    <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-primary"></span> Sản
                        phẩm</span>
                    <span class="text-text-main">{{ $this->monthlyData['products'][11] ?? 0 }}</span>
                </div>
                <div
                    class="flex items-center justify-between text-xs font-bold uppercase tracking-widest text-text-muted">
                    <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-success"></span> Bài
                        viết</span>
                    <span class="text-text-main">{{ $this->monthlyData['articles'][11] ?? 0 }}</span>
                </div>
                <div
                    class="flex items-center justify-between text-xs font-bold uppercase tracking-widest text-text-muted">
                    <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-accent"></span> Người
                        dùng</span>
                    <span class="text-text-main">{{ $this->monthlyData['users'][11] ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const labels = @json($labels);
            const productsData = @json($monthlyData['products'] ?? []);
            const articlesData = @json($monthlyData['articles'] ?? []);
            const usersData = @json($monthlyData['users'] ?? []);

            // Area Chart
            const revenueOptions = {
                series: [{
                    name: 'Sản phẩm',
                    data: productsData
                }, {
                    name: 'Bài viết',
                    data: articlesData
                }, {
                    name: 'Người dùng',
                    data: usersData
                }],
                chart: {
                    height: 320,
                    type: 'area',
                    toolbar: {
                        show: false
                    },
                    fontFamily: 'Inter, sans-serif',
                    background: 'transparent'
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 2
                },
                xaxis: {
                    categories: labels,
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        style: {
                            colors: '#64748b'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: '#64748b'
                        }
                    }
                },
                grid: {
                    borderColor: 'rgba(255,255,255,0.05)',
                    strokeDashArray: 4,
                },
                colors: ['#a855f7', '#10b981', '#f59e0b'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.15,
                        opacityTo: 0.05,
                        stops: [0, 90, 100],
                    }
                },
                tooltip: {
                    theme: 'dark'
                },
                legend: {
                    show: false
                }
            };
            new ApexCharts(document.querySelector('#revenueChart'), revenueOptions).render();

            // Distribution Donut
            const totalProducts = productsData.reduce((a, b) => a + b, 0);
            const totalArticles = articlesData.reduce((a, b) => a + b, 0);
            const totalUsers = usersData.reduce((a, b) => a + b, 0);

            const distributionOptions = {
                series: [totalProducts || 1, totalArticles || 1, totalUsers || 1],
                chart: {
                    type: 'donut',
                    height: 250,
                    fontFamily: 'Inter, sans-serif',
                    background: 'transparent'
                },
                labels: ['Sản phẩm', 'Bài viết', 'Người dùng'],
                colors: ['#a855f7', '#10b981', '#f59e0b'],
                legend: {
                    show: false
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '75%',
                            labels: {
                                show: true,
                                name: {
                                    show: true,
                                    fontSize: '14px',
                                    color: '#64748b',
                                    offsetY: -10
                                },
                                value: {
                                    show: true,
                                    fontSize: '24px',
                                    color: '#e2e8f0',
                                    offsetY: 5,
                                    formatter: function(val) {
                                        return val
                                    }
                                },
                                total: {
                                    show: true,
                                    showAlways: true,
                                    label: 'Tổng',
                                    fontSize: '14px',
                                    color: '#64748b',
                                    formatter: function(w) {
                                        return w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                                    }
                                }
                            }
                        }
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    width: 0
                }
            };
            new ApexCharts(document.querySelector('#distributionChart'), distributionOptions).render();
        });
    </script>
</div>
