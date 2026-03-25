@props([
    'title' => '',
    'subtitle' => '',
    'footer' => false,
    'padding' => true,
])

<div {{ $attributes->merge(['class' => 'glass-card rounded-3xl overflow-hidden border border-white/10 hover:border-white/20 transition-all duration-500 hover:bg-white/[0.04] hover:shadow-2xl hover:shadow-primary/5 flex flex-col relative group']) }}>
    <!-- Decorative glow -->
    <div class="absolute -inset-[1px] bg-gradient-to-br from-primary/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none rounded-[inherit]"></div>

    @if ($title || $subtitle)
        <div class="px-6 py-5 border-b border-white/5 bg-white/[0.02] relative z-10 flex items-center justify-between">
            <div>
                @if ($title)
                    <h3 class="text-lg font-bold text-text-main tracking-tight group-hover:text-primary transition-colors duration-300">{{ $title }}</h3>
                @endif
                @if ($subtitle)
                    <p class="mt-1.5 text-sm text-text-muted">{{ $subtitle }}</p>
                @endif
            </div>
            @if(isset($headerActions))
                <div class="flex items-center gap-2">
                    {{ $headerActions }}
                </div>
            @endif
        </div>
    @endif

    <div class="{{ $padding ? 'p-6' : '' }} flex-1 relative z-10">
        {{ $slot }}
    </div>

    @if ($footer)
        <div class="px-6 py-4 bg-white/3 border-t border-white/5 relative z-10">
            {{ $footer }}
        </div>
    @endif
</div>
