<x-backend.layout.index-page
    title="Hộp thư liên hệ"
    subtitle="Tin nhắn từ khách hàng"
    description="Xem nội dung, thông tin liên hệ và trạng thái phản hồi của khách hàng."
    :total="$contacts->total()"
    totalLabel="liên hệ"
    searchPlaceholder="Tìm tên, email hoặc SĐT..."
    :selectedCount="count($selectedItems)"
    icon="ti ti-mail"
>
    {{-- Header Actions --}}
    <x-slot:headerActions>
        <x-backend.ui.button
            variant="neutral"
            wire:click="resetFilters"
            icon="ti ti-rotate-clockwise"
        >
            Làm mới
        </x-backend.ui.button>
    </x-slot:headerActions>

    {{-- Filters --}}
    <x-slot:filters>
        <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-hide">
            @foreach(['' => 'Tất cả', 'new' => 'Mới', 'read' => 'Đã đọc', 'replied' => 'Đã phản hồi'] as $key => $label)
                <button
                    wire:click="$set('statusFilter', '{{ $key }}')"
                    class="px-4 py-2 rounded-xl transition-all whitespace-nowrap text-xs font-bold {{ (string)$statusFilter === (string)$key ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'bg-white/5 text-text-muted hover:bg-white/10 hover:text-text-main border border-white/5' }}"
                >
                    {{ $label }}
                </button>
            @endforeach
        </div>
    </x-slot:filters>

    {{-- Table --}}
    <x-slot:table>
        <table class="w-full border-collapse">
            <thead>
                <tr class="text-left text-[11px] font-black text-text-muted uppercase tracking-[0.15em] border-b border-white/5 bg-white/[0.02]">
                    <th class="px-8 py-5 w-10">
                        <x-backend.table.table-checkbox wire:model.live="selectAll" wire:click="toggleSelectAll" />
                    </th>
                    <th class="px-6 py-5 min-w-[200px]">Khách hàng</th>
                    <th class="px-6 py-5 min-w-[250px]">Chủ đề / Nội dung</th>
                    <th class="px-6 py-5 text-center">Ngày gửi</th>
                    <th class="px-6 py-5 text-center">Trạng thái</th>
                    <th class="px-8 py-5 text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($contacts as $contact)
                    <tr class="group hover:bg-white/[0.02]transition-colors" wire:key="contact-{{ $contact->id }}">
                        <td class="px-8 py-5">
                            <x-backend.table.table-checkbox value="{{ $contact->id }}" wire:model.live="selectedItems" />
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-text-main group-hover:text-primary transition-colors">
                                    {{ $contact->name }}
                                </span>
                                <div class="flex flex-col text-[10px] font-medium text-text-muted mt-0.5 opacity-60 uppercase tracking-wider gap-0.5">
                                    <span class="flex items-center gap-1.5"><i class="ti ti-mail text-xs"></i>{{ $contact->email }}</span>
                                    <span class="flex items-center gap-1.5"><i class="ti ti-phone text-xs"></i>{{ $contact->phone }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-text-main line-clamp-1">
                                    {{ $contact->subject }}
                                </span>
                                <p class="text-[11px] text-text-muted mt-1 leading-relaxed line-clamp-2">
                                    {{ $contact->message }}
                                </p>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-center whitespace-nowrap">
                            <div class="flex flex-col uppercase tracking-wider">
                                <span class="text-[11px] font-bold text-text-main">
                                    {{ $contact->created_at->format('d/m/Y') }}
                                </span>
                                <span class="text-[10px] text-text-muted opacity-60">
                                    {{ $contact->created_at->format('H:i') }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-center">
                            @php
                                $statusConfig = [
                                    'new' => ['label' => 'Mới', 'variant' => 'success'],
                                    'read' => ['label' => 'Đã đọc', 'variant' => 'primary'],
                                    'replied' => ['label' => 'Đã phản hồi', 'variant' => 'neutral'],
                                ];
                                $config = $statusConfig[$contact->status] ?? $statusConfig['new'];
                            @endphp
                            <x-backend.ui.status-badge :variant="$config['variant']" :label="$config['label']" />
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <x-backend.ui.action-icon
                                    variant="view"
                                    :href="route('backend.contacts.show', $contact->id)"
                                    title="Xem chi tiết"
                                    icon="ti ti-eye"
                                />
                                <x-backend.ui.action-icon
                                    variant="delete"
                                    wire:click="confirmDelete({{ $contact->id }}, '{{ addslashes($contact->name) }}')"
                                    title="Xóa"
                                    icon="ti ti-trash"
                                />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-8 py-20 text-center">
                            <x-backend.ui.empty-state 
                                icon="ti ti-mail-off" 
                                title="Hộp thư trống" 
                                description="Không có tin nhắn liên hệ nào được tìm thấy." 
                            />
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-slot:table>

    {{-- Pagination --}}
    <x-slot:pagination>
        {{ $contacts->links() }}
    </x-slot:pagination>

    {{-- Bulk Actions --}}
    <x-slot:bulkActions>
        <x-backend.ui.button variant="primary" size="sm" wire:click="bulkStatus('read')" icon="ti ti-mail-opened">
            Đánh dấu đã đọc
        </x-backend.ui.button>
        <x-backend.ui.button variant="danger" size="sm" wire:click="confirmBulkDelete" icon="ti ti-trash">
            Xóa vĩnh viễn
        </x-backend.ui.button>
    </x-slot:bulkActions>

    {{-- Modals --}}
    <x-slot:modals>
        <x-backend.layout.confirm-modal
            show="$wire.showDeleteModal"
            title="Xác nhận yêu cầu xóa"
            message="Hệ thống sẽ thực hiện xóa vĩnh viễn {{ $isBulkDelete ? 'các yêu cầu liên hệ đã chọn' : 'yêu cầu liên hệ này' }}. Mọi dữ liệu liên quan sẽ bị loại bỏ và không thể khôi phục."
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
