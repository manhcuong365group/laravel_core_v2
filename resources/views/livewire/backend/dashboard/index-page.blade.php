<div class="space-y-10 pb-20">
    {{-- Dynamic Background --}}
    <div class="fixed top-0 right-0 w-[600px] h-[600px] bg-primary/5 rounded-full blur-[120px] -z-10 animate-pulse"></div>
    <div class="fixed bottom-0 left-0 w-[500px] h-[500px] bg-secondary/5 rounded-full blur-[100px] -z-10 animate-pulse" style="animation-delay: 3s"></div>

    {{-- Welcome Header --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 px-4">
        <div class="space-y-2">
            <h1 class="text-5xl font-black text-text-main tracking-tight italic uppercase">
                Command <span class="text-primary tracking-[-0.05em] not-italic">Center</span>
            </h1>
            <p class="text-text-muted font-medium opacity-60 flex items-center gap-2 tracking-wide text-sm">
                <span class="w-2 h-2 rounded-full bg-success animate-ping"></span>
                Hệ thống đang hoạt động ổn định • Cập nhật lần cuối: {{ now()->format('H:i:s') }}
            </p>
        </div>
        <div class="flex items-center gap-3">
            <div class="px-4 py-2 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md flex items-center gap-3">
                <i class="ti ti-calendar-event text-primary"></i>
                <span class="text-[11px] font-black text-text-main uppercase tracking-widest">{{ now()->translatedFormat('d F, Y') }}</span>
            </div>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 px-4">
        {{-- Total Orders --}}
        <div id="stats-total-orders" class="group relative" aria-label="Thống kê đơn hàng mới">
            <div class="absolute -inset-0.5 bg-gradient-to-r from-primary/20 to-primary/5 rounded-[2.5rem] opacity-50 blur-[2px] transition-opacity group-hover:opacity-100"></div>
            <div class="relative rounded-[2.5rem] border border-white/5 bg-bg-surface/40 backdrop-blur-xl p-8 overflow-hidden">
                <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-primary/5 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                <div class="flex items-start justify-between mb-8">
                    <div class="w-14 h-14 rounded-2xl bg-primary/10 flex items-center justify-center text-primary shadow-lg shadow-primary/5 rotate-3 group-hover:rotate-0 transition-transform">
                        <i class="ti ti-shopping-cart text-2xl"></i>
                    </div>
                    <span class="text-[10px] font-black {{ ($stats['orderGrowth'] ?? 0) >= 0 ? 'text-success bg-success/10' : 'text-danger bg-danger/10' }} px-3 py-1 rounded-full uppercase tracking-widest">
                        {{ ($stats['orderGrowth'] ?? 0) >= 0 ? '+' : '' }}{{ $stats['orderGrowth'] ?? 0 }}%
                    </span>
                </div>
                <div>
                    <p class="text-[11px] font-black text-text-muted uppercase tracking-[0.2em] mb-1 opacity-60 italic">Đơn hàng mới</p>
                    <p class="text-4xl font-black text-text-main tracking-tighter">{{ number_format($stats['newOrders'] ?? 0) }}</p>
                </div>
            </div>
        </div>

        {{-- Monthly Revenue --}}
        <div id="stats-monthly-revenue" class="group relative" aria-label="Thống kê doanh thu tháng">
            <div class="absolute -inset-0.5 bg-gradient-to-r from-success/20 to-success/5 rounded-[2.5rem] opacity-50 blur-[2px] transition-opacity group-hover:opacity-100"></div>
            <div class="relative rounded-[2.5rem] border border-white/5 bg-bg-surface/40 backdrop-blur-xl p-8 overflow-hidden">
                <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-success/5 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                <div class="flex items-start justify-between mb-8">
                    <div class="w-14 h-14 rounded-2xl bg-success/10 flex items-center justify-center text-success shadow-lg shadow-success/5 -rotate-3 group-hover:rotate-0 transition-transform">
                        <i class="ti ti-currency-dollar text-2xl"></i>
                    </div>
                    <span class="text-[10px] font-black {{ ($stats['revenueGrowth'] ?? 0) >= 0 ? 'text-success bg-success/10' : 'text-danger bg-danger/10' }} px-3 py-1 rounded-full uppercase tracking-widest">
                        {{ ($stats['revenueGrowth'] ?? 0) >= 0 ? '+' : '' }}{{ $stats['revenueGrowth'] ?? 0 }}%
                    </span>
                </div>
                <div>
                    <p class="text-[11px] font-black text-text-muted uppercase tracking-[0.2em] mb-1 opacity-60 italic">Doanh thu tháng</p>
                    <p class="text-4xl font-black text-text-main tracking-tighter">{{ number_format($stats['revenueThisMonth'] ?? 0, 0, ',', '.') }}đ</p>
                </div>
            </div>
        </div>

        {{-- New Customers --}}
        <div id="stats-new-customers" class="group relative" aria-label="Thống kê khách hàng mới">
            <div class="absolute -inset-0.5 bg-gradient-to-r from-info/20 to-info/5 rounded-[2.5rem] opacity-50 blur-[2px] transition-opacity group-hover:opacity-100"></div>
            <div class="relative rounded-[2.5rem] border border-white/5 bg-bg-surface/40 backdrop-blur-xl p-8 overflow-hidden">
                <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-info/5 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                <div class="flex items-start justify-between mb-8">
                    <div class="w-14 h-14 rounded-2xl bg-info/10 flex items-center justify-center text-info shadow-lg shadow-info/5 rotate-6 group-hover:rotate-0 transition-transform">
                        <i class="ti ti-users text-2xl"></i>
                    </div>
                    <span class="text-[10px] font-black {{ ($stats['customerGrowth'] ?? 0) >= 0 ? 'text-success bg-success/10' : 'text-danger bg-danger/10' }} px-3 py-1 rounded-full uppercase tracking-widest">
                        {{ ($stats['customerGrowth'] ?? 0) >= 0 ? '+' : '' }}{{ $stats['customerGrowth'] ?? 0 }}%
                    </span>
                </div>
                <div>
                    <p class="text-[11px] font-black text-text-muted uppercase tracking-[0.2em] mb-1 opacity-60 italic">Khách hàng mới</p>
                    <p class="text-4xl font-black text-text-main tracking-tighter">{{ number_format($stats['newCustomers'] ?? 0) }}</p>
                </div>
            </div>
        </div>

        {{-- New Contacts --}}
        <div class="group relative">
            <div class="absolute -inset-0.5 bg-gradient-to-r from-warning/20 to-warning/5 rounded-[2.5rem] opacity-50 blur-[2px] transition-opacity group-hover:opacity-100"></div>
            <div class="relative rounded-[2.5rem] border border-white/5 bg-bg-surface/40 backdrop-blur-xl p-8 overflow-hidden">
                <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-warning/5 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                <div class="flex items-start justify-between mb-8">
                    <div class="w-14 h-14 rounded-2xl bg-warning/10 flex items-center justify-center text-warning shadow-lg shadow-warning/5 -rotate-6 group-hover:rotate-0 transition-transform">
                        <i class="ti ti-mail text-2xl"></i>
                    </div>
                    <span class="text-[10px] font-black text-text-muted bg-white/5 px-3 py-1 rounded-full uppercase tracking-widest opacity-40">Live</span>
                </div>
                <div>
                    <p class="text-[11px] font-black text-text-muted uppercase tracking-[0.2em] mb-1 opacity-60 italic">Liên hệ mới</p>
                    <p class="text-4xl font-black text-text-main tracking-tighter">{{ number_format($stats['newContacts'] ?? 0) }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 px-4">
        {{-- Recent Orders Table --}}
        <div class="lg:col-span-8 space-y-8">
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-primary/10 to-transparent rounded-[3rem] opacity-50 blur-[2px] transition-opacity group-hover:opacity-80"></div>
                <div class="relative rounded-[3rem] border border-white/5 bg-bg-surface/40 backdrop-blur-2xl p-10">
                    <div class="flex items-center justify-between mb-10">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-primary shadow-inner rotate-3">
                                <i class="ti ti-list text-xl"></i>
                            </div>
                            <h2 class="text-2xl font-black text-text-main tracking-tight uppercase italic opacity-90">Đơn hàng hiện tại</h2>
                        </div>
                        <a href="{{ route('backend.orders.index') }}" class="text-[11px] font-black text-primary uppercase tracking-[0.2em] hover:tracking-[0.3em] transition-all flex items-center gap-2">
                            Xem tất cả <i class="ti ti-arrow-right"></i>
                        </a>
                    </div>

                    <div class="overflow-x-auto overflow-hidden rounded-2xl">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="text-[11px] font-black text-text-muted uppercase tracking-[0.2em] italic border-b border-white/5">
                                    <th class="py-5 pr-4 pl-2 opacity-50">Mã đơn</th>
                                    <th class="py-5 px-4 opacity-50">Logistics</th>
                                    <th class="py-5 px-4 opacity-50">Giá trị</th>
                                    <th class="py-5 px-4 opacity-50">Trạng thái</th>
                                    <th class="py-5 pl-4 text-right opacity-50">Thời gian</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @forelse($recentOrders ?? [] as $order)
                                    <tr class="group/row transition-all hover:bg-white/[0.02]">
                                        <td class="py-6 pr-4 pl-2">
                                            <div class="flex items-center gap-3">
                                                <div class="w-2 h-2 rounded-full @if($order->status === 'completed') bg-success @elseif($order->status === 'pending') bg-warning @else bg-danger @endif shadow-lg shadow-current animate-pulse"></div>
                                                <span class="text-sm font-black text-text-main tracking-tight group-hover/row:text-primary transition-colors">#{{ $order->code }}</span>
                                            </div>
                                        </td>
                                        <td class="py-6 px-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-text-muted text-xs font-black uppercase tracking-tighter">
                                                    {{ substr($order->customer_name, 0, 2) }}
                                                </div>
                                                <div>
                                                    <p class="text-[13px] font-bold text-text-main tracking-wide">{{ $order->customer_name }}</p>
                                                    <p class="text-[10px] text-text-muted opacity-50 font-medium">{{ $order->customer_email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-6 px-4">
                                            <span class="text-sm font-black text-text-main tracking-tighter italic">{{ number_format($order->total_amount, 0, ',', '.') }}đ</span>
                                        </td>
                                        <td class="py-6 px-4">
                                            <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-[0.2em] border border-white/5
                                                @if($order->status === 'completed') bg-success/10 text-success border-success/20
                                                @elseif($order->status === 'pending') bg-warning/10 text-warning border-warning/20
                                                @elseif($order->status === 'cancelled') bg-danger/10 text-danger border-danger/20
                                                @else bg-info/10 text-info border-info/20 @endif
                                            ">
                                                {{ $order->status }}
                                            </span>
                                        </td>
                                        <td class="py-6 pl-4 text-right">
                                            <span class="text-[11px] font-bold text-text-muted opacity-50">{{ $order->created_at->diffForHumans() }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-20 text-center">
                                            <div class="flex flex-col items-center gap-4 opacity-20">
                                                <i class="ti ti-ghost text-6xl"></i>
                                                <p class="text-sm font-black uppercase tracking-widest italic">Hệ thống đang chờ đơn hàng mới</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Side Cards: Recent Activity, etc --}}
        <div class="lg:col-span-4 space-y-8">
            <div class="rounded-[3rem] border border-white/5 bg-white/[0.03] backdrop-blur-md p-10 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-secondary/5 rounded-full blur-3xl -z-10 group-hover:scale-125 transition-transform duration-1000"></div>
                
                <div class="flex items-center gap-4 mb-10">
                    <div class="w-10 h-10 rounded-xl bg-secondary/10 flex items-center justify-center text-secondary -rotate-3">
                        <i class="ti ti-activity text-xl"></i>
                    </div>
                    <h2 class="text-xl font-black text-text-main tracking-tight uppercase italic opacity-90">Trendings</h2>
                </div>

                <div class="space-y-6">
                    @forelse($stats['popularProducts'] ?? [] as $product)
                        <div class="flex items-center gap-4 group/item">
                            <div class="w-12 h-12 rounded-2xl bg-white/5 border border-white/10 overflow-hidden relative p-1">
                                <img src="{{ $product->image }}" class="w-full h-full object-cover rounded-xl opacity-80 group-hover/item:opacity-100 transition-opacity" alt="">
                            </div>
                            <div class="flex-1">
                                <p class="text-[13px] font-bold text-text-main line-clamp-1 group-hover/item:text-primary transition-colors italic tracking-wide">{{ $product->name }}</p>
                                <div class="flex items-center gap-3 mt-1">
                                    <span class="text-[10px] font-black text-text-muted opacity-40 uppercase tracking-widest">{{ $product->sold_count }} đã bán</span>
                                    <span class="w-1 h-1 rounded-full bg-white/10"></span>
                                    <span class="text-[10px] font-black text-success uppercase tracking-widest">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                                </div>
                            </div>
                            <i class="ti ti-trending-up text-success/40 group-hover/item:text-success transition-colors"></i>
                        </div>
                    @empty
                        <p class="text-[11px] text-text-muted italic opacity-40 text-center py-10">Chưa có dữ liệu xu hướng</p>
                    @endforelse
                </div>

                <div class="mt-10 pt-8 border-t border-white/5">
                    <x-backend.ui.button variant="neutral" class="w-full !rounded-2xl h-12 !bg-white/5 border-white/5 !text-xs font-black uppercase tracking-[0.2em] opacity-60 hover:opacity-100 italic">
                        Báo cáo chi tiết <i class="ti ti-chevron-right ml-2 opacity-50"></i>
                    </x-backend.ui.button>
                </div>
            </div>
        </div>
    </div>
</div>

