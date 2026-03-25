<div {{ $attributes->merge(['class' => 'rounded-2xl border border-border-glass bg-bg-surface/60 p-4']) }}>
    <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-4">
        {{ $slot }}
    </div>
</div>

