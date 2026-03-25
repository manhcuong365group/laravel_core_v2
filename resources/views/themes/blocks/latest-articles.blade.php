{{-- Latest Articles Block --}}
@php
    /** @var \App\Models\Article[]|\Illuminate\Database\Eloquent\Collection $articles */
    $articles = \App\Models\Article::with('media', 'author', 'category')
        ->where('type', $data['type'] ?? 'post')
        ->where('is_active', true)
        ->latest()
        ->take($data['limit'] ?? 6)
        ->get();
    $cols = $data['columns'] ?? 3;
@endphp

<section id="{{ $blockId }}" class="py-24 px-6 bg-white relative" x-data="{ show: false }"
    x-intersect.once="show = true">

    {{-- Delicate Background pattern --}}
    <div
        class="absolute inset-0 bg-[linear-gradient(to_right,#8080800a_1px,transparent_1px),linear-gradient(to_bottom,#8080800a_1px,transparent_1px)] bg-size-[14px_24px] pointer-events-none">
    </div>

    <div class="max-w-7xl mx-auto relative z-10 transition-all duration-1000 transform translate-y-8 opacity-0"
        :class="show ? 'translate-y-0 opacity-100' : ''">

        @if (!empty($data['title']))
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-16">
                <div class="max-w-2xl">
                    <h2 class="text-4xl lg:text-5xl font-black text-slate-900 mb-4 tracking-tight">{{ $data['title'] }}
                    </h2>
                    @if (!empty($data['subtitle']))
                        <p class="text-slate-500 text-lg font-medium">{{ $data['subtitle'] }}</p>
                    @endif
                </div>
                <a href="#"
                    class="inline-flex items-center gap-2 text-indigo-600 font-bold hover:text-indigo-700 hover:translate-x-1 transition-transform group">
                    Xem tất cả bài viết <i class="ti ti-arrow-right transition-transform group-hover:translate-x-1"></i>
                </a>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-{{ $cols }} gap-8 lg:gap-10">
            @foreach ($articles as $index => $article)
                <article
                    class="group bg-white rounded-3xl overflow-hidden flex flex-col h-full border border-slate-100/50 shadow-sm hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 relative"
                    style="transition-delay: {{ $index * 100 }}ms;">

                    {{-- Category Pill --}}
                    @if ($article->category)
                        <div class="absolute top-4 left-4 z-20">
                            <span
                                class="px-3 py-1 bg-white/90 backdrop-blur-md text-indigo-600 text-xs font-bold rounded-full shadow-sm">{{ $article->category->name }}</span>
                        </div>
                    @endif

                    <div class="aspect-video relative overflow-hidden bg-slate-50">
                        @if ($article->getFirstMediaUrl('featured_image'))
                            <img src="{{ $article->getFirstMediaUrl('featured_image') }}" alt="{{ $article->title }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out">
                        @else
                            <div
                                class="w-full h-full bg-linear-to-br from-indigo-50 to-blue-50 flex items-center justify-center">
                                <i class="ti ti-photo-off text-5xl text-indigo-200"></i>
                            </div>
                        @endif
                    </div>

                    <div class="p-6 md:p-8 flex flex-col grow">
                        @if ($data['show_date'] ?? true)
                            <div
                                class="flex items-center gap-2 text-xs text-slate-400 font-bold uppercase tracking-wider mb-3">
                                <i class="ti ti-calendar-event"></i>
                                <time datetime="{{ $article->created_at->toIso8601String() }}">
                                    {{ $article->created_at->format('d/m/Y') }}
                                </time>
                            </div>
                        @endif

                        <h3
                            class="text-xl font-bold text-slate-900 leading-snug line-clamp-2 group-hover:text-indigo-600 transition-colors mb-3">
                            <a href="#" class="after:absolute after:inset-0">{{ $article->title }}</a>
                        </h3>

                        @if (($data['show_excerpt'] ?? true) && $article->excerpt)
                            <p class="text-slate-500 text-sm leading-relaxed line-clamp-3 mb-6">{{ $article->excerpt }}
                            </p>
                        @endif

                        <div class="mt-auto flex items-center gap-3 pt-6 border-t border-slate-100 relative z-20">
                            {{-- Author Info Fake if not present --}}
                            <div class="w-8 h-8 rounded-full bg-slate-200 overflow-hidden">
                                @if ($article->author && $article->author->avatar)
                                    <img src="{{ $article->author->avatar }}" alt=""
                                        class="w-full h-full object-cover">
                                @else
                                    <div
                                        class="w-full h-full flex items-center justify-center bg-indigo-100 text-indigo-500 font-bold text-xs">
                                        {{ substr($article->author->name ?? 'A', 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <span
                                class="text-sm font-bold text-slate-700">{{ $article->author->name ?? 'Admin' }}</span>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
