@extends('theme::layouts.app')

@section('title', $article->title . ' - ' . (app(\App\Services\TenantManager::class)->getTenant()->name ??
    config('app.name')))

@section('content')
    <div class="bg-bg-main py-12">
        <div class="container mx-auto px-4">
            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-sm mb-12 text-text-muted">
                <a href="/" class="hover:text-primary transition-colors">Trang chủ</a>
                <svg class="w-4 h-4 opacity-30" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" />
                </svg>
                <a href="{{ route('articles.index') }}" class="hover:text-primary transition-colors">Tin tức</a>
                <svg class="w-4 h-4 opacity-30" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" />
                </svg>
                <span class="text-text-main font-bold truncate">{{ $article->title }}</span>
            </nav>

            <div class="max-w-4xl mx-auto">
                <header class="mb-12 text-center">
                    @if ($article->category)
                        <span
                            class="px-3 py-1 bg-primary/10 text-primary text-[10px] font-black uppercase rounded-full tracking-widest mb-6 inline-block">
                            {{ $article->category->name }}
                        </span>
                    @endif
                    <h1 class="text-4xl md:text-6xl font-black tracking-tight mb-8 leading-tight">{{ $article->title }}</h1>
                    <div class="flex items-center justify-center gap-6 text-sm text-text-muted font-medium">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ $article->published_at->format('d/m/Y') }}
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            {{ $article->view_count }} views
                        </div>
                    </div>
                </header>

                @if ($article->getFirstMediaUrl('featured_image'))
                    <div class="glass-card mb-12 overflow-hidden rounded-3xl">
                        <img src="{{ $article->getFirstMediaUrl('featured_image') }}" alt="{{ $article->title }}"
                            class="w-full aspect-video object-cover">
                    </div>
                @endif

                <div class="glass-card p-8 md:p-16 mb-16">
                    <!-- Content -->
                    <div
                        class="prose prose-lg md:prose-xl prose-slate dark:prose-invert max-w-none prose-headings:font-black prose-a:text-primary leading-loose">
                        {!! $article->content !!}
                    </div>

                    <!-- Tags -->
                    @if ($article->tags && $article->tags->count() > 0)
                        <div class="mt-16 pt-8 border-t border-border-glass">
                            <div class="flex flex-wrap gap-2 text-sm font-bold">
                                <span class="text-text-muted mr-2">Tags:</span>
                                @foreach ($article->tags as $tag)
                                    <a href="#"
                                        class="px-3 py-1 bg-bg-main hover:bg-primary hover:text-white rounded-lg transition-all">#{{ $tag->name }}</a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Author Box -->
                <div class="glass-card p-8 flex items-center gap-8 mb-16">
                    <div class="w-20 h-20 bg-primary/10 rounded-full overflow-hidden shrink-0">
                        @if ($article->author && $article->author->profile_photo_url)
                            <img src="{{ $article->author->profile_photo_url }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-primary font-black text-2xl">
                                {{ strtoupper(substr($article->author->name ?? 'A', 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div>
                        <h4 class="text-xl font-black mb-2">{{ $article->author->name ?? 'Ban biên tập' }}</h4>
                        <p class="text-text-muted text-sm leading-relaxed">Chuyên gia phân tích và biên tập nội dung số tại
                            {{ config('app.name') }}. Đam mê công nghệ và sự sáng tạo.</p>
                    </div>
                </div>

                <!-- Related -->
                @if ($relatedArticles->count() > 0)
                    <section>
                        <h3 class="text-3xl font-black mb-8">Tin liên quan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            @foreach ($relatedArticles as $ra)
                                <a href="{{ route('articles.show', $ra->slug) }}" class="glass-card group flex flex-col">
                                    <div class="aspect-video overflow-hidden rounded-t-2xl">
                                        <img src="{{ $ra->getFirstMediaUrl('featured_image') ?: '/placeholder.jpg' }}"
                                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    </div>
                                    <div class="p-6">
                                        <h4
                                            class="font-bold group-hover:text-primary transition-colors line-clamp-2 leading-tight">
                                            {{ $ra->title }}</h4>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>
        </div>
    </div>
@endsection
