@props([
    'active' => false,
    'type' => 'status', // 'status' (eye), 'featured' (star), 'toggle' (check)
    'title' => '',
])

@php
    $config = [
        'status' => [
            'icon_on' => 'ti ti-eye',
            'icon_off' => 'ti ti-eye-off',
            'color_on' => 'bg-emerald-500/10 border-emerald-500/20 text-emerald-500 shadow-emerald-500/10',
            'color_off' => 'bg-rose-500/5 border-rose-500/10 text-rose-400',
        ],
        'featured' => [
            'icon_on' => 'ti ti-star',
            'icon_off' => 'ti ti-star',
            'color_on' => 'bg-amber-500/10 border-amber-500/20 text-amber-500 shadow-amber-500/10',
            'color_off' => 'bg-slate-100 border-slate-200 text-slate-400',
        ],
        'toggle' => [
            'icon_on' => 'ti ti-check',
            'icon_off' => 'ti ti-x',
            'color_on' => 'bg-primary/10 border-primary/20 text-primary shadow-primary/10',
            'color_off' => 'bg-slate-100 border-slate-200 text-slate-400',
        ]
    ];

    $selected = $config[$type] ?? $config['status'];
    $icon = $active ? $selected['icon_on'] : $selected['icon_off'];
    $colors = $active ? $selected['color_on'] : $selected['color_off'];
@endphp

<button type="button" 
    title="{{ $title }}"
    {{ $attributes->merge(['class' => "flex items-center justify-center h-8 w-8 rounded-lg border transition-all hover:scale-110 active:scale-95 shadow-sm $colors"]) }}
>
    <i class="{{ $icon }} text-sm {{ ($type === 'featured' && $active) ? 'fill-current' : '' }}"></i>
</button>
