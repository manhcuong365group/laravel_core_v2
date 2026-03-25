@props([
    'paginator' => null,
    'total' => null,
    'recordLabel' => 'bản ghi',
])

@php
    $totalRecords = $total ?? ($paginator ? $paginator->total() : 0);
@endphp

<div {{ $attributes->merge(['class' => 'mt-4 flex flex-col gap-3 md:flex-row md:items-center md:justify-between']) }}>
    <div class="text-sm text-text-muted">
        Tổng số: <span class="font-semibold text-text-main">{{ number_format((int) $totalRecords) }}</span> {{ $recordLabel }}
    </div>

    @if ($paginator && $paginator->hasPages())
        <div>
            {{ $paginator->links() }}
        </div>
    @endif
</div>

