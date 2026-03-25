<div x-data="{
    dragging: false,
}" class="space-y-6">

    {{-- Flash message --}}
    @if (session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition
            class="bg-success/10 border border-success/20 text-success p-4 rounded-xl flex items-center gap-2">
            <i class="ti ti-check"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-text-main">
                <i class="ti ti-layout-dashboard text-primary"></i>
                {{ $pageId ? 'Chỉnh sửa Layout' : 'Tạo Layout mới' }}
            </h2>
            <p class="text-text-muted text-sm mt-1">Kéo thả và tùy chỉnh các khối để xây dựng giao diện trang</p>
        </div>
        <div class="flex items-center gap-3">
            <button wire:click="$toggle('showPreview')"
                class="px-4 py-2.5 bg-bg-surface border border-border-glass text-text-muted font-bold rounded-xl hover:bg-white/10 transition-all flex items-center gap-2">
                <i class="ti ti-eye"></i> Xem trước
            </button>
            <button wire:click="save"
                class="px-6 py-2.5 bg-linear-to-r from-primary to-accent text-white font-bold rounded-xl hover:shadow-lg hover:shadow-primary/25 hover:scale-[1.02] transition-all flex items-center gap-2">
                <i class="ti ti-device-floppy"></i> Lưu Layout
            </button>
        </div>
    </div>

    {{-- Page Info Card --}}
    <div class="glass-card p-5">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-bold text-text-muted mb-1.5">Tiêu đề trang *</label>
                <input type="text" wire:model.blur="pageTitle" placeholder="Nhập tiêu đề trang..."
                    class="w-full px-4 py-2.5 rounded-xl border border-border-glass bg-bg-surface text-text-main placeholder-text-muted/50 focus:ring-2 focus:ring-primary/50 focus:border-primary/50 transition-all">
                @error('pageTitle')
                    <span class="text-danger text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-text-muted mb-1.5">Slug (URL)</label>
                <input type="text" wire:model.blur="pageSlug" placeholder="Tự động tạo từ tiêu đề..."
                    class="w-full px-4 py-2.5 rounded-xl border border-border-glass bg-bg-surface text-text-main placeholder-text-muted/50 focus:ring-2 focus:ring-primary/50 focus:border-primary/50 transition-all">
            </div>
            <div class="flex items-end">
                <label class="relative flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" wire:model.live="isActive" class="sr-only peer">
                    <div
                        class="w-11 h-6 bg-bg-surface border border-border-glass peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary">
                    </div>
                    <span class="text-sm font-bold text-text-main">{{ $isActive ? 'Đang hiển thị' : 'Đang ẩn' }}</span>
                </label>
            </div>
        </div>
    </div>

    {{-- Blocks List --}}
    <div class="space-y-3" id="blocks-container" x-init="if (typeof Sortable !== 'undefined') {
        Sortable.create($el, {
            handle: '.drag-handle',
            animation: 200,
            ghostClass: 'opacity-30',
            onEnd: function(evt) {
                let items = [...evt.to.children].map((_, i) => i);
                // Xây dựng lại thứ tự gốc
                let order = [];
                for (let i = 0; i < evt.to.children.length; i++) {
                    order.push(parseInt(evt.to.children[i].dataset.index));
                }
                $wire.reorderBlocks(order);
            }
        });
    }">

        @forelse ($blocks as $index => $block)
            @php
                $blockClass = $registeredBlocks[$block['type']] ?? null;
                $label = $blockClass ? $blockClass::label() : $block['type'];
                $icon = $blockClass ? $blockClass::icon() : 'ti-puzzle';
                $isVisible = $block['visible'] ?? true;
            @endphp

            <div class="glass-card p-0 overflow-hidden transition-all duration-300 {{ !$isVisible ? 'opacity-50' : '' }}"
                data-index="{{ $index }}" wire:key="block-{{ $block['id'] }}">
                <div class="flex items-center gap-3 px-5 py-3.5 border-b border-white/5">
                    {{-- Drag Handle --}}
                    <div
                        class="drag-handle cursor-grab active:cursor-grabbing text-text-muted/50 hover:text-primary transition-colors">
                        <i class="ti ti-grip-vertical text-xl"></i>
                    </div>

                    {{-- Block Info --}}
                    <div class="flex items-center gap-2.5 flex-1 min-w-0">
                        <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center shrink-0">
                            <i class="ti {{ $icon }} text-primary"></i>
                        </div>
                        <div class="min-w-0">
                            <span class="text-sm font-bold text-text-main block truncate">{{ $label }}</span>
                            <span
                                class="text-[10px] text-text-muted/50 font-bold uppercase tracking-widest">{{ $block['type'] }}</span>
                        </div>
                    </div>

                    {{-- Block Actions --}}
                    <div class="flex items-center gap-1.5">
                        <x-backend.ui.action-icon :variant="$isVisible ? 'view' : 'neutral'" htmlType="button"
                            :title="$isVisible ? 'Hide block' : 'Show block'"
                            :icon="$isVisible ? 'ti ti-eye' : 'ti ti-eye-off'"
                            wire:click="toggleBlockVisibility({{ $index }})" />

                        <x-backend.ui.action-icon variant="neutral" htmlType="button" title="Block settings"
                            icon="ti ti-settings" wire:click="editBlock({{ $index }})" />

                        <x-backend.ui.action-icon variant="neutral" htmlType="button" title="Duplicate block"
                            icon="ti ti-copy" wire:click="duplicateBlock({{ $index }})" />

                        <x-backend.ui.action-icon variant="neutral" htmlType="button" title="Move up"
                            icon="ti ti-arrow-up" wire:click="moveBlockUp({{ $index }})"
                            :disabled="$index === 0" />

                        <x-backend.ui.action-icon variant="neutral" htmlType="button" title="Move down"
                            icon="ti ti-arrow-down" wire:click="moveBlockDown({{ $index }})"
                            :disabled="$index === count($blocks) - 1" />

                        <x-backend.ui.action-icon variant="delete" htmlType="button" title="Delete block"
                            icon="ti ti-trash" wire:click="confirmDeleteBlock({{ $index }})" />
                    </div>
                </div>

                {{-- Block Preview Summary --}}
                <div class="px-5 py-3 bg-white/2">
                    @if (!empty($block['data']['title']))
                        <p class="text-sm text-text-muted truncate">
                            <i class="ti ti-quote text-text-muted/30"></i>
                            {{ $block['data']['title'] }}
                        </p>
                    @else
                        <p class="text-sm text-text-muted/30 italic">Chưa cấu hình nội dung</p>
                    @endif
                </div>
            </div>
        @empty
            {{-- Empty State --}}
            <div class="glass-card p-12">
                <div class="flex flex-col items-center text-center">
                    <div class="w-20 h-20 bg-primary/10 rounded-2xl flex items-center justify-center mb-4">
                        <i class="ti ti-layout-dashboard text-4xl text-primary"></i>
                    </div>
                    <h3 class="text-xl font-black text-text-main mb-2">Chưa có khối nào</h3>
                    <p class="text-text-muted max-w-sm">Nhấn nút bên dưới để bắt đầu xây dựng giao diện trang bằng các
                        khối thành phần.</p>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Add Block Button --}}
    <button type="button" wire:click="$set('showBlockGallery', true)"
        class="w-full py-4 border-2 border-dashed border-border-glass rounded-2xl text-text-muted hover:text-primary hover:border-primary/50 hover:bg-primary/5 transition-all duration-300 flex items-center justify-center gap-2 font-bold group">
        <div
            class="w-8 h-8 bg-primary/10 group-hover:bg-primary/20 rounded-full flex items-center justify-center transition-colors">
            <i class="ti ti-plus text-primary text-lg"></i>
        </div>
        Thêm khối mới
    </button>

    {{-- Block Gallery Modal --}}
    @if ($showBlockGallery)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-transition>
            <div class="glass-card max-w-2xl w-full max-h-[80vh] overflow-hidden flex flex-col">
                {{-- Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-white/5">
                    <div>
                        <h3 class="text-lg font-black text-text-main">Chọn loại khối</h3>
                        <p class="text-sm text-text-muted">Chọn khối thành phần để thêm vào layout</p>
                    </div>
                    <button type="button" wire:click="$set('showBlockGallery', false)"
                        class="p-2 text-text-muted hover:text-text-main hover:bg-white/10 rounded-lg transition-colors">
                        <i class="ti ti-x text-xl"></i>
                    </button>
                </div>

                {{-- Gallery Grid --}}
                <div class="flex-1 overflow-y-auto p-6 space-y-6 custom-scrollbar">
                    @foreach ($blockGallery as $category => $categoryBlocks)
                        <div>
                            <h4 class="text-[11px] font-black text-text-muted uppercase tracking-[0.15em] mb-3">
                                @switch($category)
                                    @case('layout')
                                        <i class="ti ti-layout mr-1"></i> Bố cục
                                    @break

                                    @case('product')
                                        <i class="ti ti-shopping-bag mr-1"></i> Sản phẩm
                                    @break

                                    @case('content')
                                        <i class="ti ti-file-text mr-1"></i> Nội dung
                                    @break

                                    @case('marketing')
                                        <i class="ti ti-speakerphone mr-1"></i> Marketing
                                    @break

                                    @default
                                        {{ ucfirst($category) }}
                                @endswitch
                            </h4>
                            <div class="grid grid-cols-2 gap-3">
                                @foreach ($categoryBlocks as $blockInfo)
                                    <button type="button" wire:click="addBlock('{{ $blockInfo['type'] }}')"
                                        class="flex items-start gap-3 p-4 rounded-xl border border-border-glass bg-bg-surface hover:border-primary/50 hover:bg-primary/5 transition-all text-left group">
                                        <div
                                            class="w-10 h-10 rounded-lg bg-primary/10 group-hover:bg-primary/20 flex items-center justify-center shrink-0 transition-colors">
                                            <i class="ti {{ $blockInfo['icon'] }} text-xl text-primary"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <span
                                                class="text-sm font-bold text-text-main block">{{ $blockInfo['label'] }}</span>
                                            <span
                                                class="text-xs text-text-muted line-clamp-2">{{ $blockInfo['description'] }}</span>
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- Block Settings Panel (Slide-over) --}}
    @if ($editingBlockIndex !== null && isset($blocks[$editingBlockIndex]))
        @php
            $editBlock = $blocks[$editingBlockIndex];
            $editBlockClass = $registeredBlocks[$editBlock['type']] ?? null;
            $editBlockLabel = $editBlockClass ? $editBlockClass::label() : $editBlock['type'];
        @endphp
        <div class="fixed inset-0 z-50 flex justify-end bg-black/50 backdrop-blur-sm" x-transition>
            <div class="w-full max-w-lg bg-bg-main border-l border-border-glass overflow-y-auto custom-scrollbar"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full"
                x-transition:enter-end="translate-x-0">
                {{-- Panel Header --}}
                <div class="sticky top-0 z-10 bg-bg-main/95 backdrop-blur-xl px-6 py-4 border-b border-white/5">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-black text-text-main flex items-center gap-2">
                            <i class="ti ti-settings text-primary"></i>
                            {{ $editBlockLabel }}
                        </h3>
                        <button wire:click="cancelEditBlock"
                            class="p-2 text-text-muted hover:text-text-main hover:bg-white/10 rounded-lg transition-colors">
                            <i class="ti ti-x text-xl"></i>
                        </button>
                    </div>
                </div>

                {{-- Settings Form --}}
                <div class="p-6 space-y-5">
                    @foreach ($editingBlockData as $key => $value)
                        <div>
                            <label class="block text-sm font-bold text-text-muted mb-1.5 capitalize">
                                {{ str_replace('_', ' ', $key) }}
                            </label>

                            @if (is_bool($value))
                                {{-- Boolean Toggle --}}
                                <label class="relative flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" wire:model.live="editingBlockData.{{ $key }}"
                                        class="sr-only peer">
                                    <div
                                        class="w-11 h-6 bg-bg-surface border border-border-glass peer-focus:ring-4 peer-focus:ring-primary/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary">
                                    </div>
                                    <span class="text-sm text-text-main">{{ $value ? 'Bật' : 'Tắt' }}</span>
                                </label>
                            @elseif (is_int($value))
                                {{-- Number Input --}}
                                <input type="number" wire:model.live="editingBlockData.{{ $key }}"
                                    class="w-full px-4 py-2.5 rounded-xl border border-border-glass bg-bg-surface text-text-main focus:ring-2 focus:ring-primary/50 focus:border-primary/50 transition-all">
                            @elseif (Str::contains($key, ['content', 'description']) && strlen($value) > 100)
                                {{-- Textarea --}}
                                <textarea wire:model.blur="editingBlockData.{{ $key }}" rows="5"
                                    class="w-full px-4 py-2.5 rounded-xl border border-border-glass bg-bg-surface text-text-main focus:ring-2 focus:ring-primary/50 focus:border-primary/50 transition-all resize-y"></textarea>
                            @elseif (Str::contains($key, 'color'))
                                {{-- Color Picker --}}
                                <div class="flex items-center gap-3">
                                    <input type="color" wire:model.live="editingBlockData.{{ $key }}"
                                        class="w-12 h-10 rounded-lg border border-border-glass cursor-pointer">
                                    <input type="text" wire:model.live="editingBlockData.{{ $key }}"
                                        class="flex-1 px-4 py-2.5 rounded-xl border border-border-glass bg-bg-surface text-text-main focus:ring-2 focus:ring-primary/50 focus:border-primary/50 transition-all font-mono text-sm">
                                </div>
                            @else
                                {{-- Text Input --}}
                                <input type="text" wire:model.blur="editingBlockData.{{ $key }}"
                                    class="w-full px-4 py-2.5 rounded-xl border border-border-glass bg-bg-surface text-text-main placeholder-text-muted/50 focus:ring-2 focus:ring-primary/50 focus:border-primary/50 transition-all">
                            @endif
                        </div>
                    @endforeach
                </div>

                {{-- Panel Footer --}}
                <div class="sticky bottom-0 bg-bg-main/95 backdrop-blur-xl px-6 py-4 border-t border-white/5">
                    <div class="flex gap-3">
                        <button wire:click="cancelEditBlock"
                            class="flex-1 px-4 py-2.5 bg-bg-surface border border-border-glass text-text-muted font-bold rounded-xl hover:bg-white/10 transition-colors">
                            Hủy bỏ
                        </button>
                        <button type="button" wire:click="saveBlockSettings"
                            class="flex-1 px-4 py-2.5 bg-linear-to-r from-primary to-accent text-white font-bold rounded-xl hover:shadow-lg hover:shadow-primary/25 transition-all">
                            <i class="ti ti-check"></i> Áp dụng
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Delete Confirmation Modal --}}
    @if ($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" x-transition>
            <div class="glass-card max-w-md w-full p-6">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-danger/10 mx-auto mb-4">
                    <i class="ti ti-alert-triangle text-2xl text-danger"></i>
                </div>
                <h3 class="text-lg font-black text-text-main text-center mb-2">Xác nhận xóa khối</h3>
                <p class="text-text-muted text-center mb-6">
                    Bạn có chắc chắn muốn xóa khối này? Hành động này không thể hoàn tác.
                </p>
                <div class="flex gap-3">
                    <button wire:click="$set('showDeleteModal', false)"
                        class="flex-1 px-4 py-2.5 bg-bg-surface border border-border-glass text-text-muted font-bold rounded-xl hover:bg-white/10 transition-colors">
                        Hủy bỏ
                    </button>
                    <button wire:click="deleteBlock"
                        class="flex-1 px-4 py-2.5 bg-danger hover:bg-danger/80 text-white font-bold rounded-xl transition-colors">
                        <i class="ti ti-trash"></i> Xóa khối
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
@endpush
