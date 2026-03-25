@props(['field', 'sortField', 'sortDirection'])

@if($sortField === $field)
    <i class="ti ti-chevron-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-primary"></i>
@else
    <i class="ti ti-selector text-text-muted/30"></i>
@endif
