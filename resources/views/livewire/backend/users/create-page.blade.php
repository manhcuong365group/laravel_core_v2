<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <x-backend.ui.button variant="neutral" :href="route('backend.users.index')" icon="ti ti-arrow-left" size="sm">
            Quay lại
        </x-backend.ui.button>
        <div>
            <h1 class="text-2xl font-black text-text-main tracking-tight">Thêm người dùng mới</h1>
            <p class="text-sm text-text-muted mt-1">Tạo tài khoản mới cho nhân viên hoặc quản trị viên.</p>
        </div>
    </div>

    <form wire:submit="save" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Left Column: Avatar -->
            <div class="md:col-span-1 space-y-6">
                <x-backend.layout.card title="Ảnh đại diện" class="p-6">
                    <div class="flex flex-col items-center gap-4">
                        <div class="w-32 h-32 rounded-full bg-primary/10 border-2 border-dashed border-primary/30 flex items-center justify-center overflow-hidden relative group">
                            @if ($avatar)
                                <img src="{{ $avatar->temporaryUrl() }}" class="w-full h-full object-cover">
                            @else
                                <i class="ti ti-photo-plus text-3xl text-primary/40"></i>
                            @endif
                            <input type="file" wire:model="avatar" class="absolute inset-0 opacity-0 cursor-pointer">
                        </div>
                        <div class="text-center">
                            <p class="text-[10px] text-text-muted uppercase font-bold tracking-wider">PNG, JPG up to 2MB</p>
                            @error('avatar') <span class="text-xs text-danger">{{ $message }}</span> @error('avatar')
                        </div>
                    </div>
                </x-backend.layout.card>

                <x-backend.layout.card title="Trạng thái" class="p-6">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-text-main">Kích hoạt tài khoản</span>
                        <x-backend.forms.toggle wire:model="is_active" />
                    </div>
                </x-backend.layout.card>
            </div>

            <!-- Right Column: Info -->
            <div class="md:col-span-2 space-y-6">
                <x-backend.layout.card title="Thông tin cơ bản" class="p-6">
                    <div class="grid grid-cols-1 gap-6">
                        <x-backend.forms.input 
                            label="Họ và tên" 
                            wire:model="name" 
                            placeholder="Nhập tên người dùng..." 
                            required 
                        />
                        
                        <x-backend.forms.input 
                            label="Địa chỉ Email" 
                            type="email" 
                            wire:model="email" 
                            placeholder="email@example.com" 
                            required 
                        />

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <x-backend.forms.input 
                                label="Mật khẩu" 
                                type="password" 
                                wire:model="password" 
                                placeholder="••••••••" 
                                required 
                            />
                            <x-backend.forms.input 
                                label="Xác nhận mật khẩu" 
                                type="password" 
                                wire:model="password_confirmation" 
                                placeholder="••••••••" 
                                required 
                            />
                        </div>
                    </div>
                </x-backend.layout.card>

                <x-backend.layout.card title="Vai trò & Quyền hạn" class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($roles as $role)
                            <label class="flex items-center gap-3 p-3 rounded-xl border border-border-glass bg-bg-surface/50 hover:bg-primary/5 hover:border-primary/30 cursor-pointer transition-all">
                                <input type="checkbox" wire:model="selectedRoles" value="{{ $role->name }}" class="w-4 h-4 rounded border-border-glass text-primary focus:ring-primary/50 bg-bg-surface">
                                <span class="text-sm font-bold text-text-main">{{ $role->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('selectedRoles') <p class="mt-2 text-xs text-danger">{{ $message }}</p> @enderror
                </x-backend.layout.card>

                <div class="flex justify-end gap-3">
                    <x-backend.ui.button variant="neutral" :href="route('backend.users.index')">
                        Hủy bỏ
                    </x-backend.ui.button>
                    <x-backend.ui.button type="submit" variant="primary" icon="ti ti-check" wire:loading.attr="disabled">
                        <span wire:loading.remove>Lưu người dùng</span>
                        <span wire:loading>Đang lưu...</span>
                    </x-backend.ui.button>
                </div>
            </div>
        </div>
    </form>
</div>

