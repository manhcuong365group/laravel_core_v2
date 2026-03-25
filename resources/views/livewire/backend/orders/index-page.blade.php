<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-black text-text-main tracking-tight">{{ $title }}</h1>
            <p class="text-sm text-text-muted mt-1">Quản lý đơn hàng và trạng thái xử lý.</p>
        </div>
        @can('orders.create')
            <x-backend.ui.button type="primary" :href="route('backend.orders.create')">Tạo đơn hàng</x-backend.ui.button>
        @endcan
    </div>

    <x-backend.table.filter-bar>
        <div class="contents">
            <x-backend.forms.input wire:model.live.debounce.300ms="search" label="Tìm kiếm" placeholder="Mã đơn, khách hàng, điện thoại, email" />
            <x-backend.forms.select wire:model.live="statusFilter" label="Trạng thái" :options="$statuses" placeholder="Tất cả trạng thái" />
            <div class="flex items-end">
                <x-backend.ui.button type="outline" class="w-full" wire:click="$set('search', ''); $set('statusFilter', '');">Đặt lại</x-backend.ui.button>
            </div>
        </div>
    </x-backend.table.filter-bar>

    @if ($orders->count() > 0)
        @canany(['orders.status', 'orders.delete'])
            <div class="glass-card p-4 flex flex-col md:flex-row md:items-center gap-3" x-show="$wire.selectedItems.length">
                <div class="text-sm font-semibold text-text-main">
                    Đã chọn {{ count($selectedItems) }} đơn hàng
                </div>

                @can('orders.status')
                    <div class="flex items-center gap-2 md:ml-auto">
                        <select wire:model="bulkStatusValue" class="h-9 px-3 rounded-xl border border-border-glass bg-bg-surface text-text-main text-sm focus:ring-2 focus:ring-primary/50 focus:border-primary/50">
                            <option value="">Chọn trạng thái</option>
                            @foreach ($statuses as $statusKey => $statusLabel)
                                <option value="{{ $statusKey }}">{{ $statusLabel }}</option>
                            @endforeach
                        </select>
                        <input type="text" wire:model="bulkStatusNote" placeholder="Ghi chú" class="h-9 px-3 rounded-xl border border-border-glass bg-bg-surface text-text-main text-sm focus:ring-2 focus:ring-primary/50 focus:border-primary/50">
                        <x-backend.ui.button type="primary" size="sm" wire:click="bulkStatus" wire:loading.attr="disabled">Cập nhật trạng thái</x-backend.ui.button>
                    </div>
                @endcan

                @can('orders.delete')
                    <x-backend.ui.button type="danger" size="sm" wire:click="deleteSelected" wire:confirm="Xóa hàng loạt đơn hàng đã chọn?" wire:loading.attr="disabled">Xóa hàng loạt</x-backend.ui.button>
                @endcan
            </div>
        @endcanany

        <x-backend.table.table :headers="['', 'Mã đơn', 'Khách hàng', 'Trạng thái', 'Sản phẩm', 'Tổng tiền', 'Ngày tạo', 'Thao tác']">
            @foreach ($orders as $order)
                <tr class="hover:bg-white/5 transition-colors" wire:key="order-{{ $order->id }}">
                    <td class="px-4 py-3 text-center">
                        <input type="checkbox" value="{{ $order->id }}" wire:model.live="selectedItems" class="rounded border-border-glass bg-bg-surface text-primary focus:ring-primary/50">
                    </td>
                    <td class="px-4 py-3 text-sm font-semibold text-text-main">{{ $order->code }}</td>
                    <td class="px-4 py-3 text-sm text-text-muted">
                        <div class="font-medium text-text-main">{{ $order->customer_name }}</div>
                        <div>{{ $order->customer_phone }}</div>
                    </td>
                    <td class="px-4 py-3 text-sm"><x-backend.ui.status-badge :status="$order->status">{{ $order->status_label }}</x-backend.ui.status-badge></td>
                    <td class="px-4 py-3 text-sm text-text-muted">{{ $order->items_count }}</td>
                    <td class="px-4 py-3 text-sm text-text-main font-semibold">{{ number_format((float) $order->total, 0, ',', '.') }} đ</td>
                    <td class="px-4 py-3 text-sm text-text-muted">{{ $order->created_at?->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-3 text-sm">
                        <div class="flex items-center gap-1 justify-end">
                            <x-backend.ui.action-icon variant="view" :href="route('backend.orders.show', $order)" title="Xem" icon="ti ti-eye" />
                            @can('orders.edit')
                                <x-backend.ui.action-icon variant="edit" :href="route('backend.orders.edit', $order)" title="Sửa" icon="ti ti-edit" />
                            @endcan
                            @can('orders.delete')
                                <x-backend.ui.action-icon variant="delete" htmlType="button" title="Xóa" wire:click="confirmDelete({{ $order->id }}, '{{ $order->code }}')" icon="ti ti-trash" />
                            @endcan
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-backend.table.table>

        <div>{{ $orders->links() }}</div>
    @else
        <x-backend.ui.empty-state title="Không có đơn hàng" description="Thử thay đổi bộ lọc hoặc tạo đơn hàng mới." />
    @endif

    <x-backend.layout.confirm-modal wire:model="showDeleteModal" title="Xóa đơn hàng" message="Bạn có chắc chắn muốn xóa đơn hàng {{ $deleteTargetCode }}? Hành động này không thể hoàn tác.">
        <x-backend.ui.button type="outline" wire:click="$set('showDeleteModal', false)">Hủy</x-backend.ui.button>
        <x-backend.ui.button type="danger" wire:click="deleteOrder" wire:loading.attr="disabled">Xóa</x-backend.ui.button>
    </x-backend.layout.confirm-modal>
</div>

