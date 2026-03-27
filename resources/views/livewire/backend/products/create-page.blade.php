<div class="space-y-8 pb-32">
    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-text-main tracking-tight uppercase tracking-[0.1em]">{{ $pageTitle }}</h1>
            <p class="text-[11px] font-black text-text-muted mt-1 uppercase tracking-widest opacity-60">Thêm sản phẩm mới vào hệ thống quản trị.</p>
        </div>
        <x-backend.ui.button variant="neutral" :href="route('backend.products.index')" icon="ti ti-arrow-left" class="rounded-2xl font-black text-[10px] uppercase tracking-widest bg-white/5 border-white/10 hover:bg-white/10">
            Quay lại danh sách
        </x-backend.ui.button>
    </div>

    <form wire:submit="save" class="grid grid-cols-1 xl:grid-cols-12 gap-8 items-start">
        <!-- Main Form Column (8cols) -->
        <div class="xl:col-span-8 space-y-8">
            
            <!-- Basic Information -->
            <x-admin.form-section title="Thông tin cơ bản" icon="ti-info-circle" color="blue">
                <div class="grid grid-cols-1 gap-6">
                    <x-backend.forms.input wire:model.blur="name" name="name" label="Tên sản phẩm" required placeholder="Nhập tên sản phẩm..." class="text-lg font-bold" />
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-backend.forms.input wire:model="slug" name="slug" label="Slug" placeholder="tu-dong-tao-tu-ten..." hint="Dùng cho đường dẫn URL" />
                        <x-backend.forms.input wire:model="sku" name="sku" label="Mã SKU (Bắt buộc)" required placeholder="PROD-001..." />
                    </div>

                    <x-backend.forms.textarea wire:model="short_description" name="short_description" label="Mô tả ngắn" rows="3" placeholder="Tóm tắt ngắn gọn về sản phẩm..." />
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-text-main tracking-tight">Nội dung chi tiết</label>
                        <div wire:ignore class="rounded-3xl overflow-hidden border border-white/5 shadow-inner">
                            <textarea wire:model="content" id="editor" class="editor w-full px-5 py-4 bg-white/[0.02] text-text-main focus:bg-white/[0.05] transition-all duration-500 outline-none" rows="15"></textarea>
                        </div>
                        @error('content') <p class="text-[10px] font-black text-danger mt-1 uppercase tracking-widest">{{ $message }}</p> @enderror
                    </div>
                </div>
            </x-admin.form-section>

            <!-- Price & Inventory Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <x-admin.form-section title="Định giá" icon="ti-currency-dollar" color="orange">
                    <div class="space-y-6">
                        <x-backend.forms.input wire:model="price" name="price" label="Giá gốc (VNĐ)" type="text" inputmode="numeric" required
                            x-on:input="$event.target.value = (($event.target.value || '').replace(/[^0-9]/g, '')).replace(/\B(?=(\d{3})+(?!\d))/g, '.')" 
                            class="text-xl font-bold text-emerald-500" />
                        
                        <x-backend.forms.input wire:model="sale_price" name="sale_price" label="Giá khuyến mãi (VNĐ)" type="text" inputmode="numeric"
                            x-on:input="$event.target.value = (($event.target.value || '').replace(/[^0-9]/g, '')).replace(/\B(?=(\d{3})+(?!\d))/g, '.')" 
                            hint="Để trống nếu không giảm giá" />
                    </div>
                </x-admin.form-section>

                <x-admin.form-section title="Kho hàng" icon="ti-box" color="blue">
                    <div class="space-y-6">
                        <x-backend.forms.input wire:model="stock_quantity" name="stock_quantity" label="Số lượng tồn" type="number" min="0" class="text-xl font-bold" />
                        <x-backend.forms.select wire:model="stock_status" label="Trạng thái" :options="[
                            'in_stock' => 'Còn hàng',
                            'out_of_stock' => 'Hết hàng',
                            'on_backorder' => 'Đặt trước',
                        ]" />
                    </div>
                </x-admin.form-section>
            </div>

            <!-- SEO Intelligence -->
            <x-admin.seo-manager />
        </div>

        <!-- Sidebar Column (4cols) -->
        <div class="xl:col-span-4 space-y-8">
            
            <!-- Media: Thumbnail -->
            <x-admin.form-section title="Ảnh đại diện" icon="ti-photo" color="purple">
                <x-backend.forms.media-uploader 
                    wire:model="featured_image"
                    :model="$featured_image"
                    removeTempAction="removeFeaturedImage"
                    label=""
                    hint="Kích thước: 800x800px (1:1)"
                />
            </x-admin.form-section>

            <!-- Media: Gallery -->
            <x-admin.form-section title="Thư viện ảnh" icon="ti-photo-plus" color="purple">
                <x-backend.forms.media-uploader 
                    wire:model="gallery"
                    :model="$gallery"
                    multiple
                    removeTempAction="removeGalleryImage"
                    label=""
                    hint="Tải lên nhiều ảnh sản phẩm"
                />
            </x-admin.form-section>

            <!-- Categorization -->
            <x-admin.form-section title="Phân loại" icon="ti-category-2" color="amber">
                <div class="space-y-6">
                    <x-backend.forms.select wire:model="category_id" label="Danh mục" :options="$categories->pluck('name', 'id')->all()" placeholder="-- Chọn danh mục --" required />
                    <x-backend.forms.select wire:model="brand_id" label="Thương hiệu" :options="$brands->pluck('name', 'id')->all()" placeholder="-- Chọn thương hiệu --" />
                </div>
            </x-admin.form-section>

            <!-- Visibility -->
            <x-admin.form-section title="Trạng thái" icon="ti-eyeglass" color="pink">
                <div class="space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <label class="group relative flex flex-col items-center justify-center pt-6 pb-4 px-4 rounded-3xl border border-white/5 bg-white/[0.02] cursor-pointer hover:bg-emerald-500/5 transition-all overflow-hidden shadow-inner">
                            <input type="checkbox" wire:model="is_active" class="peer hidden">
                            <div class="w-10 h-10 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-text-muted transition-all peer-checked:bg-emerald-500 peer-checked:text-white peer-checked:shadow-lg peer-checked:shadow-emerald-500/20 mb-3">
                                <i class="ti ti-check text-xl transform scale-50 opacity-0 transition-all peer-checked:scale-100 peer-checked:opacity-100"></i>
                            </div>
                            <span class="text-[13px] font-black text-text-muted uppercase tracking-tighter peer-checked:text-emerald-500">Hiển thị</span>
                        </label>

                        <label class="group relative flex flex-col items-center justify-center pt-6 pb-4 px-4 rounded-3xl border border-white/5 bg-white/[0.02] cursor-pointer hover:bg-amber-500/5 transition-all overflow-hidden shadow-inner">
                            <input type="checkbox" wire:model="is_featured" class="peer hidden">
                            <div class="w-10 h-10 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-text-muted transition-all peer-checked:bg-amber-500 peer-checked:text-white peer-checked:shadow-lg peer-checked:shadow-amber-500/20 mb-3">
                                <i class="ti ti-star-filled text-xl transform scale-50 opacity-0 transition-all peer-checked:scale-100 peer-checked:opacity-100"></i>
                            </div>
                            <span class="text-[13px] font-black text-text-muted uppercase tracking-tighter peer-checked:text-amber-500">Nổi bật</span>
                        </label>
                    </div>
                    <x-backend.forms.input wire:model="order" name="order" label="Thứ tự ưu tiên" type="number" />
                </div>
            </x-admin.form-section>
        </div>

        <!-- Sticky Action Bar -->
        <x-admin.sticky-bar
            cancelHref="{{ route('backend.products.index') }}"
            target="save, featured_image, gallery"
            saveLabel="Tạo sản phẩm ngay"
            mode="Create"
        >
            <x-slot:info>
                <span class="text-[13px] font-bold text-text-main truncate max-w-[180px]" x-data="{ name: $wire.entangle('name') }" x-text="name || 'Sản phẩm mới...'"></span>
            </x-slot:info>
        </x-admin.sticky-bar>
    </form>
</div>
