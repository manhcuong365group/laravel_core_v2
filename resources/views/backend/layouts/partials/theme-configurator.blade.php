<div x-data="{
    themeColor: localStorage.getItem('themeColor') || '#6366f1',
    sidebarStyle: localStorage.getItem('sidebarStyle') || 'glass',

    setThemeColor(color) {
        this.themeColor = color;
        localStorage.setItem('themeColor', color);
        document.documentElement.style.setProperty('--primary', color);
        // Generate glow color with opacity
        const hex = color.replace('#', '');
        const r = parseInt(hex.substring(0, 2), 16);
        const g = parseInt(hex.substring(2, 4), 16);
        const b = parseInt(hex.substring(4, 6), 16);
        document.documentElement.style.setProperty('--primary-glow', `rgba(${r}, ${g}, ${b}, 0.3)`);
    },

    init() {
        this.setThemeColor(this.themeColor);
    }
}" x-init="init()">

    <!-- Config Panel -->

        <div x-show="configOpen" x-cloak class="fixed inset-0 z-60">
            <!-- Backdrop -->
            <div x-show="configOpen" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" @click="configOpen = false"
                class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm"></div>

            <!-- Panel -->
            <div x-show="configOpen" x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="translate-x-0"
                x-transition:leave-end="translate-x-full"
                class="absolute right-0 top-0 bottom-0 w-80 bg-bg-surface border-l border-border-glass shadow-2xl p-8 flex flex-col overflow-y-auto custom-scrollbar">

                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-xl font-black text-text-main tracking-tight uppercase">Giao diện</h3>
                    <button @click="configOpen = false"
                        class="text-text-muted hover:text-danger hover:rotate-90 transition-all">
                        <i class="ti ti-x text-2xl"></i>
                    </button>
                </div>

                <!-- Theme Mode -->
                <div class="space-y-4 mb-8">
                    <label class="text-xs font-black text-text-muted uppercase tracking-widest">Chế độ hiển thị</label>
                    <div class="grid grid-cols-2 gap-3">
                        <button @click="darkMode = false"
                            class="flex flex-col items-center gap-3 p-4 rounded-2xl border-2 transition-all group"
                            :class="!darkMode ? 'border-primary bg-primary/5' : 'border-border-glass hover:border-primary/50'">
                            <div
                                class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600">
                                <i class="ti ti-sun text-xl"></i>
                            </div>
                            <span class="text-xs font-bold uppercase tracking-tight"
                                :class="!darkMode ? 'text-primary' : 'text-text-muted'">Sáng</span>
                        </button>
                        <button @click="darkMode = true"
                            class="flex flex-col items-center gap-3 p-4 rounded-2xl border-2 transition-all group"
                            :class="darkMode ? 'border-primary bg-primary/5' : 'border-border-glass hover:border-primary/50'">
                            <div
                                class="w-10 h-10 rounded-xl bg-slate-800 flex items-center justify-center text-amber-500">
                                <i class="ti ti-moon-stars text-xl"></i>
                            </div>
                            <span class="text-xs font-bold uppercase tracking-tight"
                                :class="darkMode ? 'text-primary' : 'text-text-muted'">Tối</span>
                        </button>
                    </div>
                </div>

                <!-- Accent Color -->
                <div class="space-y-4 mb-8">
                    <label class="text-xs font-black text-text-muted uppercase tracking-widest">Màu chủ đạo</label>
                    <div class="grid grid-cols-4 gap-3">
                        <template
                            x-for="color in ['#6366f1', '#a855f7', '#ec4899', '#f43f5e', '#f59e0b', '#10b981', '#06b6d4', '#3b82f6']">
                            <button @click="setThemeColor(color)"
                                class="w-full aspect-square rounded-xl border-2 transition-all transform hover:scale-110 active:scale-95"
                                :style="`background-color: ${color}`"
                                :class="themeColor === color ? 'border-white ring-2 ring-primary/50' : 'border-transparent'">
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Theme Presets (Removed to keep 1 theme only) -->


                <!-- Features -->
                <div class="space-y-4 mb-8 mt-auto">
                    <div class="p-4 rounded-2xl bg-slate-500/5 border border-border-glass">
                        <p class="text-[10px] font-bold text-text-muted uppercase tracking-widest leading-relaxed">
                            Mẹo: Bạn có thể nhấn <kbd
                                class="px-1 bg-bg-surface border border-border-glass rounded tracking-normal">Ctrl +
                                K</kbd> để tìm kiếm nhanh các chức năng.
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <button @click="localStorage.clear(); location.reload();"
                        class="w-full py-3 rounded-xl bg-danger/10 text-danger text-xs font-black uppercase tracking-widest hover:bg-danger hover:text-white transition-all">
                        Reset mặc định
                    </button>
                </div>
            </div>
        </div>
</div>

<style>
    @keyframes spin-slow {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    .animate-spin-slow {
        animation: spin-slow 8s linear infinite;
    }

    .pause {
        animation-play-state: paused;
    }
</style>

