<x-backend.layout.index-page
    title="Quản lý người dùng"
    subtitle="Hệ thống"
    description="Danh sách các tài khoản nhân viên và khách hàng trong hệ thống."
    :total="$users->total()"
    totalLabel="người dùng"
    icon="ti ti-users"
    :selectedCount="count($selectedItems)"
>
    {{-- Header Actions --}}
    <x-slot:headerActions>
        <x-backend.ui.button variant="neutral" :href="route('backend.users.roles')" icon="ti ti-shield-lock">
            Quản lý vai trò
        </x-backend.ui.button>
        <x-backend.ui.button variant="primary" :href="route('backend.users.create')" icon="ti ti-plus">
            Thêm người dùng
        </x-backend.ui.button>
    </x-slot:headerActions>

    {{-- Filters --}}
    <x-slot:filters>
        <div class="relative w-full md:w-64">
            <select wire:model.live="roleFilter" class="w-full px-4 h-12 bg-white/5 border border-white/10 rounded-2xl text-sm font-medium text-text-main focus:ring-4 focus:ring-primary/10 focus:border-primary/50 transition-all outline-none appearance-none">
                <option value="">Tất cả vai trò</option>
                @foreach($roles as $r)
                    <option value="{{ $r->name }}">{{ $r->name }}</option>
                @endforeach
            </select>
            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-text-muted">
                <i class="ti ti-chevron-down"></i>
            </div>
        </div>
        
        <x-backend.ui.button variant="neutral" wire:click="resetFilters" icon="ti ti-refresh" class="!h-12 !rounded-2xl">
            Làm mới
        </x-backend.ui.button>
    </x-slot:filters>

    {{-- Table --}}
    <x-slot:table>
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-white/5 border-b border-white/5 text-left text-[11px] font-black text-text-muted uppercase tracking-[0.15em]">
                    <th class="px-8 py-5 w-10">
                        <x-backend.table.table-checkbox wire:model.live="selectAll" wire:click="toggleSelectAll" />
                    </th>
                    <th class="px-6 py-5">Người dùng</th>
                    <th class="px-6 py-5">Email</th>
                    <th class="px-6 py-5">Vai trò</th>
                    <th class="px-6 py-5 text-center">Trạng thái</th>
                    <th class="px-8 py-5 text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($users as $user)
                    <tr class="hover:bg-white/[0.02] transition-colors group" wire:key="user-{{ $user->id }}">
                        <td class="px-8 py-5">
                            <x-backend.table.table-checkbox value="{{ $user->id }}" wire:model.live="selectedItems" />
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-primary/10 border border-primary/20 flex items-center justify-center text-primary font-bold overflow-hidden shadow-lg shadow-primary/5 transition-transform group-hover:scale-105 duration-300">
                                    @if($user->getFirstMediaUrl('avatar'))
                                        <img src="{{ $user->getFirstMediaUrl('avatar', 'thumb') }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                    @else
                                        <span>{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                    @endif
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-text-main group-hover:text-primary transition-colors">
                                        {{ $user->name }}
                                    </span>
                                    <span class="text-[10px] text-text-muted font-medium mt-0.5 opacity-60 uppercase tracking-wider">ID: #{{ $user->id }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <span class="text-sm font-medium text-text-muted group-hover:text-text-main transition-colors tracking-tight">{{ $user->email }}</span>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex flex-wrap gap-1.5">
                                @forelse($user->roles as $r)
                                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-black bg-white/5 text-text-muted uppercase border border-white/5 tracking-wider transition-colors group-hover:border-primary/20 group-hover:text-text-main">
                                        {{ $r->name }}
                                    </span>
                                @empty
                                    <span class="text-xs text-text-muted italic opacity-50">Không có vai trò</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <x-backend.ui.status-badge :status="$user->is_active" />
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <x-backend.ui.action-icon variant="edit" :href="route('backend.users.edit', $user->id)" title="Chỉnh sửa" icon="ti ti-edit" />
                                @if($user->id !== auth()->id())
                                    <x-backend.ui.action-icon variant="delete" wire:click="confirmDelete({{ $user->id }}, '{{ addslashes($user->name) }}')" title="Xóa" icon="ti ti-trash" />
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-8 py-20 text-center">
                            <x-backend.ui.empty-state 
                                icon="ti ti-users-off" 
                                title="Không tìm thấy người dùng" 
                                description="Hãy thử thay đổi bộ lọc hoặc tạo tài khoản mới." 
                            />
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
        <x-backend.ui.button variant="success" size="sm" wire:click="bulkStatus(1)">
            Kích hoạt
        </x-backend.ui.button>
        <x-backend.ui.button variant="warning" size="sm" wire:click="bulkStatus(0)">
            Tạm khóa
        </x-backend.ui.button>
        <x-backend.ui.button variant="danger" size="sm" wire:click="confirmBulkDelete">
            Xóa vĩnh viễn
        </x-backend.ui.button>
    </x-slot:bulkActions>

    {{-- Modals --}}
    <x-slot:modals>
        <!-- Delete Modal -->
        <x-backend.layout.confirm-modal
            show="$wire.showDeleteModal"
            title="Xác nhận xóa tài khoản"
            message="{{ $isBulkDelete ? 'Bạn có chắc chắn muốn rời bỏ và xóa vĩnh viễn ' . count($selectedItems) . ' tài khoản đã chọn? Dữ liệu này sẽ không thể phục hồi.' : 'Bạn có chắc chắn muốn xóa vĩnh viễn người dùng \'' . $deleteTargetName . '\'? Hành động này sẽ loại bỏ quyền truy cập của họ ngay lập tức.' }}"
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
