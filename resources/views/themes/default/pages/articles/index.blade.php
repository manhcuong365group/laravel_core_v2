@extends('theme::layouts.app')

@section('title', 'Tin tức - ' . (app(\App\Services\TenantManager::class)->getTenant()->name ?? config('app.name')))

@section('content')
    <div class="bg-bg-main py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center mb-20">
                <h1 class="text-5xl md:text-7xl font-black tracking-tight mb-8">Tin tức & Sự kiện</h1>
                <p class="text-xl text-text-muted leading-relaxed">Cập nhật những thông tin mới nhất về sản phẩm, công nghệ
                    và các chương trình ưu đãi hấp dẫn.</p>
            </div>

            @if ($featuredArticles->count() > 0)
                <!-- Featured Article -->
                @php $main = $featuredArticles->first(); @endphp
                <div class="mb-20">
                    <div class="glass-card group flex flex-col lg:flex-row overflow-hidden min-h-[500px]">
                        <div class="lg:w-3/5 relative overflow-hidden">
                            <img src="{{ $main->getFirstMediaUrl('featured_image') ?: '/placeholder.jpg' }}"
                                alt="{{ $main->title }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-1000">
                            <div
                                class="absolute inset-0 bg-linear-to-t from-black/80 via-transparent to-transparent lg:hidden">
                            </div>
                        </div>
                        <div class="lg:w-2/5 p-8 md:p-12 flex flex-col justify-center">
                            <div class="flex items-center gap-4 mb-6">
                                <span
                                    class="px-3 py-1 bg-primary text-white text-[10px] font-black uppercase rounded-full tracking-wider">Nổi
                                    bật</span>
                                <span class="text-xs text-text-muted">{{ $main->published_at->format('d/m/Y') }}</span>
                            </div>
                            <h2
                                class="text-3xl md:text-4xl font-black mb-6 leading-tight group-hover:text-primary transition-colors">
                                <a href="{{ route('articles.show', $main->slug) }}">{{ $main->title }}</a>
                            </h2>
                            <p class="text-text-muted text-lg mb-10 line-clamp-3 leading-relaxed">
                                {{ $main->excerpt }}
                            </p>
                            <div>
                                <a href="{{ route('articles.show', $main->slug) }}"
                                    class="inline-flex items-center gap-2 font-bold text-primary hover:gap-4 transition-all">
                                    Đọc thêm
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                <!-- Article List -->
                <div class="lg:col-span-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @forelse($articles as $article)
                            <article class="glass-card group flex flex-col">
                                <div class="relative aspect-video rounded-t-2xl overflow-hidden">
                                    <img src="{{ $article->getFirstMediaUrl('featured_image') ?: '/placeholder.jpg' }}"
                                        alt="{{ $article->title }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                    <div class="absolute top-4 left-4">
                                        <span
                                            class="px-3 py-1 bg-white/90 backdrop-blur-md text-black text-[10px] font-black uppercase rounded-lg shadow-sm">
                                            {{ $article->category->name ?? 'Tin tức' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="p-8 grow flex flex-col">
                                    <div
                                        class="flex items-center gap-3 text-xs text-text-muted mb-4 font-bold tracking-wider">
                                        <span>{{ $article->published_at->format('d/m/Y') }}</span>
                                        <span>•</span>
                                        <span>{{ $article->view_count }} lượt xem</span>
                                    </div>
                                    <h3
                                        class="text-2xl font-black mb-4 group-hover:text-primary transition-colors line-clamp-2 leading-tight">
                                        <a href="{{ route('articles.show', $article->slug) }}">{{ $article->title }}</a>
                                    </h3>
                                    <p class="text-text-muted text-sm line-clamp-3 mb-8 leading-relaxed">
                                        {{ $article->excerpt }}
                                    </p>
                                    <div class="mt-auto">
                                        <a href="{{ route('articles.show', $article->slug) }}"
                                            class="text-sm font-black text-primary hover:underline">Chi tiết</a>
                                    </div>
                                </div>
                            </article>
                        @empty
                            <div class="col-span-full py-20 text-center glass-card">
                                <p class="text-text-muted">Không có bài viết nào.</p>
                            </div>
                        @endforelse
                    </div>
                    <div class="mt-16">
                        {{ $articles->links() }}
                    </div>
                </div>

                <!-- Blog Sidebar -->
                <div class="lg:col-span-4 space-y-8">
                    <div class="glass-card p-8">
                        <h3 class="text-xl font-black mb-6 pb-4 border-b border-border-glass">Danh mục tin</h3>
                        <ul class="space-y-4">
                            @foreach ($categories as $cat)
                                <li>
                                    <a href="?category={{ $cat->slug }}"
                                        class="flex items-center justify-between group">
                                        <span
                                            class="text-sm font-bold text-text-muted group-hover:text-primary transition-colors">{{ $cat->name }}</span>
                                        <span
                                            class="px-2 py-0.5 bg-bg-main text-[10px] font-black text-text-muted rounded-md group-hover:bg-primary group-hover:text-white transition-all">
                                            {{ $cat->articles_count ?? 0 }}
                                        </span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="glass-card p-8">
                        <h3 class="text-xl font-black mb-6 pb-4 border-b border-border-glass">Đăng ký nhận tin</h3>
                        <p class="text-sm text-text-muted mb-6">Nhận bài viết mới nhất và thông tin ưu đãi qua email của
                            bạn.</p>
                        <form action="#" class="space-y-4">
                            <input type="email" placeholder="Email của bạn"
                                class="w-full px-6 py-4 bg-bg-main border border-border-glass rounded-2xl text-sm focus:outline-none focus:border-primary transition-colors">
                            <button
                                class="w-full py-4 bg-primary text-white font-black rounded-2xl shadow-glow-primary hover:opacity-90 transition-all">Đăng
                                ký ngay</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
