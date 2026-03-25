@props([
    'title' => '',
    'description' => '',
])

<section {{ $attributes->merge(['class' => 'rounded-2xl border border-border-glass bg-bg-surface/60 p-5 md:p-6']) }}>
    @if ($title)
        <div class="mb-4">
            <h3 class="text-base md:text-lg font-bold text-text-main">{{ $title }}</h3>
            @if ($description)
                <p class="text-sm text-text-muted mt-1">{{ $description }}</p>
            @endif
        </div>
    @endif

    <div class="space-y-4">
        {{ $slot }}
    </div>
</section>

