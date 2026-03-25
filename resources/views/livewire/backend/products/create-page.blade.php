<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-text-main tracking-tight uppercase tracking-[0.1em]">{{ $pageTitle }}</h1>
            <p class="text-[11px] font-black text-text-muted mt-1 uppercase tracking-widest opacity-60">Thêm sản phẩm mới vào hệ thống quản trị.</p>
        </div>
        <x-backend.ui.button variant="neutral" :href="route('backend.products.index')" icon="ti ti-arrow-left" class="rounded-2xl font-black text-[10px] uppercase tracking-widest">
            Quay lại
        </x-backend.ui.button>
    </div>

    <form wire:submit="save" class="grid grid-cols-1 xl:grid-cols-12 gap-8 items-start">
        <!-- Main Bento Content Area -->
        <div class="xl:col-span-8 space-y-8 h-full">
            
            <!-- SECTION 1: CƠ BẢN -->
            <x-backend.layout.card title="Thông tin cơ bản" class="shadow-2xl shadow-black/20 border-white/[0.03]">
                <div class="space-y-6">
                    <x-backend.forms.input wire:model.blur="name" name="name" label="Tên sản phẩm" required placeholder="Nhập tên sản phẩm..." class="text-lg font-bold" />
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-backend.forms.input wire:model="slug" name="slug" label="Slug" placeholder="tu-dong-tao-tu-ten..." hint="Dùng cho đường dẫn URL" />
                        <x-backend.forms.input wire:model="sku" name="sku" label="Mã SKU (Bắt buộc)" required placeholder="PROD-001..." />
                    </div>

                    <x-backend.forms.textarea wire:model="short_description" name="short_description" label="Mô tả ngắn" rows="3" placeholder="Tóm tắt ngắn gọn về sản phẩm (hiển thị ở trang danh sách)..." />
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-text-main tracking-tight">Nội dung chi tiết</label>
                        <div wire:ignore class="rounded-3xl overflow-hidden border border-white/5 shadow-inner">
                            <textarea wire:model="content" id="editor" class="editor w-full px-5 py-4 bg-white/[0.02] text-text-main focus:bg-white/[0.05] transition-all duration-500 outline-none" rows="15"></textarea>
                        </div>
                        @error('content') <p class="text-[10px] font-black text-danger mt-1 uppercase tracking-widest">{{ $message }}</p> @enderror
                    </div>
                </div>
            </x-backend.layout.card>

            <!-- SECTION 2: BENTO GRID (PRICE & KHO) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- GIÁ BÁN -->
                <x-backend.layout.card title="Định giá sản phẩm" class="h-full border-primary/10 shadow-lg shadow-primary/5">
                    <div class="space-y-6">
                        <x-backend.forms.input wire:model="price" name="price" label="Giá gốc (VNĐ)" type="text" inputmode="numeric" required
                            x-on:input="$event.target.value = (($event.target.value || '').replace(/[^0-9]/g, '')).replace(/\B(?=(\d{3})+(?!\d))/g, '.')" 
                            class="text-xl font-bold text-primary" />
                        
                        <x-backend.forms.input wire:model="sale_price" name="sale_price" label="Giá khuyến mãi (VNĐ)" type="text" inputmode="numeric"
                            x-on:input="$event.target.value = (($event.target.value || '').replace(/[^0-9]/g, '')).replace(/\B(?=(\d{3})+(?!\d))/g, '.')" 
                            hint="Để trống nếu không giảm giá" />
                        
                        <div class="p-4 rounded-2xl bg-primary/5 border border-primary/10 flex items-center gap-3">
                            <i class="ti ti-info-circle text-primary text-xl"></i>
                            <p class="text-[10px] text-primary/70 font-bold uppercase tracking-wider leading-relaxed">
                                Giá khuyến mãi phải thấp hơn giá gốc để hiển thị nhãn "SALE".
                            </p>
                        </div>
                    </div>
                </x-backend.layout.card>

                <!-- KHO HÀNG -->
                <x-backend.layout.card title="Quản lý kho hàng" class="h-full">
                    <div class="space-y-6">
                        <x-backend.forms.input wire:model="stock_quantity" name="stock_quantity" label="Số lượng trong kho" type="number" min="0" class="text-xl font-bold" />
                        
                        <x-backend.forms.select wire:model="stock_status" label="Trạng thái tồn kho" :options="[
                            'in_stock' => '✅ Còn hàng (Sẵn sàng bán)',
                            'out_of_stock' => '❌ Hết hàng (Tạm ngưng nhận đơn)',
                            'on_backorder' => '⏳ Đặt trước (Cho phép mua khi hết)',
                        ]" class="font-bold" />

                        <div class="grid grid-cols-1 gap-4 opacity-50 italic">
                             <p class="text-[10px] font-black text-text-muted uppercase tracking-widest">Hệ thống sẽ tự động trừ kho khi có đơn hàng mới.</p>
                        </div>
                    </div>
                </x-backend.layout.card>
            </div>

            <!-- SECTION 3: SEO -->
            <x-backend.layout.card title="Cấu hình SEO (Search Engine Optimization)">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <x-backend.forms.input wire:model="meta_title" name="meta_title" label="SEO Title" placeholder="Tên sản phẩm | Tên cửa hàng..." hint="Tốt nhất dưới 60 ký tự" />
                        <x-backend.forms.input wire:model="meta_keywords" name="meta_keywords" label="SEO Keywords" placeholder="iphone 15, dien thoai, apple..." hint="Phân cách bằng dấu phẩy" />
                    </div>
                    <div class="space-y-2">
                         <label class="block text-sm font-semibold text-text-main tracking-tight">SEO Description</label>
                         <textarea wire:model="meta_description" class="w-full px-4 py-3 rounded-2xl border border-white/5 bg-white/5 text-text-main focus:ring-1 focus:ring-primary/50 transition-all outline-none" rows="5" placeholder="Mô tả hiển thị trên Google (Tốt nhất dưới 160 ký tự)..."></textarea>
                         <p class="text-[10px] font-black text-text-muted/40 uppercase tracking-widest text-right">0/160</p>
                    </div>
                </div>
            </x-backend.layout.card>
        </div>

        <!-- Sidebar Bento Area -->
        <div class="xl:col-span-4 space-y-8">
            
            <!-- MEDIA (THUMBNAIL) -->
            <x-backend.layout.card title="Ảnh đại diện" class="overflow-visible">
                <x-backend.forms.media-uploader 
                    wire:model="featured_image"
                    :model="$featured_image"
                    removeTempAction="removeFeaturedImage"
                    label=""
                    hint="Kích thước khuyên dùng: 800x800px (1:1)"
                />
            </x-backend.layout.card>

            <!-- MEDIA (GALLERY) -->
            <x-backend.layout.card title="Thư viện ảnh">
                <x-backend.forms.media-uploader 
                    wire:model="gallery"
                    :model="$gallery"
                    multiple
                    removeTempAction="removeGalleryImage"
                    label=""
                    hint="Chọn nhiều ảnh để tạo slideshow sản phẩm"
                />
            </x-backend.layout.card>

            <!-- PHÂN LOẠI -->
            <x-backend.layout.card title="Phân loại & Thương hiệu">
                <div class="space-y-6">
                    <x-backend.forms.select wire:model="category_id" label="Danh mục chính" :options="$categories->pluck('name', 'id')->all()" placeholder="-- Chọn danh mục --" required class="font-bold" />
                    <x-backend.forms.select wire:model="brand_id" label="Thương hiệu" :options="$brands->pluck('name', 'id')->all()" placeholder="-- Chọn thương hiệu --" class="font-bold" />
                </div>
            </x-backend.layout.card>

            <!-- TRẠNG THÁI -->
            <x-backend.layout.card title="Trạng thái hiển thị">
                <div class="space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <label class="flex flex-col items-center justify-center gap-3 p-5 rounded-3xl border border-white/5 bg-white/5 hover:bg-white/[0.08] hover:border-primary/20 transition-all cursor-pointer group shadow-inner">
                            <input type="checkbox" wire:model="is_active" class="w-6 h-6 rounded-lg border-white/10 text-primary focus:ring-primary/30 bg-white/5 transition-all">
                            <span class="text-[10px] font-black uppercase tracking-[0.2em] text-text-muted group-hover:text-primary transition-colors">Hiển thị</span>
                        </label>
                        <label class="flex flex-col items-center justify-center gap-3 p-5 rounded-3xl border border-white/5 bg-white/5 hover:bg-white/[0.08] hover:border-primary/20 transition-all cursor-pointer group shadow-inner">
                            <input type="checkbox" wire:model="is_featured" class="w-6 h-6 rounded-lg border-white/10 text-primary focus:ring-primary/30 bg-white/5 transition-all">
                            <span class="text-[10px] font-black uppercase tracking-[0.2em] text-text-muted group-hover:text-warning transition-colors">Nổi bật</span>
                        </label>
                    </div>
                    
                    <x-backend.forms.input wire:model="order" name="order" label="Thứ tự ưu tiên" type="number" hint="Số nhỏ hơn sẽ hiển thị trước" />
                </div>
            </x-backend.layout.card>

            <!-- PUBLISH ACTION -->
            <div class="xl:sticky xl:bottom-8 z-20">
                <div class="relative group">
                    <div class="absolute -inset-[2px] bg-gradient-to-r from-primary via-blue-500 to-primary rounded-[2.5rem] opacity-30 group-hover:opacity-100 blur-md transition-opacity duration-1000 animate-pulse pointer-events-none"></div>
                    <button type="submit" 
                        wire:loading.attr="disabled" 
                        wire:target="save, featured_image, gallery" 
                        class="relative w-full px-8 py-6 flex items-center justify-center gap-3 rounded-[2.5rem] bg-bg-surface border border-white/10 hover:bg-white/5 text-text-main font-black text-xl uppercase tracking-[0.1em] transition-all duration-500 active:scale-[0.98]">
                        
                        <span wire:loading.remove wire:target="save">
                            <i class="ti ti-rocket text-2xl text-primary animate-bounce-slow"></i> XÁC NHẬN PHÁT HÀNH
                        </span>
                        
                        <span wire:loading wire:target="save" class="flex items-center gap-3 text-primary">
                            <i class="ti ti-loader-2 animate-spin text-2xl"></i> ĐANG KHỞI TẠO...
                        </span>
                        
                        <span wire:loading wire:target="featured_image, gallery" class="flex items-center gap-3 text-warning">
                            <i class="ti ti-cloud-upload animate-bounce text-2xl"></i> ĐANG TẢI MEDIA...
                        </span>
                    </button>
                </div>
                
                <p class="text-center mt-4 text-[9px] font-black text-text-muted/40 uppercase tracking-[0.3em] font-mono italic">
                    All changes are verified by security agent v2.5
                </p>
            </div>
        </div>
    </form>
</div>

