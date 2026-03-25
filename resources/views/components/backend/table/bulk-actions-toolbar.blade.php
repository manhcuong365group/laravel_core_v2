@props(['count' => 0])

@if ($count > 0)
    <div 
        x-data="{ show: false }" 
        x-init="setTimeout(() => show = true, 50)"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-10"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-10"
        class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 px-6 py-3 bg-slate-900/90 dark:bg-slate-800/90 backdrop-blur-xl border border-white/10 rounded-2xl shadow-2xl flex items-center gap-6 min-w-[400px] max-w-[90vw]"
    >
        <div class="flex items-center gap-3 pr-6 border-r border-white/10 text-white">
            <div class="w-8 h-8 rounded-lg bg-primary/20 flex items-center justify-center text-primary font-bold text-sm">
                {{ $count }}
            </div>
            <span class="text-sm font-semibold whitespace-nowrap">Đã chọn {{ $count }} mục</span>
        </div>

        <div class="flex items-center gap-2">
            {{ $slot }}
        </div>

        <button 
            wire:click="$set('selectedItems', [])"
            class="ml-auto w-8 h-8 rounded-lg flex items-center justify-center hover:bg-white/5 text-white/40 hover:text-white/80 transition-colors"
            title="Bỏ chọn tất cả"
        >
            <i class="ti ti-x text-lg"></i>
        </button>
    </div>
@endif
