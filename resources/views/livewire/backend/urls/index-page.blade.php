<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-text-main tracking-tight">{{ $title }}</h1>
            <p class="text-sm text-text-muted mt-1">Danh sách URL hiện có trong hệ thống.</p>
        </div>
        <div class="flex items-center gap-2">
            <x-backend.ui.button variant="neutral" wire:click="scanSite" wire:loading.attr="disabled" icon="ti ti-scan">
                <span wire:loading.remove wire:target="scanSite">Quét Website</span>
                <span wire:loading wire:target="scanSite">Đang quét...</span>
            </x-backend.ui.button>
            <x-backend.ui.button variant="primary" :href="route('backend.urls.create')" icon="ti ti-plus">
                Thêm URL
            </x-backend.ui.button>
        </div>
    </div>

    <!-- Filters -->
    <x-backend.layout.card class="p-4">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <i class="ti ti-search absolute left-3 top-1/2 -translate-y-1/2 text-text-muted"></i>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Tìm theo tiêu đề, URL..."
                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-border-glass bg-bg-surface text-text-main placeholder-text-muted/50 focus:ring-2 focus:ring-primary/50 focus:border-primary/50 transition-all">
            </div>
            <div class="w-full md:w-48">
                <select wire:model.live="statusFilter"
                    class="w-full px-4 py-2.5 rounded-xl border border-border-glass bg-bg-surface text-text-main focus:ring-2 focus:ring-primary/50 focus:border-primary/50 transition-all">
                    <option value="">Tất cả trạng thái</option>
                    <option value="1">Hiển thị</option>
                    <option value="0">Ẩn</option>
                </select>
            </div>
        </div>
    </x-backend.layout.card>

    <!-- Bulk Actions -->
    @if (count($selectedItems) > 0)
        <div class="bg-primary/10 border border-primary/20 rounded-xl p-3 flex items-center justify-between gap-2 flex-wrap animate-in fade-in slide-in-from-top-2 duration-300">
            <div class="flex items-center gap-2">
                <span class="text-sm text-primary font-bold">Đã chọn {{ count($selectedItems) }} URL</span>
            </div>
            <div class="flex items-center gap-2">
                <x-backend.ui.button variant="danger" size="sm" wire:click="confirmBulkDelete" wire:loading.attr="disabled">
                    Xóa các mục đã chọn
                </x-backend.ui.button>
                <x-backend.ui.button variant="neutral" size="sm" wire:click="$set('selectedItems', [])">
                    Bỏ chọn
                </x-backend.ui.button>
            </div>
        </div>
    @endif

    <!-- Table -->
    <x-backend.layout.card class="overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-white/5 border-b border-white/5 text-left text-[11px] font-black text-text-muted uppercase tracking-[0.15em]">
                        <th class="px-6 py-4 w-10">
                            <input type="checkbox" wire:model.live="selectAll"
                                class="w-4 h-4 rounded border-border-glass text-primary focus:ring-primary/50 bg-bg-surface transition-all">
                        </th>
                        <th class="px-6 py-4 cursor-pointer hover:text-primary transition-colors" wire:click="sortBy('title')">
                            Tiêu đề
                            @if($sortField === 'title')
                                <i class="ti ti-chevron-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                            @endif
                        </th>
                        <th class="px-6 py-4">URL gốc</th>
                        <th class="px-6 py-4">URL rút gọn</th>
                        <th class="px-6 py-4 text-center">Lượt click</th>
                        <th class="px-6 py-4 text-center">Trạng thái</th>
                        <th class="px-6 py-4 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($urls as $url)
                        <tr class="hover:bg-white/5 transition-colors group" wire:key="url-{{ $url->id }}">
                            <td class="px-6 py-4">
                                <input type="checkbox" value="{{ $url->id }}" wire:model.live="selectedItems"
                                    class="w-4 h-4 rounded border-border-glass text-primary focus:ring-primary/50 bg-bg-surface transition-all">
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-text-main truncate block max-w-xs">{{ $url->title }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs text-text-muted truncate block max-w-xs">{{ $url->original_url }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($url->short_url_full)
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-mono text-primary truncate block max-w-[150px]">{{ $url->short_url_full }}</span>
                                        <button x-on:click="navigator.clipboard.writeText('{{ $url->short_url_full }}'); $dispatch('toast', {message: 'Đã sao chép link', type: 'success'})" 
                                            class="text-text-muted hover:text-primary transition-colors">
                                            <i class="ti ti-copy text-xs"></i>
                                        </button>
                                    </div>
                                @else
                                    <span class="text-[10px] text-text-muted/30 italic">Chưa tạo</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-sm font-bold text-text-main">{{ number_format($url->click_count) }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button wire:click="toggleStatus({{ $url->id }})" 
                                    class="w-8 h-8 mx-auto rounded-lg flex items-center justify-center border transition-all {{ $url->is_active ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-500' : 'bg-slate-500/10 border-slate-500/20 text-slate-500' }}">
                                    <i class="ti {{ $url->is_active ? 'ti-eye' : 'ti-eye-off' }} text-sm"></i>
                                </button>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('backend.urls.edit', $url) }}" 
                                        class="w-8 h-8 rounded-lg flex items-center justify-center bg-indigo-500/10 text-indigo-500 hover:bg-indigo-500 hover:text-white border border-indigo-500/20 transition-all">
                                        <i class="ti ti-edit text-base"></i>
                                    </a>
                                    <button wire:click="confirmDelete({{ $url->id }}, '{{ addslashes($url->title) }}')"
                                        class="w-8 h-8 rounded-lg flex items-center justify-center bg-rose-500/10 text-rose-500 hover:bg-rose-500 hover:text-white border border-rose-500/20 transition-all">
                                        <i class="ti ti-trash text-base"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center gap-3">
                                    <i class="ti ti-link-off text-4xl text-text-muted/30"></i>
                                    <p class="text-text-muted">Không tìm thấy URL nào.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($urls->hasPages())
            <div class="px-6 py-4 border-t border-white/5">
                {{ $urls->links() }}
            </div>
        @endif
    </x-backend.layout.card>

    <x-backend.layout.confirm-modal
        show="showDeleteModal"
        title="Xác nhận xóa"
        :message="$isBulkDelete ? 'Bạn có chắc chắn muốn xóa các URL đã chọn? Hành động này không thể hoàn tác.' : 'Bạn có chắc chắn muốn xóa URL \'' . $deleteTargetName . '\'? Hành động này không thể hoàn tác.'"
    >
        <x-backend.ui.button variant="neutral" wire:click="$set('showDeleteModal', false)">Hủy</x-backend.ui.button>
        <x-backend.ui.button variant="danger" wire:click="$isBulkDelete ? 'deleteSelected' : 'deleteUrl'">
            Xác nhận xóa
        </x-backend.ui.button>
    </x-backend.layout.confirm-modal>
</div>
