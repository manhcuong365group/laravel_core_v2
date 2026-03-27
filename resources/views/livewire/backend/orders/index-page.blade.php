<x-backend.layout.index-page
    title="Quản lý đơn hàng"
    subtitle="Order Fulfillment"
    description="Theo dõi, xử lý và quản trị luồng đơn hàng từ khách hàng. Tối ưu hóa quy trình đóng gói và giao vận."
    :total="$orders->total()"
    totalLabel="đơn hàng"
    searchPlaceholder="Mã đơn, tên khách, số điện thoại..."
    :selectedCount="count($selectedItems)"
    icon="ti ti-shopping-cart"
>
    {{-- Header Actions --}}
    <x-slot:headerActions>
        @can('orders.create')
            <x-backend.ui.button
                type="primary"
                :href="route('backend.orders.create')"
                class="!rounded-2xl shadow-xl shadow-primary/30 group h-11 px-6"
            >
                <div class="flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-white/20 flex items-center justify-center group-hover:rotate-90 transition-all duration-500">
                        <i class="ti ti-plus text-sm"></i>
                    </span>
                    <span class="font-bold tracking-tight">Tạo đơn hàng</span>
                </div>
            </x-backend.ui.button>
        @endcan
    </x-slot:headerActions>

    {{-- Filters --}}
    <x-slot:filters>
        <x-backend.ui.dropdown align="left" width="64">
            <x-slot name="trigger">
                <button class="flex items-center gap-3 px-4 py-2.5 rounded-2xl bg-white/[0.03] border border-white/10 hover:border-primary/30 text-xs font-black text-text-muted hover:text-text-main transition-all duration-300">
                    <i class="ti ti-filter text-base text-primary/70"></i>
                    <span>{{ $statusFilter ? ($statuses[$statusFilter] ?? 'Tất cả trạng thái') : 'Trạng thái đơn hàng' }}</span>
                    <i class="ti ti-chevron-down text-[10px] ml-1"></i>
                </button>
            </x-slot>
            <x-slot name="content">
                <div class="max-h-80 overflow-y-auto no-scrollbar py-2">
                    <button wire:click="$set('statusFilter', '')" class="w-full text-left px-5 py-3 text-xs font-black uppercase tracking-widest border-l-4 border-transparent text-text-muted hover:bg-white/5 hover:text-text-main transition-all">Tất cả trạng thái</button>
                    @foreach($statuses as $key => $label)
                        <button 
                            wire:click="$set('statusFilter', '{{ $key }}')" 
                            class="w-full text-left px-5 py-3 text-xs font-black uppercase tracking-widest border-l-4 transition-all {{ $statusFilter === $key ? 'border-primary text-primary bg-primary/5' : 'border-transparent text-text-muted hover:bg-white/5 hover:text-text-main' }}"
                        >
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
            </x-slot>
        </x-backend.ui.dropdown>
    </x-slot:filters>

    {{-- Table --}}
    <x-slot:table>
        <table class="w-full border-collapse">
            <thead>
                <tr class="text-left border-b border-white/5 bg-white/[0.01]">
                    <th class="pl-8 pr-4 py-6 w-16 text-center">
                        <x-backend.table.table-checkbox wire:click="toggleSelectAll" :checked="$selectAll" class="scale-110" />
                    </th>
                    <x-backend.table.table-th sort="code" :sortField="$sortField" :sortDirection="$sortDirection" class="px-6 py-6 whitespace-nowrap">
                        Mã đơn & Khách hàng
                    </x-backend.table.table-th>
                    <th class="px-6 py-6 text-[11px] font-black text-text-muted uppercase tracking-[0.2em]">Trạng thái</th>
                    <th class="px-6 py-6 text-[11px] font-black text-text-muted uppercase tracking-[0.2em] text-center">Sản phẩm</th>
                    <x-backend.table.table-th sort="total" :sortField="$sortField" :sortDirection="$sortDirection" class="px-6 py-6 text-right whitespace-nowrap">
                        Tổng thanh toán
                    </x-backend.table.table-th>
                    <th class="px-6 py-6 text-[11px] font-black text-text-muted uppercase tracking-[0.2em] text-center">Ngày tạo</th>
                    <th class="pr-8 pl-4 py-6 text-[11px] font-black text-text-muted uppercase tracking-[0.2em] text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($orders as $order)
                    <tr class="group hover:bg-white/[0.03] transition-all duration-500" wire:key="order-{{ $order->id }}">
                        <td class="pl-8 pr-4 py-6 text-center border-b border-white/5">
                            <x-backend.table.table-checkbox value="{{ $order->id }}" wire:model.live="selectedItems" class="scale-110" />
                        </td>
                        <td class="px-6 py-6 border-b border-white/5">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-primary group-hover:scale-110 transition-transform shadow-inner">
                                    <i class="ti ti-hash text-xl opacity-40"></i>
                                </div>
                                <div class="flex flex-col space-y-1">
                                    <span class="text-[15px] font-black text-text-main tracking-tight">{{ $order->code }}</span>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] font-bold text-text-muted uppercase tracking-wider">{{ $order->customer_name }}</span>
                                        <span class="w-1 h-1 rounded-full bg-white/10"></span>
                                        <span class="text-[10px] font-medium text-text-muted/60 italic">{{ $order->customer_phone }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-6 border-b border-white/5">
                            @php
                                $statusType = match($order->status) {
                                    'pending', 'new' => 'warning',
                                    'processing', 'shipping' => 'info',
                                    'completed' => 'success',
                                    'cancelled', 'returned' => 'danger',
                                    default => 'neutral'
                                };
                            @endphp
                            <x-admin.status-badge 
                                :status="$statusType" 
                                :label="$order->status_label" 
                                :animate="in_array($order->status, ['new', 'processing'])"
                            />
                        </td>
                        <td class="px-6 py-6 border-b border-white/5 text-center">
                            <span class="px-3 py-1 rounded-lg bg-white/5 border border-white/5 text-text-main text-xs font-black">
                                {{ $order->items_count }}
                            </span>
                        </td>
                        <td class="px-6 py-6 border-b border-white/5 text-right">
                            <span class="text-[16px] font-black text-emerald-500 tracking-tighter">
                                {{ number_format((float) $order->total, 0, ',', '.') }}đ
                            </span>
                        </td>
                        <td class="px-6 py-6 border-b border-white/5 text-center">
                            <div class="flex flex-col gap-0.5">
                                <span class="text-[11px] font-bold text-text-main">{{ $order->created_at?->format('d/m/Y') }}</span>
                                <span class="text-[9px] font-black text-text-muted/40 uppercase tracking-tighter">{{ $order->created_at?->format('H:i') }}</span>
                            </div>
                        </td>
                        <td class="pr-8 pl-4 py-6 border-b border-white/5 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <x-backend.ui.action-icon
                                    variant="view"
                                    :href="route('backend.orders.show', $order)"
                                    title="Xem chi tiết"
                                    class="text-amber-500/70 hover:text-amber-500"
                                />
                                @can('orders.edit')
                                    <x-backend.ui.action-icon
                                        variant="edit"
                                        :href="route('backend.orders.edit', $order)"
                                        title="Hiệu chỉnh"
                                        class="text-blue-500/70 hover:text-blue-500"
                                    />
                                @endcan
                                @can('orders.delete')
                                    <x-backend.ui.action-icon
                                        variant="delete"
                                        wire:click="confirmDelete({{ $order->id }}, '{{ $order->code }}')"
                                        title="Xóa đơn hàng"
                                        class="text-rose-500/70 hover:text-rose-500"
                                    />
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-8 py-32 text-center">
                            <div class="flex flex-col items-center justify-center gap-6">
                                <div class="w-24 h-24 rounded-[2.5rem] bg-gradient-to-br from-white/5 to-white/[0.02] border border-white/10 flex items-center justify-center animate-pulse shadow-inner">
                                    <i class="ti ti-shopping-cart-x text-5xl text-text-muted/20"></i>
                                </div>
                                <div class="space-y-2">
                                    <p class="text-2xl font-black text-text-main tracking-tight">Chưa có đơn hàng</p>
                                    <p class="text-sm text-text-muted max-w-[320px] mx-auto leading-relaxed">Hệ thống đang sẵn sàng chờ đợi những đơn hàng đầu tiên từ khách hàng của bạn.</p>
                                </div>
                                <button wire:click="$set('search', '')" class="h-10 px-8 rounded-xl bg-white/5 border border-white/10 text-primary text-[11px] font-black uppercase tracking-[0.2em] hover:bg-primary hover:text-white transition-all">
                                    Làm mới bộ lọc
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-slot:table>

    {{-- Pagination --}}
    <x-slot:pagination>
        @if ($orders->hasPages())
            {{ $orders->links() }}
        @endif
    </x-slot:pagination>

    {{-- Bulk Actions --}}
    <x-slot:bulkActions>
        <div class="flex flex-col md:flex-row items-center gap-3 w-full">
            <div class="flex items-center gap-2">
                <select wire:model="bulkStatusValue" class="h-10 px-4 rounded-xl border border-white/10 bg-white/5 text-text-main text-xs font-bold focus:ring-2 focus:ring-primary/50 outline-none transition-all">
                    <option value="">-- Chọn trạng thái mới --</option>
                    @foreach ($statuses as $statusKey => $statusLabel)
                        <option value="{{ $statusKey }}">{{ $statusLabel }}</option>
                    @endforeach
                </select>
                <input type="text" wire:model="bulkStatusNote" placeholder="Ghi chú nội bộ..." class="h-10 px-4 rounded-xl border border-white/10 bg-white/5 text-text-main text-xs focus:ring-2 focus:ring-primary/50 outline-none transition-all min-w-[200px]">
                <x-backend.ui.button type="primary" size="sm" wire:click="bulkStatus" class="!rounded-xl h-10 px-6 shadow-lg shadow-primary/20">
                    <span class="font-black uppercase text-[10px] tracking-widest">Cập nhật loạt</span>
                </x-backend.ui.button>
            </div>

            <div class="hidden md:block w-px h-6 bg-white/10 mx-2"></div>

            <x-backend.ui.button 
                size="sm"
                wire:click="confirmBulkDelete" 
                class="!rounded-xl h-10 px-6 border-none bg-rose-500 hover:bg-rose-600 shadow-lg shadow-rose-500/20"
            >
                <i class="ti ti-trash-x mr-2 text-white"></i>
                <span class="font-black uppercase text-[10px] tracking-widest text-white">Xóa hàng loạt</span>
            </x-backend.ui.button>
        </div>
    </x-slot:bulkActions>

    {{-- Modals --}}
    <x-slot:modals>
        <x-backend.layout.confirm-modal
            show="showDeleteModal"
            title="Xóa bỏ đơn hàng"
            message="Hành động này sẽ xóa vĩnh viễn đơn hàng khỏi hệ thống. Bạn chỉ nên thực hiện với các đơn hàng rác hoặc thử nghiệm. Tiếp tục?"
        >
            <x-backend.ui.button
                type="outline"
                size="md"
                wire:click="$set('showDeleteModal', false)"
                class="h-12 px-8 !rounded-2xl border-white/10 font-bold"
            >
                Hủy bỏ
            </x-backend.ui.button>
            <x-backend.ui.button
                type="danger"
                size="md"
                wire:click="executeDelete"
                class="h-12 px-8 !rounded-2xl bg-rose-500 hover:bg-rose-600 shadow-2xl shadow-rose-500/40 font-bold text-white"
            >
                Xác nhận xóa
            </x-backend.ui.button>
        </x-backend.layout.confirm-modal>
    </x-slot:modals>
</x-backend.layout.index-page>
