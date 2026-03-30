<x-backend.layout.index-page
    title="Vai trò & Quyền"
    subtitle="Quản trị"
    description="Quản lý các cấp bậc phân quyền và các định danh hành động trong hệ thống."
    :total="$roles->count()"
    totalLabel="vai trò"
    icon="ti ti-shield-lock"
>
    {{-- Header Actions --}}
    <x-slot:headerActions>
        <div class="flex items-center gap-3">
            <x-backend.ui.button variant="neutral" :href="route('backend.users.index')" icon="ti ti-users" class="!rounded-2xl h-11 border-white/5 bg-white/5 hover:bg-white/10">
                Quản lý người dùng
            </x-backend.ui.button>
            <x-backend.ui.button variant="primary" :href="route('backend.users.roles.create')" icon="ti ti-plus" class="!rounded-2xl h-11 shadow-lg shadow-primary/20">
                Thêm vai trò
            </x-backend.ui.button>
        </div>
    </x-slot:headerActions>

    {{-- Filters --}}
    <x-slot:filters>
        <div class="flex items-center gap-4">
            <div class="relative flex-1 max-w-md group">
                <i class="ti ti-search absolute left-4 top-1/2 -translate-y-1/2 text-text-muted text-lg group-focus-within:text-primary transition-colors"></i>
                <input type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Tìm theo tên vai trò..."
                    class="w-full h-12 pl-12 pr-4 rounded-2xl border border-white/5 bg-white/[0.03] text-sm text-text-main placeholder:text-text-muted/50 focus:border-primary/30 focus:bg-white/[0.05] focus:outline-none transition-all"
                >
            </div>
            
            <x-backend.ui.button variant="neutral" icon="ti ti-refresh" wire:click="$reset('search')" class="!h-12 !w-12 !p-0 !rounded-2xl border-white/5 bg-white/5 hover:bg-white/10" title="Làm mới">
            </x-backend.ui.button>
        </div>
    </x-slot:filters>

    {{-- Main Content: Grid for Roles --}}
    <x-slot:table>
        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse ($roles as $role)
                    <div class="relative group">
                        {{-- Background Glow --}}
                        <div class="absolute -inset-0.5 bg-gradient-to-br from-primary/20 to-secondary/20 rounded-[2rem] opacity-0 group-hover:opacity-100 blur transition-all duration-500"></div>
                        
                        {{-- Card Content --}}
                        <div class="relative rounded-[2rem] border border-white/5 bg-bg-surface/40 backdrop-blur-xl p-8 flex flex-col h-full hover:border-white/10 transition-all duration-300">
                            {{-- Card Header --}}
                            <div class="flex items-start justify-between mb-8">
                                <div class="flex items-center gap-5">
                                    <div class="w-16 h-16 rounded-3xl bg-gradient-to-br from-primary/10 to-primary/5 border border-primary/20 flex items-center justify-center text-primary shadow-xl shadow-primary/5 transition-transform duration-500 group-hover:scale-110 group-hover:rotate-3">
                                        <i class="ti ti-shield-check text-3xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-black text-text-main group-hover:text-primary transition-colors tracking-tight">{{ ucfirst($role->name) }}</h3>
                                        <div class="flex items-center gap-2 mt-1.5 translate-y-0.5">
                                            <span class="w-1.5 h-1.5 rounded-full bg-success shadow-[0_0_8px_rgba(var(--success-rgb),0.5)]"></span>
                                            <p class="text-[10px] text-text-muted font-black uppercase tracking-[0.15em] opacity-60">{{ $role->users->count() }} tài khoản</p>
                                        </div>
                                    </div>
                                </div>
                                
                                {{-- Actions Menu --}}
                                <div class="flex gap-2">
                                    <x-backend.ui.action-icon variant="edit" :href="route('backend.users.roles.edit', $role)" icon="ti ti-edit" class="!w-10 !h-10 !rounded-xl !bg-white/5 !border-white/5 hover:!bg-primary/20 hover:!border-primary/30" />
                                    @if ($role->name !== 'super-admin')
                                        <x-backend.ui.action-icon variant="delete" wire:click="confirmDelete({{ $role->id }}, '{{ addslashes($role->name) }}')" icon="ti ti-trash" class="!w-10 !h-10 !rounded-xl !bg-white/5 !border-white/5 hover:!bg-danger/20 hover:!border-danger/30" />
                                    @endif
                                </div>
                            </div>

                            {{-- Permissions Section --}}
                            <div class="mt-auto">
                                <div class="flex items-center justify-between text-[11px] uppercase tracking-[0.2em] font-black text-text-muted mb-5 opacity-70 border-b border-white/5 pb-4">
                                    <div class="flex items-center gap-2">
                                        <i class="ti ti-lock-access text-sm"></i>
                                        <span>Quyền hạn được cấp</span>
                                    </div>
                                    <span class="px-3 py-1 rounded-full border border-white/10 text-primary font-black bg-primary/5">{{ $role->permissions->count() }}</span>
                                </div>

                                <div class="flex flex-wrap gap-2 min-h-[140px] max-h-[180px] overflow-y-auto custom-scrollbar pr-2 pb-6">
                                    @forelse($role->permissions->take(12) as $permission)
                                        <span class="inline-flex items-center px-4 py-1.5 rounded-xl text-[10px] font-black border border-white/5 text-text-muted/80 bg-white/5 transition-all hover:border-primary/20 hover:text-text-main hover:bg-white/10 hover:-translate-y-0.5 uppercase tracking-wide">
                                            {{ $permission->name }}
                                        </span>
                                    @empty
                                        <div class="flex flex-col items-center justify-center w-full py-8 opacity-40">
                                            <i class="ti ti-shield-off text-3xl mb-2"></i>
                                            <span class="text-[11px] font-bold uppercase">Trống</span>
                                        </div>
                                    @endforelse
                                    
                                    @if($role->permissions->count() > 12)
                                        <div class="w-full mt-2 flex justify-center">
                                            <span class="text-[10px] font-black text-text-muted uppercase tracking-widest opacity-40 italic">
                                                và {{ $role->permissions->count() - 12 }} quyền khác...
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            {{-- Decorative Background Icon --}}
                            <div class="absolute bottom-6 right-6 opacity-[0.02] group-hover:opacity-[0.05] transition-opacity pointer-events-none -rotate-12 group-hover:scale-125 transition-transform duration-700">
                                <i class="ti ti-shield-check text-8xl"></i>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-32 text-center rounded-[3rem] border border-dashed border-white/10 bg-white/[0.01]">
                        <x-backend.ui.empty-state 
                            icon="ti ti-shield-off" 
                            title="Không tìm thấy vai trò" 
                            description="Hãy thử thay đổi từ khóa tìm kiếm hoặc tạo vai trò mới." 
                        />
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Permissions Quick List --}}
        @if($permissions->isNotEmpty())
            <div class="px-8 pb-12 mt-4">
                <div class="relative overflow-hidden group">
                    <div class="absolute -inset-[1px] bg-gradient-to-r from-primary/10 via-transparent to-secondary/10 opacity-50 blur-[2px] rounded-[3rem]"></div>
                    <div class="relative rounded-[3rem] border border-white/5 bg-white/[0.02] p-12 overflow-hidden backdrop-blur-sm">
                        {{-- Background Pattern --}}
                        <div class="absolute -right-20 -bottom-20 opacity-[0.03] rotate-12 pointer-events-none group-hover:scale-110 transition-transform duration-1000">
                            <i class="ti ti-lock-access text-[25rem]"></i>
                        </div>
                        
                        <div class="relative z-10">
                            <div class="flex items-center gap-6 mb-12">
                                <div class="w-1.5 h-12 rounded-full bg-gradient-to-t from-primary to-secondary"></div>
                                <div>
                                    <h3 class="text-2xl font-black text-text-main tracking-tight uppercase italic underline decoration-primary/30 decoration-4 underline-offset-8">Danh mục định danh quyền</h3>
                                    <p class="text-sm text-text-muted font-medium mt-3 opacity-60">Toàn bộ danh sách quyền thực thi hiện có sẵn trong hệ thống phục vụ việc phân tách vai trò.</p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                                @foreach ($permissions as $permission)
                                    <div class="group/perm relative">
                                        <div class="absolute inset-0 bg-primary/5 opacity-0 group-hover/perm:opacity-100 rounded-2xl blur transition-opacity"></div>
                                        <div class="relative rounded-2xl border border-white/5 bg-white/[0.04] px-5 py-4 hover:border-primary/20 hover:bg-white/[0.08] transition-all flex items-center gap-3">
                                            <div class="w-2 h-2 rounded-full bg-primary/20 group-hover/perm:bg-primary group-hover/perm:shadow-[0_0_8px_rgba(var(--primary-rgb),0.5)] transition-all"></div>
                                            <p class="text-[11px] text-text-muted group-hover/perm:text-text-main truncate font-black uppercase tracking-tighter" title="{{ $permission->name }}">
                                                {{ $permission->name }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
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
            <x-backend.ui.button variant="neutral" @click="$wire.showDeleteModal = false" class="!rounded-2xl">
                Hủy bỏ
            </x-backend.ui.button>
            <x-backend.ui.button variant="danger" wire:click="executeDelete" class="!rounded-2xl shadow-lg shadow-danger/20">
                Xác nhận xóa
            </x-backend.ui.button>
        </x-backend.layout.confirm-modal>
    </x-slot:modals>
</x-backend.layout.index-page>

