@props([
    'show' => 'false',
    'title' => 'Xác nhận',
    'message' => 'Bạn có chắc chắn muốn thực hiện hành động này?',
])

<div x-data="{ open: @entangle($show) }" 
     x-show="open" 
     x-cloak
     class="fixed inset-0 z-[100] flex items-center justify-center p-4">
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="absolute inset-0 bg-black/60 backdrop-blur-sm" 
         @click="open = false"></div>

    <div x-show="open"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 translate-y-4"
         class="relative w-full max-w-md rounded-3xl border border-white/10 bg-bg-surface/90 backdrop-blur-xl p-6 shadow-2xl">
        
        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 rounded-2xl bg-danger/10 flex items-center justify-center text-danger">
                <i class="ti ti-alert-triangle text-2xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-text-main leading-none">{{ $title }}</h3>
                <p class="text-sm text-text-muted mt-2 leading-relaxed">{{ $message }}</p>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 mt-8">
            {{ $slot }}
        </div>
    </div>
</div>

