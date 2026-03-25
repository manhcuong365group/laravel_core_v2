@php
    $canAccessMenuItem = function ($item) {
        if (empty($item->permission)) {
            return true;
        }

        $permissions = array_filter(array_map('trim', explode(',', $item->permission)));
        if (empty($permissions)) {
            return true;
        }

        return auth()->user()?->hasRole('super-admin') || auth()->user()?->hasAnyPermission($permissions);
    };

    $routeUrl = function ($routeName, $routeParams = null) {
        if (empty($routeName) || !\Route::has($routeName)) {
            return null;
        }

        $params = $routeParams;
        if (is_string($routeParams)) {
            $decoded = json_decode($routeParams, true);
            $params = is_array($decoded) ? $decoded : [];
        }

        if (!is_array($params)) {
            $params = (array) $params;
        }

        return route($routeName, $params);
    };

    $paletteItems = [];
    foreach ($sidebarMenus ?? [] as $group) {
        foreach ($group['items'] ?? [] as $item) {
            if (!$canAccessMenuItem($item)) {
                continue;
            }

            $url = $routeUrl($item->route_name, $item->route_params ?? null);
            if ($url) {
                $paletteItems[] = [
                    'title' => $item->label,
                    'url' => $url,
                    'icon' => $item->icon ?: 'ti ti-point',
                    'source' => 'menu',
                ];
            }

            foreach ($item->children ?? [] as $child) {
                if (!$canAccessMenuItem($child)) {
                    continue;
                }

                $childUrl = $routeUrl($child->route_name, $child->route_params ?? null);
                if ($childUrl) {
                    $paletteItems[] = [
                        'title' => $item->label . ' / ' . $child->label,
                        'url' => $childUrl,
                        'icon' => $child->icon ?: $item->icon ?: 'ti ti-point',
                        'source' => 'menu',
                    ];
                }
            }
        }
    }
@endphp

<div x-data="{
    open: false,
    search: '',
    selectedIndex: 0,
    items: @js($paletteItems),
    recentItems: [],
    get mergedItems() {
        const unique = new Map();
        [...this.recentItems, ...this.items].forEach((item) => {
            if (item?.url && !unique.has(item.url)) unique.set(item.url, item);
        });
        return Array.from(unique.values());
    },
    get filteredItems() {
        const keyword = this.search.trim().toLowerCase();
        if (!keyword) return this.mergedItems;
        return this.mergedItems.filter((item) => item.title.toLowerCase().includes(keyword));
    },
    loadRecentItems() {
        try {
            const raw = localStorage.getItem('adminRecentPages');
            const parsed = raw ? JSON.parse(raw) : [];
            this.recentItems = Array.isArray(parsed)
                ? parsed.map((item) => ({ ...item, source: 'recent', icon: item.icon || 'ti ti-history' }))
                : [];
        } catch (_) {
            this.recentItems = [];
        }
    },
    openPalette() {
        this.loadRecentItems();
        this.open = true;
        this.selectedIndex = 0;
        this.$nextTick(() => {
            this.$refs.searchInput?.focus();
            this.$refs.searchInput?.select();
        });
    },
    closePalette() {
        this.open = false;
        this.search = '';
        this.selectedIndex = 0;
    },
    selectNext() {
        if (!this.filteredItems.length) return;
        this.selectedIndex = (this.selectedIndex + 1) % this.filteredItems.length;
    },
    selectPrev() {
        if (!this.filteredItems.length) return;
        this.selectedIndex = (this.selectedIndex - 1 + this.filteredItems.length) % this.filteredItems.length;
    },
    selectCurrent() {
        const item = this.filteredItems[this.selectedIndex];
        if (!item?.url) return;
        this.closePalette();
        window.location.href = item.url;
    }
}" @keydown.window.prevent.ctrl.k="openPalette()" @keydown.window.prevent.meta.k="openPalette()"
    @keydown.window="
        if ($event.key === '/' && !['INPUT', 'TEXTAREA', 'SELECT'].includes($event.target.tagName) && !$event.target.isContentEditable) {
            $event.preventDefault();
            openPalette();
        }
    "
    @open-command-palette.window="openPalette()" @keydown.window.escape.stop="closePalette()" class="relative z-50">

    <div x-show="open" x-cloak x-transition.opacity @click="closePalette()"
        class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50"></div>

    <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 z-[60] flex items-start justify-center pt-20 sm:pt-32 p-4 pointer-events-none">

        <div @click.stop
            class="pointer-events-auto w-full max-w-xl bg-bg-surface rounded-2xl shadow-2xl border border-border-glass overflow-hidden flex flex-col max-h-[60vh]">
            <div class="flex items-center px-4 py-3 border-b border-border-glass">
                <i class="ti ti-search text-text-muted text-xl"></i>
                <input x-ref="searchInput" x-model="search" @keydown.arrow-down.prevent="selectNext()"
                    @keydown.arrow-up.prevent="selectPrev()" @keydown.enter.prevent="selectCurrent()"
                    @keydown.escape.stop.prevent="closePalette()" type="text" placeholder="Tìm kiếm nhanh..."
                    class="w-full px-3 py-1 text-text-main placeholder-text-muted bg-transparent border-none focus:ring-0 focus:outline-none text-lg">
                <button type="button" @click="closePalette()"
                    class="text-xs bg-bg-main px-2 py-1 rounded text-text-muted">ESC</button>
            </div>

            <div class="overflow-y-auto p-2">
                <template x-for="(item, index) in filteredItems" :key="item.url + '-' + index">
                    <a :href="item.url" @click="closePalette()" @mouseover="selectedIndex = index"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors cursor-pointer"
                        :class="{
                            'bg-primary/10 text-primary': selectedIndex === index,
                            'text-text-muted hover:bg-bg-main': selectedIndex !== index
                        }">
                        <i class="ti text-xl" :class="item.icon"></i>
                        <span x-text="item.title" class="font-medium"></span>
                        <span x-show="item.source === 'recent'"
                            class="ml-auto text-[10px] font-bold uppercase tracking-widest text-text-muted/70">Recent</span>
                        <i x-show="selectedIndex === index" class="ti ti-corner-down-left ml-auto text-primary"></i>
                    </a>
                </template>

                <div x-show="filteredItems.length === 0" class="px-4 py-8 text-center text-text-muted">
                    <i class="ti ti-search-off text-3xl mb-2 block text-text-muted/50"></i>
                    Không tìm thấy kết quả cho "<span x-text="search" class="font-semibold text-text-main"></span>"
                </div>
            </div>

            <div class="bg-bg-main px-4 py-2 border-t border-border-glass text-xs text-text-muted flex justify-between">
                <span>
                    Di chuyển
                    <kbd class="font-sans bg-bg-surface border border-border-glass rounded px-1">↓</kbd>
                    <kbd class="font-sans bg-bg-surface border border-border-glass rounded px-1">↑</kbd>
                </span>
                <span>
                    Mở
                    <kbd class="font-sans bg-bg-surface border border-border-glass rounded px-1">Enter</kbd>
                    hoặc
                    <kbd class="font-sans bg-bg-surface border border-border-glass rounded px-1">/</kbd>
                </span>
            </div>
        </div>
    </div>
</div>
