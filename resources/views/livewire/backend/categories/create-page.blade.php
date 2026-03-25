<div class="space-y-6 pb-12">
    <!-- Header Page -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center border border-primary/20 shadow-inner">
                <i class="ti ti-category-plus text-2xl text-primary animate-pulse"></i>
            </div>
            <div>
                <h1 class="text-2xl font-black text-text-main tracking-tight">{{ $pageTitle }}</h1>
                <p class="text-sm text-text-muted mt-1 leading-relaxed">Tạo mới và thiết lập các thông số cho danh mục của bạn.</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
             <x-backend.ui.button variant="neutral" :href="route('backend.categories.index', $type)" icon="ti ti-arrow-left" class="bg-bg-surface hover:bg-bg-body">
                Quay lại
            </x-backend.ui.button>
        </div>
    </div>

    <!-- Main Content -->
    <form wire:submit="save" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Global Validation Errors -->
        @if ($errors->any())
            <div class="lg:col-span-12 p-4 mb-4 bg-danger/10 border border-danger/20 rounded-2xl animate-shake">
                <div class="flex items-center gap-3 text-danger">
                    <i class="ti ti-alert-triangle text-xl"></i>
                    <div>
                        <p class="font-bold text-sm">Có lỗi xảy ra khi tạo danh mục!</p>
                        <p class="text-xs opacity-80 mt-1">Vui lòng kiểm tra lại các thông tin của form dưới đây.</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Left Column: Form Details -->
        <div class="lg:col-span-8 space-y-8">
            <!-- Basic Information Card -->
            <x-backend.layout.card variant="glass" class="p-8 group/card glow-on-hover overflow-visible">
                <div class="flex items-center gap-3 mb-8 border-b border-border-glass pb-6">
                    <div class="w-8 h-8 rounded-lg bg-primary/20 flex items-center justify-center">
                        <i class="ti ti-info-square-rounded text-primary text-lg"></i>
                    </div>
                    <h3 class="text-lg font-black text-text-main uppercase tracking-wider">Thông tin chi tiết</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                         <x-backend.forms.input 
                            wire:model.blur="name" 
                            name="name" 
                            label="Tên danh mục" 
                            required 
                            placeholder="Tiêu đề bắt mắt thu hút người dùng..."
                            class="bg-bg-body/50 focus:bg-white"
                        />
                    </div>
                    <div class="md:col-span-1">
                        <x-backend.forms.input 
                            wire:model="slug" 
                            name="slug" 
                            label="URL tĩnh (Slug)" 
                            placeholder="tu-dong-tao-tu-ten..."
                            class="bg-bg-body/50"
                        />
                    </div>
                    <div class="md:col-span-1">
                        <x-backend.forms.input 
                            wire:model="order" 
                            name="order" 
                            label="Thứ tự hiển thị" 
                            type="number"
                            placeholder="0"
                            class="bg-bg-body/50"
                        />
                    </div>
                    <div class="md:col-span-2">
                        <x-backend.forms.textarea 
                            wire:model="description" 
                            name="description" 
                            label="Mô tả nội dung" 
                            rows="5" 
                            placeholder="Tóm tắt ngắn gọn nội dung của danh mục này giúp người dùng dễ dàng nắm bắt..."
                            class="bg-bg-body/50"
                        />
                    </div>
                </div>
            </x-backend.layout.card>

            <!-- SEO Configuration Card -->
            <x-backend.layout.card variant="glass" class="p-8 group/card glow-on-hover-secondary overflow-visible border-secondary/10">
                <div class="flex items-center gap-3 mb-8 border-b border-border-glass pb-6">
                    <div class="w-8 h-8 rounded-lg bg-secondary/20 flex items-center justify-center">
                        <i class="ti ti-brand-google text-secondary text-lg"></i>
                    </div>
                    <h3 class="text-lg font-black text-text-main uppercase tracking-wider">Tối ưu hóa tìm kiếm (SEO)</h3>
                </div>
                
                <div class="space-y-6">
                    <div>
                        <x-backend.forms.input 
                            wire:model.blur="meta_title" 
                            name="meta_title" 
                            label="Tiêu đề SEO (Meta Title)" 
                            placeholder="Tiêu đề hiển thị trên trình duyệt (Tối đa 60 ký tự)..."
                            class="bg-bg-body/50"
                        />

                        <div class="flex items-center justify-between -mt-3 mb-4 px-2">
                             <span class="text-[10px] text-text-muted italic opacity-60">Đề xuất: 50 - 60 ký tự</span>
                             <span class="text-[10px] font-mono {{ strlen($meta_title) > 60 ? 'text-danger' : 'text-success' }}">
                                {{ strlen($meta_title) }}/60
                             </span>
                        </div>
                    </div>

                    <div>
                        <x-backend.forms.textarea 
                            wire:model.blur="meta_description" 
                            name="meta_description" 
                            label="Mô tả SEO (Meta Description)" 
                            rows="3" 
                            placeholder="Mô tả ngắn gọn hiển thị trên kết quả tìm kiếm (Tối đa 160 ký tự)..."
                            class="bg-bg-body/50"
                        />

                        <div class="flex items-center justify-between -mt-3 mb-4 px-2">
                             <span class="text-[10px] text-text-muted italic opacity-60">Đề xuất: 150 - 160 ký tự</span>
                             <span class="text-[10px] font-mono {{ strlen($meta_description) > 160 ? 'text-danger' : 'text-success' }}">
                                {{ strlen($meta_description) }}/160
                             </span>
                        </div>
                    </div>

                    <x-backend.forms.input 
                        wire:model="meta_keywords" 
                        name="meta_keywords" 
                        label="Từ khóa SEO (Meta Keywords)" 
                        placeholder="Từ khóa 1, từ khóa 2, ..."
                        class="bg-bg-body/50"
                    />
                </div>
            </x-backend.layout.card>
        </div>

        <!-- Right Column: Settings and Action -->
        <div class="lg:col-span-4 space-y-8 lg:sticky lg:top-24 self-start">
            <!-- Settings Card -->
            <x-backend.layout.card variant="glass" class="p-8 group/card overflow-visible">
                 <div class="flex items-center gap-3 mb-8 border-b border-border-glass pb-6">
                    <div class="w-8 h-8 rounded-lg bg-accent/20 flex items-center justify-center">
                        <i class="ti ti-settings-automation text-accent text-lg"></i>
                    </div>
                    <h3 class="text-lg font-black text-text-main uppercase tracking-wider">Cài đặt</h3>
                </div>

                <div class="space-y-6">
                    <x-backend.forms.select 
                        wire:model="parent_id" 
                        name="parent_id" 
                        label="Danh mục cha" 
                        :options="$parentCategories->pluck('name', 'id')->all()" 
                        placeholder="-- Cấp độ cao nhất --"
                        class="bg-bg-body/50"
                    />

                    <div class="p-4 bg-bg-body/30 rounded-2xl border border-border-glass/50 space-y-4">
                        <label class="flex items-center justify-between cursor-pointer group">
                             <span class="text-sm font-bold text-text-main group-hover:text-primary transition-colors">Công khai hiển thị</span>
                             <div class="relative">
                                <input type="checkbox" wire:model="is_active" class="sr-only peer">
                                <div class="w-11 h-6 bg-border-glass peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-border-glass after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary border-none shadow-inner"></div>
                             </div>
                        </label>
                        <div class="h-px bg-border-glass w-full"></div>
                        <label class="flex items-center justify-between cursor-pointer group">
                             <span class="text-sm font-bold text-text-main group-hover:text-primary transition-colors">Hiển thị trên menu</span>
                             <div class="relative">
                                <input type="checkbox" wire:model="show_in_menu" class="sr-only peer">
                                <div class="w-11 h-6 bg-border-glass peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-border-glass after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-info border-none shadow-inner"></div>
                             </div>
                        </label>
                    </div>
                </div>
            </x-backend.layout.card>

            <!-- Media Card -->
            <x-backend.layout.card variant="glass" class="p-8 group/card overflow-visible">
                 <div class="flex items-center gap-3 mb-8 border-b border-border-glass pb-6">
                    <div class="w-8 h-8 rounded-lg bg-info/20 flex items-center justify-center">
                        <i class="ti ti-photo-circle text-info text-lg"></i>
                    </div>
                    <h3 class="text-lg font-black text-text-main uppercase tracking-wider">Hình ảnh đại diện</h3>
                </div>

                <div class="space-y-4">
                    <div class="relative group/upload cursor-pointer group-active:scale-95 transition-all">
                        <div class="w-full aspect-[4/3] rounded-2xl border-2 border-dashed border-border-glass bg-bg-surface/50 flex flex-col items-center justify-center gap-4 group-hover/upload:border-primary/50 group-hover/upload:bg-primary/5 transition-all overflow-hidden relative">
                            @if ($image)
                                <div class="absolute inset-0 bg-primary/20 backdrop-blur-sm opacity-0 group-hover/upload:opacity-100 transition-opacity flex items-center justify-center z-10">
                                    <div class="w-10 h-10 rounded-full bg-white text-primary flex items-center justify-center shadow-lg">
                        <i class="ti ti-edit text-lg"></i>
                                    </div>
                                </div>
                                <img src="{{ $image->temporaryUrl() }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-16 h-16 rounded-2xl bg-bg-body flex items-center justify-center border border-border-glass group-hover/upload:scale-110 transition-transform shadow-sm">
                                    <i class="ti ti-photo-plus text-3xl text-text-muted/30 group-hover/upload:text-primary group-hover/upload:animate-bounce transition-colors"></i>
                                </div>
                                <div class="text-center px-4">
                                    <span class="block text-[11px] font-black text-text-muted uppercase tracking-widest">Kéo thả hoặc nhấp để tải</span>
                                    <span class="block text-[10px] text-text-muted mt-1 opacity-60 italic">Đề xuất: 800x600px, dung lượng < 2MB</span>
                                </div>
                            @endif
                        </div>
                        <input type="file" wire:model="image" class="absolute inset-0 opacity-0 cursor-pointer z-20">
                    </div>
                    @error('image') <p class="text-xs text-danger mt-1 font-bold">{{ $message }}</p> @enderror
                </div>
            </x-backend.layout.card>

            <div class="flex flex-col gap-4">
                <x-backend.ui.button 
                    variant="primary" 
                    size="lg" 
                    htmlType="submit"
                    wire:loading.attr="disabled"
                    class="w-full py-4 shadow-xl shadow-primary/30 active:scale-95 transition-all"
                >
                    <i class="ti ti-device-floppy text-xl mr-2" wire:loading.remove></i>
                    <i class="ti ti-loader-2 animate-spin text-xl mr-2" wire:loading></i>
                    <span wire:loading.remove class="uppercase tracking-widest font-black">Lưu danh mục</span>
                    <span wire:loading class="uppercase tracking-widest font-black">Đang lưu...</span>
                </x-backend.ui.button>
                <x-backend.ui.button 
                    variant="neutral" 
                    :href="route('backend.categories.index', $type)" 
                    class="w-full py-3 opacity-80 hover:opacity-100 transition-opacity"
                >
                    Hủy bỏ thay đổi
                </x-backend.ui.button>
            </div>
        </div>
    </form>
</div>


