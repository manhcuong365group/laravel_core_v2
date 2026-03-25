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
            
            <!-- Basic Information Card -->
            <x-backend.layout.card>
                <x-slot:title>
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-xl bg-blue-500/10 text-blue-500 border border-blue-500/10">
                            <i class="ti ti-info-circle text-xl"></i>
                        </div>
                        <span class="text-xl font-bold tracking-tight">Chi tiết sản phẩm</span>
                    </div>
                </x-slot:title>

                <div class="grid grid-cols-1 gap-6 pt-2">
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
            </x-backend.layout.card>

            <!-- Media Gallery Section -->
            <x-backend.layout.card>
                <x-slot:title>
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-xl bg-purple-500/10 text-purple-500 border border-purple-500/10">
                            <i class="ti ti-photo-star text-xl"></i>
                        </div>
                        <span class="text-xl font-bold tracking-tight">Hình ảnh & Truyền thông</span>
                    </div>
                </x-slot:title>

                <div class="space-y-8 pt-2">
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
            </x-backend.layout.card>

            <!-- SEO Intelligence Card -->
            <x-backend.layout.card>
                <x-slot:title>
                    <div class="flex items-center justify-between w-full">
                        <div class="flex items-center gap-3 text-xl font-bold tracking-tight">
                            <div class="p-2 rounded-xl bg-emerald-500/10 text-emerald-500 border border-emerald-500/10">
                                <i class="ti ti-search text-xl"></i>
                            </div>
                            <span>Tối ưu hóa tìm kiếm (SEO)</span>
                        </div>
                        <button type="button" 
                            x-on:click="
                                $wire.meta_title = $wire.name;
                                $wire.meta_description = ($wire.short_description || '').replace(/<[^>]*>?/gm, '').substring(0, 160);
                                $dispatch('toast', { message: 'Đã phân tích nội dung và gợi ý từ khóa SEO!', type: 'success' })
                            "
                            class="flex items-center gap-2 px-4 py-2 rounded-2xl bg-primary/10 text-primary text-[11px] font-black uppercase tracking-widest border border-primary/20 hover:bg-primary hover:text-white transition-all shadow-md group active:scale-95"
                        >
                            <i class="ti ti-wand text-base group-hover:rotate-12 transition-transform"></i>
                            Tự động đề xuất
                        </button>
                    </div>
                </x-slot:title>

                <!-- Premium SEO Preview -->
                 <div class="mb-8 p-6 rounded-[2rem] bg-indigo-950/20 border border-white/5 relative overflow-hidden group shadow-2xl" x-data="{ 
                     get seoTitle() { return $wire.meta_title || $wire.name || 'Tiêu đề sản phẩm trên kết quả tìm kiếm Google' },
                     get seoDesc() { return $wire.meta_description || 'Viết một mô tả thu hút khách hàng nhấn vào sản phẩm của bạn. Độ dài chuẩn là từ 150-160 ký tự...' }
                 }">
                    <div class="absolute -top-12 -right-12 w-48 h-48 bg-primary/10 blur-[80px] rounded-full"></div>
                    
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-2xl bg-white flex items-center justify-center text-[#202124] shadow-sm ring-4 ring-white/5">
                            <i class="ti ti-brand-google text-2xl"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs text-text-muted/80 leading-tight font-black uppercase tracking-widest">Google Engine Preview</span>
                        </div>
                    </div>

                    <div class="space-y-1.5 relative z-10">
                        <div class="text-[#8ab4f8] text-2xl font-bold leading-tight hover:underline cursor-pointer transition-colors line-clamp-1" x-text="seoTitle"></div>
                        <div class="text-[#34a853] text-[15px] flex items-center gap-1.5 font-medium">
                            <i class="ti ti-link text-sm opacity-50"></i>
                            <span class="opacity-60">{{ url('/products') }}</span>
                            <i class="ti ti-chevron-right text-[10px] opacity-40"></i>
                            <span class="font-black tracking-tight" x-text="$wire.slug || 'san-pham-target'"></span>
                        </div>
                        <div class="text-[#bdc1c6] text-[15px] leading-relaxed line-clamp-2 mt-2 font-medium opacity-80" x-text="seoDesc"></div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4">
                    <div class="space-y-6">
                        <x-backend.forms.input wire:model="meta_title" name="meta_title" label="Tiêu đề SEO (Meta Title)" placeholder="Tối đa 60 ký tự để hiển thị tốt nhất" />
                        <x-backend.forms.input wire:model="meta_keywords" name="meta_keywords" label="Từ khóa (Keywords)" placeholder="Nhập từ khóa, cách nhau bằng dấu phẩy" />
                    </div>
                    <div class="h-full">
                        <x-backend.forms.textarea wire:model="meta_description" name="meta_description" label="Đoạn trích (Meta Description)" rows="5" placeholder="Mô tả này sẽ hiển thị bên dưới tiêu đề trên Google..." class="leading-relaxed h-full" />
                    </div>
                </div>
            </x-backend.layout.card>
        </div>

        <!-- Sidebar Column (4cols) -->
        <div class="xl:col-span-4 space-y-8 xl:sticky xl:top-24">
            
            <!-- PRICING & INVENTORY - Unified Premium Card -->
            <x-backend.layout.card>
                <x-slot:title>
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-xl bg-orange-500/10 text-orange-500 border border-orange-500/10">
                            <i class="ti ti-currency-dollar text-xl"></i>
                        </div>
                        <span class="text-xl font-bold tracking-tight">Giá & Kho hàng</span>
                    </div>
                </x-slot:title>

                <div class="space-y-6 pt-4">
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
            </x-backend.layout.card>

            <!-- Categorization -->
            <x-backend.layout.card>
                <x-slot:title>
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-xl bg-amber-500/10 text-amber-500 border border-amber-500/10">
                            <i class="ti ti-category-2 text-xl"></i>
                        </div>
                        <span class="text-xl font-bold tracking-tight">Phân loại</span>
                    </div>
                </x-slot:title>
                
                <div class="space-y-5 pt-2">
                    <x-backend.forms.select wire:model="category_id" label="Danh mục sản phẩm" :options="$categories->pluck('name', 'id')->all()" placeholder="Chọn danh mục chính" />
                    <x-backend.forms.select wire:model="brand_id" label="Thương hiệu liên quan" :options="$brands->pluck('name', 'id')->all()" placeholder="Chọn brand/hãng" />
                </div>
            </x-backend.layout.card>

            <!-- Visibility Settings -->
            <x-backend.layout.card>
                <x-slot:title>
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-xl bg-pink-500/10 text-pink-500 border border-pink-500/10">
                            <i class="ti ti-eyeglass text-xl"></i>
                        </div>
                        <span class="text-xl font-bold tracking-tight">Cài đặt hiển thị</span>
                    </div>
                </x-slot:title>

                <div class="space-y-6 pt-2">
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

                </div>
            </x-backend.layout.card>
        </div>

        <!-- PREMIUM FLOATING ACTION BAR -->
        <div class="fixed bottom-8 left-1/2 -translate-x-1/2 z-[100] w-[calc(100%-2rem)] max-w-5xl animate-in slide-in-from-bottom-full duration-700 delay-300">
            <div class="relative group">
                <!-- Glow Effect -->
                <div class="absolute -inset-0.5 bg-gradient-to-r from-primary via-blue-500 to-indigo-500 rounded-full blur-xl opacity-20 group-hover:opacity-40 transition duration-1000 group-hover:duration-300"></div>
                
                <!-- Glass Bar Container -->
                <div class="relative flex items-center gap-4 px-4 py-4 md:px-8 md:py-5 bg-black/60 backdrop-blur-3xl rounded-full border border-white/10 shadow-[0_0_50px_rgba(0,0,0,1)]">
                    
                    <!-- Icon Detail (Desktop Only) -->
                    <div class="hidden md:flex items-center gap-4 pr-6 border-r border-white/10">
                        <div class="w-10 h-10 rounded-2xl bg-white/5 flex items-center justify-center border border-white/5 shadow-inner">
                            <i class="ti ti-edit text-primary text-xl"></i>
                        </div>
                        <div class="flex flex-col min-w-[120px]">
                            <span class="text-[10px] font-black uppercase tracking-widest text-text-muted mb-0.5 opacity-60 italic">Mode: Edit Product</span>
                            <span class="text-[13px] font-bold text-text-main truncate max-w-[180px]" x-data="{ name: $wire.entangle('name') }" x-text="name || 'Sản phẩm mới...'"></span>
                        </div>
                    </div>

                    <!-- Secondary Actions -->
                    <div class="flex items-center gap-2 flex-1 md:flex-none">
                        <x-backend.ui.button variant="neutral" :href="route('backend.products.index')" class="flex-1 md:flex-none h-12 rounded-full px-6 bg-white/5 border-white/10 hover:bg-white/10 hover:text-white transition-all active:scale-95">
                            <span class="font-black uppercase tracking-tighter text-[11px]">Hủy bỏ</span>
                        </x-backend.ui.button>
                    </div>

                    <!-- Primary Action -->
                    <div class="flex-1 md:w-auto relative group/save">
                        <button type="submit" 
                            wire:loading.attr="disabled" 
                            wire:target="save, featured_image, gallery" 
                            class="w-full h-12 md:px-10 flex items-center justify-center gap-3 rounded-full bg-gradient-to-r from-primary to-blue-600 text-white font-black text-sm uppercase tracking-widest shadow-2xl shadow-primary/20 hover:shadow-primary/40 active:scale-95 transition-all duration-300 relative overflow-hidden disabled:opacity-50 disabled:cursor-not-allowed">
                            
                            <!-- Shine Effect -->
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover/save:translate-x-full transition-transform duration-1000 ease-in-out"></div>

                            <span wire:loading.remove wire:target="save, featured_image, gallery" class="flex items-center gap-2.5">
                                <i class="ti ti-device-floppy text-xl"></i> Lưu thay đổi
                            </span>
                            
                            <span wire:loading wire:target="save" class="flex items-center gap-3">
                                <i class="ti ti-loader animate-spin text-xl"></i> <span>Đang kết nối...</span>
                            </span>
                            
                            <span wire:loading wire:target="featured_image, gallery" class="flex items-center gap-3">
                                <i class="ti ti-upload-cloud animate-pulse text-xl"></i> <span>Đang lưu media...</span>
                            </span>
                        </button>
                        <!-- Small Shortcut Hint (Desktop) -->
                        <div class="absolute -top-10 left-1/2 -translate-x-1/2 bg-black/80 px-2.5 py-1 rounded-lg text-[10px] font-bold text-white/40 border border-white/5 opacity-0 group-hover/save:opacity-100 transition-opacity duration-300 pointer-events-none whitespace-nowrap">
                            Shortcut: <span class="text-white">⌘ + S</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>


