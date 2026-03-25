<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-text-main tracking-tight">Dashboard</h1>
            <p class="text-sm text-text-muted mt-1">Tổng quan hệ thống</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-backend.layout.card class="p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                    <i class="ti ti-shopping-cart text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-text-muted uppercase tracking-widest text-[11px]">Đơn hàng mới</p>
                    <p class="text-2xl font-black text-text-main">{{ $stats['newOrders'] ?? 0 }}</p>
                </div>
            </div>
        </x-backend.layout.card>

        <x-backend.layout.card class="p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-success/10 text-success flex items-center justify-center">
                    <i class="ti ti-currency-dollar text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-text-muted uppercase tracking-widest text-[11px]">Doanh thu tháng</p>
                    <p class="text-2xl font-black text-text-main">{{ number_format($stats['revenueThisMonth'] ?? 0, 0, ',', '.') }}đ</p>
                </div>
            </div>
        </x-backend.layout.card>

        <x-backend.layout.card class="p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-info/10 text-info flex items-center justify-center">
                    <i class="ti ti-users text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-text-muted uppercase tracking-widest text-[11px]">Khách hàng mới</p>
                    <p class="text-2xl font-black text-text-main">{{ $stats['newCustomers'] ?? 0 }}</p>
                </div>
            </div>
        </x-backend.layout.card>

        <x-backend.layout.card class="p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-warning/10 text-warning flex items-center justify-center">
                    <i class="ti ti-mail text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-text-muted uppercase tracking-widest text-[11px]">Liên hệ mới</p>
                    <p class="text-2xl font-black text-text-main">{{ $stats['newContacts'] ?? 0 }}</p>
                </div>
            </div>
        </x-backend.layout.card>
    </div>

    <!-- Recent Orders -->
    <x-backend.layout.card title="Đơn hàng gần đây" class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-border-glass text-[11px] font-black text-text-muted uppercase tracking-widest">
                        <th class="py-3 pr-4">Mã đơn</th>
                        <th class="py-3 px-4">Khách hàng</th>
                        <th class="py-3 px-4">Tổng tiền</th>
                        <th class="py-3 px-4">Trạng thái</th>
                        <th class="py-3 pl-4 text-right">Ngày đặt</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-glass">
                    @forelse($recentOrders ?? [] as $order)
                        <tr class="hover:bg-bg-surface/50 transition-colors">
                            <td class="py-3 pr-4 font-bold text-primary">#{{ $order->code }}</td>
                            <td class="py-3 px-4">
                                <p class="text-sm font-bold text-text-main">{{ $order->customer_name }}</p>
                                <p class="text-[11px] text-text-muted">{{ $order->customer_email }}</p>
                            </td>
                            <td class="py-3 px-4 font-bold text-success">{{ number_format($order->total_amount, 0, ',', '.') }}đ</td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-1 rounded-md text-[11px] font-bold uppercase tracking-widest
                                    @if($order->status === 'completed') bg-success/10 text-success
                                    @elseif($order->status === 'pending') bg-warning/10 text-warning
                                    @elseif($order->status === 'cancelled') bg-danger/10 text-danger
                                    @else bg-info/10 text-info @endif
                                ">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="py-3 pl-4 text-right text-sm text-text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-text-muted">Chưa có đơn hàng nào</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-backend.layout.card>
</div>
