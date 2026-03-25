<div class="space-y-6">
    @include('backend.layouts.partials.breadcrumbs', [
        'items' => [
            ['label' => 'Cài đặt hệ thống', 'url' => route('backend.settings.general')],
            ['label' => 'Mạng xã hội'],
        ],
    ])

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-text-main tracking-tight">Mạng xã hội</h1>
            <p class="mt-1 text-sm text-text-muted">Quản lý các liên kết đến trang cộng đồng của bạn.</p>
        </div>
    </div>

    @include('livewire.backend.settings._tabs')

    <form wire:submit.prevent="update">
        <x-backend.layout.form-section title="Liên kết mạng xã hội" description="Các đường dẫn sẽ được hiển thị ở website/public profile.">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <x-backend.forms.input wire:model="facebook" name="facebook" type="url" label="Facebook" placeholder="https://facebook.com/yourpage" />
                <x-backend.forms.input wire:model="youtube" name="youtube" type="url" label="YouTube" placeholder="https://youtube.com/@channel" />
                <x-backend.forms.input wire:model="instagram" name="instagram" type="url" label="Instagram" placeholder="https://instagram.com/username" />
                <x-backend.forms.input wire:model="twitter" name="twitter" type="url" label="Twitter / X" placeholder="https://x.com/username" />
                <x-backend.forms.input wire:model="linkedin" name="linkedin" type="url" label="LinkedIn" placeholder="https://linkedin.com/company/..." />
                <x-backend.forms.input wire:model="tiktok" name="tiktok" type="url" label="TikTok" placeholder="https://tiktok.com/@username" />
                <x-backend.forms.input wire:model="zalo" name="zalo" type="url" label="Zalo OA / Profile" placeholder="https://zalo.me/..." />
            </div>

            <div class="flex justify-end">
                <x-backend.ui.button type="primary" htmlType="submit" wire:loading.attr="disabled">
                    <span wire:loading.remove>Lưu thay đổi mạng xã hội</span>
                    <span wire:loading>Đang lưu...</span>
                </x-backend.ui.button>
            </div>
        </x-backend.layout.form-section>
    </form>
</div>


