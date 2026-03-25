<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-text-main tracking-tight">Quản lý thương hiệu</h1>
            <p class="text-sm text-text-muted mt-1">Danh sách thương hiệu hiện có trong hệ thống.</p>
        </div>
        <div class="flex items-center gap-2">
            <x-backend.ui.button type="primary" :href="route('backend.brands.create')" icon="ti ti-plus" class="!rounded-2xl shadow-lg shadow-primary/20 group h-12 px-6">
                Thêm thương hiệu
            </x-backend.ui.button>
        </div>
    </div>

    <!-- Filters -->
    <x-backend.layout.card class="p-4">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <i class="ti ti-search absolute left-3 top-1/2 -translate-y-1/2 text-text-muted"></i>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Tìm kiếm tên thương hiệu, website..."
                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-border-glass bg-bg-surface text-text-main placeholder-text-muted/50 focus:ring-2 focus:ring-primary/50 focus:border-primary/50 transition-all">
            </div>
        </div>
    </x-backend.layout.card>

    <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
        <x-backend.table.bulk-actions-toolbar :selectedCount="count($selectedItems)">
            <x-backend.ui.button type="success" size="sm" wire:click="bulkStatus(1)" class="!rounded-xl">
                Hiển thị
            </x-backend.ui.button>
            <x-backend.ui.button type="warning" size="sm" wire:click="bulkStatus(0)" class="!rounded-xl">
                Ẩn
            </x-backend.ui.button>
            <x-backend.ui.button type="danger" size="sm" wire:click="deleteSelected" class="!rounded-xl">
                Xóa
            </x-backend.ui.button>
        </x-backend.table.bulk-actions-toolbar>
    </div>

    <!-- Table -->
    <x-backend.layout.card class="overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-white/5 border-b border-white/5 text-left text-[11px] font-black text-text-muted uppercase tracking-[0.15em]">
                        <th class="px-6 py-4 w-10">
                            <input type="checkbox" wire:model.live="selectAll" wire:click="toggleSelectAll"
                                class="w-4 h-4 rounded border-border-glass text-primary focus:ring-primary/50 bg-bg-surface transition-all">
                        </th>
                        <th class="px-6 py-4">Logo</th>
                        <th class="px-6 py-4 cursor-pointer hover:text-primary transition-colors" wire:click="sortBy('name')">
                            Tên thương hiệu
                            @if($sortField === 'name')
                                <i class="ti ti-chevron-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                            @endif
                        </th>
                        <th class="px-6 py-4">Website</th>
                        <th class="px-6 py-4">Trạng thái</th>
                        <th class="px-6 py-4 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($brands as $brand)
                        <tr class="hover:bg-white/5 transition-colors group" wire:key="brand-{{ $brand->id }}">
                            <td class="px-6 py-4">
                                <input type="checkbox" value="{{ $brand->id }}" wire:model.live="selectedItems"
                                    class="w-4 h-4 rounded border-border-glass text-primary focus:ring-primary/50 bg-bg-surface transition-all">
                            </td>
                            <td class="px-6 py-4">
                                @if($brand->getFirstMediaUrl('logo'))
                                    <img src="{{ $brand->getFirstMediaUrl('logo', 'thumb') }}" alt="{{ $brand->name }}" class="w-10 h-10 object-contain rounded-lg bg-white p-1">
                                @else
                                    <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-bg-surface border border-border-glass text-text-muted">
                                        <i class="ti ti-photo"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-text-main group-hover:text-primary transition-colors">
                                        {{ $brand->name }}
                                    </span>
                                    <span class="text-xs text-text-muted">{{ $brand->slug }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-text-muted">
                                @if($brand->website)
                                    <a href="{{ $brand->website }}" target="_blank" class="hover:text-primary flex items-center gap-1">
                                        {{ $brand->website }} <i class="ti ti-external-link"></i>
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <x-backend.ui.status-badge :status="$brand->is_active" />
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <x-backend.ui.action-icon variant="edit" :href="route('backend.brands.edit', $brand->id)" title="Sửa" />
                                    <x-backend.ui.action-icon variant="delete" wire:click="confirmDelete({{ $brand->id }}, '{{ addslashes($brand->name) }}')" title="Xóa" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center gap-3">
                                    <i class="ti ti-folders-off text-4xl text-text-muted/30"></i>
                                    <p class="text-text-muted">Không tìm thấy thương hiệu nào.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($brands->hasPages())
            <div class="px-6 py-4 border-t border-white/5">
                {{ $brands->links() }}
            </div>
        @endif
    </x-backend.layout.card>

    <!-- Delete Modal -->
    <x-backend.layout.confirm-modal
        show="showDeleteModal"
        title="Xác nhận xóa"
        message="Bạn có chắc chắn muốn xóa thương hiệu '{{ $deleteTargetName }}'? Hành động này không thể hoàn tác."
    >
        <x-backend.ui.button type="outline" wire:click="$set('showDeleteModal', false)" class="!rounded-xl">Hủy</x-backend.ui.button>
        <x-backend.ui.button type="danger" wire:click="deleteBrand" class="!rounded-xl shadow-lg shadow-red-500/20">Xóa vĩnh viễn</x-backend.ui.button>
    </x-backend.layout.confirm-modal>
</div>


