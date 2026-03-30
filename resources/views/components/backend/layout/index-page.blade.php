@props([
    'title' => '',
    'subtitle' => '',
    'description' => '',
    'total' => 0,
    'totalLabel' => 'mục',
    'searchPlaceholder' => 'Tìm kiếm...',
    'showBulkActions' => false,
    'selectedCount' => 0,
    'icon' => 'ti ti-layout-2',
])

<div class="space-y-8 animate-in fade-in duration-700">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-2">
            @if($subtitle)
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/10 border border-primary/20 text-primary text-xs font-bold tracking-wider uppercase backdrop-blur-sm">
                    <i class="{{ $icon }} text-sm"></i>
                    <span>{{ $subtitle }}</span>
                </div>
            @endif
            
            <h1 class="text-4xl font-black text-text-main tracking-tight flex items-center gap-3">
                {{ $title }}
                @if($total > 0)
                    <span class="text-xs font-medium px-2 py-0.5 rounded-md bg-white/5 border border-white/10 text-text-muted mt-2">
                        {{ $total }} {{ $totalLabel }}
                    </span>
                @endif
            </h1>
            
            @if($description)
                <p class="text-text-muted max-w-2xl leading-relaxed">
                    {{ $description }}
                </p>
            @endif
        </div>

        @if(isset($headerActions))
            <div class="flex items-center gap-3">
                {{ $headerActions }}
            </div>
        @endif
    </div>

    {{-- Main Content Section --}}
    <div class="grid grid-cols-1 gap-8">
        {{-- Filters & Search Card --}}
        <x-backend.layout.card class="!p-0 border-none bg-transparent shadow-none overflow-visible">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-4 p-6 bg-white/5 border border-white/10 rounded-3xl backdrop-blur-md">
                <div class="flex flex-col sm:flex-row items-center gap-4 w-full lg:w-auto">
                    {{-- Search Input --}}
                    @if(isset($search))
                        {{ $search }}
                    @else
                        <div class="relative w-full sm:w-80 group">
                            <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-text-muted group-focus-within:text-primary transition-colors">
                                <i class="ti ti-search text-lg"></i>
                            </div>
                            <input
                                type="text"
                                wire:model.live.debounce.400ms="search"
                                placeholder="{{ $searchPlaceholder }}"
                                class="w-full pl-12 pr-12 h-12 bg-white/5 border border-white/10 rounded-2xl text-sm font-medium text-text-main placeholder-text-muted/50 focus:ring-4 focus:ring-primary/10 focus:border-primary/50 transition-all outline-none"
                            >
                            <div wire:loading wire:target="search" class="absolute inset-y-0 right-4 flex items-center">
                                <i class="ti ti-loader-2 animate-spin text-primary"></i>
                            </div>
                        </div>
                    @endif

                    {{-- Additional Filters --}}
                    @if(isset($filters))
                        <div class="flex items-center gap-2 w-full sm:w-auto overflow-x-auto no-scrollbar pb-1 sm:pb-0 font-medium">
                            {{ $filters }}
                        </div>
                    @endif
                </div>

                <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                    @if(isset($toolbar))
                        {{ $toolbar }}
                    @endif
                </div>
            </div>
        </x-backend.layout.card>

        {{-- Table Card --}}
        <x-backend.layout.card class="!p-0 border-none bg-white/5 backdrop-blur-md rounded-3xl overflow-hidden shadow-2xl">
            <div class="overflow-x-auto no-scrollbar min-h-60">
                {{ $table }}
            </div>

            {{-- Pagination Footer --}}
            @if (isset($pagination))
                <div class="px-8 py-6 border-t border-white/5 bg-white/[0.01]">
                    {{ $pagination }}
                </div>
            @endif
        </x-backend.layout.card>
    </div>

    {{-- Bulk Actions Toolbar --}}
    @if(isset($bulkActions))
        <x-backend.table.bulk-actions-toolbar :count="$selectedCount">
            {{ $bulkActions }}
        </x-backend.table.bulk-actions-toolbar>
    @endif

    {{-- Modals --}}
    @if(isset($modals))
        {{ $modals }}
    @endif
</div>
