@props([
    'status' => 'default', // success, danger, warning, info, neutral
    'label' => '',
    'animate' => false
])

@php
    $classes = match($status) {
        'success' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
        'danger' => 'bg-rose-500/10 text-rose-500 border-rose-500/20',
        'warning' => 'bg-amber-500/10 text-amber-500 border-amber-500/20',
        'info' => 'bg-blue-500/10 text-blue-500 border-blue-500/20',
        'purple' => 'bg-purple-500/10 text-purple-500 border-purple-500/20',
        default => 'bg-white/5 text-text-muted border-white/10',
    };
    
    $dotClasses = match($status) {
        'success' => 'bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.6)]',
        'danger' => 'bg-rose-500 shadow-[0_0_8px_rgba(244,63,94,0.6)]',
        'warning' => 'bg-amber-500 shadow-[0_0_8px_rgba(245,158,11,0.6)]',
        'info' => 'bg-blue-500 shadow-[0_0_8px_rgba(59,130,246,0.6)]',
        'purple' => 'bg-purple-500 shadow-[0_0_8px_rgba(168,85,247,0.6)]',
        default => 'bg-text-muted opacity-50',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg border text-[10px] font-black uppercase tracking-widest transition-all $classes"]) }}>
    <span class="w-1.5 h-1.5 rounded-full {{ $dotClasses }} {{ $animate ? 'animate-pulse' : '' }}"></span>
    {{ $label }}
</span>
