{{-- Text Content Block --}}
<section id="{{ $blockId }}" class="py-24 px-6 relative overflow-hidden"
    @if (!empty($data['bg_color'])) style="background-color: {{ $data['bg_color'] }}" @else class="bg-white" @endif
    x-data="{ show: false }" x-intersect.once="show = true">

    <div class="mx-auto {{ $data['max_width'] ?? 'max-w-4xl' }} relative z-10 transition-all duration-1000 transform translate-y-8 opacity-0"
        style="text-align: {{ $data['text_align'] ?? 'left' }}" :class="show ? 'translate-y-0 opacity-100' : ''">

        @if (!empty($data['title']))
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 mb-8 tracking-tight leading-tight">
                {{ $data['title'] }}</h2>
        @endif

        @if (!empty($data['content']))
            <div
                class="prose prose-lg lg:prose-xl prose-slate max-w-none text-slate-600 prose-headings:font-black prose-headings:text-slate-900 prose-a:text-indigo-600 prose-a:font-bold prose-a:no-underline hover:prose-a:underline prose-img:rounded-3xl prose-img:shadow-xl prose-li:marker:text-indigo-500">
                {!! $data['content'] ?? '' !!}
            </div>
        @endif
    </div>
</section>
