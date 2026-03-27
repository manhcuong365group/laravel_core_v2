<x-backend.layout.index-page
    :title="$title"
    subtitle="Category Tree"
    :description="'Quản lý cấu trúc phân cấp ' . ($type === 'product' ? 'sản phẩm' : 'bài viết') . '. Tối ưu hóa luồng dữ liệu và điều hướng người dùng.'"
    :total="$categories->total()"
    totalLabel="danh mục"
    searchPlaceholder="Tìm tên danh mục hoặc slug..."
    :selectedCount="count($selectedItems)"
    icon="ti ti-folders"
>
    {{-- Header Actions --}}
    <x-slot:headerActions>
        <x-backend.ui.button
            type="primary"
            :href="route('backend.categories.create', $type)"
            class="!rounded-2xl shadow-xl shadow-primary/30 group h-11 px-6"
        >
            <div class="flex items-center gap-2">
                <span class="w-6 h-6 rounded-lg bg-white/20 flex items-center justify-center group-hover:rotate-90 transition-all duration-500">
                    <i class="ti ti-plus text-sm"></i>
                </span>
                <span class="font-bold tracking-tight">Thêm danh mục</span>
            </div>
        </x-backend.ui.button>
    </x-slot:headerActions>

    {{-- Filters --}}
    <x-slot:filters>
        <div class="flex items-center p-1 bg-white/[0.03] border border-white/5 rounded-2xl">
            @foreach(['' => 'Tất cả', '1' => 'Hoạt động', '0' => 'Đang ẩn'] as $key => $label)
                <button
                    wire:click="$set('statusFilter', '{{ $key }}')"
                    class="px-4 py-2 rounded-xl transition-all duration-300 text-xs font-black uppercase tracking-widest {{ (string)$statusFilter === (string)$key ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-text-muted hover:text-text-main hover:bg-white/5' }}"
                >
                    {{ $label }}
                </button>
            @endforeach
        </div>
    </x-slot:filters>

    {{-- Table --}}
    <x-slot:table>
        <table class="w-full border-collapse">
            <thead>
                <tr class="text-left border-b border-white/5 bg-white/[0.01]">
                    <th class="pl-8 pr-4 py-6 w-16 text-center">
                        <x-backend.table.table-checkbox wire:click="toggleSelectAll" :checked="$selectAll" class="scale-110" />
                    </th>
                    <x-backend.table.table-th sort="name" :sortField="$sortField" :sortDirection="$sortDirection" class="px-6 py-6 min-w-[350px]">
                        Tên danh mục & Phân cấp
                    </x-backend.table.table-th>
                    <th class="px-6 py-6 text-[11px] font-black text-text-muted uppercase tracking-[0.2em]">Slug (URL)</th>
                    <th class="px-6 py-6 text-[11px] font-black text-text-muted uppercase tracking-[0.2em] text-center">Thứ tự</th>
                    <th class="px-6 py-6 text-[11px] font-black text-text-muted uppercase tracking-[0.2em] text-center">Trạng thái</th>
                    <th class="pr-8 pl-4 py-6 text-[11px] font-black text-text-muted uppercase tracking-[0.2em] text-right">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($categories as $category)
                    <tr class="group hover:bg-white/[0.03] transition-all duration-500" wire:key="category-{{ $category->id }}">
                        <td class="pl-8 pr-4 py-5 text-center border-b border-white/5">
                            <x-backend.table.table-checkbox value="{{ $category->id }}" wire:model.live="selectedItems" class="scale-110" />
                        </td>
                        <td class="px-6 py-5 border-b border-white/5">
                            <div class="flex flex-col space-y-1.5">
                                <div class="flex items-center gap-3">
                                    @if($category->parent_id)
                                        <div class="flex items-center text-text-muted/30 ml-2">
                                            <i class="ti ti-corner-down-right text-lg"></i>
                                        </div>
                                    @else
                                        <div class="w-8 h-8 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-primary/60 group-hover:scale-110 transition-transform">
                                            <i class="ti {{ $category->type === 'product' ? 'ti-package' : 'ti-news' }} text-base"></i>
                                        </div>
                                    @endif
                                    <a href="{{ route('backend.categories.edit', ['type' => $type, 'category' => $category->id]) }}" class="text-[15px] font-black text-text-main hover:text-primary transition-all duration-300 tracking-tight">
                                        {{ $category->name }}
                                    </a>
                                </div>
                                @if($category->parent)
                                    <div class="flex items-center gap-1.5 ml-10">
                                        <span class="text-[9px] font-black text-text-muted/40 uppercase tracking-widest">Cha:</span>
                                        <span class="text-[10px] font-bold text-text-muted">{{ $category->parent->name }}</span>
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-5 border-b border-white/5">
                            <span class="text-xs font-mono text-text-muted/60 bg-white/5 py-1 px-2.5 rounded-lg border border-white/5 group-hover:bg-white/10 transition-colors">
                                /{{ $category->slug }}
                            </span>
                        </td>
                        <td class="px-6 py-5 border-b border-white/5 text-center">
                            <div class="inline-flex justify-center">
                                <x-backend.ui.editable-field
                                    wire:key="category-order-{{ $category->id }}"
                                    :value="$category->order"
                                    field="order"
                                    :id="$category->id"
                                    class="text-xs font-black text-text-main text-center bg-white/5 px-2 py-0.5 rounded-lg border border-transparent hover:border-white/10"
                                />
                            </div>
                        </td>
                        <td class="px-6 py-5 border-b border-white/5 text-center">
                            <x-admin.status-badge 
                                :status="$category->is_active ? 'success' : 'neutral'" 
                                :label="$category->is_active ? 'Hoạt động' : 'Đang ẩn'" 
                            />
                        </td>
                        <td class="pr-8 pl-4 py-5 border-b border-white/5 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <x-backend.ui.action-icon
                                    variant="edit"
                                    :href="route('backend.categories.edit', ['type' => $type, 'category' => $category->id])"
                                    title="Hiệu chỉnh"
                                    class="text-blue-500/70 hover:text-blue-500"
                                />
                                <x-backend.ui.action-icon
                                    variant="delete"
                                    wire:click="confirmDelete({{ $category->id }}, '{{ addslashes($category->name) }}')"
                                    title="Xóa bỏ"
                                    class="text-rose-500/70 hover:text-rose-500"
                                />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-8 py-32 text-center">
                            <div class="flex flex-col items-center justify-center gap-6">
                                <div class="w-24 h-24 rounded-[2.5rem] bg-gradient-to-br from-white/5 to-white/[0.02] border border-white/10 flex items-center justify-center animate-pulse shadow-inner">
                                    <i class="ti ti-folders-off text-5xl text-text-muted/20"></i>
                                </div>
                                <div class="space-y-2">
                                    <p class="text-2xl font-black text-text-main tracking-tight">Cấu trúc đang trống</p>
                                    <p class="text-sm text-text-muted max-w-[320px] mx-auto leading-relaxed">Hệ thống chưa có danh mục nào. Hãy thiết lập các danh mục cha để bắt đầu phân loại dữ liệu.</p>
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
        @if ($categories->hasPages())
            {{ $categories->links() }}
        @endif
    </x-slot:pagination>

    {{-- Bulk Actions --}}
    <x-slot:bulkActions>
        <div class="flex items-center gap-2">
            <x-backend.ui.button 
                type="success" 
                wire:click="bulkStatus(1)" 
                size="sm"
                class="!rounded-xl px-4 py-2 border-none bg-emerald-500 hover:bg-emerald-600 shadow-lg shadow-emerald-500/20"
            >
                <i class="ti ti-eye mr-2 text-white"></i>
                <span class="font-black uppercase text-[10px] tracking-widest text-white">Hiện loạt</span>
            </x-backend.ui.button>

            <x-backend.ui.button 
                type="outline" 
                wire:click="bulkStatus(0)" 
                size="sm"
                class="!rounded-xl px-4 py-2 border-white/10 hover:border-white/20 bg-white/5"
            >
                <i class="ti ti-eye-off mr-2"></i>
                <span class="font-black uppercase text-[10px] tracking-widest">Ẩn loạt</span>
            </x-backend.ui.button>

            <div class="w-px h-6 bg-white/10 mx-1"></div>

            <x-backend.ui.button 
                size="sm"
                wire:click="confirmBulkDelete" 
                class="!rounded-xl px-4 py-2 border-none bg-rose-500 hover:bg-rose-600 shadow-lg shadow-rose-500/20"
            >
                <i class="ti ti-trash-x mr-2 text-white"></i>
                <span class="font-black uppercase text-[10px] tracking-widest text-white">Xóa vĩnh viễn</span>
            </x-backend.ui.button>
        </div>
    </x-slot:bulkActions>

    {{-- Modals --}}
    <x-slot:modals>
        <x-backend.layout.confirm-modal
            show="showDeleteModal"
            title="Xác nhận yêu cầu xóa"
            message="Việc xóa danh mục cha có thể ảnh hưởng đến các danh mục con liên quan. Bạn có chắc chắn muốn thực hiện hành động này không?"
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
