@props(['items'])

<nav class="flex mb-4" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-2">
        <li class="inline-flex items-center">
            <a href="{{ route('backend.dashboard') }}"
                class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
                <i class="ti ti-home mr-1"></i>
                Dashboard
            </a>
        </li>
        @foreach ($items as $item)
            <li>
                <div class="flex items-center">
                    <i class="ti ti-chevron-right text-slate-400 text-xs mx-1"></i>
                    @if (isset($item['url']))
                        <a href="{{ $item['url'] }}"
                            class="text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
                            {{ $item['label'] }}
                        </a>
                    @else
                        <span class="text-sm font-medium text-slate-800">{{ $item['label'] }}</span>
                    @endif
                </div>
            </li>
        @endforeach
    </ol>
</nav>

