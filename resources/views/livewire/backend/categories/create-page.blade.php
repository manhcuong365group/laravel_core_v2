<div class="space-y-8 pb-32">
    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center border border-primary/20 text-primary shadow-inner">
                 <i class="ti {{ $pageIcon ?? 'ti-category' }} text-2xl"></i>
            </div>
            <div>
                <h1 class="text-3xl font-black text-text-main tracking-tight uppercase tracking-[0.1em]">{{ $pageTitle }}</h1>
                <p class="text-[11px] font-black text-text-muted mt-1 uppercase tracking-widest opacity-60">Thêm mới danh mục vào hệ thống.</p>
            </div>
        </div>
        <x-backend.ui.button variant="neutral" :href="route('backend.categories.index', $type)" icon="ti ti-arrow-left" class="rounded-2xl font-black text-[10px] uppercase tracking-widest bg-white/5 border-white/10 hover:bg-white/10">
            Quay lại danh sách
        </x-backend.ui.button>
    </div>

    <form wire:submit="save" class="grid grid-cols-1 xl:grid-cols-12 gap-8 items-start">
        <!-- Main Form Column (8cols) -->
        <div class="xl:col-span-8 space-y-8">
            
            <!-- Basic Information -->
            <x-admin.form-section title="Thông tin chi tiết" icon="ti-info-circle" color="blue">
                <div class="grid grid-cols-1 gap-6">
                    <x-backend.forms.input wire:model.blur="name" name="name" label="Tên danh mục" required placeholder="Nhập tên danh mục..." class="text-lg font-bold" />
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-backend.forms.input wire:model="slug" name="slug" label="URL tĩnh (Slug)" placeholder="tu-dong-tao-tu-ten..." />
                        <x-backend.forms.input wire:model="order" name="order" label="Thứ tự hiển thị" type="number" placeholder="0" />
                    </div>

                    <x-backend.forms.textarea wire:model="description" name="description" label="Mô tả nội dung" rows="5" placeholder="Mô tả tóm tắt nội dung danh mục..." />
                </div>
            </x-admin.form-section>

            <!-- SEO Intelligence -->
            <x-admin.seo-manager nameModel="name" descModel="description" />
        </div>

        <!-- Sidebar Column (4cols) -->
        <div class="xl:col-span-4 space-y-8">
            
            <!-- Categorization -->
            <x-admin.form-section title="Cấu trúc" icon="ti-hierarchy" color="amber">
                <x-backend.forms.select 
                    wire:model="parent_id" 
                    name="parent_id" 
                    label="Danh mục cha" 
                    :options="$parentCategories->pluck('name', 'id')->all()" 
                    placeholder="-- Cấp độ cao nhất --"
                />
            </x-admin.form-section>

            <!-- Visibility Settings -->
            <x-admin.form-section title="Trạng thái hiển thị" icon="ti-eyeglass" color="pink">
                <div class="space-y-4">
                    <label class="flex items-center justify-between cursor-pointer p-4 bg-white/5 rounded-2xl border border-white/5 hover:bg-white/10 transition-colors">
                        <span class="text-sm font-bold text-text-main">Công khai hiển thị</span>
                        <div class="relative">
                            <input type="checkbox" wire:model="is_active" class="sr-only peer">
                            <div class="w-11 h-6 bg-white/10 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-white/10 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500 transition-colors"></div>
                        </div>
                    </label>
                    
                    <label class="flex items-center justify-between cursor-pointer p-4 bg-white/5 rounded-2xl border border-white/5 hover:bg-white/10 transition-colors">
                        <span class="text-sm font-bold text-text-main">Hiển thị trên menu</span>
                        <div class="relative">
                            <input type="checkbox" wire:model="show_in_menu" class="sr-only peer">
                            <div class="w-11 h-6 bg-white/10 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-white/10 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-500 transition-colors"></div>
                        </div>
                    </label>
                </div>
            </x-admin.form-section>

            <!-- Media Section -->
            <x-admin.form-section title="Hình ảnh đại diện" icon="ti-photo" color="purple">
                <div class="space-y-4">
                    <x-backend.forms.media-uploader 
                        wire:model="image"
                        :model="$image"
                        label=""
                        hint="Đề xuất: 800x600px"
                    />
                </div>
            </x-admin.form-section>
        </div>

        <!-- Sticky Action Bar -->
        <x-admin.sticky-bar
            cancelHref="{{ route('backend.categories.index', $type) }}"
            target="save, image"
            saveLabel="Tạo danh mục ngay"
            mode="Create"
        >
            <x-slot:info>
                <span class="text-[13px] font-bold text-text-main truncate max-w-[180px]" x-data="{ name: $wire.entangle('name') }" x-text="name || 'Danh mục mới...'"></span>
            </x-slot:info>
        </x-admin.sticky-bar>
    </form>
</div>
