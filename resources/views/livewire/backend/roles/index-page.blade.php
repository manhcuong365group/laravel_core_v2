<x-backend.layout.index-page
    title="Quản lý vai trò"
    subtitle="Hệ thống"
    description="Quản lý vai trò và phân quyền người dùng trong hệ thống."
    :total="$roles->count()"
    totalLabel="vai trò"
    icon="ti ti-shield-lock"
>
    {{-- Header Actions --}}
    <x-slot:headerActions>
        <x-backend.ui.button variant="neutral" :href="route('backend.users.index')" icon="ti ti-users">
            Quản lý người dùng
        </x-backend.ui.button>
        <x-backend.ui.button variant="primary" :href="route('backend.users.roles.create')" icon="ti ti-plus">
            Thêm vai trò
        </x-backend.ui.button>
    </x-slot:headerActions>

    {{-- Filters --}}
    <x-slot:filters>
        <x-backend.ui.button variant="neutral" icon="ti ti-refresh" wire:click="$reset('search')" class="!h-12 !rounded-2xl">
            Làm mới
        </x-backend.ui.button>
    </x-slot:filters>

    {{-- Custom Table replacement: Grid for Roles --}}
    <x-slot:table>
        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($roles as $role)
                    <div class="rounded-2xl border border-white/5 bg-white/[0.03] p-6 group hover:border-primary/30 transition-all duration-300 hover:shadow-2xl hover:shadow-primary/5">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-primary/10 border border-primary/20 flex items-center justify-center text-primary shadow-inner">
                                    <i class="ti ti-shield-check text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-text-main group-hover:text-primary transition-colors">{{ ucfirst($role->name) }}</h3>
                                    <p class="text-[10px] text-text-muted font-black uppercase tracking-widest mt-0.5 opacity-60">{{ $role->users->count() }} người dùng</p>
                                </div>
                            </div>
                            <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-all translate-x-1 group-hover:translate-x-0">
                                <x-backend.ui.action-icon variant="edit" :href="route('backend.users.roles.edit', $role)" icon="ti ti-edit" />
                                @if ($role->name !== 'super-admin')
                                    <x-backend.ui.action-icon variant="delete" wire:click="confirmDelete({{ $role->id }}, '{{ addslashes($role->name) }}')" icon="ti ti-trash" />
                                @endif
                            </div>
                        </div>

                        <div class="mt-6 pt-6 border-t border-white/5">
                            <div class="flex items-center justify-between text-[11px] uppercase tracking-[0.1em] font-black text-text-muted mb-4 opacity-70">
                                <span>Quyền được cấp</span>
                                <span class="px-2 py-0.5 rounded-lg border border-white/10 text-text-main bg-white/5">{{ $role->permissions->count() }}</span>
                            </div>

                            <div class="flex flex-wrap gap-1.5 min-h-[100px] max-h-[150px] overflow-y-auto custom-scrollbar pr-1">
                                @forelse($role->permissions as $permission)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold border border-white/5 text-text-muted bg-white/5 transition-colors group-hover:border-primary/10 group-hover:text-text-main">
                                        {{ $permission->name }}
                                    </span>
                                @empty
                                    <span class="text-xs text-text-muted italic opacity-50">Chưa được phân quyền</span>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center">
                        <x-backend.ui.empty-state 
                            icon="ti ti-shield-off" 
                            title="Không tìm thấy vai trò" 
                            description="Hãy thử thay đổi từ khóa tìm kiếm hoặc tạo vai trò mới." 
                        />
                    </div>
                @endforelse
            </div>
        </div>

        @if($permissions->isNotEmpty())
            <div class="mt-8 px-8 pb-8">
                <div class="rounded-3xl border border-white/5 bg-white/[0.02] p-8 overflow-hidden relative">
                    <div class="absolute top-0 right-0 p-8 opacity-[0.03] pointer-events-none">
                        <i class="ti ti-lock-access text-9xl"></i>
                    </div>
                    
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-2 h-8 rounded-full bg-primary/40"></div>
                            <div>
                                <h3 class="text-lg font-black text-text-main tracking-tight uppercase">Danh mục quyền</h3>
                                <p class="text-sm text-text-muted font-medium mt-0.5">Tất cả các định danh quyền hạn hiện có trong hệ thống</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
                            @foreach ($permissions as $permission)
                                <div class="rounded-xl border border-white/5 bg-white/5 px-4 py-3 hover:bg-white/10 hover:border-primary/20 transition-all group/perm">
                                    <p class="text-[11px] text-text-muted group-hover/perm:text-text-main truncate font-medium flex items-center gap-2" title="{{ $permission->name }}">
                                        <i class="ti ti-circle-check text-primary/40 group-hover/perm:text-primary"></i>
                                        {{ $permission->name }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </x-slot:table>

    {{-- Modals --}}
    <x-slot:modals>
        <!-- Delete Modal -->
        <x-backend.layout.confirm-modal
            show="$wire.showDeleteModal"
            title="Xác nhận xóa vai trò"
            message="Bạn có chắc chắn muốn xóa vĩnh viễn vai trò '{{ $deleteTargetName }}'? Vai trò này sẽ bị gỡ khỏi tất cả người dùng đang sở hữu nó. Hành động này không thể hoàn tác."
        >
            <x-backend.ui.button variant="neutral" @click="$wire.showDeleteModal = false">
                Hủy bỏ
            </x-backend.ui.button>
            <x-backend.ui.button variant="danger" wire:click="executeDelete">
                Xác nhận xóa
            </x-backend.ui.button>
        </x-backend.layout.confirm-modal>
    </x-slot:modals>
</x-backend.layout.index-page>
