<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <x-backend.ui.button variant="neutral" :href="route('backend.users.roles')" icon="ti ti-arrow-left" size="sm">
            Quay lại
        </x-backend.ui.button>
        <div>
            <h1 class="text-2xl font-black text-text-main tracking-tight">Sửa vai trò: {{ $role->name }}</h1>
            <p class="text-sm text-text-muted mt-1">Cập nhật tên vai trò và quyền hạn.</p>
        </div>
    </div>

    <form wire:submit="save" class="space-y-6">
        <x-backend.layout.card title="Thông tin vai trò" class="p-6">
            <div class="grid grid-cols-1 gap-6">
                <x-backend.forms.input 
                    label="Tên vai trò" 
                    wire:model="name" 
                    placeholder="VD: marketing, editor, manager..." 
                    required 
                    :disabled="$role->name === 'super-admin'"
                />
                @if($role->name === 'super-admin')
                    <p class="text-xs text-warning">Không thể đổi tên vai trò quản trị tối cao.</p>
                @endif
            </div>
        </x-backend.layout.card>

        <x-backend.layout.card title="Phân quyền" class="p-6">
            <p class="text-sm text-text-muted mb-6">Chọn các quyền mà vai trò này được phép thực hiện.</p>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($permissions as $permission)
                    <label class="flex items-center gap-3 p-3 rounded-xl border border-border-glass bg-bg-surface/50 hover:bg-primary/5 hover:border-primary/30 cursor-pointer transition-all">
                        <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission->name }}" 
                            class="w-4 h-4 rounded border-border-glass text-primary focus:ring-primary/50 bg-bg-surface"
                            @if($role->name === 'super-admin') disabled @endif>
                        <span class="text-[11px] font-bold text-text-main uppercase tracking-tight">{{ $permission->name }}</span>
                    </label>
                @endforeach
            </div>
            @if($role->name === 'super-admin')
                <p class="mt-4 text-xs text-warning">Vai trò quản trị tối cao luôn có tất cả các quyền.</p>
            @endif
            @error('selectedPermissions') <p class="mt-2 text-xs text-danger">{{ $message }}</p> @enderror
        </x-backend.layout.card>

        <div class="flex justify-end gap-3">
            <x-backend.ui.button variant="neutral" :href="route('backend.users.roles')">
                Hủy bỏ
            </x-backend.ui.button>
            <x-backend.ui.button type="submit" variant="primary" icon="ti ti-check" wire:loading.attr="disabled">
                <span wire:loading.remove>Cập nhật vai trò</span>
                <span wire:loading>Đang lưu...</span>
            </x-backend.ui.button>
        </div>
    </form>
</div>

