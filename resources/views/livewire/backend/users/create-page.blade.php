<div class="space-y-8 pb-32">
    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center border border-primary/20 text-primary shadow-inner">
                 <i class="ti ti-user-plus text-2xl"></i>
            </div>
            <div>
                <h1 class="text-3xl font-black text-text-main tracking-tight uppercase tracking-[0.1em]">Thêm người dùng</h1>
                <p class="text-[11px] font-black text-text-muted mt-1 uppercase tracking-widest opacity-60">Khởi tạo tài khoản mới và phân quyền truy cập hệ thống.</p>
            </div>
        </div>
        <x-backend.ui.button variant="neutral" :href="route('backend.users.index')" icon="ti ti-arrow-left" class="rounded-2xl font-black text-[10px] uppercase tracking-widest bg-white/5 border-white/10 hover:bg-white/10">
            Quay lại danh sách
        </x-backend.ui.button>
    </div>

    <form wire:submit="save" class="grid grid-cols-1 xl:grid-cols-12 gap-8 items-start">
        <!-- Main Column (8cols) -->
        <div class="xl:col-span-8 space-y-8">
            <!-- Account Info -->
            <x-admin.form-section title="Thông tin tài khoản" icon="ti-id-badge" color="blue">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <x-backend.forms.input wire:model="name" name="name" label="Họ và tên" required placeholder="Nhập tên đầy đủ..." class="text-lg font-bold" />
                    </div>
                    <x-backend.forms.input wire:model="email" type="email" name="email" label="Địa chỉ Email" required placeholder="email@example.com" />
                    <x-backend.forms.input wire:model="phone" name="phone" label="Số điện thoại" placeholder="0123... (Tùy chọn)" />
                </div>
            </x-admin.form-section>

            <!-- Password -->
            <x-admin.form-section title="Mật khẩu truy cập" icon="ti-shield-lock" color="orange">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-backend.forms.input wire:model="password" type="password" name="password" label="Mật khẩu" required placeholder="••••••••" />
                    <x-backend.forms.input wire:model="password_confirmation" type="password" name="password_confirmation" label="Xác nhận mật khẩu" required placeholder="••••••••" />
                </div>
            </x-admin.form-section>

            <!-- Roles -->
            <x-admin.form-section title="Vai trò & Quyền hạn" icon="ti-shield-check" color="purple">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($roles as $role)
                        <label class="flex items-center gap-3 p-4 rounded-2xl border border-white/5 bg-white/[0.02] hover:bg-white/[0.05] transition-all cursor-pointer group">
                            <input type="checkbox" wire:model="selectedRoles" value="{{ $role->name }}" class="w-5 h-5 rounded-lg border-white/10 text-primary focus:ring-primary/20 bg-white/5 transition-all">
                            <span class="text-sm font-black text-text-main uppercase tracking-tight group-hover:text-primary transition-colors">{{ $role->name }}</span>
                        </label>
                    @endforeach
                </div>
            </x-admin.form-section>
        </div>

        <!-- Sidebar Column (4cols) -->
        <div class="xl:col-span-4 space-y-8">
            <!-- Status -->
            <x-admin.form-section title="Trạng thái" icon="ti-settings" color="emerald">
                <label class="flex items-center justify-between cursor-pointer p-4 bg-white/5 rounded-2xl border border-white/5 hover:bg-white/10 transition-colors">
                    <span class="text-sm font-bold text-text-main uppercase tracking-tight">Kích hoạt ngay</span>
                    <div class="relative">
                        <input type="checkbox" wire:model="is_active" class="sr-only peer">
                        <div class="w-11 h-6 bg-white/10 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-white/10 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500 transition-colors"></div>
                    </div>
                </label>
            </x-admin.form-section>

            <!-- Avatar -->
            <x-admin.form-section title="Ảnh đại diện" icon="ti-photo" color="blue">
                <div class="flex flex-col items-center gap-6">
                    <div class="relative group cursor-pointer">
                        <div class="w-40 h-40 rounded-full border-4 border-dashed border-white/10 bg-white/5 flex items-center justify-center overflow-hidden group-hover:border-primary/50 transition-all shadow-2xl">
                            @if ($avatar)
                                <img src="{{ $avatar->temporaryUrl() }}" class="w-full h-full object-cover">
                            @else
                                <i class="ti ti-user-plus text-5xl text-text-muted/20 group-hover:text-primary transition-colors"></i>
                            @endif
                            <div class="absolute inset-0 bg-primary/20 backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <i class="ti ti-camera text-2xl text-white"></i>
                            </div>
                        </div>
                        <input type="file" wire:model="avatar" class="absolute inset-0 opacity-0 cursor-pointer">
                    </div>
                </div>
            </x-admin.form-section>
        </div>

        <!-- Sticky Action Bar -->
        <x-admin.sticky-bar
            cancelHref="{{ route('backend.users.index') }}"
            target="save, avatar"
            saveLabel="Tạo tài khoản ngay"
            mode="New User"
        >
            <x-slot:info>
                <span class="text-[13px] font-bold text-text-main truncate max-w-[180px]" x-data="{ name: $wire.entangle('name') }" x-text="name || 'Người dùng mới...'"></span>
            </x-slot:info>
        </x-admin.sticky-bar>
    </form>
</div>
