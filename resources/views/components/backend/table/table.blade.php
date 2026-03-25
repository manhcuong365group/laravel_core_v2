@props([
    'headers' => [],
])

<div {{ $attributes->merge(['class' => 'overflow-hidden rounded-2xl border border-border-glass bg-bg-surface/60']) }}>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-white/10">
            @if (!empty($headers))
                <thead class="bg-white/5">
                    <tr>
                        @foreach ($headers as $header)
                            <th class="px-4 py-3 text-left text-xs uppercase tracking-wide text-text-muted font-semibold">
                                {{ $header }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
            @endif
            <tbody class="divide-y divide-white/5">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>

