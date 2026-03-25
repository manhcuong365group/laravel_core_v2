<aside id="sidebar"
    class="fixed top-0 left-0 z-40 h-screen flex flex-col transition-all duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] border-r border-border-glass bg-bg-surface shadow-[0.15rem_0_1.25rem_0_rgba(67,89,113,.12)] dark:bg-bg-surface dark:shadow-[0.15rem_0_1.25rem_0_rgba(0,0,0,.2)]"
    :class="sidebarOpen ? 'w-72' : 'w-24'">

    <!-- Logo Section: Modern Industrial -->
    <div class="relative flex items-center h-[76px] px-6 shrink-0 overflow-hidden">
        <a href="{{ route('backend.dashboard') }}" class="relative flex items-center gap-3 group">
            <div
                class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center shadow-lg transition-all duration-500 group-hover:rotate-6">
                <span class="text-white font-black text-lg tracking-tighter">NI</span>
            </div>

            <div x-show="sidebarOpen" x-transition:enter="transition-all duration-500 delay-100"
                x-transition:enter-start="opacity-0 -translate-x-4" x-transition:enter-end="opacity-100 translate-x-0"
                class="flex flex-col">
                <span class="text-xl font-bold text-text-main font-bold tracking-tight leading-none">
                    Admin
                </span>
                <span class="text-[10px] text-text-muted font-bold uppercase tracking-widest mt-1">
                    PRO
                </span>
            </div>
        </a>
    </div>

    <!-- Toggle Button (Floating) -->
    <button @click="sidebarOpen = !sidebarOpen"
        class="hidden lg:flex absolute -right-3.5 top-18 w-7 h-7 bg-primary text-white rounded-full items-center justify-center shadow-xl hover:scale-110 transition-all duration-500 z-50">
        <i class="ti ti-chevron-left text-sm transition-transform duration-500"
            :class="sidebarOpen ? '' : 'rotate-180'"></i>
    </button>

    <!-- Navigation Menu -->
    @php
        $checkActive = function ($routeName, $routeParams) {
            if (!$routeName) {
                return false;
            }
            if (!request()->routeIs($routeName . '*')) {
                return false;
            }
            if (!empty($routeParams)) {
                $params = is_string($routeParams) ? json_decode($routeParams, true) : (array) $routeParams;
                if (is_array($params)) {
                    foreach ($params as $key => $value) {
                        if (request()->route()->parameter($key) != $value) {
                            return false;
                        }
                    }
                }
            }
            return true;
        };
    @endphp
    <nav class="flex-1 min-h-0 px-4 py-6 space-y-1 overflow-y-auto custom-scrollbar">

        @foreach ($sidebarMenus as $group)
            <div class="space-y-1">
                @if ($group['group'] !== 'Khác')
                    <div x-show="sidebarOpen" class="px-3 pt-6 pb-2">
                        <span
                            class="text-[11px] font-bold text-text-muted uppercase tracking-[0.05em] opacity-60">{{ $group['group'] }}</span>
                    </div>
                @endif

                @foreach ($group['items'] as $item)
                    @php
                        $showItem = true;
                        if ($item->permission) {
                            $perms = explode(',', $item->permission);
                            $showItem =
                                auth()->user()->hasAnyPermission($perms) || auth()->user()->hasRole('super-admin');
                        }

                        // Active logic
                        $isActive = false;
                        if ($checkActive($item->route_name, $item->route_params)) {
                            $isActive = true;
                        }
                        if (!$isActive && $item->children->isNotEmpty()) {
                            foreach ($item->children as $child) {
                                if ($checkActive($child->route_name, $child->route_params)) {
                                    $isActive = true;
                                    break;
                                }
                            }
                        }
                    @endphp

                    @if ($showItem)
                        @if ($item->children->isNotEmpty())
                            {{-- Submenu Item --}}
                            <div x-data="{ open: {{ $isActive ? 'true' : 'false' }} }" class="space-y-0.5">
                                <button @click="open = !open; if(!sidebarOpen) sidebarOpen = true"
                                    class="w-full relative flex items-center justify-between gap-3 px-3.5 py-2.5 rounded-lg transition-all duration-300 group {{ $isActive ? 'bg-primary/10 text-primary' : 'text-text-muted hover:bg-slate-100 dark:hover:bg-slate-800/50 hover:text-text-main font-bold' }}">

                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-300 {{ $isActive ? 'bg-primary/20 text-primary' : 'bg-slate-100 dark:bg-slate-800 text-text-muted group-hover:scale-110 group-hover:text-primary group-hover:bg-primary/10' }}">
                                            <i class="{{ $item->icon }} text-lg"></i>
                                        </div>
                                        <span x-show="sidebarOpen"
                                            class="font-semibold tracking-tight text-[14px]">
                                            {{ $item->label }}
                                        </span>
                                    </div>
                                    <i x-show="sidebarOpen"
                                        class="ti ti-chevron-right text-[10px] opacity-60 transition-transform duration-300"
                                        :class="open ? 'rotate-90' : ''"></i>
                                </button>
                                <div x-show="open && sidebarOpen" x-collapse x-cloak>
                                    <div class="relative space-y-1 mt-1 ml-7 pl-2 border-l-2 border-slate-200/50 dark:border-slate-800/50">
                                        @foreach ($item->children as $sub)
                                            @php
                                                $showSub = true;
                                                if ($sub->permission) {
                                                    $subPerms = explode(',', $sub->permission);
                                                    $showSub =
                                                        auth()->user()->hasAnyPermission($subPerms) ||
                                                        auth()->user()->hasRole('super-admin');
                                                }
                                                $subActive = $checkActive($sub->route_name, $sub->route_params);
                                            @endphp
                                            @if ($showSub)
                                                <a href="{{ $sub->route_name ? route($sub->route_name, $sub->route_params ?? []) : '#' }}"
                                                    @if($subActive) x-init="$nextTick(() => $el.scrollIntoView({ behavior: 'smooth', block: 'nearest' }))" @endif
                                                    class="relative flex items-center gap-3 py-2.5 px-4 rounded-xl text-[13px] transition-all duration-300 group/sub {{ $subActive ? 'text-primary font-bold bg-primary/5 shadow-inner' : 'text-text-muted hover:text-text-main hover:bg-slate-50 dark:hover:bg-slate-800/30 hover:translate-x-1' }}">
                                                    
                                                    <!-- Dot Indicator -->
                                                    <div class="absolute -left-[11px] flex items-center justify-center">
                                                        <div class="w-4 h-4 rounded-full bg-bg-surface flex items-center justify-center">
                                                            <div class="transition-all duration-300 {{ $subActive ? 'w-2 h-2 bg-primary shadow-[0_0_8px_var(--color-primary)] rounded-full' : 'w-1.5 h-1.5 bg-slate-300 dark:bg-slate-700 rounded-full group-hover/sub:bg-primary group-hover/sub:scale-110' }}"></div>
                                                        </div>
                                                    </div>

                                                    <span class="relative truncate">{{ $sub->label }}</span>
                                                    
                                                    @if ($subActive)
                                                        <i class="ti ti-chevron-right text-[10px] ml-auto animate-pulse"></i>
                                                    @endif
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @else
                            {{-- Single Item --}}
                            <a href="{{ $item->route_name ? route($item->route_name, $item->route_params ?? []) : '#' }}"
                                @if($isActive) x-init="$nextTick(() => $el.scrollIntoView({ behavior: 'smooth', block: 'nearest' }))" @endif
                                class="relative flex items-center gap-3 px-3.5 py-2.5 rounded-lg transition-all duration-300 group {{ $isActive ? 'bg-linear-to-r from-primary to-primary/80 text-white shadow-lg shadow-primary/30' : 'text-text-muted hover:bg-slate-100 dark:hover:bg-slate-800/50 hover:text-text-main font-bold' }}">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-300 {{ $isActive ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-100 dark:bg-slate-800 text-text-muted group-hover:scale-110 group-hover:text-primary group-hover:bg-primary/10' }}">
                                    <i class="{{ $item->icon }} text-lg"></i>
                                </div>
                                <span x-show="sidebarOpen"
                                    class="font-semibold tracking-tight text-[14px]">{{ $item->label }}</span>

                                @if ($isActive)
                                    <span class="absolute right-3 w-1.5 h-1.5 rounded-full bg-white shadow-sm"></span>
                                @endif
                            </a>
                        @endif
                    @endif
                @endforeach
            </div>
        @endforeach

    </nav>

    <!-- Sidebar Footer -->
    <div class="p-4 shrink-0 border-t border-border-glass">
        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg text-danger hover:bg-danger/10 transition-all duration-300 group">
                <i class="ti ti-power text-xl"></i>
                <span x-show="sidebarOpen" class="font-bold tracking-tight">Đăng xuất</span>
            </button>
        </form>
    </div>
</aside>



