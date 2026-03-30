@props([
    'title' => null,
    'icon' => null,
    'color' => 'primary', // primary, blue, orange, purple, emerald, pink, amber
    'action' => null,
    'padding' => 'pt-2'
])

@php
    $colorClasses = match($color) {
        'blue' => 'bg-blue-500/10 text-blue-500 border-blue-500/10',
        'orange' => 'bg-orange-500/10 text-orange-500 border-orange-500/10',
        'purple' => 'bg-purple-500/10 text-purple-500 border-purple-500/10',
        'emerald' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/10',
        'pink' => 'bg-pink-500/10 text-pink-500 border-pink-500/10',
        'amber' => 'bg-amber-500/10 text-amber-500 border-amber-500/10',
        default => 'bg-primary/10 text-primary border-primary/10',
    };
@endphp

<x-backend.layout.card {{ $attributes }}>
    @if($title || $icon)
        <x-slot:title>
            <div class="flex items-center justify-between w-full">
                <div class="flex items-center gap-3">
                    @if($icon)
                        <div class="p-2 rounded-xl border {{ $colorClasses }}">
                            <i class="ti {{ $icon }} text-xl"></i>
                        </div>
                    @endif
                    @if($title)
                        <span class="text-xl font-bold tracking-tight">{{ $title }}</span>
                    @endif
                </div>
                @if($action)
                    <div>{{ $action }}</div>
                @endif
            </div>
        </x-slot:title>
    @endif

    <div class="{{ $padding }}">
        {{ $slot }}
    </div>
</x-backend.layout.card>
