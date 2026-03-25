<div class="space-y-6">
    @include('backend.layouts.partials.breadcrumbs', [
        'items' => [
            ['label' => 'Cài đặt hệ thống', 'url' => route('backend.settings.general')],
            ['label' => 'Điều hướng URL'],
        ],
    ])

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-text-main tracking-tight">Điều hướng URL</h1>
            <p class="mt-1 text-sm text-text-muted">Quản lý các thiết lập redirect 301, 302 cho website.</p>
        </div>
        <x-backend.ui.button type="primary" wire:click="resetFields" onclick="document.getElementById('redirect-modal').showModal()">
            <i class="ti ti-plus mr-1"></i> Thêm Redirect
        </x-backend.ui.button>
    </div>

    <div class="bg-white/5 border border-border-glass rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-white/5 text-text-muted uppercase text-[11px] font-bold tracking-widest">
                        <th class="px-6 py-4">URL cũ</th>
                        <th class="px-6 py-4">URL mới</th>
                        <th class="px-6 py-4">Mã lỗi</th>
                        <th class="px-6 py-4 text-center">Lượt truy cập</th>
                        <th class="px-6 py-4 text-center">Trạng thái</th>
                        <th class="px-6 py-4 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-glass">
                    @forelse ($redirects as $redirect)
                        <tr class="hover:bg-white/5 transition-colors group">
                            <td class="px-6 py-4 font-medium text-text-main">
                                <span class="text-text-muted mr-1">/</span>{{ ltrim($redirect->old_url, '/') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-text-muted mr-1">/</span>{{ ltrim($redirect->new_url, '/') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $redirect->status_code == 301 ? 'bg-success/20 text-success' : 'bg-warning/20 text-warning' }}">
                                    {{ $redirect->status_code }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-text-muted">
                                {{ number_format($redirect->hit_count) }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button wire:click="toggleStatus({{ $redirect->id }})" class="relative inline-flex items-center cursor-pointer">
                                    <div class="w-9 h-5 bg-white/20 rounded-full {{ $redirect->is_active ? 'bg-success' : '' }} transition-colors"></div>
                                    <div class="absolute left-0.5 top-0.5 w-4 h-4 bg-white rounded-full transition-transform {{ $redirect->is_active ? 'translate-x-4' : '' }}"></div>
                                </button>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <button wire:click="edit({{ $redirect->id }})" onclick="document.getElementById('redirect-modal').showModal()" class="p-1.5 text-text-muted hover:text-primary transition-colors">
                                    <i class="ti ti-edit text-lg"></i>
                                </button>
                                <button wire:click="delete({{ $redirect->id }})" wire:confirm="Bạn có chắc chắn muốn xóa redirect này?" class="p-1.5 text-text-muted hover:text-danger transition-colors">
                                    <i class="ti ti-trash text-lg"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-text-muted">
                                <div class="flex flex-col items-center">
                                    <i class="ti ti-arrow-move text-4xl mb-2 opacity-20"></i>
                                    <p>Chưa có điều hướng nào được thiết lập.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($redirects->hasPages())
            <div class="p-6 border-t border-border-glass">
                {{ $redirects->links() }}
            </div>
        @endif
    </div>

    {{-- Modal Thêm/Sửa --}}
    <dialog id="redirect-modal" class="modal bg-transparent p-0 backdrop:bg-black/60 backdrop:backdrop-blur-sm" wire:ignore.self>
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div class="bg-background-dark border border-border-glass rounded-3xl w-full max-w-lg shadow-2xl overflow-hidden">
                <div class="px-8 py-6 border-b border-border-glass flex items-center justify-between">
                    <h3 class="text-xl font-black text-text-main tracking-tight">
                        {{ $isEdit ? 'Chỉnh sửa Redirect' : 'Thêm Redirect mới' }}
                    </h3>
                    <button onclick="document.getElementById('redirect-modal').close()" class="text-text-muted hover:text-text-main">
                        <i class="ti ti-x text-2xl"></i>
                    </button>
                </div>
                <form wire:submit.prevent="save" class="p-8 space-y-6">
                    <div class="space-y-4">
                        <x-backend.forms.input wire:model="old_url" name="old_url" label="URL cũ (đường dẫn)" placeholder="ví dụ: san-pham-cu" required />
                        <x-backend.forms.input wire:model="new_url" name="new_url" label="URL mới (đường dẫn)" placeholder="ví dụ: san-pham-moi" required />
                        
                        <div class="grid grid-cols-2 gap-4">
                            <x-backend.forms.select wire:model="status_code" name="status_code" label="Mã trạng thái"
                                :options="[
                                    '301' => '301 Moved Permanently',
                                    '302' => '302 Found (Temporary)',
                                    '307' => '307 Temporary Redirect',
                                ]" required />
                            
                            <div class="flex items-end pb-2">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" wire:model="is_active" class="sr-only peer">
                                    <div class="w-11 h-6 bg-white/20 rounded-full peer-checked:bg-success transition-colors"></div>
                                    <div class="absolute left-0.5 w-5 h-5 bg-white rounded-full transition-transform peer-checked:translate-x-5"></div>
                                    <span class="ml-3 text-sm font-semibold text-text-main">Kích hoạt</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-4">
                        <button type="button" onclick="document.getElementById('redirect-modal').close()" class="px-6 py-2.5 rounded-xl text-sm font-bold text-text-muted hover:text-text-main transition-colors">
                            Hủy
                        </button>
                        <x-backend.ui.button type="primary" htmlType="submit">
                            {{ $isEdit ? 'Cập nhật' : 'Thêm mới' }}
                        </x-backend.ui.button>
                    </div>
                </form>
            </div>
        </div>
    </dialog>
</div>


