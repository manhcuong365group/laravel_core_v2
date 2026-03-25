<x-backend.layout.index-page
    title="Sản phẩm & Kho hàng"
    subtitle="Inventory Management"
    description="Hệ thống quản lý sản phẩm chuyên sâu, tối ưu hóa quy trình kiểm kê và phân phối sản phẩm đa kênh."
    :total="$products->total()"
    totalLabel="sản phẩm"
    searchPlaceholder="Tìm tên sản phẩm, SKU hoặc thông số..."
    :selectedCount="count($selectedItems)"
    icon="ti ti-box-seam"
>
    {{-- Header Actions --}}
    <x-slot:headerActions>
        <div class="flex items-center gap-4">
            <x-backend.ui.button
                type="outline"
                :href="route('backend.products.create')"
                class="!rounded-2xl h-11 px-6 border-white/10 hover:border-primary/50"
            >
                <i class="ti ti-file-import text-lg mr-2"></i>
                <span class="font-bold">Nhập File</span>
            </x-backend.ui.button>

            <x-backend.ui.button
                type="primary"
                :href="route('backend.products.create')"
                class="!rounded-2xl shadow-xl shadow-primary/30 group h-11 px-6"
            >
                <div class="flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-white/20 flex items-center justify-center group-hover:rotate-90 transition-all duration-500">
                        <i class="ti ti-plus text-sm"></i>
                    </span>
                    <span class="font-bold tracking-tight">Thêm sản phẩm</span>
                </div>
            </x-backend.ui.button>
        </div>
    </x-slot:headerActions>

    {{-- Filters --}}
    <x-slot:filters>
        {{-- Status Filter --}}
        <div class="flex items-center p-1 bg-white/[0.03] border border-white/5 rounded-2xl">
            @foreach(['' => 'Tất cả', '1' => 'Đang bán', '0' => 'Ngừng bán'] as $key => $label)
                <button
                    wire:click="$set('statusFilter', '{{ $key }}')"
                    class="px-4 py-2 rounded-xl transition-all duration-300 text-xs font-black uppercase tracking-widest {{ (string)$statusFilter === (string)$key ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-text-muted hover:text-text-main hover:bg-white/5' }}"
                >
                    {{ $label }}
                </button>
            @endforeach
        </div>
        
        <div class="h-8 w-px bg-white/10 mx-3 hidden sm:block"></div>
        
        {{-- Category Filter --}}
        <x-backend.ui.dropdown align="left" width="64">
            <x-slot name="trigger">
                <button class="flex items-center gap-3 px-4 py-2.5 rounded-2xl bg-white/[0.03] border border-white/10 hover:border-primary/30 text-xs font-black text-text-muted hover:text-text-main transition-all duration-300">
                    <i class="ti ti-category text-base text-primary/70"></i>
                    <span>{{ $categoryFilter ? ($categories->firstWhere('id', $categoryFilter)?->name ?? 'Tất cả danh mục') : 'Phân loại danh mục' }}</span>
                    <i class="ti ti-chevron-down text-[10px] ml-1"></i>
                </button>
            </x-slot>
            <x-slot name="content">
                <div class="max-h-80 overflow-y-auto no-scrollbar py-2">
                    <button wire:click="$set('categoryFilter', null)" class="w-full text-left px-5 py-3 text-xs font-black uppercase tracking-widest border-l-4 border-transparent text-text-muted hover:bg-white/5 hover:text-text-main transition-all">Tất cả danh mục</button>
                    @foreach($categories as $category)
                        <button 
                            wire:click="$set('categoryFilter', {{ $category->id }})" 
                            class="w-full text-left px-5 py-3 text-xs font-black uppercase tracking-widest border-l-4 transition-all {{ $categoryFilter == $category->id ? 'border-primary text-primary bg-primary/5' : 'border-transparent text-text-muted hover:bg-white/5 hover:text-text-main' }}"
                        >
                            {{ $category->name }}
                        </button>
                    @endforeach
                </div>
            </x-slot>
        </x-backend.ui.dropdown>

        {{-- Brand Filter --}}
        <x-backend.ui.dropdown align="left" width="64">
            <x-slot name="trigger">
                <button class="flex items-center gap-3 px-4 py-2.5 rounded-2xl bg-white/[0.03] border border-white/10 hover:border-primary/30 text-xs font-black text-text-muted hover:text-text-main transition-all duration-300">
                    <i class="ti ti-building-store text-base text-violet-400/70"></i>
                    <span>{{ $brandFilter ? ($brands->firstWhere('id', $brandFilter)?->name ?? 'Tất cả thương hiệu') : 'Theo thương hiệu' }}</span>
                    <i class="ti ti-chevron-down text-[10px] ml-1"></i>
                </button>
            </x-slot>
            <x-slot name="content">
                <div class="max-h-80 overflow-y-auto no-scrollbar py-2">
                    <button wire:click="$set('brandFilter', null)" class="w-full text-left px-5 py-3 text-xs font-black uppercase tracking-widest border-l-4 border-transparent text-text-muted hover:bg-white/5 hover:text-text-main transition-all">Tất cả thương hiệu</button>
                    @foreach($brands as $brand)
                        <button 
                            wire:click="$set('brandFilter', {{ $brand->id }})" 
                            class="w-full text-left px-5 py-3 text-xs font-black uppercase tracking-widest border-l-4 transition-all {{ $brandFilter == $brand->id ? 'border-primary text-primary bg-primary/5' : 'border-transparent text-text-muted hover:bg-white/5 hover:text-text-main' }}"
                        >
                            {{ $brand->name }}
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
                        <x-backend.table.table-checkbox
                            wire:click="toggleSelectAll"
                            :checked="$selectAll"
                            class="scale-110"
                        />
                    </th>
                    <x-backend.table.table-th sort="name" :sortField="$sortField" :sortDirection="$sortDirection" class="px-6 py-6 min-w-[320px]">
                        Thông tin chi tiết
                    </x-backend.table.table-th>
                    <th class="px-6 py-6 text-[11px] font-black text-text-muted uppercase tracking-[0.2em] whitespace-nowrap">Danh mục & Brand</th>
                    <x-backend.table.table-th sort="price" :sortField="$sortField" :sortDirection="$sortDirection" class="px-6 py-6 text-right whitespace-nowrap">
                        Giá
                    </x-backend.table.table-th>
                    <x-backend.table.table-th sort="stock_quantity" :sortField="$sortField" :sortDirection="$sortDirection" class="px-6 py-6 text-center whitespace-nowrap">
                        Tồn kho / Thứ tự
                    </x-backend.table.table-th>
                    <th class="px-6 py-6 text-[11px] font-black text-text-muted uppercase tracking-[0.2em] text-center whitespace-nowrap">Trạng thái</th>
                    <th class="pr-8 pl-4 py-6 text-[11px] font-black text-text-muted uppercase tracking-[0.2em] text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($products as $product)
                    <tr class="group hover:bg-white/[0.03] transition-all duration-500" wire:key="product-{{ $product->id }}">
                        <td class="pl-8 pr-4 py-6 text-center border-b border-white/5">
                            <x-backend.table.table-checkbox
                                value="{{ $product->id }}"
                                wire:model.live="selectedItems"
                                class="scale-110"
                            />
                        </td>
                        <td class="px-6 py-6 border-b border-white/5">
                            <div class="flex items-center gap-5">
                                <div class="relative flex-shrink-0 group/img cursor-zoom-in">
                                    <div class="w-16 h-16 rounded-2xl overflow-hidden bg-gradient-to-br from-white/5 to-white/10 border border-white/10 group-hover:border-primary/40 transition-all duration-500 shadow-lg">
                                        @php
                                            $imageUrl = $product->getFirstMediaUrl('featured_image', 'thumb') ?: ($product->featured_image ? Storage::url($product->featured_image) : null);
                                        @endphp
                                        @if($imageUrl)
                                            <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover/img:scale-110 transition-transform duration-700">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <div class="flex flex-col items-center">
                                                    <i class="ti ti-camera-off text-xl text-text-muted/20"></i>
                                                    <span class="text-[8px] font-bold text-text-muted/30 uppercase tracking-tighter mt-1 leading-none">No Photo</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    @if($product->is_featured)
                                        <div class="absolute -top-1.5 -right-1.5 w-6 h-6 rounded-xl bg-gradient-to-br from-amber-400 to-orange-600 flex items-center justify-center shadow-lg border-2 border-slate-900 group-hover:scale-110 transition-transform">
                                            <i class="ti ti-star-filled text-[10px] text-white"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex flex-col space-y-1.5 min-w-0">
                                    <a href="{{ route('backend.products.edit', $product->id) }}" class="text-[15px] font-black text-text-main hover:text-primary transition-all duration-300 line-clamp-1 tracking-tight">
                                        {{ $product->name }}
                                    </a>
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-lg bg-white/5 border border-white/5 text-[10px] font-black text-text-muted uppercase tracking-wider">
                                            <i class="ti ti-barcode text-xs text-primary/50"></i>
                                            {{ $product->sku ?? 'NO-SKU' }}
                                        </span>
                                        <span class="flex items-center gap-1.5 text-[10px] font-black uppercase tracking-widest {{ $product->stock_quantity > 10 ? 'text-emerald-500' : ($product->stock_quantity > 0 ? 'text-orange-500' : 'text-red-500') }}">
                                            <span class="w-1.5 h-1.5 rounded-full bg-current shadow-[0_0_8px_currentColor]"></span>
                                            {{ $product->stock_quantity > 10 ? 'Còn hàng' : ($product->stock_quantity > 0 ? 'Sắp hết' : 'Hết hàng') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-6 border-b border-white/5">
                            <div class="flex flex-col gap-2">
                                <span class="px-2.5 py-1 rounded-xl bg-white/[0.03] border border-white/5 text-text-main text-[10px] font-black uppercase tracking-[0.1em] inline-flex w-fit group-hover:border-primary/20 transition-all">
                                    <i class="ti ti-category-2 mr-1.5 text-primary/60"></i>
                                    {{ $product->category?->name ?? 'Uncategorized' }}
                                </span>
                                @if($product->brand)
                                    <span class="text-[10px] font-black text-text-muted uppercase tracking-widest pl-1 opacity-60 group-hover:opacity-100 transition-opacity">
                                        {{ $product->brand->name }}
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-6 text-right border-b border-white/5">
                            <div class="flex flex-col gap-1 items-end">
                                <x-backend.ui.editable-field
                                    wire:key="price-{{ $product->id }}"
                                    :value="$product->sale_price ?: $product->price"
                                    :display-value="number_format($product->sale_price ?: $product->price)"
                                    field="{{ $product->sale_price ? 'sale_price' : 'price' }}"
                                    :id="$product->id"
                                    suffix="đ"
                                    class="text-[15px] font-black text-text-main text-right hover:text-primary transition-colors cursor-pointer"
                                />
                                @if($product->sale_price && $product->price > 0)
                                    <div class="flex items-center gap-1.5 text-[10px] font-bold">
                                        <span class="text-text-muted line-through opacity-50">{{ number_format($product->price) }}đ</span>
                                        <span class="text-rose-500">Giảm {{ round((1 - $product->sale_price / $product->price) * 100) }}%</span>
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-6 border-b border-white/5">
                            <div class="flex flex-col items-center gap-2.5">
                                <x-backend.ui.editable-field
                                    wire:key="stock-{{ $product->id }}"
                                    :value="$product->stock_quantity"
                                    field="stock_quantity"
                                    :id="$product->id"
                                    class="text-xs font-black text-text-main text-center bg-white/5 px-2 py-0.5 rounded-lg border border-transparent hover:border-white/10"
                                />
                                <div class="flex items-center gap-1 opacity-50 group-hover:opacity-100 transition-all">
                                    <span class="text-[9px] font-black text-text-muted uppercase tracking-widest">Thứ tự:</span>
                                    <x-backend.ui.editable-field
                                        wire:key="order-{{ $product->id }}"
                                        :value="$product->order"
                                        field="order"
                                        :id="$product->id"
                                        class="text-[9px] font-black text-text-muted text-center"
                                    />
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-6 border-b border-white/5">
                            <div class="flex flex-col items-center gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex flex-col items-center gap-1">
                                        <x-backend.ui.toggle-icon 
                                            type="status" 
                                            :active="$product->is_active" 
                                            wire:click="toggleStatus({{ $product->id }})" 
                                            title="Bật/Tắt hiển thị"
                                            class="hover:scale-110 transition-transform"
                                        />
                                        <span class="text-[8px] font-black uppercase text-text-muted/40 tracking-tighter">Hiển thị</span>
                                    </div>
                                    <div class="flex flex-col items-center gap-1">
                                        <x-backend.ui.toggle-icon 
                                            type="featured" 
                                            :active="$product->is_featured" 
                                            wire:click="toggleFeatured({{ $product->id }})" 
                                            title="Đánh dấu nổi bật"
                                            class="hover:scale-110 transition-transform"
                                        />
                                        <span class="text-[8px] font-black uppercase text-text-muted/40 tracking-tighter">Nổi bật</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="pr-8 pl-4 py-6 text-right border-b border-white/5">
                            <div class="flex items-center justify-end gap-1.5">
                                <x-backend.ui.action-icon
                                    variant="copy"
                                    wire:click="copyProduct({{ $product->id }})"
                                    title="Nhân bản sản phẩm"
                                    class="text-emerald-500/70 hover:text-emerald-500"
                                />
                                <x-backend.ui.action-icon
                                    variant="view"
                                    :href="route('products.show', $product->slug)"
                                    title="Xem chi tiết"
                                    target="_blank"
                                    class="text-amber-500/70 hover:text-amber-500"
                                />
                                <x-backend.ui.action-icon
                                    variant="edit"
                                    :href="route('backend.products.edit', $product->id)"
                                    title="Hiệu chỉnh thông tin"
                                    class="text-blue-500/70 hover:text-blue-500"
                                />
                                <x-backend.ui.action-icon
                                    variant="delete"
                                    wire:click="confirmDelete({{ $product->id }}, '{{ addslashes($product->name) }}')"
                                    title="Xử lý xóa"
                                    class="text-rose-500/70 hover:text-rose-500"
                                />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-8 py-32 text-center">
                            <div class="flex flex-col items-center justify-center gap-6">
                                <div class="w-24 h-24 rounded-[2.5rem] bg-gradient-to-br from-white/5 to-white/[0.02] border border-white/10 flex items-center justify-center animate-pulse shadow-inner">
                                    <i class="ti ti-box-off text-5xl text-text-muted/20"></i>
                                </div>
                                <div class="space-y-2">
                                    <p class="text-2xl font-black text-text-main tracking-tight">Kho hàng đang trống</p>
                                    <p class="text-sm text-text-muted max-w-[320px] mx-auto leading-relaxed">
                                        Hệ thống chưa tìm thấy sản phẩm nào phù hợp với bộ lọc hiện tại. Hãy thử thay đổi cấu hình tìm kiếm.
                                    </p>
                                </div>
                                <button wire:click="$set('search', '')" class="h-10 px-8 rounded-xl bg-white/5 border border-white/10 text-primary text-[11px] font-black uppercase tracking-[0.2em] hover:bg-primary hover:text-white hover:shadow-xl hover:shadow-primary/30 transition-all active:scale-95">
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
        @if ($products->hasPages())
            {{ $products->links() }}
        @endif
    </x-slot:pagination>

    {{-- Bulk Actions Toolbar --}}
    <x-slot:bulkActions>
        <div class="flex items-center gap-2">
            <x-backend.ui.button 
                type="success" 
                wire:click="bulkStatus(1)" 
                size="sm"
                class="!rounded-xl px-4 py-2 border-none bg-emerald-500 hover:bg-emerald-600 shadow-lg shadow-emerald-500/20"
            >
                <i class="ti ti-package-import mr-2"></i>
                <span class="font-black uppercase text-[10px] tracking-widest">Mở bán loạt</span>
            </x-backend.ui.button>

            <x-backend.ui.button 
                type="outline" 
                wire:click="bulkStatus(0)" 
                size="sm"
                class="!rounded-xl px-4 py-2 border-white/10 hover:border-white/20 bg-white/5"
            >
                <i class="ti ti-package-export mr-2"></i>
                <span class="font-black uppercase text-[10px] tracking-widest">Tạm ngưng loạt</span>
            </x-backend.ui.button>

            <div class="w-px h-6 bg-white/10 mx-1"></div>

            <x-backend.ui.button 
                size="sm"
                wire:click="confirmBulkDelete" 
                class="!rounded-xl px-4 py-2 border-none bg-rose-500 hover:bg-rose-600 shadow-lg shadow-rose-500/20"
            >
                <i class="ti ti-trash-x mr-2"></i>
                <span class="font-black uppercase text-[10px] tracking-widest text-white">Xóa vĩnh viễn</span>
            </x-backend.ui.button>
        </div>
    </x-slot:bulkActions>

    {{-- Modals --}}
    <x-slot:modals>
        <x-backend.layout.confirm-modal
            show="showDeleteModal"
            title="Xử lý dữ liệu nghiêm trọng"
            message="Bạn đang yêu cầu hệ thống xóa bỏ dữ liệu sản phẩm. Hành động này sẽ loại bỏ mọi thông tin kho hàng, hình ảnh và lịch sử liên quan. Đây là thao tác không thể khôi phục."
        >
            <x-backend.ui.button
                type="outline"
                size="md"
                wire:click="$set('showDeleteModal', false)"
                class="h-12 px-8 !rounded-2xl border-white/10 font-bold"
            >
                Hủy yêu cầu
            </x-backend.ui.button>
            <x-backend.ui.button
                type="danger"
                size="md"
                wire:click="executeDelete"
                class="h-12 px-8 !rounded-2xl bg-rose-500 hover:bg-rose-600 shadow-2xl shadow-rose-500/40 font-bold"
            >
                Xác nhận thực thi
            </x-backend.ui.button>
        </x-backend.layout.confirm-modal>
    </x-slot:modals>
</x-backend.layout.index-page>
