<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <x-backend.ui.button variant="neutral" :href="route('backend.orders.index')" icon="ti ti-arrow-left" size="sm" />
            <div>
                <h1 class="text-2xl font-black text-text-main tracking-tight">{{ $title }}</h1>
                <div class="flex items-center gap-2 mt-1">
                    <span class="text-sm text-text-muted">Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</span>
                    <span class="w-1 h-1 rounded-full bg-text-muted/30"></span>
                    <x-backend.ui.status-badge :status="$order->status" :label="$order->status_label" />
                </div>
            </div>
        </div>
        <div class="flex items-center gap-2">
            @can('update', $order)
                <x-backend.ui.button variant="neutral" wire:click="$set('showStatusModal', true)" icon="ti ti-edit">
                    Cập nhật trạng thái
                </x-backend.ui.button>
                <x-backend.ui.button variant="primary" :href="route('backend.orders.edit', $order)" icon="ti ti-pencil">
                    Sửa đơn hàng
                </x-backend.ui.button>
            @endcan
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Sản phẩm -->
            <x-backend.layout.card title="Sản phẩm" class="p-6">
                <div class="overflow-x-auto -mx-6">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-border-glass">
                                <th class="px-6 py-3 text-[11px] font-black text-text-muted uppercase tracking-widest">Sản phẩm</th>
                                <th class="px-6 py-3 text-[11px] font-black text-text-muted uppercase tracking-widest w-24 text-center">SL</th>
                                <th class="px-6 py-3 text-[11px] font-black text-text-muted uppercase tracking-widest w-32 text-right">Giá</th>
                                <th class="px-6 py-3 text-[11px] font-black text-text-muted uppercase tracking-widest w-32 text-right">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-glass/50">
                            @foreach($order->items as $item)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @if($item->product && $item->product->featured_image)
                                                <img src="{{ Storage::url($item->product->featured_image) }}" class="w-10 h-10 rounded-lg object-cover">
                                            @else
                                                <div class="w-10 h-10 rounded-lg bg-bg-surface flex items-center justify-center border border-border-glass">
                                                    <i class="ti ti-package text-text-muted/50"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="text-sm font-bold text-text-main">{{ $item->product_name_snapshot }}</div>
                                                <div class="text-xs text-text-muted">SKU: {{ $item->sku_snapshot }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm font-medium text-text-main">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-medium text-text-main">
                                        {{ number_format($item->price, 0, ',', '.') }}đ
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-bold text-text-main">
                                        {{ number_format($item->line_total, 0, ',', '.') }}đ
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-right text-sm text-text-muted">Tạm tính:</td>
                                <td class="px-6 py-4 text-right text-sm font-bold text-text-main">{{ number_format($order->subtotal, 0, ',', '.') }}đ</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="px-6 py-3 text-right text-sm text-text-muted">Phí vận chuyển:</td>
                                <td class="px-6 py-3 text-right text-sm font-bold text-text-main">{{ number_format($order->shipping_fee, 0, ',', '.') }}đ</td>
                            </tr>
                            <tr class="bg-bg-surface/30">
                                <td colspan="3" class="px-6 py-4 text-right text-base font-black text-text-main uppercase tracking-tight">Tổng cộng:</td>
                                <td class="px-6 py-4 text-right text-xl font-black text-primary">{{ number_format($order->total, 0, ',', '.') }}đ</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </x-backend.layout.card>

            <!-- Lịch sử trạng thái -->
            <x-backend.layout.card title="Lịch sử trạng thái" class="p-6">
                <div class="relative pl-6 space-y-6 before:absolute before:left-[11px] before:top-2 before:bottom-2 before:w-0.5 before:bg-border-glass">
                    @forelse($order->statusHistories->sortByDesc('created_at') as $history)
                        <div class="relative">
                            <div class="absolute -left-[21px] top-1.5 w-4 h-4 rounded-full border-2 border-bg-surface bg-primary shadow-sm"></div>
                            <div class="flex flex-col">
                                <div class="flex items-center gap-2">
                                    <x-backend.ui.status-badge :status="$history->status" :label="App\Models\Order::statusOptions()[$history->status] ?? $history->status" />
                                    <span class="text-xs text-text-muted">{{ $history->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                @if($history->note)
                                    <div class="mt-2 text-sm text-text-main bg-bg-surface p-3 rounded-lg border border-border-glass">
                                        {{ $history->note }}
                                    </div>
                                @endif
                                <div class="mt-1 text-[10px] text-text-muted uppercase font-bold tracking-widest">
                                    Thực hiện bởi: {{ $history->changer ? $history->changer->name : 'Hệ thống' }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-text-muted italic">Chưa có lịch sử thay đổi.</p>
                    @endforelse
                </div>
            </x-backend.layout.card>
        </div>

        <div class="space-y-6">
            <!-- Thông tin khách hàng -->
            <x-backend.layout.card title="Khách hàng" class="p-6">
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                            <i class="ti ti-user text-primary text-xl"></i>
                        </div>
                        <div>
                            <div class="text-sm font-black text-text-main">{{ $order->customer_name }}</div>
                            <div class="text-xs text-text-muted">{{ $order->customer_email ?: 'Không có email' }}</div>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-full bg-success/10 flex items-center justify-center shrink-0">
                            <i class="ti ti-phone text-success text-xl"></i>
                        </div>
                        <div>
                            <div class="text-xs text-text-muted uppercase tracking-widest font-black mb-1">Số điện thoại</div>
                            <div class="text-sm font-bold text-text-main">{{ $order->customer_phone }}</div>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-full bg-warning/10 flex items-center justify-center shrink-0">
                            <i class="ti ti-map-pin text-warning text-xl"></i>
                        </div>
                        <div>
                            <div class="text-xs text-text-muted uppercase tracking-widest font-black mb-1">Địa chỉ giao hàng</div>
                            <div class="text-sm font-medium text-text-main leading-relaxed">
                                {{ $order->customer_address ?: 'Không có địa chỉ' }}
                            </div>
                        </div>
                    </div>
                </div>
            </x-backend.layout.card>

            <!-- Ghi chú -->
            <x-backend.layout.card title="Ghi chú" class="p-6">
                <div class="space-y-4">
                    <div>
                        <div class="text-xs text-text-muted uppercase tracking-widest font-black mb-2">Ghi chú khách hàng</div>
                        <div class="text-sm text-text-main p-3 bg-bg-surface rounded-lg border border-border-glass min-h-[60px]">
                            {{ $order->notes ?: 'Không có ghi chú' }}
                        </div>
                    </div>
                    <div>
                        <div class="text-xs text-text-muted uppercase tracking-widest font-black mb-2">Ghi chú nội bộ</div>
                        <div class="text-sm text-text-main p-3 bg-bg-surface rounded-lg border border-border-glass min-h-[60px]">
                            {{ $order->admin_notes ?: 'Không có ghi chú' }}
                        </div>
                    </div>
                </div>
            </x-backend.layout.card>
        </div>
    </div>

    <!-- Modal cập nhật trạng thái -->
    @if($showStatusModal)
        <div class="fixed inset-0 z-[60] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-bg-main/80 backdrop-blur-sm" wire:click="$set('showStatusModal', false)"></div>
            <div class="relative w-full max-w-md bg-bg-surface border border-border-glass rounded-2xl shadow-2xl overflow-hidden">
                <div class="p-6 border-b border-border-glass flex items-center justify-between">
                    <h3 class="text-lg font-black text-text-main uppercase tracking-tight">Cập nhật trạng thái</h3>
                    <button wire:click="$set('showStatusModal', false)" class="text-text-muted hover:text-text-main transition-colors">
                        <i class="ti ti-x text-xl"></i>
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <x-backend.forms.select wire:model="status" name="status" label="Trạng thái mới" :options="$statuses" required />
                    <x-backend.forms.textarea wire:model="statusNote" name="statusNote" label="Lý do / Ghi chú" rows="3" placeholder="Nhập lý do thay đổi trạng thái..." />
                </div>
                <div class="p-6 bg-bg-surface/50 border-t border-border-glass flex justify-end gap-3">
                    <x-backend.ui.button variant="neutral" wire:click="$set('showStatusModal', false)">
                        Hủy bỏ
                    </x-backend.ui.button>
                    <x-backend.ui.button variant="primary" wire:click="updateStatus">
                        Xác nhận
                    </x-backend.ui.button>
                </div>
            </div>
        </div>
    @endif
</div>

