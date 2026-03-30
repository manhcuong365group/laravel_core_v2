<x-backend.layout.index-page
    title="Thương hiệu"
    subtitle="Brand Partners"
    description="Quản lý danh sách các thương hiệu và đối tác. Tăng cường uy tín sản phẩm thông qua việc nhận diện thương hiệu rõ ràng."
    :total="$brands->total()"
    totalLabel="thương hiệu"
    searchPlaceholder="Tìm tên thương hiệu, slug..."
    :selectedCount="count($selectedItems)"
    icon="ti ti-trademark"
>
    {{-- Header Actions --}}
    <x-slot:headerActions>
        <x-backend.ui.button
            type="primary"
            :href="route('backend.brands.create')"
            class="!rounded-2xl shadow-xl shadow-primary/30 group h-11 px-6"
        >
            <div class="flex items-center gap-2">
                <span class="w-6 h-6 rounded-lg bg-white/20 flex items-center justify-center group-hover:rotate-90 transition-all duration-500">
                    <i class="ti ti-plus text-sm"></i>
                </span>
                <span class="font-bold tracking-tight">Thêm thương hiệu</span>
            </div>
        </x-backend.ui.button>
    </x-slot:headerActions>

    {{-- Filters --}}
    <x-slot:filters>
        <div class="flex items-center p-1 bg-white/[0.03] border border-white/5 rounded-2xl">
            @foreach(['' => 'Tất cả', '1' => 'Hoạt động', '0' => 'Đang ẩn'] as $key => $label)
                <button
                    wire:click="$set('statusFilter', '{{ $key }}')"
                    class="px-4 py-2 rounded-xl transition-all duration-300 text-xs font-black uppercase tracking-widest {{ (string)$statusFilter === (string)$key ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-text-muted hover:text-text-main hover:bg-white/5' }}"
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
                <tr class="text-left border-b border-white/5 bg-white/[0.01]">
                    <th class="pl-8 pr-4 py-6 w-16 text-center">
                        <x-backend.table.table-checkbox wire:click="toggleSelectAll" :checked="$selectAll" class="scale-110" />
                    </th>
                    <x-backend.table.table-th sort="name" :sortField="$sortField" :sortDirection="$sortDirection" class="px-6 py-6 min-w-[300px]">
                        Nhận diện & Tên thương hiệu
                    </x-backend.table.table-th>
                    <th class="px-6 py-6 text-[11px] font-black text-text-muted uppercase tracking-[0.2em]">Website</th>
                    <th class="px-6 py-6 text-[11px] font-black text-text-muted uppercase tracking-[0.2em] text-center">Thứ tự</th>
                    <th class="px-6 py-6 text-[11px] font-black text-text-muted uppercase tracking-[0.2em] text-center">Trạng thái</th>
                    <th class="pr-8 pl-4 py-6 text-[11px] font-black text-text-muted uppercase tracking-[0.2em] text-right">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($brands as $brand)
                    <tr class="group hover:bg-white/[0.03] transition-all duration-500" wire:key="brand-{{ $brand->id }}">
                        <td class="pl-8 pr-4 py-6 text-center border-b border-white/5">
                            <x-backend.table.table-checkbox value="{{ $brand->id }}" wire:model.live="selectedItems" class="scale-110" />
                        </td>
                        <td class="px-6 py-6 border-b border-white/5">
                            <div class="flex items-center gap-5">
                                <div class="w-14 h-14 rounded-2xl overflow-hidden bg-white p-2 border border-white/10 group-hover:border-primary/40 transition-all duration-500 shadow-md flex-shrink-0 flex items-center justify-center">
                                    @if($brand->getFirstMediaUrl('logo'))
                                        <img src="{{ $brand->getFirstMediaUrl('logo', 'thumb') }}" alt="{{ $brand->name }}" class="max-w-full max-h-full object-contain group-hover:scale-110 transition-transform duration-700">
                                    @else
                                        <i class="ti ti-photo text-xl text-slate-300"></i>
                                    @endif
                                </div>
                                <div class="flex flex-col space-y-1">
                                    <a href="{{ route('backend.brands.edit', $brand->id) }}" class="text-[15px] font-black text-text-main hover:text-primary transition-all duration-300 tracking-tight">
                                        {{ $brand->name }}
                                    </a>
                                    <span class="text-[10px] font-mono text-text-muted/60 lowercase tracking-wider italic">/{{ $brand->slug }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-6 border-b border-white/5">
                            @if($brand->website)
                                <a href="{{ $brand->website }}" target="_blank" class="flex items-center gap-1.5 text-xs font-bold text-text-muted hover:text-primary transition-colors group/link">
                                    <span class="max-w-[180px] truncate">{{ str_replace(['http://', 'https://'], '', $brand->website) }}</span>
                                    <i class="ti ti-external-link text-[10px] opacity-40 group-hover/link:opacity-100"></i>
                                </a>
                            @else
                                <span class="text-[10px] font-black text-text-muted/30 uppercase italic">N/A</span>
                            @endif
                        </td>
                        <td class="px-6 py-6 border-b border-white/5 text-center">
                            <div class="inline-flex justify-center">
                                <x-backend.ui.editable-field
                                    wire:key="brand-order-{{ $brand->id }}"
                                    :value="$brand->order"
                                    field="order"
                                    :id="$brand->id"
                                    class="text-xs font-black text-text-main text-center bg-white/5 px-2 py-0.5 rounded-lg border border-transparent hover:border-white/10"
                                />
                            </div>
                        </td>
                        <td class="px-6 py-6 border-b border-white/5 text-center">
                            <x-admin.status-badge 
                                :status="$brand->is_active ? 'success' : 'neutral'" 
                                :label="$brand->is_active ? 'Công khai' : 'Đang ẩn'" 
                            />
                        </td>
                        <td class="pr-8 pl-4 py-6 border-b border-white/5 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <x-backend.ui.action-icon
                                    variant="edit"
                                    :href="route('backend.brands.edit', $brand->id)"
                                    title="Hiệu chỉnh"
                                    class="text-blue-500/70 hover:text-blue-500"
                                />
                                <x-backend.ui.action-icon
                                    variant="delete"
                                    wire:click="confirmDelete({{ $brand->id }}, '{{ addslashes($brand->name) }}')"
                                    title="Xóa bỏ"
                                    class="text-rose-500/70 hover:text-rose-500"
                                />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-8 py-32 text-center">
                            <div class="flex flex-col items-center justify-center gap-6">
                                <div class="w-24 h-24 rounded-[2.5rem] bg-gradient-to-br from-white/5 to-white/[0.02] border border-white/10 flex items-center justify-center animate-pulse shadow-inner">
                                    <i class="ti ti-building-store text-5xl text-text-muted/20"></i>
                                </div>
                                <div class="space-y-2">
                                    <p class="text-2xl font-black text-text-main tracking-tight">Chưa có thương hiệu</p>
                                    <p class="text-sm text-text-muted max-w-[320px] mx-auto leading-relaxed">Hệ thống chưa tìm thấy dữ liệu thương hiệu nào. Hãy thêm mới các đối tác cung cấp sản phẩm của bạn.</p>
                                </div>
                                <button wire:click="$set('search', '')" class="h-10 px-8 rounded-xl bg-white/5 border border-white/10 text-primary text-[11px] font-black uppercase tracking-[0.2em] hover:bg-primary hover:text-white transition-all">
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
        @if ($brands->hasPages())
            {{ $brands->links() }}
        @endif
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
                <i class="ti ti-eye mr-2 text-white"></i>
                <span class="font-black uppercase text-[10px] tracking-widest text-white">Hiện loạt</span>
            </x-backend.ui.button>

            <x-backend.ui.button 
                type="outline" 
                wire:click="bulkStatus(0)" 
                size="sm"
                class="!rounded-xl px-4 py-2 border-white/10 hover:border-white/20 bg-white/5"
            >
                <i class="ti ti-eye-off mr-2"></i>
                <span class="font-black uppercase text-[10px] tracking-widest">Ẩn loạt</span>
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
            title="Xử lý dữ liệu thương hiệu"
            message="Việc xóa thương hiệu sẽ ảnh hưởng đến việc phân loại sản phẩm. Các sản phẩm thuộc thương hiệu này sẽ trở thành 'Không có thương hiệu'. Bạn có chắc chắn muốn tiếp tục?"
        >
            <x-backend.ui.button
                type="outline"
                size="md"
                wire:click="$set('showDeleteModal', false)"
                class="h-12 px-8 !rounded-2xl border-white/10 font-bold"
            >
                Hủy bỏ
            </x-backend.ui.button>
            <x-backend.ui.button
                type="danger"
                size="md"
                wire:click="executeDelete"
                class="h-12 px-8 !rounded-2xl bg-rose-500 hover:bg-rose-600 shadow-2xl shadow-rose-500/40 font-bold text-white"
            >
                Xác nhận xóa
            </x-backend.ui.button>
        </x-backend.layout.confirm-modal>
    </x-slot:modals>
</x-backend.layout.index-page>
