@props([
    'align' => 'right',
    'width' => '48',
    'contentClasses' => 'py-1 bg-white/5 border border-white/10 backdrop-blur-3xl rounded-3xl shadow-2xl',
    'dropdownClasses' => ''
])

@php
switch ($align) {
    case 'left':
        $alignmentClasses = 'ltr:origin-top-left rtl:origin-top-right start-0';
        break;
    case 'top':
        $alignmentClasses = 'origin-top';
        break;
    case 'none':
    case 'false':
        $alignmentClasses = '';
        break;
    case 'right':
    default:
        $alignmentClasses = 'ltr:origin-top-right rtl:origin-top-left end-0';
        break;
}

switch ($width) {
    case '48':
        $width = 'w-48';
        break;
    case '56':
        $width = 'w-56';
        break;
    case '64':
        $width = 'w-64';
        break;
}
@endphp

<div class="relative {{ $dropdownClasses }}" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
    <div @click="open = ! open">
        {{ $trigger }}
    </div>

    <div x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
            class="absolute z-50 mt-4 {{ $width }} rounded-3xl overflow-hidden {{ $alignmentClasses }}"
            style="display: none;"
            @click="open = false">
        <div class="rounded-3xl ring-1 ring-white/5 {{ $contentClasses }}">
            {{ $content }}
        </div>
    </div>
</div>
