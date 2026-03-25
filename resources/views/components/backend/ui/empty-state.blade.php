@props([
    'title' => 'Khong co du lieu',
    'description' => 'Khong tim thay ket qua phu hop.',
    'icon' => 'ti ti-inbox',
])

<div {{ $attributes->merge(['class' => 'rounded-2xl border border-dashed border-border-glass p-8 text-center']) }}>
    <i class="{{ $icon }} text-3xl text-text-muted/70"></i>
    <h4 class="text-sm md:text-base font-semibold text-text-main mt-3">{{ $title }}</h4>
    <p class="text-sm text-text-muted mt-1">{{ $description }}</p>
    @if (!$slot->isEmpty())
        <div class="mt-4">
            {{ $slot }}
        </div>
    @endif
</div>

