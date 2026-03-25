<x-backend.layout.index-page
    :title="$title"
    subtitle="Quản lý danh mục"
    :description="'Tổ chức và phân loại nội dung ' . ($type === 'product' ? 'sản phẩm' : 'bài viết') . ' của bạn một cách khoa học để cải thiện trải nghiệm người dùng và SEO.'"
    :total="$categories->total()"
    totalLabel="mục"
    searchPlaceholder="Tìm kiếm danh mục..."
    :selectedCount="count($selectedItems)"
    icon="ti ti-folders"
>
    {{-- Header Actions --}}
    <x-slot:headerActions>
        <x-backend.ui.button
            type="primary"
            :href="route('backend.categories.create', $type)"
            class="!rounded-2xl shadow-lg shadow-primary/20 group h-12 px-6"
        >
            <div class="flex items-center gap-2">
                <span class="w-6 h-6 rounded-lg bg-white/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="ti ti-plus text-sm"></i>
                </span>
                <span class="font-bold">Thêm danh mục mới</span>
            </div>
        </x-backend.ui.button>
    </x-slot:headerActions>

    {{-- Filters --}}
    <x-slot:filters>
        <button class="px-4 py-2 rounded-xl bg-white/10 border border-white/10 text-xs font-bold text-text-main whitespace-nowrap">Tất cả</button>
        <button class="px-4 py-2 rounded-xl hover:bg-white/5 border border-transparent hover:border-white/10 text-xs font-bold text-text-muted transition-all whitespace-nowrap">Hoạt động</button>
        <button class="px-4 py-2 rounded-xl hover:bg-white/5 border border-transparent hover:border-white/10 text-xs font-bold text-text-muted transition-all whitespace-nowrap">Đang ẩn</button>
    </x-slot:filters>

    {{-- Toolbar Actions (Quick Bulk) --}}
    <x-slot:toolbar>
        <div class="flex items-center gap-2">
            @if(count($selectedItems) > 0)
                <button
                    wire:click="bulkStatus(1)"
                    class="w-10 h-10 flex items-center justify-center rounded-xl bg-green-500/10 border border-green-500/20 text-green-500 hover:bg-green-500 hover:text-white transition-all shadow-sm"
                    title="Hiển thị hàng loạt"
                >
                    <i class="ti ti-eye text-lg"></i>
                </button>
                <button
                    wire:click="bulkStatus(0)"
                    class="w-10 h-10 flex items-center justify-center rounded-xl bg-orange-500/10 border border-orange-500/20 text-orange-500 hover:bg-orange-500 hover:text-white transition-all shadow-sm"
                    title="Ẩn hàng loạt"
                >
                    <i class="ti ti-eye-off text-lg"></i>
                </button>
                <button
                    wire:click="confirmBulkDelete"
                    class="w-10 h-10 flex items-center justify-center rounded-xl bg-red-500/10 border border-red-500/20 text-red-500 hover:bg-red-500 hover:text-white transition-all shadow-sm"
                    title="Xóa hàng loạt"
                >
                    <i class="ti ti-trash text-lg"></i>
                </button>
            @endif
        </div>
    </x-slot:toolbar>

    {{-- Table --}}
    <x-slot:table>
        <table class="w-full border-collapse">
            <thead>
                <tr class="text-left text-[11px] font-black text-text-muted uppercase tracking-[0.2em] border-b border-white/5 bg-white/[0.02]">
                    <th class="pl-8 pr-4 py-6 w-16">
                        <div class="flex items-center justify-center">
                            <input
                                type="checkbox"
                                wire:click="toggleSelectAll"
                                @if($selectAll) checked @endif
                                class="w-5 h-5 rounded-lg border-white/10 bg-white/5 text-primary focus:ring-offset-0 focus:ring-primary/20 transition-all cursor-pointer"
                            >
                        </div>
                    </th>
                    <th class="px-6 py-6 min-w-[300px]">
                        <button wire:click="sortBy('name')" class="flex items-center gap-2 hover:text-primary transition-colors">
                            <span>Tên danh mục</span>
                            <x-backend.table.sort-icon field="name" :sortField="$sortField" :sortDirection="$sortDirection" />
                        </button>
                    </th>
                    <th class="px-6 py-6">Đường dẫn (Slug)</th>
                    <th class="px-6 py-6">Trạng thái</th>
                    <th class="px-6 py-6">Thứ tự</th>
                    <th class="pr-8 pl-4 py-6 text-right">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($categories as $category)
                    <tr class="group hover:bg-white/[0.03] transition-all duration-300" wire:key="category-{{ $category->id }}">
                        <td class="pl-8 pr-4 py-5">
                            <div class="flex items-center justify-center">
                                <input
                                    type="checkbox"
                                    value="{{ $category->id }}"
                                    wire:model.live="selectedItems"
                                    class="w-5 h-5 rounded-lg border-white/10 bg-white/5 text-primary focus:ring-offset-0 focus:ring-primary/20 transition-all cursor-pointer"
                                >
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                <div class="flex flex-col space-y-1">
                                    <div class="flex items-center gap-2">
                                        @if($category->parent_id)
                                            <span class="text-text-muted/40 font-bold ml-1">↳</span>
                                        @endif
                                        <a
                                            href="{{ route('backend.categories.edit', ['type' => $type, 'category' => $category->id]) }}"
                                            class="text-sm font-black text-text-main group-hover:text-primary transition-colors"
                                        >
                                            {{ $category->name }}
                                        </a>
                                    </div>
                                    @if($category->parent)
                                        <span class="text-[10px] text-text-muted flex items-center gap-1 leading-none uppercase tracking-wider font-bold">
                                            <i class="ti ti-folder text-xs"></i>
                                            Thuộc: {{ $category->parent->name }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <span class="text-xs font-mono text-text-muted/70 bg-white/5 py-1 px-2 rounded-md border border-white/5 group-hover:bg-white/10 transition-colors">
                                {{ $category->slug }}
                            </span>
                        </td>
                        <td class="px-6 py-5">
                            <x-backend.ui.toggle-icon 
                                type="status" 
                                :active="$category->is_active" 
                                wire:click="toggleStatus({{ $category->id }})" 
                                title="Trạng thái hiển thị"
                            />
                        </td>
                        <td class="px-6 py-5 text-center">
                            <div class="inline-flex justify-center">
                                <x-backend.ui.editable-field
                                    wire:key="category-order-{{ $category->id }}"
                                    :value="$category->order"
                                    field="order"
                                    :id="$category->id"
                                    class="text-xs font-black text-text-main text-center"
                                />
                            </div>
                        </td>
                        <td class="pr-8 pl-4 py-5">
                            <div class="flex items-center justify-end gap-2">
                                <x-backend.ui.action-icon
                                    variant="edit"
                                    :href="route('backend.categories.edit', ['type' => $type, 'category' => $category->id])"
                                    title="Chỉnh sửa nội dung"
                                />
                                <x-backend.ui.action-icon
                                    variant="delete"
                                    wire:click="confirmDelete({{ $category->id }}, '{{ addslashes($category->name) }}')"
                                    title="Xóa danh mục"
                                />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-8 py-24 text-center">
                            <div class="flex flex-col items-center justify-center gap-4">
                                <div class="w-20 h-20 rounded-3xl bg-white/5 border border-white/10 flex items-center justify-center animate-pulse">
                                    <i class="ti ti-folders-off text-4xl text-text-muted/30"></i>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-lg font-black text-text-main tracking-tight">Không tìm thấy danh mục</p>
                                    <p class="text-xs text-text-muted max-w-[240px] mx-auto leading-relaxed">
                                        Chúng tôi không tìm thấy danh mục nào phù hợp với tìm kiếm của bạn. Hãy thử thay đổi từ khóa.
                                    </p>
                                </div>
                                <button wire:click="$set('search', '')" class="text-primary text-xs font-black uppercase tracking-widest hover:underline mt-2">
                                    Xóa bộ lọc
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
        <div class="flex items-center gap-3">
            <x-backend.ui.button type="success" wire:click="bulkStatus(1)" size="sm">
                <i class="ti ti-eye"></i>
                <span>Hiện</span>
            </x-backend.ui.button>

            <x-backend.ui.button type="outline" wire:click="bulkStatus(0)" size="sm">
                <i class="ti ti-eye-off"></i>
                <span>Ẩn</span>
            </x-backend.ui.button>

            <x-backend.ui.button type="danger" wire:click="confirmBulkDelete" size="sm">
                <i class="ti ti-trash"></i>
                <span>Xóa</span>
            </x-backend.ui.button>
        </div>
    </x-slot:bulkActions>

    {{-- Modals --}}
    <x-slot:modals>
        <x-backend.layout.confirm-modal
            show="showDeleteModal"
            title="Xác nhận yêu cầu xóa"
            message="Hệ thống sẽ thực hiện xóa vĩnh viễn {{ $isBulkDelete ? 'các danh mục đã chọn' : 'danh mục [' . $deleteTargetName . ']' }}. Mọi dữ liệu liên quan sẽ bị loại bỏ và không thể khôi phục. Bạn có chắc chắn muốn tiếp tục?"
        >
            <x-backend.ui.button
                type="outline"
                size="md"
                wire:click="$set('showDeleteModal', false)"
                class="h-11 px-6 !rounded-xl"
            >
                Hủy bỏ
            </x-backend.ui.button>
            <x-backend.ui.button
                type="danger"
                size="md"
                wire:click="executeDelete"
                class="h-11 px-6 !rounded-xl shadow-lg shadow-red-500/20"
            >
                Xác nhận xóa
            </x-backend.ui.button>
        </x-backend.layout.confirm-modal>
    </x-slot:modals>
</x-backend.layout.index-page>
