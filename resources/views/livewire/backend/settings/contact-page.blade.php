<div class="space-y-6">
    @include('backend.layouts.partials.breadcrumbs', [
        'items' => [
            ['label' => 'Cài đặt hệ thống', 'url' => route('backend.settings.general')],
            ['label' => 'Thông tin liên hệ'],
        ],
    ])

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-text-main tracking-tight">Thông tin liên hệ</h1>
            <p class="mt-1 text-sm text-text-muted">Cấu hình địa chỉ, số điện thoại và bản đồ.</p>
        </div>
    </div>

    @include('livewire.backend.settings._tabs')

    <form wire:submit.prevent="update" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <x-backend.layout.form-section title="Thông tin công ty" description="Tên doanh nghiệp, địa chỉ và thời gian làm việc.">
                <x-backend.forms.input wire:model="company_name" name="company_name" label="Tên công ty" placeholder="Nhập tên doanh nghiệp" />
                <x-backend.forms.textarea wire:model="address" name="address" label="Địa chỉ" rows="3" placeholder="Số nhà, tên đường, quận/huyện..." />
                <x-backend.forms.input wire:model="working_hours" name="working_hours" label="Giờ làm việc" placeholder="VD: Thứ 2 - Thứ 7: 08:00 - 18:00" />
            </x-backend.layout.form-section>

            <x-backend.layout.form-section title="Liên lạc trực tiếp" description="Thông tin liên hệ cho khách hàng.">
                <x-backend.forms.input wire:model="phone" name="phone" label="Điện thoại bàn" placeholder="VD: 028 38 123 456" />
                <x-backend.forms.input wire:model="hotline" name="hotline" label="Hotline" placeholder="VD: 1900 1234" />
                <x-backend.forms.input wire:model="email" name="email" type="email" label="Email liên hệ" placeholder="contact@domain.com" />
            </x-backend.layout.form-section>

            <x-backend.layout.form-section title="Bản đồ (Google Maps)" description="Thiết lập mã nhúng và liên kết bản đồ." class="lg:col-span-2">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <x-backend.forms.textarea wire:model.lazy="google_maps_embed" name="google_maps_embed" label="Mã nhúng (Embed code)" rows="4"
                            placeholder="<iframe src='...'></iframe>" class="font-mono text-xs" />
                        <p class="text-xs text-text-muted">Cách lấy: Google Maps > Chia sẻ > Nhúng bản đồ > Sao chép HTML.</p>

                        <x-backend.forms.input wire:model="google_maps_link" name="google_maps_link" type="url" label="Đường dẫn trực tiếp"
                            placeholder="https://maps.google.com/..." />
                        <p class="text-xs text-text-muted">Dùng để mở ứng dụng Google Maps trên điện thoại.</p>
                    </div>

                    <div class="rounded-xl border border-dashed border-border-glass bg-white/5 p-4 flex items-center justify-center min-h-56">
                        @if (!empty($google_maps_embed))
                            <div class="w-full h-full rounded-lg overflow-hidden aspect-video">
                                {!! $google_maps_embed !!}
                            </div>
                        @else
                            <div class="text-center">
                                <i class="ti ti-map text-4xl text-text-muted"></i>
                                <p class="text-sm text-text-muted mt-2">Xem trước bản đồ tại đây.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </x-backend.layout.form-section>
        </div>

        <div class="flex justify-end">
            <x-backend.ui.button type="primary" htmlType="submit" wire:loading.attr="disabled">
                <span wire:loading.remove>Lưu thông tin liên hệ</span>
                <span wire:loading>Đang lưu...</span>
            </x-backend.ui.button>
        </div>
    </form>
</div>


