<div>
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-text-main flex items-center gap-3">
                <span class="bg-gradient-to-br from-primary to-accent w-12 h-12 rounded-xl flex items-center justify-center text-white shadow-glow-primary rotate-3">
                    <x-mary-icon name="o-shopping-bag" class="w-7 h-7" />
                </span>
                <div>
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-primary to-accent">Sửa Sản Phẩm</span>
                    <div class="text-sm font-medium text-text-muted mt-1">ID: #{{ $productId }} • Cập nhật thông tin chi tiết</div>
                </div>
            </h1>
        </div>
        <div class="flex items-center gap-3">
            <x-mary-button label="Huỷ & Quay lại" icon="o-arrow-left" link="{{ route('backend.products.index') }}" class="btn-ghost" />
            <x-mary-button label="Lưu Thay Đổi" icon="o-check" class="btn-primary shadow-glow-primary px-8" wire:click="save" spinner="save" />
        </div>
    </div>

    <x-mary-form wire:submit="save">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Cột chính (8/12) -->
            <div class="lg:col-span-8 space-y-8">
                <!-- Thông tin cốt lõi -->
                <div class="glass-card p-8 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                        <x-mary-icon name="o-document-text" class="w-32 h-32" />
                    </div>
                    
                    <h2 class="text-xl font-bold mb-8 flex items-center gap-3">
                        <span class="w-10 h-10 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                            <x-mary-icon name="o-identification" class="w-6 h-6" />
                        </span>
                        Thông tin cốt lõi
                    </h2>
                    
                    <div class="space-y-6 relative z-10">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <div class="md:col-span-3">
                                <x-mary-input label="Tên sản phẩm" wire:model.blur="form.name" placeholder="Ví dụ: iPhone 15 Pro Max" class="input-lg font-bold" required />
                            </div>
                            <x-mary-input label="Mã SKU" wire:model="form.sku" placeholder="SKU-..." required />
                        </div>
                        
                        <x-mary-input label="Slug thương mại" wire:model="form.slug" prefix="shop.com/p/" placeholder="tu-dong-tao" hint="Đường dẫn tĩnh chuẩn SEO" />

                        <x-mary-textarea label="Mô tả tóm tắt" wire:model="form.short_description" rows="2" placeholder="Hiển thị ở trang danh sách..." />
                        
                        <div wire:ignore class="space-y-2">
                            <label class="text-sm font-bold text-text-muted flex items-center gap-2">
                                <x-mary-icon name="o-pencil-square" class="w-4 h-4" />
                                Nội dung chi tiết
                            </label>
                            <div class="rounded-xl overflow-hidden border border-border-glass focus-within:ring-2 ring-primary/20 transition-all">
                                <textarea id="content" class="editor w-full" wire:model="form.content" rows="10"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Định giá & Kho hàng -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Định giá -->
                    <div class="glass-card p-8 border-l-4 border-l-success">
                        <h2 class="text-xl font-bold mb-8 flex items-center gap-3 text-success">
                            <x-mary-icon name="o-currency-dollar" class="w-6 h-6" />
                            Giá bán (VNĐ)
                        </h2>
                        
                        <div class="space-y-6">
                            <x-mary-input 
                                label="Giá niêm yết *" 
                                wire:model.blur="form.price" 
                                prefix="đ" 
                                class="text-2xl font-black text-success"
                                x-on:input="$el.value = String($el.value).replace(/[^0-9]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')"
                            />
                            <x-mary-input 
                                label="Giá ưu đãi" 
                                wire:model.blur="form.sale_price" 
                                prefix="đ" 
                                hint="Bỏ trống nếu giữ giá gốc" 
                                class="text-xl font-bold"
                                x-on:input="$el.value = String($el.value).replace(/[^0-9]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')"
                            />
                        </div>
                    </div>

                    <!-- Kho hàng (Nếu không có biến thể) -->
                    @if(!$has_variants)
                    <div class="glass-card p-8 border-l-4 border-l-info">
                        <h2 class="text-xl font-bold mb-8 flex items-center gap-3 text-info">
                            <x-mary-icon name="o-cube" class="w-6 h-6" />
                            Quản lý kho
                        </h2>
                        
                        <div class="space-y-6">
                            <x-mary-input label="Số lượng sẵn có" wire:model="form.stock_quantity" type="number" min="0" class="text-xl font-bold" required />
                            <x-mary-select label="Trạng thái hàng" wire:model="form.stock_status" :options="collect([
                                ['id' => 'in_stock', 'name' => '📦 Còn hàng'],
                                ['id' => 'out_of_stock', 'name' => '❌ Hết hàng'],
                                ['id' => 'on_backorder', 'name' => '⏳ Cho phép đặt trước'],
                            ])" />
                        </div>
                    </div>
                    @endif
                </div>

                <!-- BIẾN THỂ SẢN PHẨM (Variants Matrix) -->
                <div class="glass-card p-8 relative overflow-hidden group border-t-4 border-t-primary">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-xl font-bold flex items-center gap-3">
                            <span class="w-10 h-10 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                                <x-mary-icon name="o-swatch" class="w-6 h-6" />
                            </span>
                            Biến thể sản phẩm
                        </h2>
                        <x-mary-toggle wire:model.live="has_variants" label="Sử dụng biến thể?" class="toggle-primary" />
                    </div>

                    @if($has_variants)
                        <div class="space-y-8 animate-in fade-in slide-in-from-top-4 duration-500">
                            <!-- Attribute Selection -->
                            <div class="bg-base-200/50 rounded-3xl p-6 border border-border-glass space-y-6">
                                <p class="text-sm font-medium text-text-muted italic">Chọn lại thuộc tính sẽ làm thay đổi ma trận phiên bản bên dưới:</p>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    @foreach($this->allAttributes as $attribute)
                                        <div class="space-y-3">
                                            <label class="block text-sm font-black uppercase tracking-widest text-primary">{{ $attribute->name }}</label>
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($attribute->values as $value)
                                                    <label class="cursor-pointer group">
                                                        <input type="checkbox" wire:model.live="selected_attributes.{{ $attribute->id }}.{{ $value->id }}" value="{{ $value->id }}" class="hidden peer">
                                                        <span class="px-4 py-2 rounded-full border border-border-glass bg-white/5 text-sm font-bold transition-all 
                                                            peer-checked:bg-primary peer-checked:text-white peer-checked:border-primary peer-checked:shadow-glow-primary
                                                            group-hover:border-primary/50">
                                                            {{ $value->value }}
                                                        </span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Variants Tables -->
                            @if(count($variants) > 0)
                                <div class="overflow-hidden rounded-3xl border border-border-glass bg-base-100/30">
                                    <table class="table w-full">
                                        <thead class="bg-base-200/50 text-xs uppercase font-bold tracking-widest">
                                            <tr>
                                                <th class="py-4">Phiên bản</th>
                                                <th>Mã SKU</th>
                                                <th>Giá chênh lệch</th>
                                                <th>Tồn kho</th>
                                                <th class="w-20">Bật</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($variants as $index => $variant)
                                                <tr wire:key="ev-{{ $variant['key'] }}" class="hover:bg-white/5 border-b border-white/5 transition-colors">
                                                    <td class="font-bold text-primary">{{ $variant['name'] }}</td>
                                                    <td>
                                                        <x-mary-input wire:model="variants.{{ $index }}.sku" class="input-sm rounded-lg" />
                                                    </td>
                                                    <td>
                                                        <x-mary-input wire:model="variants.{{ $index }}.price" prefix="đ" class="input-sm rounded-lg font-bold" />
                                                    </td>
                                                    <td>
                                                        <x-mary-input wire:model="variants.{{ $index }}.stock" type="number" class="input-sm rounded-lg" />
                                                    </td>
                                                    <td>
                                                        <x-mary-toggle wire:model="variants.{{ $index }}.is_active" class="toggle-xs toggle-success" />
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="py-12 text-center bg-base-200/30 rounded-3xl border-2 border-dashed border-border-glass">
                                    <p class="text-text-muted font-medium italic">Vui lòng chọn thuộc tính để tạo biến thể.</p>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="py-10 text-center text-text-muted opacity-50 italic">
                            Chế độ sản phẩm đơn nhất đang bật.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Cột bên phải/Floating Window (4/12) -->
            <div class="lg:col-span-4 space-y-8 sticky top-24">
                <!-- Phân loại & Hiển thị -->
                <div class="glass-card p-6 shadow-xl space-y-8 border-t-4 border-t-accent">
                    <div>
                        <h2 class="text-lg font-black mb-6 uppercase tracking-widest text-text-muted flex items-center gap-2">
                            <x-mary-icon name="o-adjustments-horizontal" class="w-5 h-5" />
                            Cấu hình hiển thị
                        </h2>
                        
                        <div class="bg-base-200/50 rounded-2xl p-4 space-y-4 border border-border-glass">
                            <x-mary-toggle label="Mở bán công khai" wire:model="form.is_active" class="toggle-success" />
                            <div class="h-px bg-border-glass !my-2"></div>
                            <x-mary-toggle label="Sản phẩm nổi bật (Vip)" wire:model="form.is_featured" class="toggle-warning" />
                        </div>
                    </div>

                    <div class="space-y-6">
                        <x-mary-select label="Danh mục chính" icon="o-folder" wire:model="form.category_id" :options="$this->categories" placeholder="-- Phân loại --" required />
                        <x-mary-select label="Nhãn hàng/Brand" icon="o-sparkles" wire:model="form.brand_id" :options="$this->brands" placeholder="-- Thương hiệu --" />
                        <x-mary-input label="Thứ tự ưu tiên" wire:model="form.order" type="number" hint="Số nhỏ sẽ lên đầu trang" />
                    </div>
                </div>

                <!-- Media Center -->
                <div class="glass-card p-6 shadow-2xl space-y-6 overflow-hidden">
                    <h2 class="text-lg font-black uppercase tracking-widest text-text-muted flex items-center gap-2">
                        <x-mary-icon name="o-photo" class="w-5 h-5 text-error" />
                        Media Center
                    </h2>
                    
                    <div class="space-y-8">
                        <x-backend.forms.media-uploader 
                            wire:model="form.featured_image" 
                            label="Ảnh đại diện" 
                            :multiple="false"
                            :currentImages="$product->getMedia('featured_image')"
                            deleteAction="deleteMedia"
                            :model="$form->featured_image"
                            removeTempAction="form.removeFeaturedImage"
                            gridClass="grid-cols-1"
                        />

                        <div class="h-px bg-border-glass"></div>

                        <x-backend.forms.media-uploader 
                            wire:model="form.gallery" 
                            wire:sort="sortMedia"
                            label="Thư viện ảnh" 
                            :multiple="true"
                            :currentImages="$currentGalleryMedia"
                            deleteAction="deleteMedia"
                            :model="$form->gallery"
                            removeTempAction="form.removeGalleryImage"
                            gridClass="grid-cols-2 lg:grid-cols-3"
                            hint="Mẹo: Cầm chuột kéo thả để sắp xếp thứ tự ảnh."
                        />
                    </div>
                </div>

                <!-- Save Action Floating -->
                <div class="glass-card p-4 flex gap-3 shadow-[0_-10px_40px_rgba(0,0,0,0.1)]">
                    <x-mary-button label="Huỷ" class="btn-ghost flex-1" link="{{ route('backend.products.index') }}" />
                    <x-mary-button label="Lưu Ngay" icon="o-paper-airplane" class="btn-primary shadow-glow-primary flex-[2]" wire:click="save" spinner="save" />
                </div>
            </div>
        </div>
    </x-mary-form>
</div>
