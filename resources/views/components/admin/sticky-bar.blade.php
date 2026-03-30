@props([
    'target' => 'save',
    'cancelHref' => '#',
    'info' => null,
    'saveLabel' => 'Lưu thay đổi',
    'mode' => 'Edit'
])

<div class="fixed bottom-8 left-1/2 -translate-x-1/2 z-[100] w-[calc(100%-2rem)] max-w-5xl animate-in slide-in-from-bottom-full duration-700 delay-300">
    <div class="relative group">
        <!-- Glow Effect -->
        <div class="absolute -inset-0.5 bg-gradient-to-r from-primary via-blue-500 to-indigo-500 rounded-full blur-xl opacity-20 group-hover:opacity-40 transition duration-1000 group-hover:duration-300"></div>
        
        <!-- Glass Bar Container -->
        <div class="relative flex items-center gap-4 px-4 py-4 md:px-8 md:py-5 bg-black/60 backdrop-blur-3xl rounded-full border border-white/10 shadow-[0_0_50px_rgba(0,0,0,1)]">
            
            <!-- Icon Detail (Desktop Only) -->
            <div class="hidden md:flex items-center gap-4 pr-6 border-r border-white/10">
                <div class="w-10 h-10 rounded-2xl bg-white/5 flex items-center justify-center border border-white/5 shadow-inner">
                    <i class="ti ti-edit text-primary text-xl"></i>
                </div>
                <div class="flex flex-col min-w-[120px]">
                    <span class="text-[10px] font-black uppercase tracking-widest text-text-muted mb-0.5 opacity-60 italic">Mode: {{ $mode }}</span>
                    {{ $info }}
                </div>
            </div>

            <!-- Secondary Actions -->
            <div class="flex items-center gap-2 flex-1 md:flex-none">
                <x-backend.ui.button variant="neutral" :href="$cancelHref" class="flex-1 md:flex-none h-12 rounded-full px-6 bg-white/5 border-white/10 hover:bg-white/10 hover:text-white transition-all active:scale-95">
                    <span class="font-black uppercase tracking-tighter text-[11px]">Hủy bỏ</span>
                </x-backend.ui.button>
            </div>

            <!-- Primary Action -->
            <div class="flex-1 md:w-auto relative group/save">
                <button type="submit" 
                    wire:loading.attr="disabled" 
                    wire:target="{{ $target }}" 
                    class="w-full h-12 md:px-10 flex items-center justify-center gap-3 rounded-full bg-gradient-to-r from-primary to-blue-600 text-white font-black text-sm uppercase tracking-widest shadow-2xl shadow-primary/20 hover:shadow-primary/40 active:scale-95 transition-all duration-300 relative overflow-hidden disabled:opacity-50 disabled:cursor-not-allowed">
                    
                    <!-- Shine Effect -->
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover/save:translate-x-full transition-transform duration-1000 ease-in-out"></div>

                    <span wire:loading.remove wire:target="{{ $target }}" class="flex items-center gap-2.5">
                        <i class="ti ti-device-floppy text-xl"></i> {{ $saveLabel }}
                    </span>
                    
                    <span wire:loading wire:target="save" class="flex items-center gap-3">
                        <i class="ti ti-loader animate-spin text-xl"></i> <span>Đang lưu...</span>
                    </span>
                    
                    @if(str_contains($target, 'image') || str_contains($target, 'gallery'))
                        <span wire:loading wire:target="featured_image, gallery" class="flex items-center gap-3">
                            <i class="ti ti-upload-cloud animate-pulse text-xl"></i> <span>Đang xử lý media...</span>
                        </span>
                    @endif
                </button>
                <!-- Small Shortcut Hint (Desktop) -->
                <div class="absolute -top-10 left-1/2 -translate-x-1/2 bg-black/80 px-2.5 py-1 rounded-lg text-[10px] font-bold text-white/40 border border-white/5 opacity-0 group-hover/save:opacity-100 transition-opacity duration-300 pointer-events-none whitespace-nowrap">
                    Shortcut: <span class="text-white">⌘ + S</span>
                </div>
            </div>
        </div>
    </div>
</div>
