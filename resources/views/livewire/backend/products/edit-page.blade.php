<div class="space-y-8 pb-32">
    <!-- Premium Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="flex items-start gap-5">
            <div class="hidden sm:flex w-16 h-16 rounded-3xl bg-gradient-to-br from-primary/20 to-primary/5 items-center justify-center border border-primary/20 shadow-2xl shadow-primary/10 relative group overflow-hidden">
                <div class="absolute inset-0 bg-primary/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <i class="ti ti-package text-3xl text-primary relative z-10 transition-transform duration-500 group-hover:scale-110"></i>
            </div>
            <div class="space-y-1">
                <div class="flex items-center gap-3">
                    <h1 class="text-4xl font-black text-text-main tracking-tight">{{ $pageTitle }}</h1>
                    <span class="px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-500 text-[10px] font-black uppercase tracking-wider border border-emerald-500/20 shadow-sm shadow-emerald-500/5">Đang kích hoạt</span>
                </div>
                <div class="flex items-center gap-4 text-sm font-medium">
                    <span class="flex items-center gap-1.5 text-text-muted">
                        <i class="ti ti-history text-lg opacity-60"></i> Cập nhật: {{ $product->updated_at->diffForHumans() }}
                    </span>
                    <div class="w-1 h-1 rounded-full bg-white/10"></div>
                    <a href="{{ route('products.show', $product->slug) }}" target="_blank" class="flex items-center gap-1.5 text-primary hover:text-white transition-colors group">
                        <i class="ti ti-world text-lg opacity-80 group-hover:scale-110 transition-transform"></i>
                        <span>Xem thực tế trang cửa hàng</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <x-backend.ui.button variant="neutral" :href="route('backend.products.index')" icon="ti ti-arrow-left" class="bg-white/5 border-white/10 hover:bg-white/10">
                Tất cả sản phẩm
            </x-backend.ui.button>
        </div>
    </div>

    <form wire:submit="save" class="grid grid-cols-1 xl:grid-cols-12 gap-8 items-start">
        <!-- Main Form Column (8cols) -->
        <div class="xl:col-span-8 space-y-8">
            
            <!-- Basic Information -->
            <x-admin.form-section title="Chi tiết sản phẩm" icon="ti-info-circle" color="blue">
                <div class="grid grid-cols-1 gap-6">
                    <x-backend.forms.input 
                        wire:model.blur="name" 
                        name="name" 
                        label="Tên sản phẩm" 
                        required 
                        placeholder="Ví dụ: Apple iPhone 15 Pro Max" 
                        class="text-lg font-bold placeholder:font-medium placeholder:opacity-30"
                    />
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-backend.forms.input wire:model="slug" name="slug" label="Đường dẫn tĩnh (URL Slug)" placeholder="tu-dong-tao-tu-ten" />
                        <x-backend.forms.input wire:model="sku" name="sku" label="Mã định danh (SKU)" placeholder="Ví dụ: SKU-PRO-MAX-001" />
                    </div>

                    <x-backend.forms.textarea wire:model="short_description" name="short_description" label="Mô tả tóm tắt" rows="3" placeholder="Nhập tóm tắt nhanh về các đặc điểm nổi bật nhất của sản phẩm..." />
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-text-main/80 ml-1 italic opacity-70">Nội dung chi tiết (Rich Text)</label>
                        <div wire:ignore class="rounded-3xl border border-white/5 overflow-hidden focus-within:border-primary/50 transition-colors shadow-2xl shadow-black/20">
                            <textarea wire:model="content" class="editor w-full px-5 py-4 bg-white/[0.02] text-text-main focus:outline-none focus:bg-white/[0.04] transition-all min-h-[400px] leading-relaxed">{{ $content }}</textarea>
                        </div>
                        @error('content') <p class="text-xs text-danger mt-1 ml-2 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>
            </x-admin.form-section>

            <!-- Media Gallery -->
            <x-admin.form-section title="Hình ảnh & Truyền thông" icon="ti-photo-star" color="purple">
                <div class="space-y-8">
                    <x-backend.forms.media-uploader 
                        wire:model="featured_image"
                        :model="$featured_image"
                        :currentImages="$currentFeaturedMedia"
                        label="Ảnh đại diện chính"
                        hint="Thumbnail đẹp nhất 1:1, dung lượng dưới 2MB"
                        :deleteAction="'deleteMedia'"
                        :removeTempAction="'$set(\'featured_image\', null)'"
                    />

                    <div class="h-px bg-white/5 shadow-inner"></div>

                    <x-backend.forms.media-uploader 
                        wire:model="gallery"
                        :model="$gallery"
                        :currentImages="$currentGalleryMedia"
                        label="Album thư viện chi tiết"
                        multiple="true"
                        hint="Tải lên nhiều ảnh (Tối đa 10 ảnh), dung lượng dưới 2MB mỗi file"
                        :deleteAction="'deleteMedia'"
                        :removeTempAction="'removeGalleryImage'"
                    />
                </div>
            </x-admin.form-section>

            <!-- SEO Intelligence -->
            <x-admin.seo-manager />
        </div>

        <!-- Sidebar Column (4cols) -->
        <div class="xl:col-span-4 space-y-8 xl:sticky xl:top-24">
            
            <!-- PRICING & INVENTORY -->
            <x-admin.form-section title="Giá & Kho hàng" icon="ti-currency-dollar" color="orange" padding="p-6">
                <div class="space-y-6">
                    <div class="grid grid-cols-1 gap-6">
                        <div class="relative group">
                            <x-backend.forms.input 
                                wire:model="price" 
                                name="price" 
                                label="Giá niêm yết (₫)" 
                                type="text" 
                                inputmode="numeric" 
                                required
                                class="text-xl font-black text-emerald-500 bg-emerald-500/[0.03] border-emerald-500/20 focus:border-emerald-500 py-4 shadow-inner"
                                x-on:input="$event.target.value = (($event.target.value || '').replace(/[^0-9]/g, '')).replace(/\B(?=(\d{3})+(?!\d))/g, '.')" 
                            />
                            <div class="absolute right-4 top-10 flex items-center justify-center p-1.5 rounded-lg bg-emerald-500 text-white shadow-lg pointer-events-none opacity-20 group-focus-within:opacity-100 transition-opacity">
                                <i class="ti ti-coin text-sm"></i>
                            </div>
                        </div>

                        <div class="relative group">
                            <x-backend.forms.input 
                                wire:model="sale_price" 
                                name="sale_price" 
                                label="Giá khuyến mãi (₫)" 
                                type="text" 
                                inputmode="numeric"
                                class="text-xl font-black text-rose-500 bg-rose-500/[0.03] border-rose-500/20 focus:border-rose-500 py-4 shadow-inner"
                                x-on:input="$event.target.value = (($event.target.value || '').replace(/[^0-9]/g, '')).replace(/\B(?=(\d{3})+(?!\d))/g, '.')" 
                            />
                            <div class="absolute right-4 top-10 flex items-center justify-center p-1.5 rounded-lg bg-rose-500 text-white shadow-lg pointer-events-none opacity-20 group-focus-within:opacity-100 transition-opacity">
                                <i class="ti ti-tag text-sm"></i>
                            </div>
                        </div>
                    </div>

                    <div class="h-px bg-gradient-to-r from-transparent via-white/5 to-transparent my-2"></div>

                    <div class="grid grid-cols-2 gap-4">
                        <x-backend.forms.input wire:model="stock_quantity" name="stock_quantity" label="Tồn kho" type="number" min="0" />
                        <x-backend.forms.select wire:model="stock_status" label="Trạng thái" :options="[
                            'in_stock' => 'Còn hàng',
                            'out_of_stock' => 'Hết hàng',
                            'on_backorder' => 'Cho đặt trước',
                        ]" />
                    </div>
                </div>
            </x-admin.form-section>

            <!-- Categorization -->
            <x-admin.form-section title="Phân loại" icon="ti-category-2" color="amber">
                <div class="space-y-5">
                    <x-backend.forms.select wire:model="category_id" label="Danh mục sản phẩm" :options="$categories->pluck('name', 'id')->all()" placeholder="Chọn danh mục chính" />
                    <x-backend.forms.select wire:model="brand_id" label="Thương hiệu liên quan" :options="$brands->pluck('name', 'id')->all()" placeholder="Chọn brand/hãng" />
                </div>
            </x-admin.form-section>

            <!-- Visibility Settings -->
            <x-admin.form-section title="Cài đặt hiển thị" icon="ti-eyeglass" color="pink">
                <div class="grid grid-cols-2 gap-4">
                    <label class="group relative flex flex-col items-center justify-center pt-6 pb-4 px-4 rounded-3xl border border-white/5 bg-white/[0.02] cursor-pointer hover:bg-emerald-500/5 transition-all overflow-hidden shadow-inner">
                        <div class="absolute -top-1 -right-1 w-8 h-8 bg-emerald-500/20 blur-xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <input type="checkbox" wire:model="is_active" class="peer hidden">
                        <div class="w-10 h-10 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-text-muted transition-all peer-checked:bg-emerald-500 peer-checked:text-white peer-checked:shadow-lg peer-checked:shadow-emerald-500/20 mb-3">
                            <i class="ti ti-check text-xl transform scale-50 opacity-0 transition-all peer-checked:scale-100 peer-checked:opacity-100"></i>
                        </div>
                        <span class="text-[13px] font-black text-text-muted uppercase tracking-tighter peer-checked:text-emerald-500">Hiển thị</span>
                    </label>

                    <label class="group relative flex flex-col items-center justify-center pt-6 pb-4 px-4 rounded-3xl border border-white/5 bg-white/[0.02] cursor-pointer hover:bg-amber-500/5 transition-all overflow-hidden shadow-inner">
                        <div class="absolute -top-1 -right-1 w-8 h-8 bg-amber-500/20 blur-xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <input type="checkbox" wire:model="is_featured" class="peer hidden">
                        <div class="w-10 h-10 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-text-muted transition-all peer-checked:bg-amber-500 peer-checked:text-white peer-checked:shadow-lg peer-checked:shadow-amber-500/20 mb-3">
                            <i class="ti ti-star-filled text-xl transform scale-50 opacity-0 transition-all peer-checked:scale-100 peer-checked:opacity-100"></i>
                        </div>
                        <span class="text-[13px] font-black text-text-muted uppercase tracking-tighter peer-checked:text-amber-500">Nổi bật</span>
                    </label>
                </div>
            </x-admin.form-section>
        </div>

        <!-- Sticky Action Bar -->
        <x-admin.sticky-bar
            cancelHref="{{ route('backend.products.index') }}"
            target="save, featured_image, gallery"
        >
            <x-slot:info>
                <span class="text-[13px] font-bold text-text-main truncate max-w-[180px]" x-data="{ name: $wire.entangle('name') }" x-text="name || 'Sản phẩm mới...'"></span>
            </x-slot:info>
        </x-admin.sticky-bar>
    </form>
</div>
