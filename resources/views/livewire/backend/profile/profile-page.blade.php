<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-text-main tracking-tight uppercase italic">Hồ sơ cá nhân</h1>
            <p class="text-sm text-text-muted mt-1">Cập nhật thông tin cá nhân và ảnh đại diện của bạn.</p>
        </div>
    </div>

    <form wire:submit="updateProfile">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Avatar Section -->
            <div class="lg:col-span-4">
                <x-backend.layout.card class="p-6 flex flex-col items-center text-center">
                    <div class="relative group">
                        <div class="w-40 h-40 rounded-3xl overflow-hidden border-4 border-primary/20 bg-bg-surface shadow-xl shadow-primary/10">
                            @if ($avatar)
                                <img src="{{ $avatar->temporaryUrl() }}" class="w-full h-full object-cover">
                            @else
                                <img src="{{ Auth::user()->avatar_url }}" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <label class="absolute -bottom-3 -right-3 w-12 h-12 bg-primary text-white rounded-2xl flex items-center justify-center cursor-pointer shadow-lg hover:scale-110 transition-transform">
                            <i class="ti ti-camera text-xl"></i>
                            <input type="file" wire:model="avatar" class="hidden" accept="image/*">
                        </label>
                    </div>
                    
                    <div class="mt-8 space-y-1">
                        <h4 class="font-black text-text-main text-lg uppercase">{{ $name }}</h4>
                        <p class="text-xs font-bold text-text-muted uppercase tracking-widest">{{ Auth::user()->roles->first()->name ?? 'Admin' }}</p>
                    </div>

                    <div class="w-full mt-6 pt-6 border-t border-border-glass">
                        <p class="text-[10px] text-text-muted uppercase tracking-tighter">Định dạng hỗ trợ: JPG, PNG. Tối đa 1MB.</p>
                        @error('avatar') <span class="text-danger text-[10px] block mt-1 font-bold">{{ $message }}</span> @enderror
                    </div>
                </x-backend.layout.card>
            </div>

            <!-- Info Section -->
            <div class="lg:col-span-8 space-y-6">
                <x-backend.layout.card class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-text-muted uppercase tracking-widest">Họ và tên</label>
                            <x-backend.forms.input name="name" wire:model="name" placeholder="Nhập họ tên" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-text-muted uppercase tracking-widest">Địa chỉ Email</label>
                            <x-backend.forms.input name="email" wire:model="email" type="email" placeholder="Nhập email" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-text-muted uppercase tracking-widest">Số điện thoại</label>
                            <x-backend.forms.input name="phone" wire:model="phone" placeholder="Nhập số điện thoại" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-text-muted uppercase tracking-widest">Địa chỉ</label>
                            <x-backend.forms.input name="address" wire:model="address" placeholder="Nhập địa chỉ" />
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-border-glass flex justify-end gap-3">
                        <x-backend.ui.button variant="neutral" type="button" :href="route('backend.dashboard')">Hủy</x-backend.ui.button>
                        <x-backend.ui.button variant="primary" type="submit" wire:loading.attr="disabled">
                            <span wire:loading.remove>Lưu thay đổi</span>
                            <span wire:loading class="flex items-center gap-2"><i class="ti ti-loader animate-spin"></i> Đang lưu...</span>
                        </x-backend.ui.button>
                    </div>
                </x-backend.layout.card>
            </div>
        </div>
    </form>
</div>
