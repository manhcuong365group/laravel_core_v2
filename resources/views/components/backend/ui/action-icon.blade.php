@props([
    'variant' => 'neutral',
    'href' => null,
    'htmlType' => 'button',
    'title' => '',
    'icon' => 'ti ti-dots',
])

@php
    $base = 'inline-flex items-center justify-center h-9 w-9 rounded-lg border transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-bg-main';
    $variants = [
        'neutral' => [
            'class' => 'border-border-glass text-text-muted hover:text-text-main hover:bg-white/5 focus:ring-white/20',
            'icon' => 'ti ti-dots'
        ],
        'edit' => [
            'class' => 'border-primary/25 text-primary hover:bg-primary/10 hover:border-primary/40 focus:ring-primary/35',
            'icon' => 'ti ti-edit'
        ],
        'delete' => [
            'class' => 'border-danger/25 text-danger hover:bg-danger/10 hover:border-danger/40 focus:ring-danger/35',
            'icon' => 'ti ti-trash'
        ],
        'view' => [
            'class' => 'border-info/25 text-info hover:bg-info/10 hover:border-info/40 focus:ring-info/35',
            'icon' => 'ti ti-external-link'
        ],
        'copy' => [
            'class' => 'border-violet-500/25 text-violet-500 hover:bg-violet-500/10 hover:border-violet-500/40 focus:ring-violet-500/35',
            'icon' => 'ti ti-copy'
        ],
    ];
    $selected = $variants[$variant] ?? $variants['neutral'];
    $classes = $base . ' ' . $selected['class'];
    $displayIcon = ($icon === 'ti ti-dots') ? $selected['icon'] : $icon;
@endphp

@if ($href)
    <a href="{{ $href }}" title="{{ $title }}" {{ $attributes->merge(['class' => $classes]) }}>
        <i class="{{ $displayIcon }} text-lg"></i>
    </a>
@else
    <button type="{{ $htmlType }}" title="{{ $title }}" {{ $attributes->merge(['class' => $classes]) }}>
        <i class="{{ $displayIcon }} text-lg"></i>
    </button>
@endif
