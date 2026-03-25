@props([
    'type' => 'primary',
    'htmlType' => 'button',
    'href' => null,
    'size' => 'md',
    'icon' => null,
    'iconPosition' => 'left',
    'block' => false,
    'active' => false,
    'loading' => false,
    'disabled' => false,
])

@php
    $baseClasses =
        'inline-flex items-center justify-center font-semibold rounded-xl border transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-bg-main active:scale-[0.99] disabled:opacity-60 disabled:cursor-not-allowed';

    $types = [
        'primary' =>
            'border-primary bg-primary text-white shadow-glow-primary hover:bg-primary/90 hover:border-primary/90 focus:ring-primary/45',
        'secondary' =>
            'border-white/10 bg-white/5 text-text-main hover:bg-white/10 hover:border-white/20 focus:ring-white/20',
        'success' => 'border-success bg-success text-white hover:bg-success/90 focus:ring-success/40',
        'danger' => 'border-danger bg-danger text-white hover:bg-danger/90 focus:ring-danger/40',
        'warning' => 'border-accent bg-accent text-white hover:bg-accent/90 focus:ring-accent/40',
        'outline' =>
            'border-border-glass bg-transparent text-text-muted hover:text-text-main hover:bg-white/5 focus:ring-white/15',
    ];

    $sizes = [
        'xs' => 'h-7 px-2.5 text-xs gap-1',
        'sm' => 'h-9 px-3.5 text-sm gap-1.5',
        'md' => 'h-10 px-4 text-sm gap-2',
        'lg' => 'h-11 px-5 text-base gap-2',
        'xl' => 'h-12 px-6 text-lg gap-2.5',
    ];

    $activeClass = $active ? 'ring-2 ring-primary/35 border-primary/80' : '';
    $blockClass = $block ? 'w-full' : '';
    $disabledAttrs = $disabled || $loading;
    $stateClass = $disabledAttrs ? 'pointer-events-none' : '';
    $classes =
        trim($baseClasses . ' ' . ($types[$type] ?? $types['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']) . ' ' . $activeClass . ' ' . $blockClass . ' ' . $stateClass);
@endphp

@if ($href)
    <a href="{{ $disabledAttrs ? '#' : $href }}" @if ($disabledAttrs) aria-disabled="true" @endif
        {{ $attributes->merge(['class' => $classes]) }}>
        @if ($loading)
            <i class="ti ti-loader-2 animate-spin text-base"></i>
        @endif
        @if ($icon && $iconPosition === 'left' && !$loading)
            <i class="{{ $icon }} text-base"></i>
        @endif
        {{ $slot }}
        @if ($icon && $iconPosition === 'right' && !$loading)
            <i class="{{ $icon }} text-base"></i>
        @endif
    </a>
@else
    <button {{ $attributes->merge(['class' => $classes, 'type' => $htmlType, 'disabled' => $disabledAttrs]) }}>
        @if ($loading)
            <i class="ti ti-loader-2 animate-spin text-base"></i>
        @endif
        @if ($icon && $iconPosition === 'left' && !$loading)
            <i class="{{ $icon }} text-base"></i>
        @endif
        {{ $slot }}
        @if ($icon && $iconPosition === 'right' && !$loading)
            <i class="{{ $icon }} text-base"></i>
        @endif
    </button>
@endif
