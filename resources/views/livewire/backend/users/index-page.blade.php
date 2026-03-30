<x-backend.layout.index-page
    title="Quản lý nhân sự"
    subtitle="User Directory"
    description="Kiểm soát quyền truy cập và thông tin cá nhân của các thành viên trong hệ thống. Đảm bảo bảo mật và phân quyền chính xác."
    :total="$users->total()"
    totalLabel="người dùng"
    searchPlaceholder="Tìm tên, email hoặc vai trò..."
    :selectedCount="count($selectedItems)"
    icon="ti ti-users"
>
    {{-- Header Actions --}}
    <x-slot:headerActions>
        <div class="flex items-center gap-3">
            <x-backend.ui.button
                variant="neutral"
                :href="route('backend.users.roles')"
                class="!rounded-2xl h-11 px-6 bg-white/5 border-white/10 hover:bg-white/10"
            >
                <i class="ti ti-shield-lock text-lg mr-2 opacity-60"></i>
                <span class="font-bold">Phân quyền</span>
            </x-backend.ui.button>

            <x-backend.ui.button
                type="primary"
                :href="route('backend.users.create')"
                class="!rounded-2xl shadow-xl shadow-primary/30 group h-11 px-6"
            >
                <div class="flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-white/20 flex items-center justify-center group-hover:rotate-90 transition-all duration-500">
                        <i class="ti ti-user-plus text-sm"></i>
                    </span>
                    <span class="font-bold tracking-tight">Thêm thành viên</span>
                </div>
            </x-backend.ui.button>
        </div>
    </x-slot:headerActions>

    {{-- Filters --}}
    <x-slot:filters>
        <x-backend.ui.dropdown align="left" width="64">
            <x-slot name="trigger">
                <button class="flex items-center gap-3 px-4 py-2.5 rounded-2xl bg-white/[0.03] border border-white/10 hover:border-primary/30 text-xs font-black text-text-muted hover:text-text-main transition-all duration-300">
                    <i class="ti ti-shield-check text-base text-primary/70"></i>
                    <span>{{ $roleFilter ?: 'Lọc theo vai trò' }}</span>
                    <i class="ti ti-chevron-down text-[10px] ml-1"></i>
                </button>
            </x-slot>
            <x-slot name="content">
                <div class="max-h-80 overflow-y-auto no-scrollbar py-2">
                    <button wire:click="$set('roleFilter', '')" class="w-full text-left px-5 py-3 text-xs font-black uppercase tracking-widest border-l-4 border-transparent text-text-muted hover:bg-white/5 hover:text-text-main transition-all">Tất cả vai trò</button>
                    @foreach($roles as $r)
                        <button 
                            wire:click="$set('roleFilter', '{{ $r->name }}')" 
                            class="w-full text-left px-5 py-3 text-xs font-black uppercase tracking-widest border-l-4 transition-all {{ $roleFilter === $r->name ? 'border-primary text-primary bg-primary/5' : 'border-transparent text-text-muted hover:bg-white/5 hover:text-text-main' }}"
                        >
                            {{ $r->name }}
                        </button>
                    @endforeach
                </div>
            </x-slot>
        </x-backend.ui.dropdown>
    </x-slot:filters>

    {{-- Table --}}
    <x-slot:table>
        <table class="w-full border-collapse">
            <thead>
                <tr class="text-left border-b border-white/5 bg-white/[0.01]">
                    <th class="pl-8 pr-4 py-6 w-16 text-center">
                        <x-backend.table.table-checkbox wire:click="toggleSelectAll" :checked="$selectAll" class="scale-110" />
                    </th>
                    <x-backend.table.table-th sort="name" :sortField="$sortField" :sortDirection="$sortDirection" class="px-6 py-6 min-w-[300px]">
                        Danh tính thành viên
                    </x-backend.table.table-th>
                    <th class="px-6 py-6 text-[11px] font-black text-text-muted uppercase tracking-[0.2em]">Địa chỉ Email</th>
                    <th class="px-6 py-6 text-[11px] font-black text-text-muted uppercase tracking-[0.2em]">Quyền hạn</th>
                    <th class="px-6 py-6 text-[11px] font-black text-text-muted uppercase tracking-[0.2em] text-center">Trạng thái</th>
                    <th class="pr-8 pl-4 py-6 text-[11px] font-black text-text-muted uppercase tracking-[0.2em] text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($users as $user)
                    <tr class="group hover:bg-white/[0.03] transition-all duration-500" wire:key="user-{{ $user->id }}">
                        <td class="pl-8 pr-4 py-6 text-center border-b border-white/5">
                            <x-backend.table.table-checkbox value="{{ $user->id }}" wire:model.live="selectedItems" class="scale-110" />
                        </td>
                        <td class="px-6 py-6 border-b border-white/5">
                            <div class="flex items-center gap-5">
                                <div class="w-12 h-12 rounded-2xl overflow-hidden bg-gradient-to-br from-primary/20 to-primary/5 border border-primary/20 group-hover:border-primary/40 transition-all duration-500 shadow-lg flex-shrink-0 flex items-center justify-center text-primary font-black text-lg">
                                    @if($user->getFirstMediaUrl('avatar'))
                                        <img src="{{ $user->getFirstMediaUrl('avatar', 'thumb') }}" alt="{{ $user->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                    @else
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    @endif
                                </div>
                                <div class="flex flex-col space-y-1">
                                    <a href="{{ route('backend.users.edit', $user->id) }}" class="text-[15px] font-black text-text-main hover:text-primary transition-all duration-300 tracking-tight">
                                        {{ $user->name }}
                                    </a>
                                    <span class="text-[10px] font-black text-text-muted/40 uppercase tracking-[0.2em]">ID: #USR-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-6 border-b border-white/5 text-sm font-medium text-text-muted/80">
                            {{ $user->email }}
                        </td>
                        <td class="px-6 py-6 border-b border-white/5">
                            <div class="flex flex-wrap gap-1.5">
                                @forelse($user->roles as $r)
                                    <span class="px-2.5 py-1 rounded-lg bg-white/5 border border-white/5 text-text-main text-[9px] font-black uppercase tracking-wider group-hover:border-primary/20 transition-all">
                                        {{ $r->name }}
                                    </span>
                                @empty
                                    <span class="text-[10px] font-black text-text-muted/30 uppercase italic">Chưa cấp quyền</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-6 py-6 border-b border-white/5 text-center">
                            <x-admin.status-badge 
                                :status="$user->is_active ? 'success' : 'neutral'" 
                                :label="$user->is_active ? 'Hoạt động' : 'Đã khóa'" 
                            />
                        </td>
                        <td class="pr-8 pl-4 py-6 border-b border-white/5 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <x-backend.ui.action-icon
                                    variant="edit"
                                    :href="route('backend.users.edit', $user->id)"
                                    title="Hiệu chỉnh"
                                    class="text-blue-500/70 hover:text-blue-500"
                                />
                                @if($user->id !== auth()->id())
                                    <x-backend.ui.action-icon
                                        variant="delete"
                                        wire:click="confirmDelete({{ $user->id }}, '{{ addslashes($user->name) }}')"
                                        title="Xóa tài khoản"
                                        class="text-rose-500/70 hover:text-rose-500"
                                    />
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-8 py-32 text-center">
                            <div class="flex flex-col items-center justify-center gap-6">
                                <div class="w-24 h-24 rounded-[2.5rem] bg-gradient-to-br from-white/5 to-white/[0.02] border border-white/10 flex items-center justify-center animate-pulse shadow-inner">
                                    <i class="ti ti-users-off text-5xl text-text-muted/20"></i>
                                </div>
                                <div class="space-y-2">
                                    <p class="text-2xl font-black text-text-main tracking-tight">Không có thành viên</p>
                                    <p class="text-sm text-text-muted max-w-[320px] mx-auto leading-relaxed">Hệ thống không tìm thấy tài khoản nào khớp với bộ lọc. Hãy kiểm tra lại từ khóa tìm kiếm.</p>
                                </div>
                                <button wire:click="resetFilters" class="h-10 px-8 rounded-xl bg-white/5 border border-white/10 text-primary text-[11px] font-black uppercase tracking-[0.2em] hover:bg-primary hover:text-white transition-all">
                                    Làm mới bộ lọc
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-slot:table>

    {{-- Pagination --}}
    <x-slot:pagination>
        {{ $users->links() }}
    </x-slot:pagination>

    {{-- Bulk Actions --}}
    <x-slot:bulkActions>
        <div class="flex items-center gap-2">
            <x-backend.ui.button 
                type="success" 
                wire:click="bulkStatus(1)" 
                size="sm"
                class="!rounded-xl px-4 py-2 border-none bg-emerald-500 hover:bg-emerald-600 shadow-lg shadow-emerald-500/20"
            >
                <i class="ti ti-user-check mr-2 text-white"></i>
                <span class="font-black uppercase text-[10px] tracking-widest text-white">Mở khóa loạt</span>
            </x-backend.ui.button>

            <x-backend.ui.button 
                type="outline" 
                wire:click="bulkStatus(0)" 
                size="sm"
                class="!rounded-xl px-4 py-2 border-white/10 hover:border-white/20 bg-white/5"
            >
                <i class="ti ti-user-x mr-2 text-white"></i>
                <span class="font-black uppercase text-[10px] tracking-widest text-white">Tạm khóa loạt</span>
            </x-backend.ui.button>

            <div class="w-px h-6 bg-white/10 mx-1"></div>

            <x-backend.ui.button 
                size="sm"
                wire:click="confirmBulkDelete" 
                class="!rounded-xl px-4 py-2 border-none bg-rose-500 hover:bg-rose-600 shadow-lg shadow-rose-500/20"
            >
                <i class="ti ti-trash-x mr-2 text-white"></i>
                <span class="font-black uppercase text-[10px] tracking-widest text-white">Xóa vĩnh viễn</span>
            </x-backend.ui.button>
        </div>
    </x-slot:bulkActions>

    {{-- Modals --}}
    <x-slot:modals>
        <x-backend.layout.confirm-modal
            show="showDeleteModal"
            title="Xử lý tài khoản người dùng"
            message="Việc xóa tài khoản là hành động không thể khôi phục. Người dùng sẽ mất quyền truy cập vào hệ thống ngay lập tức. Bạn có chắc chắn muốn thực thi?"
        >
            <x-backend.ui.button
                type="outline"
                size="md"
                wire:click="$set('showDeleteModal', false)"
                class="h-12 px-8 !rounded-2xl border-white/10 font-bold"
            >
                Hủy yêu cầu
            </x-backend.ui.button>
            <x-backend.ui.button
                type="danger"
                size="md"
                wire:click="executeDelete"
                class="h-12 px-8 !rounded-2xl bg-rose-500 hover:bg-rose-600 shadow-2xl shadow-rose-500/40 font-bold text-white"
            >
                Xác nhận thực thi
            </x-backend.ui.button>
        </x-backend.layout.confirm-modal>
    </x-slot:modals>
</x-backend.layout.index-page>
