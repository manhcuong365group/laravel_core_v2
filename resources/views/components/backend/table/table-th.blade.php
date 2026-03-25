@props([
    'sort' => null,
    'sortField' => null,
    'sortDirection' => null,
    'class' => '',
])

@php
    $canSort = !empty($sort);
    $isSortedByMe = $canSort && $sortField === $sort;
    $alignmentClass = '';
    // Kiểm tra trực tiếp biến $class vì nó đã được khai báo trong @props
    if (strpos($class, 'text-center') !== false) $alignmentClass = 'justify-center';
    elseif (strpos($class, 'text-right') !== false) $alignmentClass = 'justify-end';
@endphp

<th {{ $attributes->merge(['class' => 'px-6 py-4 text-[11px] font-black text-text-muted uppercase tracking-[0.15em] ' . $class . ' ' . ($canSort ? 'cursor-pointer hover:text-primary transition-colors' : '')]) }}
    @if($canSort) wire:click="sortBy('{{ $sort }}')" @endif>
    <div class="flex items-center gap-1 {{ $alignmentClass }}">
        {{ $slot }}
        @if($canSort)
            @if($isSortedByMe)
                <i class="ti ti-chevron-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-primary"></i>
            @else
                <i class="ti ti-selector text-text-muted/30"></i>
            @endif
        @endif
    </div>
</th>
