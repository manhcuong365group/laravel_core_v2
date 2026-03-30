<div>
    <!-- HEADER -->
    <div class="mb-10 bento-grid grid grid-cols-1 md:grid-cols-12 gap-6 items-end">
        <div class="md:col-span-6 space-y-2">
            <div class="flex items-center gap-3">
                <span class="px-3 py-1 bg-primary/10 text-primary text-[10px] font-black uppercase tracking-[0.2em] rounded-full backdrop-blur-md border border-primary/20">Kho hàng Pro Max</span>
                <span class="w-1.5 h-1.5 rounded-full bg-primary/50 animate-pulse"></span>
            </div>
            <h1 class="text-4xl md:text-5xl font-black text-text-main tracking-tight uppercase leading-none">
                Sản Phẩm <span class="text-primary/50 text-2xl md:text-3xl ml-2">{{ $this->products->total() }}</span>
            </h1>
            <p class="text-text-muted/60 font-medium text-sm">Quản lý kho hàng, giá bán và các biến thể sản phẩm chuyên nghiệp.</p>
        </div>
        
        <div class="md:col-span-6 flex flex-wrap justify-start md:justify-end gap-3 h-fit">
            <x-mary-button label="Xuất Excel" icon="o-arrow-down-tray" wire:click="exportExcel" class="btn-ghost text-primary hover:bg-primary/10 border-primary/20 rounded-2xl h-12" />
            <x-mary-button label="Nhập Excel" icon="o-arrow-up-tray" @click="$wire.importModal = true" class="btn-ghost text-info hover:bg-info/10 border-info/20 rounded-2xl h-12" />
            <x-mary-button label="Thêm mới" icon="o-plus" link="{{ route('backend.products.create') }}" class="btn-primary shadow-glow-primary rounded-2xl px-8 h-12 font-black" />
        </div>
    </div>

    <!-- FILTERS & SEARCH -->
    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-8">
        <div class="md:col-span-6">
            <div class="relative w-full group">
                <input 
                    type="text"
                    wire:model.live.debounce.300ms="search" 
                    placeholder="Tìm tên, SKU, Barcode (nhấn / để focus)..." 
                    id="search-input"
                    class="w-full rounded-2xl bg-white/5 border border-white/5 focus:border-primary/50 focus:ring-4 focus:ring-primary/10 transition-all duration-300 pl-11 pr-12 h-14 text-sm font-medium shadow-2xl backdrop-blur-xl outline-none text-text-main placeholder:text-text-muted/50"
                />
                <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
                    <x-mary-icon name="o-magnifying-glass" class="w-5 h-5 text-text-muted/40 group-focus-within:text-primary transition-colors" />
                </div>
                <div class="absolute right-4 top-1/2 -translate-y-1/2 flex items-center gap-1 opacity-20 group-focus-within:opacity-100 transition-opacity pointer-events-none">
                    <kbd class="kbd kbd-sm bg-base-300 text-[10px] font-black border-none shadow-inner">/</kbd>
                </div>
            </div>
        </div>
        <div class="md:col-span-2">
            <x-mary-select wire:model.live="statusFilter" icon="o-funnel" :options="collect([['id' => '', 'name' => 'Tất cả trạng thái'], ['id' => '1', 'name' => 'Đang bán'], ['id' => '0', 'name' => 'Ngừng bán']])" class="rounded-2xl h-14 border-white/5 backdrop-blur-xl font-medium shadow-lg select-transparent" />
        </div>
        <div class="md:col-span-2">
            <x-mary-select wire:model.live="categoryFilter" icon="o-folder" :options="$this->categories" placeholder="Danh mục" class="rounded-2xl h-14 border-white/5 backdrop-blur-xl font-medium shadow-lg select-transparent" />
        </div>
        <div class="md:col-span-2">
            <x-mary-select wire:model.live="brandFilter" icon="o-tag" :options="$this->brands" placeholder="Thương hiệu" class="rounded-2xl h-14 border-white/5 backdrop-blur-xl font-medium shadow-lg select-transparent" />
        </div>
    </div>

    <!-- TABLE CARD -->
    <x-mary-card class="!p-0 bg-white/5 backdrop-blur-3xl rounded-[2.5rem] overflow-hidden shadow-2xl border-white/10" shadow="none">
        <div class="overflow-x-auto overflow-y-visible">
            <table class="table w-full text-text-main whitespace-nowrap border-separate border-spacing-0">
                <thead class="bg-white/5 text-text-muted/70 text-[10px] uppercase font-black tracking-[0.15em] border-b border-white/10">
                    <tr>
                        <th class="w-16 text-center py-6 border-b border-white/10">
                            <x-mary-checkbox wire:click="toggleSelectAll" :checked="$selectAll" class="checkbox-sm rounded-md checkbox-primary backdrop-blur" />
                        </th>
                        <th class="py-6 border-b border-white/10">Thông tin sản phẩm</th>
                        <th class="py-6 border-b border-white/10">Danh mục / Hiệu</th>
                        <th class="text-right py-6 border-b border-white/10">Giá niêm yết</th>
                        <th class="text-center py-6 border-b border-white/10 px-6">Kho hàng</th>
                        <th class="text-center py-6 border-b border-white/10">Trạng thái</th>
                        <th class="text-right w-48 py-6 pr-8 border-b border-white/10">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($this->products as $product)
                        <tr wire:key="product-{{ $product->id }}" class="hover:bg-white/10 transition-colors duration-200 group">
                            <td class="text-center py-6">
                                <x-mary-checkbox wire:model.live="selectedItems" value="{{ $product->id }}" class="checkbox-sm rounded-md checkbox-primary backdrop-blur" />
                            </td>
                            <td class="py-6">
                                <div class="flex items-center gap-5">
                                    <div class="relative group/avatar">
                                        <div class="avatar w-14 h-14 rounded-2xl bg-white/5 border border-white/10 overflow-hidden flex-shrink-0 shadow-2xl group-hover/avatar:scale-110 transition-transform duration-500">
                                            @if($product->getFirstMediaUrl('featured_image', 'thumb'))
                                                <img src="{{ $product->getFirstMediaUrl('featured_image', 'thumb') }}" alt="thumbnail" class="object-cover w-full h-full" />
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-text-muted/20 bg-white/5">
                                                    <x-mary-icon name="o-photo" class="w-6 h-6" />
                                                </div>
                                            @endif
                                        </div>
                                        @if($product->is_featured)
                                            <div class="absolute -top-1 -right-1 w-4 h-4 bg-warning rounded-full border-2 border-[#0f172a] shadow-glow-warning scale-75"></div>
                                        @endif
                                    </div>
                                    <div class="space-y-1">
                                        <div class="font-black text-text-main text-base tracking-tight leading-tight group-hover:text-primary transition-colors">{{ $product->name }}</div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-[10px] font-black uppercase text-text-muted/50 tracking-widest bg-white/5 px-2 py-0.5 rounded border border-white/5">{{ $product->sku ?: 'NO-SKU' }}</span>
                                            @if($product->is_featured)
                                                <span class="text-[8px] font-black uppercase text-warning tracking-tighter">Premium</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-6">
                                <div class="space-y-1.5">
                                    <div class="text-[10px] font-black uppercase tracking-[0.1em] text-primary/80">{{ $product->category?->name ?? 'Uncategorized' }}</div>
                                    @if($product->brand)
                                        <div class="text-[10px] text-text-muted/40 font-bold uppercase tracking-widest">{{ $product->brand->name }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="text-right py-6">
                                @if($product->sale_price)
                                    <div class="font-black text-success text-lg drop-shadow-[0_0_10px_rgba(16,185,129,0.4)] tracking-tight">{{ number_format($product->sale_price) }}đ</div>
                                    <div class="text-xs line-through text-text-muted/30 font-bold tracking-tighter">{{ number_format($product->price) }}đ</div>
                                @else
                                    <div class="font-black text-text-main text-lg tracking-tight">{{ number_format($product->price) }}đ</div>
                                @endif
                            </td>
                            <td class="text-center py-6 px-6">
                                <div class="inline-flex flex-col items-center">
                                    <span class="text-base font-black {{ $product->stock_quantity > 0 ? 'text-text-main' : 'text-danger' }} tracking-tight">{{ $product->stock_quantity }}</span>
                                    <div class="text-[10px] font-black uppercase tracking-widest opacity-30">Tồn kho</div>
                                </div>
                            </td>
                            <td class="text-center py-6">
                                <div class="flex flex-col items-center gap-2">
                                    <div class="flex items-center gap-4">
                                        <div class="flex flex-col items-center gap-1">
                                            <x-mary-toggle wire:click="toggleStatus({{ $product->id }})" :checked="$product->is_active" class="toggle-sm toggle-success" />
                                            <span class="text-[8px] font-black uppercase tracking-tighter {{ $product->is_active ? 'text-success' : 'text-text-muted/40' }}">Hiển thị</span>
                                        </div>
                                        <div class="flex flex-col items-center gap-1">
                                            <x-mary-toggle wire:click="toggleFeatured({{ $product->id }})" :checked="$product->is_featured" class="toggle-sm toggle-warning" />
                                            <span class="text-[8px] font-black uppercase tracking-tighter {{ $product->is_featured ? 'text-warning' : 'text-text-muted/40' }}">Nổi bật</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-right py-6 pr-8">
                                <div class="flex justify-end gap-1 items-center opacity-30 group-hover:opacity-100 transition-all duration-300">
                                    <x-mary-button icon="o-eye" link="{{ route('products.show', $product->slug) }}" target="_blank" class="btn-ghost btn-circle btn-sm text-info" tooltip="Xem Frontend" />
                                    <x-mary-button icon="o-bolt" wire:click="editProduct({{ $product->id }})" class="btn-ghost btn-circle btn-sm text-warning" tooltip="Sửa nhanh" />
                                    <x-mary-button icon="o-pencil" link="{{ route('backend.products.edit', $product->id) }}" class="btn-ghost btn-circle btn-sm text-primary" tooltip="Chỉnh sửa chi tiết" />
                                    <x-mary-button icon="o-trash" wire:click="confirmDelete({{ $product->id }}, '{{ addslashes($product->name) }}')" class="btn-ghost btn-circle btn-sm text-danger" tooltip="Xóa" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-32">
                                <div class="flex flex-col items-center opacity-20">
                                    <x-mary-icon name="o-cube-transparent" class="w-20 h-20 mb-4" />
                                    <div class="text-lg font-black uppercase tracking-[0.3em]">Kho hàng trống</div>
                                    <p class="text-xs font-medium tracking-normal normal-case mt-2">Bắt đầu bằng cách thêm sản phẩm đầu tiên của bạn.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($this->products->hasPages())
            <div class="p-6 border-t border-white/10 bg-white/5 backdrop-blur-3xl">
                {{ $this->products->links() }}
            </div>
        @endif
    </x-mary-card>

    <!-- Bulk Actions -->
    <x-backend.table.bulk-actions-toolbar :count="count($selectedItems)">
        <x-mary-button icon="o-check" label="Hiện sản phẩm" wire:click="bulkStatus(1)" class="btn-success btn-sm text-white rounded-xl shadow-glow-success font-black px-6 h-10" />
        <x-mary-button icon="o-x-mark" label="Ẩn sản phẩm" wire:click="bulkStatus(0)" class="btn-warning btn-sm text-white rounded-xl shadow-md font-black px-6 h-10" />
        <div class="w-px h-6 bg-white/10 mx-2"></div>
        <x-mary-button icon="o-trash" label="Xóa đã chọn" wire:click="confirmBulkDelete" class="btn-error btn-sm text-white rounded-xl font-black shadow-glow-error px-6 h-10" />
    </x-backend.table.bulk-actions-toolbar>

    <!-- Quick Edit Drawer -->
    <x-mary-drawer wire:model="showQuickDrawer" title="Sửa Nhanh #{{ $editingProduct['id'] }}" right separator class="w-11/12 md:max-w-md backdrop-blur-xl bg-white/10">

        <div class="space-y-8 p-1">
            <div class="glass-card p-6 border-t-2 border-t-warning shadow-xl">
                <div class="space-y-6">
                    <div>
                        <label class="text-[10px] font-black uppercase text-text-muted tracking-widest mb-2 block">Tên sản phẩm</label>
                        <div class="text-base font-bold text-text-main line-clamp-2 leading-tight bg-base-300/30 p-3 rounded-xl border border-white/5">{{ $editingProduct['name'] }}</div>
                    </div>

                    <x-mary-input label="Mã SKU" wire:model="editingProduct.sku" icon="o-qr-code" readonly class="bg-base-300/20" />
                    
                    <div class="grid grid-cols-2 gap-4">
                        <x-mary-input 
                            label="Giá bán (đ) *" 
                            wire:model="editingProduct.price" 
                            class="font-black text-success"
                            x-on:input="$el.value = String($el.value).replace(/[^0-9]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')"
                        />
                        <x-mary-input 
                            label="Giá ưu đãi (đ)" 
                            wire:model="editingProduct.sale_price" 
                            class="font-bold"
                            x-on:input="$el.value = String($el.value).replace(/[^0-9]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')"
                        />
                    </div>

                    @if(!$editingProduct['has_variants'])
                    <x-mary-input label="Kho hàng *" wire:model="editingProduct.stock_quantity" type="number" icon="o-cube" />
                    @else
                    <div class="bg-primary/5 p-4 rounded-xl border border-primary/10 flex items-center gap-3">
                        <x-mary-icon name="o-information-circle" class="w-5 h-5 text-primary" />
                        <div class="text-xs font-medium text-primary">Sản phẩm có biến thể. Vui lòng vào trang Edit để cập nhật kho chi tiết.</div>
                    </div>
                    @endif

                    <x-mary-toggle label="Mở bán công khai" wire:model="editingProduct.is_active" class="toggle-success" />
                </div>
            </div>

            <div class="flex items-center justify-between gap-4 pt-4">
                <x-mary-button label="Huỷ bỏ" wire:click="$set('showQuickDrawer', false)" class="btn-ghost flex-1 rounded-2xl" />
                <x-mary-button label="Cập Nhật ✨" wire:click="updateQuickEdit" class="btn-warning shadow-glow-warning flex-[2] rounded-2xl font-black" spinner="updateQuickEdit" />
            </div>
        </div>
    </x-mary-drawer>

    {{-- Import Modal --}}
    <x-mary-modal wire:model="importModal" title="Nhập sản phẩm từ Excel" separator>
        <div class="space-y-6">
            <div class="p-4 rounded-2xl bg-primary/5 border border-primary/10">
                <h4 class="font-bold text-primary flex items-center gap-2 mb-2 text-sm">
                    <x-mary-icon name="o-information-circle" class="w-5 h-5" />
                    Hướng dẫn nhập liệu
                </h4>
                <ul class="text-[11px] text-text-muted/80 space-y-1 ml-6 list-disc">
                    <li>Sử dụng file .xlsx, .xls hoặc .csv</li>
                    <li>Dung lượng tối đa 10MB</li>
                    <li>Hệ thống sẽ cập nhật sản phẩm nếu trùng SKU, ngược lại sẽ thêm mới</li>
                    <li>Nên xuất Excel trước để lấy file mẫu chuẩn nhất</li>
                </ul>
            </div>

            <x-mary-file wire:model="importFile" label="Chọn file Excel" hint="Chấp nhận .xlsx, .xls, .csv" accept=".xlsx,.xls,.csv" />
            
            <div wire:loading wire:target="importFile">
                <div class="flex items-center gap-2 text-primary animate-pulse font-bold text-sm mt-2">
                    <x-mary-loading class="loading-sm" />
                    Đang tải file lên...
                </div>
            </div>
        </div>

        <x-slot:actions>
            <x-mary-button label="Hủy" @click="$wire.importModal = false" />
            <x-mary-button label="Bắt đầu Nhập 🚀" icon="o-check" wire:click="importExcel" class="btn-primary" wire:loading.attr="disabled" />
        </x-slot:actions>
    </x-mary-modal>

    @push('scripts')
    <style>
        /* Fix Mary Select Dropdown Transparency */
        .mary-select-content, .dropdown-content {
            background-color: #111827 !important; /* Màu nền Dark Gray đậm */
            color: white !important;
            border: 1px solid rgba(255,255,255,0.1);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5) !important;
            backdrop-filter: blur(20px);
        }
        .mary-select-content li:hover, .dropdown-content li:hover {
            background-color: rgba(255,255,255,0.1) !important;
        }

        /* Fix Double Background for native select & Option visibility */
        .select-transparent select {
            background-color: transparent !important;
            border: none !important;
            box-shadow: none !important;
            outline: none !important;
            color: white !important;
        }

        .select-transparent select option {
            background-color: #1f2937; /* Màu nền cho option trên Windows/Chrome */
            color: white;
        }

        .select-transparent {
            background-color: rgba(255, 255, 255, 0.05); /* Lớp nền duy nhất */
        }
    </style>
    <script>
        document.addEventListener('keydown', (e) => {
            if (e.key === '/' && e.target.tagName !== 'INPUT' && e.target.tagName !== 'TEXTAREA') {
                e.preventDefault();
                const searchInput = document.getElementById('search-input');
                if (searchInput) searchInput.focus();
            }
        });
    </script>
    @endpush
</div>
