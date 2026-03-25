<div class="space-y-6">
    @include('backend.layouts.partials.breadcrumbs', [
        'items' => [
            ['label' => 'Cài đặt hệ thống', 'url' => route('backend.settings.general')],
            ['label' => 'Cấu hình chung'],
        ],
    ])

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-text-main tracking-tight">Cấu hình chung</h1>
            <p class="mt-1 text-sm text-text-muted">Thiết lập thông tin cơ bản và hệ thống cho website.</p>
        </div>
    </div>

    @include('livewire.backend.settings._tabs')

    <form wire:submit.prevent="update">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <x-backend.layout.form-section title="Thông tin website" description="Tên hiển thị, tagline, logo và favicon.">
                <x-backend.forms.input wire:model="site_name" name="site_name" label="Tên website" required />
                <x-backend.forms.input wire:model="site_tagline" name="site_tagline" label="Site tagline" placeholder="Slogan hoặc mô tả ngắn" />

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-text-muted mb-1.5 uppercase tracking-widest text-[11px]">Logo</label>
                        <div class="relative group">
                            <div class="w-full h-32 rounded-xl border border-dashed border-border-glass bg-white/5 flex items-center justify-center overflow-hidden">
                                @if ($site_logo)
                                    <img src="{{ $site_logo->temporaryUrl() }}" class="max-h-full object-contain p-2">
                                @elseif ($current_site_logo)
                                    <img src="{{ $current_site_logo }}" class="max-h-full object-contain p-2">
                                @else
                                    <i class="ti ti-photo-plus text-3xl text-text-muted"></i>
                                @endif
                            </div>
                            <input type="file" wire:model="site_logo" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer">
                        </div>
                        <p class="mt-1.5 text-xs text-text-muted">Định dạng: PNG, SVG, JPG. Tối đa 2MB.</p>
                        @error('site_logo') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-text-muted mb-1.5 uppercase tracking-widest text-[11px]">Favicon</label>
                        <div class="relative group">
                            <div class="w-full h-32 rounded-xl border border-dashed border-border-glass bg-white/5 flex items-center justify-center overflow-hidden">
                                @if ($site_favicon)
                                    <img src="{{ $site_favicon->temporaryUrl() }}" class="h-12 w-12 object-contain border border-border-glass rounded">
                                @elseif ($current_site_favicon)
                                    <img src="{{ $current_site_favicon }}" class="h-12 w-12 object-contain border border-border-glass rounded">
                                @else
                                    <i class="ti ti-circle-plus text-3xl text-text-muted"></i>
                                @endif
                            </div>
                            <input type="file" wire:model="site_favicon" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer">
                        </div>
                        <p class="mt-1.5 text-xs text-text-muted">Khuyên dùng: 32x32 hoặc 64x64px.</p>
                        @error('site_favicon') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
            </x-backend.layout.form-section>

            <div class="space-y-6">
                <x-backend.layout.form-section title="Cài đặt hệ thống" description="Múi giờ, định dạng và chế độ bảo trì.">
                    <x-backend.forms.select wire:model="timezone" name="timezone" label="Múi giờ"
                        :options="[
                            'Asia/Ho_Chi_Minh' => 'Việt Nam (GMT+7)',
                            'Asia/Bangkok' => 'Bangkok (GMT+7)',
                            'Asia/Singapore' => 'Singapore (GMT+8)',
                            'UTC' => 'UTC (GMT+0)',
                        ]" placeholder="" />

                    <x-backend.forms.select wire:model="date_format" name="date_format" label="Định dạng ngày"
                        :options="[
                            'd/m/Y' => 'Ngày/Tháng/Năm (31/12/2025)',
                            'Y-m-d' => 'Năm-Tháng-Ngày (2025-12-31)',
                            'd-m-Y' => 'Ngày-Tháng-Năm (31-12-2025)',
                            'm/d/Y' => 'Tháng/Ngày/Năm (12/31/2025)',
                        ]" placeholder="" />

                    <label
                        class="flex items-start justify-between p-4 rounded-xl border border-border-glass bg-white/5 cursor-pointer hover:bg-white/10 transition-colors">
                        <div class="pr-4">
                            <p class="font-semibold text-text-main">Chế độ bảo trì</p>
                            <p class="text-xs text-text-muted">Người dùng sẽ không thể truy cập website.</p>
                        </div>
                        <div class="relative mt-1">
                            <input type="checkbox" wire:model="maintenance_mode" class="sr-only peer">
                            <div class="w-11 h-6 bg-white/20 rounded-full peer-checked:bg-danger transition-colors"></div>
                            <div class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full transition-transform peer-checked:translate-x-5"></div>
                        </div>
                    </label>
                </x-backend.layout.form-section>

                <x-backend.ui.button type="primary" htmlType="submit" class="w-full" wire:loading.attr="disabled">
                    <span wire:loading.remove>Cập nhật cấu hình</span>
                    <span wire:loading>Đang cập nhật...</span>
                </x-backend.ui.button>
            </div>
        </div>
    </form>
</div>


