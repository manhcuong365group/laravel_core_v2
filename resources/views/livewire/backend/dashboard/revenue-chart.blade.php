<div wire:ignore class="mt-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Main Activity Chart --}}
        <div class="lg:col-span-2 glass-card p-8 relative overflow-hidden group">
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-primary/5 rounded-full blur-3xl group-hover:bg-primary/10 transition-colors duration-700"></div>
            
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4 relative z-10">
                <div>
                    <h3 class="text-xl font-black text-text-main tracking-tight flex items-center gap-3">
                        <span class="w-1.5 h-6 bg-primary rounded-full"></span>
                        Hoạt động hệ thống
                    </h3>
                    <p class="text-sm text-text-muted font-medium mt-1">Xu hướng tăng trưởng trong 12 tháng qua</p>
                </div>
                
                <div class="flex flex-wrap items-center gap-6 p-2 bg-background/30 backdrop-blur-md rounded-2xl border border-white/5">
                    <div class="flex items-center gap-2 group/item cursor-pointer">
                        <div class="w-2.5 h-2.5 rounded-full bg-primary shadow-[0_0_10px_rgba(0,194,255,0.5)] group-hover/item:scale-125 transition-transform"></div>
                        <span class="text-xs font-bold text-text-muted group-hover/item:text-text-main transition-colors">Sản phẩm</span>
                    </div>
                    <div class="flex items-center gap-2 group/item cursor-pointer">
                        <div class="w-2.5 h-2.5 rounded-full bg-success shadow-[0_0_10px_rgba(0,240,255,0.5)] group-hover/item:scale-125 transition-transform"></div>
                        <span class="text-xs font-bold text-text-muted group-hover/item:text-text-main transition-colors">Bài viết</span>
                    </div>
                    <div class="flex items-center gap-2 group/item cursor-pointer">
                        <div class="w-2.5 h-2.5 rounded-full bg-accent shadow-[0_0_10px_rgba(112,0,255,0.5)] group-hover/item:scale-125 transition-transform"></div>
                        <span class="text-xs font-bold text-text-muted group-hover/item:text-text-main transition-colors">Người dùng</span>
                    </div>
                </div>
            </div>
            
            <div id="revenueChart" class="w-full h-80 relative z-10"></div>
        </div>

        {{-- Distribution Chart --}}
        <div class="glass-card p-8 relative overflow-hidden group">
            <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-accent/5 rounded-full blur-3xl group-hover:bg-accent/10 transition-colors duration-700"></div>
            
            <div class="mb-8 relative z-10">
                <h3 class="text-xl font-black text-text-main tracking-tight flex items-center gap-3">
                    <span class="w-1.5 h-6 bg-accent rounded-full"></span>
                    Phân bổ nội dung
                </h3>
                <p class="text-sm text-text-muted font-medium mt-1">Tỷ lệ đóng góp của các thực thể</p>
            </div>
            
            <div id="distributionChart" class="w-full h-64 flex items-center justify-center relative z-10"></div>
            
            <div class="mt-8 space-y-4 relative z-10">
                <div class="flex items-center justify-between p-3 bg-white/5 rounded-xl hover:bg-white/10 transition-colors cursor-default border border-transparent hover:border-white/5">
                    <div class="flex items-center gap-3">
                        <div class="w-2.5 h-2.5 rounded-full bg-primary shadow-[0_0_10px_rgba(0,194,255,0.4)]"></div>
                        <span class="text-sm font-bold text-text-muted">Sản phẩm</span>
                    </div>
                    <span class="text-sm font-black text-text-main">{{ number_format($this->monthlyData['products'][11] ?? 0) }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-white/5 rounded-xl hover:bg-white/10 transition-colors cursor-default border border-transparent hover:border-white/5">
                    <div class="flex items-center gap-3">
                        <div class="w-2.5 h-2.5 rounded-full bg-success shadow-[0_0_10px_rgba(0,240,255,0.4)]"></div>
                        <span class="text-sm font-bold text-text-muted">Bài viết</span>
                    </div>
                    <span class="text-sm font-black text-text-main">{{ number_format($this->monthlyData['articles'][11] ?? 0) }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-white/5 rounded-xl hover:bg-white/10 transition-colors cursor-default border border-transparent hover:border-white/5">
                    <div class="flex items-center gap-3">
                        <div class="w-2.5 h-2.5 rounded-full bg-accent shadow-[0_0_10px_rgba(112,0,255,0.4)]"></div>
                        <span class="text-sm font-bold text-text-muted">Người dùng</span>
                    </div>
                    <span class="text-sm font-black text-text-main">{{ number_format($this->monthlyData['users'][11] ?? 0) }}</span>
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
                    toolbar: { show: false },
                    fontFamily: 'Outfit, sans-serif',
                    background: 'transparent',
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 1000,
                        animateOnLegendClick: true,
                        dynamicAnimation: {
                            enabled: true,
                            speed: 350
                        }
                    }
                },
                dataLabels: { enabled: false },
                stroke: {
                    curve: 'smooth',
                    width: [3, 3, 3],
                    lineCap: 'round'
                },
                xaxis: {
                    categories: labels,
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: {
                        style: {
                            colors: '#94a3b8',
                            fontSize: '12px',
                            fontWeight: 600
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: '#94a3b8',
                            fontSize: '12px',
                            fontWeight: 600
                        },
                        formatter: function(val) {
                            return Math.floor(val);
                        }
                    }
                },
                grid: {
                    borderColor: 'rgba(255,255,255,0.05)',
                    strokeDashArray: 6,
                    xaxis: { lines: { show: true } },
                    yaxis: { lines: { show: true } },
                    padding: { top: 0, right: 0, bottom: 0, left: 10 }
                },
                colors: ['#00C2FF', '#00F0FF', '#7000FF'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.4,
                        opacityTo: 0.05,
                        stops: [0, 90, 100],
                    }
                },
                markers: {
                    size: 4,
                    colors: ['#00C2FF', '#00F0FF', '#7000FF'],
                    strokeColors: '#0f172a',
                    strokeWidth: 2,
                    hover: { size: 6 }
                },
                tooltip: {
                    theme: 'dark',
                    custom: function({series, seriesIndex, dataPointIndex, w}) {
                        return '<div class="glass-tooltip p-3 border border-white/10 rounded-xl bg-slate-900/90 backdrop-blur-md shadow-2xl animate-fade-in-up">' +
                            '<div class="text-xs font-black text-slate-400 mb-2 uppercase tracking-widest">' + w.globals.categoryLabels[dataPointIndex] + '</div>' +
                            '<div class="space-y-1.5">' +
                                '<div class="flex items-center justify-between gap-4">' +
                                    '<span class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-[#00C2FF]"></span><span class="text-xs font-bold text-slate-200">Sản phẩm</span></span>' +
                                    '<span class="text-sm font-black text-white">' + series[0][dataPointIndex] + '</span>' +
                                '</div>' +
                                '<div class="flex items-center justify-between gap-4">' +
                                    '<span class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-[#00F0FF]"></span><span class="text-xs font-bold text-slate-200">Bài viết</span></span>' +
                                    '<span class="text-sm font-black text-white">' + series[1][dataPointIndex] + '</span>' +
                                '</div>' +
                                '<div class="flex items-center justify-between gap-4">' +
                                    '<span class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-[#7000FF]"></span><span class="text-xs font-bold text-slate-200">Người dùng</span></span>' +
                                    '<span class="text-sm font-black text-white">' + series[2][dataPointIndex] + '</span>' +
                                '</div>' +
                            '</div>' +
                        '</div>';
                    }
                },
                legend: { show: false }
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
                    fontFamily: 'Outfit, sans-serif',
                    background: 'transparent',
                    animations: {
                        enabled: true,
                        speed: 1200
                    }
                },
                labels: ['Sản phẩm', 'Bài viết', 'Người dùng'],
                colors: ['#00C2FF', '#00F0FF', '#7000FF'],
                legend: { show: false },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '82%',
                            labels: {
                                show: true,
                                name: {
                                    show: true,
                                    fontSize: '14px',
                                    fontWeight: 700,
                                    color: '#94a3b8',
                                    offsetY: -8
                                },
                                value: {
                                    show: true,
                                    fontSize: '28px',
                                    fontWeight: 900,
                                    color: '#f8fafc',
                                    offsetY: 8,
                                    formatter: function(val) { return val }
                                },
                                total: {
                                    show: true,
                                    showAlways: true,
                                    label: 'TỔNG CỘNG',
                                    fontSize: '11px',
                                    fontWeight: 800,
                                    color: '#64748b',
                                    formatter: function(w) {
                                        return w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                                    }
                                }
                            }
                        }
                    }
                },
                dataLabels: { enabled: false },
                stroke: { width: 0 },
                tooltip: { enabled: false }
            };
            new ApexCharts(document.querySelector('#distributionChart'), distributionOptions).render();
        });
    </script>
</div>

