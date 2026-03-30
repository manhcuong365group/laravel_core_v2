<section class="page space-y-8">
    <!-- HEADER -->
    <x-mary-header separator class="mb-8">
        <x-slot:title>
            <p class="text-[11px] font-black text-primary uppercase tracking-[0.2em] mb-1 opacity-80">Quản lý hệ thống</p>
            <h1 class="text-3xl font-black text-text-main tracking-tight uppercase tracking-[0.1em]">{{ $title }}</h1>
        </x-slot:title>
        <x-slot:subtitle>
            <span class="text-xs font-medium text-text-muted uppercase tracking-widest opacity-60">Tổng số: {{ $this->categories->total() }} danh mục đang hoạt động</span>
        </x-slot:subtitle>
        
        <x-slot:middle class="!justify-end" wire:ignore>
            <div class="relative w-full sm:w-96 group">
                <input 
                    type="text"
                    wire:model.live.debounce.300ms="search" 
                    placeholder="Tìm tên danh mục..." 
                    class="w-full rounded-2xl bg-white/5 border border-white/5 focus:border-primary/50 focus:ring-4 focus:ring-primary/10 transition-all duration-300 pl-11 pr-12 h-11 text-sm font-medium shadow-2xl backdrop-blur-md outline-none text-text-main placeholder:text-text-muted/50"
                    id="search-input"
                    aria-label="Tìm kiếm danh mục"
                />
                <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
                    <x-mary-icon name="o-magnifying-glass" class="w-4.5 h-4.5 text-text-muted/60 group-focus-within:text-primary transition-colors" />
                </div>
                <div class="absolute right-4 top-1/2 -translate-y-1/2 flex items-center gap-1 opacity-40 group-focus-within:opacity-100 transition-opacity pointer-events-none">
                    <kbd class="kbd kbd-sm bg-base-300/50 text-[10px] font-black border-none shadow-inner opacity-60">/</kbd>
                </div>
            </div>
        </x-slot:middle>
        <x-slot:actions>
            <x-mary-button label="Thêm mới" icon="o-plus" link="{{ route('backend.categories.create', $type) }}" class="btn-primary shadow-lg shadow-primary/30 font-black" />
        </x-slot:actions>
    </x-mary-header>

    <!-- FILTERS -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
        <x-mary-select wire:model.live="statusFilter" icon="o-funnel" :options="collect([['id' => '', 'name' => 'Tất cả trạng thái'], ['id' => '1', 'name' => 'Đang hiển thị'], ['id' => '0', 'name' => 'Đã ẩn']])" class="rounded-2xl glass-card border-white/10 shadow-sm font-bold text-xs" />
    </div>

    <!-- TABLE CARD -->
    <x-mary-card class="!p-0 glass-card rounded-3xl overflow-hidden shadow-2xl border-white/10" shadow="none">
        <div class="overflow-x-auto relative min-h-[400px]">
            <!-- Loading Overlay -->
            <div wire:loading class="absolute inset-0 bg-base-100/20 backdrop-blur-[2px] z-10 flex items-center justify-center transition-all">
                <div class="flex flex-col items-center gap-3">
                    <span class="loading loading-spinner loading-lg text-primary"></span>
                    <span class="text-xs font-black uppercase tracking-widest text-primary animate-pulse">Đang tải dữ liệu...</span>
                </div>
            </div>

            <table class="table w-full text-base-content whitespace-nowrap">
                <thead class="bg-base-200/50 text-base-content/80 text-[10px] uppercase tracking-[0.15em] font-black border-b border-white/10">
                    <tr>
                        <th class="w-12 text-center py-5">
                            <x-mary-checkbox wire:click="toggleSelectAll" :checked="$selectAll" class="checkbox-sm rounded-md checkbox-primary backdrop-blur" />
                        </th>
                        @if($sortField === 'order')
                        <th class="w-10"></th>
                        @endif
                        <th class="py-4">Danh mục</th>
                        <th class="py-4">Mô tả</th>
                        <th class="text-center py-4">Thứ tự</th>
                        <th class="text-center py-4">Hiển thị</th>
                        <th class="text-right w-32 py-4">Thao tác</th>
                    </tr>
                </thead>
                <tbody 
                    @if($sortField === 'order')
                    x-data="{ 
                        sortable: null,
                        init() {
                            this.sortable = new Sortable($refs.list, {
                                animation: 150,
                                handle: '.handle',
                                ghostClass: 'bg-primary/10',
                                onEnd: (evt) => {
                                    const items = Array.from($refs.list.querySelectorAll('tr')).map((tr, index) => {
                                        return { id: tr.getAttribute('wire:key').replace('category-', ''), order: index + 1 };
                                    });
                                    $wire.updateOrder(items);
                                }
                            });
                        }
                    }"
                    x-ref="list"
                    @endif
                    class="divide-y divide-white/5"
                >
                    @forelse($this->categories as $category)
                        <tr wire:key="category-{{ $category->id }}" class="hover:bg-white/5 transition-all duration-300 group">
                            <td class="text-center py-4">
                                <x-mary-checkbox wire:model.live="selectedItems" value="{{ $category->id }}" class="checkbox-sm rounded-md checkbox-primary backdrop-blur" />
                            </td>
                            @if($sortField === 'order')
                            <td class="text-center py-4 cursor-move handle">
                                <x-mary-icon name="o-bars-3" class="w-4 h-4 text-base-content/20 group-hover:text-primary transition-colors" />
                            </td>
                            @endif
                            <td class="py-4">
                                <div class="flex items-center gap-4">
                                    <div class="avatar w-12 h-12 rounded-2xl bg-base-300/50 border border-white/10 overflow-hidden flex-shrink-0 shadow-inner group-hover:border-primary/30 transition-colors">
                                        @if($category->getFirstMediaUrl('image', 'thumb'))
                                            <img src="{{ $category->getFirstMediaUrl('image', 'thumb') }}" alt="thumbnail" class="object-cover w-full h-full group-hover:scale-110 transition-transform duration-500" />
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-base-content/20 bg-base-200">
                                                <x-mary-icon name="o-photo" class="w-5 h-5" />
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-black text-text-main text-sm md:text-base transition-colors group-hover:text-primary tracking-tight">{{ $category->name }}</div>
                                        @if($category->parent)
                                            <div class="text-[9px] text-text-muted/60 uppercase tracking-[0.1em] flex items-center gap-1 mt-1 font-bold">
                                                <x-mary-icon name="o-chevron-double-right" class="w-2.5 h-2.5 text-primary/50" />
                                                {{ $category->parent->name }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-4">
                                <div class="text-xs text-text-muted/70 max-w-xs truncate font-medium">{{ $category->description ?: '---' }}</div>
                            </td>
                            <td class="text-center py-4">
                                <span class="px-2.5 py-1 rounded-lg bg-base-300/50 text-[10px] font-black tracking-widest text-text-muted group-hover:text-primary transition-colors">{{ str_pad($category->order, 2, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td class="text-center py-4">
                                <div class="flex justify-center items-center">
                                    <x-mary-toggle wire:click="toggleStatus({{ $category->id }})" :checked="$category->is_active" class="toggle-sm toggle-success" />
                                </div>
                            </td>
                            <td class="text-right pr-6">
                                <div class="flex justify-end gap-1 opacity-0 group-hover:opacity-100 items-center transition-all duration-300 translate-x-4 group-hover:translate-x-0">
                                    <x-mary-button icon="o-pencil" link="{{ route('backend.categories.edit', ['type' => $type, 'category' => $category->id]) }}" class="btn-ghost btn-circle btn-sm text-primary hover:bg-primary/10" tooltip="Chỉnh sửa" />
                                    <x-mary-button icon="o-trash" wire:click="deleteCategory({{ $category->id }})" class="btn-ghost btn-circle btn-sm text-error hover:bg-error/10" tooltip="Xóa" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-32">
                                <div class="flex flex-col items-center max-w-sm mx-auto">
                                    <div class="w-20 h-20 rounded-full bg-base-200/50 flex items-center justify-center mb-6 border border-white/5 shadow-inner">
                                        <x-mary-icon name="o-folder-open" class="w-10 h-10 text-text-muted/30" />
                                    </div>
                                    <h3 class="text-xl font-black text-text-main uppercase tracking-tight mb-2">Chưa có danh mục nào</h3>
                                    <p class="text-xs text-text-muted font-medium mb-8 leading-relaxed">Hệ thống hiện chưa có danh mục nào trong mục này. Bắt đầu bằng cách thêm mới một danh mục đầu tiên.</p>
                                    <x-mary-button label="Thêm danh mục mới ✨" icon="o-plus" link="{{ route('backend.categories.create', $type) }}" class="btn-primary btn-md shadow-xl shadow-primary/20 font-black rounded-2xl px-8" />
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($this->categories->hasPages())
            <div class="p-6 border-t border-white/10 bg-base-200/20 backdrop-blur-md flex justify-center">
                {{ $this->categories->links() }}
            </div>
        @endif
    </x-mary-card>

    <!-- Bulk Actions -->
    <x-backend.table.bulk-actions-toolbar :count="count($selectedItems)">
        <x-mary-button icon="o-check" label="Hiện đã chọn" wire:click="bulkStatus(1)" class="btn-success btn-sm text-white rounded-xl shadow-glow-success font-black h-10 px-6" />
        <x-mary-button icon="o-x-mark" label="Ẩn đã chọn" wire:click="bulkStatus(0)" class="btn-warning btn-sm text-white rounded-xl shadow-md font-black h-10 px-6" />
        <div class="w-px h-6 bg-white/10 mx-2"></div>
        <x-mary-button icon="o-trash" label="Xóa đã chọn" wire:click="executeDelete" class="btn-error btn-sm text-white rounded-xl font-black shadow-glow-error h-10 px-6" />
    </x-backend.table.bulk-actions-toolbar>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('keydown', (e) => {
            if (e.key === '/' && e.target.tagName !== 'INPUT' && e.target.tagName !== 'TEXTAREA') {
                e.preventDefault();
                document.getElementById('search-input').focus();
            }
        });
    </script>
    @endpush
</section>

