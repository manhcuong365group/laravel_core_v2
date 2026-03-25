<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-text-main tracking-tight">Quản lý Menu</h1>
            <p class="text-text-muted mt-1">Tuỳ chỉnh cấu trúc và thứ tự hiển thị sidebar.</p>
        </div>
        <button wire:click="create"
            class="hidden md:flex items-center gap-2 px-5 py-2.5 bg-primary text-white font-bold rounded-xl hover:bg-primary/90 transition-all shadow-lg shadow-primary/20">
            <i class="ti ti-plus"></i> Thêm menu mới
        </button>
    </div>

    {{-- Menu List (Tree View) --}}
    <div class="space-y-8" id="menu-groups-container">
        @foreach ($menus as $groupData)
            <div class="bg-slate-50 dark:bg-slate-900/50 rounded-3xl p-6 border border-border-glass group-panel">
                <div class="mb-4 flex items-center gap-3 cursor-move group-handle">
                    <i class="ti ti-grip-vertical text-border-glass hover:text-primary transition-colors"
                        title="Kéo thả nguyên cụm"></i>
                    <span
                        class="text-sm font-black text-text-muted uppercase tracking-[0.2em]">{{ $groupData['group'] }}</span>
                    <div class="h-px flex-1 bg-border-glass"></div>
                </div>

                <div class="space-y-4 group-list min-h-[50px]" data-group="{{ $groupData['group'] }}">
                    @foreach ($groupData['items'] as $menu)
                        <div data-id="{{ $menu->id }}"
                            class="menu-item bg-white dark:bg-slate-800 rounded-2xl border border-border-glass shadow-sm p-4 cursor-move transition-all hover:border-primary/50 group">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-bg-main dark:bg-slate-900 flex items-center justify-center text-primary group-hover:bg-primary/10 transition-colors">
                                        <i class="{{ $menu->icon ?? 'ti ti-category' }} text-xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-text-main group-hover:text-primary transition-colors">
                                            {{ $menu->label }}</h3>
                                        <div class="flex items-center gap-2 text-xs text-text-muted mt-0.5">
                                            @if ($menu->route_name)
                                                <span
                                                    class="bg-primary/10 text-primary px-2 py-0.5 rounded-md font-mono">{{ $menu->route_name }}</span>
                                            @endif
                                            @if ($menu->permission)
                                                <span
                                                    class="bg-accent/10 text-accent px-2 py-0.5 rounded-md font-mono flex items-center gap-1">
                                                    <i class="ti ti-shield-lock text-[10px]"></i>
                                                    {{ $menu->permission }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button wire:click="edit({{ $menu->id }})"
                                        class="p-2 rounded-lg hover:bg-primary/10 text-text-muted hover:text-primary transition-colors">
                                        <i class="ti ti-pencil"></i>
                                    </button>
                                    <button wire:click="delete({{ $menu->id }})"
                                        class="p-2 rounded-lg hover:bg-danger/10 text-text-muted hover:text-danger transition-colors">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- Children (Submenu) --}}
                            <div
                                class="ml-8 mt-3 pl-4 border-l-2 {{ $menu->children->isNotEmpty() ? 'border-border-glass' : 'border-transparent hover:border-border-glass' }} space-y-3 nested-sortable-sub min-h-[10px]">
                                @foreach ($menu->children as $child)
                                    <div data-id="{{ $child->id }}"
                                        class="menu-item bg-bg-main/50 dark:bg-slate-900/50 rounded-xl border border-border-glass p-3 flex items-center justify-between group/child hover:bg-white dark:hover:bg-slate-800 transition-colors">
                                        <div class="flex items-center gap-3">
                                            <i class="ti ti-corner-down-right text-text-muted"></i>
                                            <span class="font-medium text-text-main">{{ $child->label }}</span>
                                            @if ($child->route_name)
                                                <span
                                                    class="text-xs text-text-muted font-mono bg-white/50 dark:bg-slate-900 px-1.5 py-0.5 rounded border border-border-glass">{{ $child->route_name }}</span>
                                            @endif
                                        </div>
                                        <div
                                            class="flex items-center gap-1 opacity-0 group-hover/child:opacity-100 transition-opacity">
                                            <button wire:click="edit({{ $child->id }})"
                                                class="p-1.5 rounded-lg hover:bg-primary/10 text-text-muted hover:text-primary">
                                                <i class="ti ti-pencil text-sm"></i>
                                            </button>
                                            <button wire:click="delete({{ $child->id }})"
                                                class="p-1.5 rounded-lg hover:bg-danger/10 text-text-muted hover:text-danger">
                                                <i class="ti ti-trash text-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    {{-- Create/Edit Modal --}}
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" x-data
            @click.self="$wire.set('showModal', false)">
            <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden animate-fade-in-up">
                <div class="px-6 py-5 border-b border-border-glass flex items-center justify-between bg-bg-main/30">
                    <h3 class="text-lg font-bold text-text-main">
                        {{ $isEdit ? 'Chỉnh sửa Menu' : 'Thêm Menu Mới' }}
                    </h3>
                    <button wire:click="$set('showModal', false)"
                        class="text-text-muted hover:text-text-main transition-colors">
                        <i class="ti ti-x text-xl"></i>
                    </button>
                </div>

                <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto custom-scrollbar">
                    {{-- Form Fields --}}
                    <!-- Label -->
                    <div>
                        <label class="block text-sm font-bold text-text-main mb-1">Tên hiển thị <span
                                class="text-danger">*</span></label>
                        <input type="text" wire:model="label"
                            class="w-full px-4 py-2.5 rounded-xl border border-border-glass bg-bg-main/50 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all placeholder:text-text-muted/50"
                            placeholder="Nhập tên menu...">
                        @error('label')
                            <span class="text-xs text-danger mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Icon & Is Active -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-text-main mb-1">Icon (Tabler)</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-text-muted"><i
                                        class="{{ $icon ? $icon : 'ti ti-circle' }}"></i></span>
                                <input type="text" wire:model.live="icon"
                                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-border-glass bg-bg-main/50 focus:border-primary outline-none transition-all"
                                    placeholder="ti ti-home">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-text-main mb-1">Trạng thái</label>
                            <label class="flex items-center gap-3 cursor-pointer mt-2">
                                <div class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" wire:model="is_active" class="sr-only peer">
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary">
                                    </div>
                                </div>
                                <span
                                    class="text-sm font-medium text-text-main">{{ $is_active ? 'Hiện' : 'Ẩn' }}</span>
                            </label>
                        </div>
                    </div>

                    <!-- Route Selection & Params -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-text-main mb-1">Route liên kết</label>
                            <select wire:model="route_name"
                                class="w-full px-4 py-2.5 rounded-xl border border-border-glass bg-bg-main/50 focus:border-primary outline-none transition-all appearance-none">
                                <option value="">-- Chọn Route (hoặc trống) --</option>
                                @foreach ($adminRoutes as $route)
                                    <option value="{{ $route['name'] }}">{{ $route['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-text-main mb-1">Route Parameters (JSON)</label>
                            <input type="text" wire:model="route_params"
                                class="w-full px-4 py-2.5 rounded-xl border border-border-glass bg-bg-main/50 focus:border-primary outline-none transition-all font-mono text-sm"
                                placeholder='vd: {"type": "product"}'>
                        </div>
                    </div>

                    <!-- Parent Menu -->
                    <div>
                        <label class="block text-sm font-bold text-text-main mb-1">Menu cha</label>
                        <select wire:model="parent_id"
                            class="w-full px-4 py-2.5 rounded-xl border border-border-glass bg-bg-main/50 focus:border-primary outline-none transition-all appearance-none">
                            <option value="">-- Là menu gốc --</option>
                            @foreach ($parentOptions as $parentId => $parentLabel)
                                <option value="{{ $parentId }}">{{ $parentLabel }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Permission & Group Label -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-text-main mb-1">Quyền hạn (Permission)</label>
                            <input type="text" wire:model="permission"
                                class="w-full px-4 py-2.5 rounded-xl border border-border-glass bg-bg-main/50 focus:border-primary outline-none transition-all"
                                placeholder="vd: products.view">
                        </div>
                        <div x-show="!$wire.parent_id">
                            <label class="block text-sm font-bold text-text-main mb-1">Nhóm (Group Label)</label>
                            <input type="text" wire:model="group_label"
                                class="w-full px-4 py-2.5 rounded-xl border border-border-glass bg-bg-main/50 focus:border-primary outline-none transition-all"
                                placeholder="vd: Hệ thống">
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-border-glass flex justify-end gap-3 bg-bg-main/30">
                    <button wire:click="$set('showModal', false)"
                        class="px-5 py-2.5 rounded-xl text-text-muted font-bold hover:bg-white/50 transition-all border border-transparent hover:border-border-glass">
                        Hủy bỏ
                    </button>
                    <button wire:click="save"
                        class="px-5 py-2.5 rounded-xl bg-primary text-white font-bold hover:bg-primary/90 transition-all shadow-lg shadow-primary/20">
                        {{ $isEdit ? 'Cập nhật' : 'Tạo mới' }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- SortableJS Integration --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
        <script>
            document.addEventListener('livewire:initialized', () => {
                let sortables = [];

                function initSortables() {
                    // Destroy old instances on re-render
                    sortables.forEach(s => s.destroy());
                    sortables = [];

                    document.querySelectorAll('.group-list').forEach(function(el) {
                        sortables.push(new Sortable(el, {
                            group: 'nested', // allows dragging BETWEEN groups and into children!
                            animation: 150,
                            fallbackOnBody: true,
                            swapThreshold: 0.65,
                            ghostClass: 'bg-primary/10',
                            onEnd: function() {
                                updateOrder();
                            }
                        }));
                    });

                    // Sortable for entire groups
                    let groupsContainer = document.getElementById('menu-groups-container');
                    if (groupsContainer) {
                        sortables.push(new Sortable(groupsContainer, {
                            group: 'group-panels',
                            handle: '.group-handle',
                            animation: 150,
                            ghostClass: 'opacity-50',
                            onEnd: function() {
                                updateOrder();
                            }
                        }));
                    }

                    document.querySelectorAll('.nested-sortable-sub').forEach(function(el) {
                        sortables.push(new Sortable(el, {
                            group: 'nested', // allows dragging BETWEEN groups and into children!
                            animation: 150,
                            fallbackOnBody: true,
                            swapThreshold: 0.65,
                            ghostClass: 'bg-primary/10',
                            onEnd: function() {
                                updateOrder();
                            }
                        }));
                    });
                }

                initSortables();

                Livewire.hook('morph.updated', (el, component) => {
                    initSortables();
                });

                function updateOrder() {
                    let order = [];
                    let globalOrder = 1;

                    document.querySelectorAll('.group-list').forEach(container => {
                        let groupName = container.getAttribute('data-group');
                        let items = container.querySelectorAll(':scope > .menu-item');

                        items.forEach(item => {
                            let id = item.getAttribute('data-id');
                            let subItems = [];
                            let subList = item.querySelectorAll('.nested-sortable-sub > .menu-item');

                            let currentOrder = globalOrder++;

                            subList.forEach(subItem => {
                                subItems.push({
                                    value: subItem.getAttribute('data-id'),
                                    order: globalOrder++
                                });
                            });

                            order.push({
                                value: id,
                                group: groupName,
                                order: currentOrder,
                                items: subItems
                            });
                        });
                    });

                    @this.call('updateOrder', order);
                }
            });
        </script>
    @endpush
</div>
