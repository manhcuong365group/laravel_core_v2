<x-backend.layout.index-page
    title="Người đăng ký bản tin"
    subtitle="Quản lý"
    description="Danh sách các email đăng ký nhận bản tin marketing."
    :total="$subscribers->total()"
    totalLabel="người đăng ký"
    icon="ti ti-mail-opened"
    :selectedCount="count($selectedItems)"
>
    {{-- Header Actions --}}
    <x-slot:headerActions>
        <x-backend.ui.button variant="neutral" wire:click="export" icon="ti ti-download">
            Xuất CSV
        </x-backend.ui.button>
        <x-backend.ui.button variant="primary" wire:click="openCreateModal" icon="ti ti-plus">
            Thêm người đăng ký
        </x-backend.ui.button>
    </x-slot:headerActions>

    {{-- Filters --}}
    <x-slot:filters>
        <select wire:model.live="status" class="px-4 h-12 bg-white/5 border border-white/10 rounded-2xl text-sm font-medium text-text-main focus:ring-4 focus:ring-primary/10 focus:border-primary/50 transition-all outline-none">
            <option value="">Tất cả trạng thái</option>
            @foreach($statuses as $key => $label)
                <option value="{{ $key }}">{{ $label }}</option>
            @endforeach
        </select>
        
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
                    <th class="px-6 py-5">Thông tin người nhận</th>
                    <th class="px-6 py-5">Nguồn đăng ký</th>
                    <th class="px-6 py-5">Ngày đăng ký</th>
                    <th class="px-6 py-5">Trạng thái</th>
                    <th class="px-8 py-5 text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($subscribers as $subscriber)
                    <tr class="hover:bg-white/[0.02] transition-colors group" wire:key="subscriber-{{ $subscriber->id }}">
                        <td class="px-8 py-5">
                            <x-backend.table.table-checkbox value="{{ $subscriber->id }}" wire:model.live="selectedItems" />
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-text-main group-hover:text-primary transition-colors">
                                    {{ $subscriber->name ?: 'Không có tên' }}
                                </span>
                                <span class="text-xs text-text-muted mt-0.5">{{ $subscriber->email }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="inline-flex items-center px-2.5 py-1 rounded-lg bg-white/5 border border-white/10 text-xs text-text-muted">
                                {{ $subscriber->source ?: 'N/A' }}
                            </div>
                        </td>
                        <td class="px-6 py-5 text-sm text-text-muted">
                            {{ $subscriber->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-5">
                            <x-backend.ui.status-badge :status="$subscriber->status" />
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <x-backend.ui.action-icon variant="edit" wire:click="openEditModal({{ $subscriber->id }})" title="Chỉnh sửa" icon="ti ti-edit" />
                                <x-backend.ui.action-icon variant="delete" wire:click="confirmDelete({{ $subscriber->id }}, '{{ addslashes($subscriber->email) }}')" title="Xóa" icon="ti ti-trash" />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-8 py-20 text-center">
                            <x-backend.ui.empty-state 
                                icon="ti ti-mail-off" 
                                title="Không tìm thấy người đăng ký" 
                                description="Hãy thử thay đổi bộ lọc hoặc thêm người đăng ký mới." 
                            />
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-slot:table>

    {{-- Pagination --}}
    <x-slot:pagination>
        {{ $subscribers->links() }}
    </x-slot:pagination>

    {{-- Bulk Actions --}}
    <x-slot:bulkActions>
        <x-backend.ui.button variant="success" size="sm" wire:click="bulkStatus('subscribed')">
            Đánh dấu đã đăng ký
        </x-backend.ui.button>
        <x-backend.ui.button variant="warning" size="sm" wire:click="bulkStatus('unsubscribed')">
            Đánh dấu đã hủy
        </x-backend.ui.button>
        <x-backend.ui.button variant="danger" size="sm" wire:click="confirmBulkDelete">
            Xóa vĩnh viễn
        </x-backend.ui.button>
    </x-slot:bulkActions>

    {{-- Modals --}}
    <x-slot:modals>
        <!-- Create/Edit Modal -->
        <x-backend.modal show="showEditModal" title="{{ $subscriberId ? 'Cập nhật người đăng ký' : 'Thêm người đăng ký mới' }}">
            <form wire:submit="saveSubscriber" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2 col-span-2 md:col-span-1">
                        <label class="text-xs font-black text-text-muted uppercase tracking-wider mb-2 block">Họ và tên</label>
                        <input type="text" wire:model="formData.name" placeholder="Nhập họ tên..."
                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-2xl text-sm text-text-main focus:ring-4 focus:ring-primary/10 focus:border-primary/50 transition-all outline-none">
                        @error('formData.name') <span class="text-danger text-xs italic">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="space-y-2 col-span-2 md:col-span-1">
                        <label class="text-xs font-black text-text-muted uppercase tracking-wider mb-2 block">Email nhận tin <span class="text-primary">*</span></label>
                        <input type="email" wire:model="formData.email" placeholder="Nhập địa chỉ email..."
                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-2xl text-sm text-text-main focus:ring-4 focus:ring-primary/10 focus:border-primary/50 transition-all outline-none">
                        @error('formData.email') <span class="text-danger text-xs italic">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="space-y-2 col-span-2 md:col-span-1">
                        <label class="text-xs font-black text-text-muted uppercase tracking-wider mb-2 block">Trạng thái đăng ký</label>
                        <select wire:model="formData.status"
                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-2xl text-sm text-text-main focus:ring-4 focus:ring-primary/10 focus:border-primary/50 transition-all outline-none appearance-none">
                            @foreach($statuses as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('formData.status') <span class="text-danger text-xs italic">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="space-y-2 col-span-2 md:col-span-1">
                        <label class="text-xs font-black text-text-muted uppercase tracking-wider mb-2 block">Nguồn đăng ký</label>
                        <input type="text" wire:model="formData.source" placeholder="Vd: Landing page, Sidebar..."
                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-2xl text-sm text-text-main focus:ring-4 focus:ring-primary/10 focus:border-primary/50 transition-all outline-none">
                        @error('formData.source') <span class="text-danger text-xs italic">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <x-backend.ui.button type="button" variant="neutral" @click="showEditModal = false">
                        Hủy bỏ
                    </x-backend.ui.button>
                    <x-backend.ui.button type="submit" variant="primary">
                        {{ $subscriberId ? 'Cập nhật ngay' : 'Thêm mới ngay' }}
                    </x-backend.ui.button>
                </div>
            </form>
        </x-backend.modal>

        <!-- Delete Modal -->
        <x-backend.layout.confirm-modal
            show="$wire.showDeleteModal"
            title="Xác nhận xóa người nhận"
            message="Bạn có chắc chắn muốn xóa vĩnh viễn người nhận '{{ $deleteTargetName }}'? Dữ liệu này sẽ không thể khôi phục."
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
