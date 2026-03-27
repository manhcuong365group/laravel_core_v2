<div class="space-y-8 pb-32">
    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center border border-primary/20 text-primary shadow-inner">
                 <i class="ti ti-trademark text-2xl"></i>
            </div>
            <div>
                <h1 class="text-3xl font-black text-text-main tracking-tight uppercase tracking-[0.1em]">Thêm thương hiệu</h1>
                <p class="text-[11px] font-black text-text-muted mt-1 uppercase tracking-widest opacity-60">Khởi tạo thương hiệu mới trong hệ thống.</p>
            </div>
        </div>
        <x-backend.ui.button variant="neutral" :href="route('backend.brands.index')" icon="ti ti-arrow-left" class="rounded-2xl font-black text-[10px] uppercase tracking-widest bg-white/5 border-white/10 hover:bg-white/10">
            Quay lại danh sách
        </x-backend.ui.button>
    </div>

    <form wire:submit="save" class="grid grid-cols-1 xl:grid-cols-12 gap-8 items-start">
        <!-- Main Column (8cols) -->
        <div class="xl:col-span-8 space-y-8">
            <x-admin.form-section title="Thông tin cơ bản" icon="ti-info-circle" color="blue">
                <div class="space-y-6">
                    <x-backend.forms.input wire:model.blur="name" name="name" label="Tên thương hiệu" required placeholder="Nhập tên thương hiệu..." class="text-lg font-bold" />
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-backend.forms.input wire:model="slug" name="slug" label="Đường dẫn tĩnh (Slug)" placeholder="tu-dong-tao-tu-ten..." />
                        <x-backend.forms.input wire:model="website" name="website" label="Website (URL)" placeholder="https://example.com" />
                    </div>

                    <x-backend.forms.textarea wire:model="description" name="description" label="Mô tả thương hiệu" rows="5" placeholder="Mô tả về thương hiệu..." />
                </div>
            </x-admin.form-section>
        </div>

        <!-- Sidebar Column (4cols) -->
        <div class="xl:col-span-4 space-y-8">
            <!-- Settings -->
            <x-admin.form-section title="Thiết lập" icon="ti-settings" color="orange">
                <div class="space-y-6">
                    <label class="flex items-center justify-between cursor-pointer p-4 bg-white/5 rounded-2xl border border-white/5 hover:bg-white/10 transition-colors">
                        <span class="text-sm font-bold text-text-main">Trạng thái kích hoạt</span>
                        <div class="relative">
                            <input type="checkbox" wire:model="is_active" class="sr-only peer">
                            <div class="w-11 h-6 bg-white/10 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-white/10 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500 transition-colors"></div>
                        </div>
                    </label>
                    
                    <x-backend.forms.input wire:model="order" name="order" label="Thứ tự hiển thị" type="number" />
                </div>
            </x-admin.form-section>

            <!-- Logo Section -->
            <x-admin.form-section title="Logo thương hiệu" icon="ti-photo" color="purple">
                <x-backend.forms.media-uploader 
                    wire:model="logo"
                    :model="$logo"
                    label=""
                    hint="Đề xuất: Dạng PNG trong suốt"
                />
            </x-admin.form-section>
        </div>

        <!-- Sticky Action Bar -->
        <x-admin.sticky-bar
            cancelHref="{{ route('backend.brands.index') }}"
            target="save, logo"
            saveLabel="Tạo thương hiệu ngay"
            mode="Create"
        >
            <x-slot:info>
                <span class="text-[13px] font-bold text-text-main truncate max-w-[180px]" x-data="{ name: $wire.entangle('name') }" x-text="name || 'Thương hiệu mới...'"></span>
            </x-slot:info>
        </x-admin.sticky-bar>
    </form>
</div>
