<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-text-main tracking-tight uppercase italic">Bảo mật tài khoản</h1>
            <p class="text-sm text-text-muted mt-1">Quản lý mật khẩu và các cài đặt an ninh cho tài khoản của bạn.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Security Tips -->
        <div class="lg:col-span-4">
            <x-backend.layout.card class="p-6 bg-linear-to-br from-primary/10 to-transparent border-primary/20">
                <div class="w-12 h-12 rounded-2xl bg-primary/20 flex items-center justify-center text-primary mb-6 shadow-lg shadow-primary/10">
                    <i class="ti ti-shield-lock text-2xl"></i>
                </div>
                <h4 class="font-black text-text-main text-lg uppercase italic mb-3 tracking-tighter">Lời khuyên bảo mật</h4>
                <ul class="space-y-4">
                    <li class="flex gap-3">
                        <i class="ti ti-check text-primary mt-1"></i>
                        <span class="text-xs text-text-muted leading-relaxed font-bold">Mật khẩu nên chứa ít nhất 8 ký tự bao gồm chữ hoa, chữ thường và số.</span>
                    </li>
                    <li class="flex gap-3">
                        <i class="ti ti-check text-primary mt-1"></i>
                        <span class="text-xs text-text-muted leading-relaxed font-bold">Không sử dụng mật khẩu trùng với các tài khoản khác của bạn.</span>
                    </li>
                    <li class="flex gap-3">
                        <i class="ti ti-check text-primary mt-1"></i>
                        <span class="text-xs text-text-muted leading-relaxed font-bold">Thường xuyên thay đổi mật khẩu sau mỗi 3-6 tháng.</span>
                    </li>
                </ul>
            </x-backend.layout.card>
        </div>

        <!-- Password Change Section -->
        <div class="lg:col-span-8">
            <x-backend.layout.card class="p-6">
                <form wire:submit="updatePassword" class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-text-muted uppercase tracking-widest">Mật khẩu hiện tại</label>
                        <x-backend.forms.input name="current_password" wire:model="current_password" type="password" placeholder="Nhập mật khẩu hiện tại" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-text-muted uppercase tracking-widest">Mật khẩu mới</label>
                            <x-backend.forms.input name="password" wire:model="password" type="password" placeholder="Nhập mật khẩu mới" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-text-muted uppercase tracking-widest">Xác nhận mật khẩu mới</label>
                            <x-backend.forms.input name="password_confirmation" wire:model="password_confirmation" type="password" placeholder="Xác nhận mật khẩu mới" />
                        </div>
                    </div>

                    <div class="pt-6 border-t border-border-glass flex justify-end gap-3">
                        <x-backend.ui.button variant="neutral" type="button" :href="route('backend.dashboard')">Hủy</x-backend.ui.button>
                        <x-backend.ui.button variant="primary" type="submit" wire:loading.attr="disabled">
                            <span wire:loading.remove>Cập nhật mật khẩu</span>
                            <span wire:loading class="flex items-center gap-2"><i class="ti ti-loader animate-spin"></i> Đang cập nhật...</span>
                        </x-backend.ui.button>
                    </div>
                </form>
            </x-backend.layout.card>
        </div>
    </div>
</div>
