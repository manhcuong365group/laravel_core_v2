<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-text-main tracking-tight">Thêm thương hiệu</h1>
            <p class="text-sm text-text-muted mt-1">Thêm thương hiệu mới vào hệ thống.</p>
        </div>
        <x-backend.ui.button variant="neutral" :href="route('backend.brands.index')" icon="ti ti-arrow-left">
            Quay lại
        </x-backend.ui.button>
    </div>

    <form wire:submit="save" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <x-backend.layout.card title="Thông tin cơ bản" class="p-6">
                <div class="space-y-4">
                    <x-backend.forms.input wire:model.blur="name" name="name" label="Tên thương hiệu" required placeholder="Nhập tên thương hiệu..." />
                    <x-backend.forms.input wire:model="slug" name="slug" label="Slug" placeholder="tu-dong-tao-tu-ten..." />
                    <x-backend.forms.input wire:model="website" name="website" label="Website" placeholder="https://example.com" />
                    <x-backend.forms.textarea wire:model="description" name="description" label="Mô tả" rows="4" placeholder="Nhập mô tả thương hiệu..." />
                </div>
            </x-backend.layout.card>
        </div>

        <div class="space-y-6 lg:sticky lg:top-24 self-start">
            <x-backend.layout.card title="Thiết lập" class="p-6">
                <div class="space-y-4">
                    <div class="flex flex-col gap-3 mt-4">
                        <label class="inline-flex items-center gap-2 cursor-pointer group">
                            <input type="checkbox" wire:model="is_active" class="w-4 h-4 rounded border-border-glass text-primary focus:ring-primary/50 bg-bg-surface transition-all">
                            <span class="text-sm font-bold text-text-main group-hover:text-primary transition-colors">Kích hoạt</span>
                        </label>
                    </div>
                    <div class="mt-4">
                        <x-backend.forms.input wire:model="order" name="order" label="Thứ tự hiển thị" type="number" />
                    </div>
                </div>
            </x-backend.layout.card>

            <x-backend.layout.card title="Logo" class="p-6">
                <div class="space-y-1.5">
                    <label class="block text-[11px] font-black text-text-muted uppercase tracking-[0.15em]">Logo thương hiệu</label>
                    <div class="relative group cursor-pointer">
                        <div class="w-full h-40 rounded-xl border-2 border-dashed border-border-glass bg-bg-surface flex flex-col items-center justify-center gap-2 group-hover:border-primary/50 transition-all overflow-hidden">
                            @if ($logo)
                                <img src="{{ $logo->temporaryUrl() }}" class="w-full h-full object-contain p-4">
                            @else
                                <i class="ti ti-photo-plus text-3xl text-text-muted/30"></i>
                                <span class="text-[10px] font-black text-text-muted uppercase tracking-widest">Tải logo lên</span>
                            @endif
                        </div>
                        <input type="file" wire:model="logo" class="absolute inset-0 opacity-0 cursor-pointer">
                    </div>
                    @error('logo') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>
            </x-backend.layout.card>

            <div class="flex flex-col gap-2">
                <x-backend.ui.button variant="primary" size="lg" wire:click="save" class="w-full shadow-lg shadow-primary/20">
                    <i class="ti ti-device-floppy mr-1.5"></i>
                    Lưu thương hiệu
                </x-backend.ui.button>
                <x-backend.ui.button variant="neutral" :href="route('backend.brands.index')" class="w-full">
                    Hủy bỏ
                </x-backend.ui.button>
            </div>
        </div>
    </form>
</div>

