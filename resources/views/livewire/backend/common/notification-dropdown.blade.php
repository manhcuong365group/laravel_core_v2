<div x-data="{ open: false }" class="relative">
    <button @click="open = !open"
        class="relative w-12 h-12 flex items-center justify-center rounded-2xl bg-slate-100/80 dark:bg-slate-800/50 text-text-muted hover:bg-white dark:hover:bg-slate-800 border-transparent border hover:border-border-glass shadow-inner hover:shadow-sm transition-all hover:text-primary group">
        <i class="ti ti-bell text-2xl transition-transform group-hover:scale-110 group-hover:rotate-12"></i>
        @if($this->unreadCount > 0)
            <span class="absolute top-2.5 right-2.5 w-5 h-5 bg-linear-to-tr from-rose-500 to-orange-500 text-white text-[10px] font-black rounded-full flex items-center justify-center border-2 border-white dark:border-slate-900 shadow-lg shadow-rose-500/30 animate-pulse">
                {{ $this->unreadCount > 9 ? '9+' : $this->unreadCount }}
            </span>
        @endif
    </button>

    <!-- Dropdown -->
    <div x-show="open" @click.away="open = false" 
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 scale-95"
        class="absolute right-0 mt-4 w-[360px] glass-card overflow-hidden z-50">
        
        <div class="px-6 py-5 border-b border-border-glass flex items-center justify-between bg-bg-main/50">
            <h3 class="font-black text-text-main uppercase tracking-widest text-xs">Thông báo mới</h3>
            <div class="flex items-center gap-3">
                @if($this->unreadCount > 0)
                    <span class="px-2 py-0.5 bg-primary/10 text-primary text-[10px] font-bold rounded-full">
                        {{ $this->unreadCount }} mới
                    </span>
                    <button wire:click="markAllAsRead" class="text-[10px] font-black text-text-muted hover:text-primary uppercase tracking-tighter transition-colors">
                        Đọc hết
                    </button>
                @endif
            </div>
        </div>

        <div class="max-h-[400px] overflow-y-auto custom-scrollbar">
            @forelse($this->notifications as $notif)
                <div wire:click="markAsRead('{{ $notif->id }}')"
                    class="flex items-start gap-4 px-6 py-5 hover:bg-bg-main transition-all border-b border-border-glass last:border-0 group cursor-pointer">
                    <div class="w-12 h-12 bg-linear-to-br {{ $notif->data['color'] ?? 'from-primary to-primary/80' }} rounded-2xl flex items-center justify-center shrink-0 shadow-lg transition-transform group-hover:scale-110">
                        <i class="{{ $notif->data['icon'] ?? 'ti ti-bell' }} text-white text-xl"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-text-main leading-tight truncate group-hover:text-primary transition-colors">
                            {{ $notif->data['title'] ?? 'Thông báo' }}
                        </p>
                        <p class="text-xs text-text-muted mt-1 line-clamp-2">
                            {{ $notif->data['message'] ?? '' }}
                        </p>
                        <p class="text-[10px] font-bold text-text-muted uppercase tracking-widest mt-2 flex items-center gap-1">
                            <i class="ti ti-clock"></i> {{ $notif->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <div class="w-20 h-20 rounded-3xl bg-white/5 flex items-center justify-center mx-auto mb-4 border border-white/5 shadow-inner">
                        <i class="ti ti-bell-off text-3xl text-text-muted/20"></i>
                    </div>
                    <p class="text-sm font-black text-text-main uppercase tracking-widest">Tuyệt vời!</p>
                    <p class="text-xs text-text-muted mt-1">Bạn đã đọc hết tất cả thông báo.</p>
                </div>
            @endforelse
        </div>

        @if(count($this->notifications) > 0)
            <a href="#"
                class="block text-center py-4 bg-slate-50 dark:bg-slate-700/50 hover:bg-primary hover:text-white text-[11px] font-black uppercase tracking-[0.2em] transition-all">
                Xem tất cả thông báo
            </a>
        @endif
    </div>
</div>
