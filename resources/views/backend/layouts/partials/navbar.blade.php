<nav class="sticky top-0 z-30 transition-all duration-500"
    :class="adminTheme === 'default' ? 'bg-bg-surface border-b border-border-glass shadow-sm' : 'glass-header shadow-2xl'">
    <div class="flex items-center justify-between h-[76px] px-6 lg:px-10">

        <!-- Left Section: Navigation & Search -->
        <div class="flex items-center gap-8">
            <!-- Mobile Toggle -->
            <button @click="sidebarOpen = !sidebarOpen"
                class="lg:hidden w-10 h-10 flex items-center justify-center rounded-xl bg-bg-surface shadow-sm border border-border-glass hover:bg-bg-main transition-all active:scale-95">
                <i class="ti ti-menu-2 text-xl text-text-main"></i>
            </button>

            <!-- Greeting (Enhanced Typography) -->
            <div class="hidden md:flex flex-col" x-data="timeOfDayIcons" x-init="setTimeOfDay()">
                <div class="flex items-center gap-2">
                    <span x-text="icon" class="text-lg"></span>
                    <span class="text-[10px] font-black text-primary uppercase tracking-[0.2em] leading-none mb-0.5">Hệ
                        thống quản trị</span>
                </div>
                <h2 class="text-[14px] font-bold text-text-main tracking-tight">
                    Chào buổi sáng, {{ Auth::user()->name ?? 'Admin' }}!
                </h2>
            </div>

            <!-- Global Search (High-Craft Design) -->
            <div class="hidden xl:flex items-center">
                <button type="button" @click="$dispatch('open-command-palette')"
                    class="group relative flex items-center w-80 px-4 py-2.5 bg-slate-100/80 dark:bg-slate-800/50 hover:bg-white dark:hover:bg-slate-800 border border-transparent hover:border-border-glass rounded-2xl text-text-muted transition-all duration-300 shadow-inner hover:shadow-sm">
                    <i class="ti ti-search text-lg text-text-muted group-hover:text-primary transition-colors mr-3"></i>
                    <span class="text-sm font-medium tracking-tight">Tìm kiếm nhanh...</span>
                    <div class="ml-auto flex items-center gap-1 opacity-60">
                        <kbd
                            class="px-1.5 py-0.5 text-[10px] font-black bg-white dark:bg-slate-900 border border-border-glass rounded-md shadow-[0_1px_2px_rgba(0,0,0,0.05)]">Ctrl</kbd>
                        <kbd
                            class="px-1.5 py-0.5 text-[10px] font-black bg-bg-surface border border-border-glass rounded-md shadow-xs">K</kbd>
                    </div>
                </button>
            </div>
        </div>

        <!-- Right Section: Actions & Profile -->
        <div class="flex items-center gap-3">

            <!-- Quick Link: Website -->
            <a href="/" target="_blank"
                class="hidden sm:flex items-center justify-center w-12 h-12 rounded-2xl text-text-muted hover:text-primary hover:bg-primary/10 border border-transparent hover:border-primary/20 transition-all group"
                title="Xem Website">
                <i class="ti ti-world text-2xl transition-transform group-hover:rotate-12"></i>
            </a>

            <!-- Theme Toggle -->
            <button @click="darkMode = !darkMode"
                class="w-12 h-12 flex items-center justify-center rounded-2xl bg-white/5 text-text-muted hover:bg-white/10 border border-white/5 shadow-sm transition-all active:rotate-45">
                <i class="ti text-2xl" :class="darkMode ? 'ti-sun text-amber-500' : 'ti-moon text-primary'"></i>
            </button>

            <!-- Notifications (Premium Dropdown) -->
            <livewire:backend.common.notification-dropdown />

            <!-- Profile Menu (Premium Design) -->
            <div x-data="{ open: false }" class="relative ml-2">
                <button @click="open = !open"
                    class="flex items-center gap-3 p-1.5 pr-4 rounded-3xl bg-slate-100/80 dark:bg-slate-800/50 border border-transparent hover:bg-white dark:hover:bg-slate-800 hover:border-border-glass shadow-inner hover:shadow-sm transition-all active:scale-95 group">
                    <div class="relative">
                        <div
                            class="w-10 h-10 bg-linear-to-br from-primary to-accent rounded-xl flex items-center justify-center shadow-lg shadow-primary/20 overflow-hidden transition-transform group-hover:rotate-6">
                            @if (Auth::user()->avatar_url ?? null)
                                <img src="{{ Auth::user()->avatar_url }}" class="w-full h-full object-cover">
                            @else
                                <i class="ti ti-user-bolt text-white text-xl"></i>
                            @endif
                        </div>
                        <span
                            class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 bg-success border-2 border-bg-surface rounded-full shadow-sm"></span>
                    </div>
                    <div class="hidden lg:flex flex-col items-start translate-y-px">
                        <span
                            class="text-sm font-black text-text-main leading-none tracking-tight">{{ Auth::user()->name ?? 'Administrator' }}</span>
                        <span
                            class="text-[10px] font-bold text-text-muted uppercase tracking-[0.15em] mt-1">{{ Auth::user()->role ?? 'Super Admin' }}</span>
                    </div>
                    <i class="hidden lg:block ti ti-selector text-text-muted/50 ml-1 text-lg"></i>
                </button>

                <!-- Profile Dropdown -->
                <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                    class="absolute right-0 mt-4 w-64 glass-card overflow-hidden" x-cloak>
                    <div class="p-6 bg-white/5 border-b border-white/5 flex flex-col items-center text-center">
                        <div
                            class="w-20 h-20 bg-linear-to-br from-primary to-accent rounded-3xl flex items-center justify-center text-white text-3xl shadow-xl shadow-primary/20 mb-4 overflow-hidden">
                            @if (Auth::user()->avatar_url)
                                <img src="{{ Auth::user()->avatar_url }}" class="w-full h-full object-cover">
                            @else
                                <span class="font-black">LC</span>
                            @endif
                        </div>
                        <h4 class="font-black text-text-main text-lg tracking-tight">
                            {{ Auth::user()->name ?? 'Admin' }}</h4>
                        <p class="text-xs font-bold text-text-muted uppercase tracking-widest mt-1">
                            {{ Auth::user()->email ?? 'admin@example.com' }}</p>
                    </div>

                    <div class="p-3">
                        <div class="space-y-1">
                            <a href="{{ route('backend.profile.index') }}"
                                class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[13px] font-bold text-text-muted hover:bg-white/5 hover:text-primary transition-all group">
                                <div
                                    class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                                    <i class="ti ti-user-cog text-lg transition-transform group-hover:scale-110"></i>
                                </div>
                                Hồ sơ cá nhân
                            </a>
                            <a href="{{ route('backend.profile.security') }}"
                                class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[13px] font-bold text-text-muted hover:bg-white/5 hover:text-primary transition-all group">
                                <div
                                    class="w-8 h-8 rounded-lg bg-accent/10 flex items-center justify-center text-accent">
                                    <i class="ti ti-key text-lg transition-transform group-hover:scale-110"></i>
                                </div>
                                Bảo mật tài khoản
                            </a>
                            <button type="button" @click="configOpen = true; open = false"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-[13px] font-bold text-text-muted hover:bg-white/5 hover:text-primary transition-all group text-left">
                                <div
                                    class="w-8 h-8 rounded-lg bg-indigo-500/10 flex items-center justify-center text-indigo-500">
                                    <i class="ti ti-palette text-lg transition-transform group-hover:scale-110"></i>
                                </div>
                                Cấu hình giao diện
                            </button>
                        </div>

                        <div class="my-3 mx-4 border-t border-white/5"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-[13px] font-bold text-danger hover:bg-danger/10 transition-all group text-left">
                                <div
                                    class="w-8 h-8 rounded-lg bg-danger/10 flex items-center justify-center text-danger">
                                    <i
                                        class="ti ti-logout-2 text-lg transition-transform group-hover:translate-x-1"></i>
                                </div>
                                Đăng xuất
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

@pushOnce('scripts')
    <script>
        function timeOfDayIcons() {
            return {
                icon: '',
                setTimeOfDay() {
                    const hours = new Date().getHours();
                    if (hours >= 5 && hours < 10) {
                        this.icon = '🌅';
                    } else if (hours >= 10 && hours < 17) {
                        this.icon = '🌞';
                    } else if (hours >= 17 && hours < 20) {
                        this.icon = '🌇';
                    } else {
                        this.icon = '🌙';
                    }
                }
            }
        }
    </script>
@endPushOnce


