@extends('theme::layouts.app')

@section('title', $product->name . ' - ' . (app(\App\Services\TenantManager::class)->getTenant()->name ??
    config('app.name')))

@section('content')
    <div class="bg-bg-main py-12">
        <div class="container mx-auto px-4">
            <!-- Breadcrumbs -->
            <nav
                class="flex items-center gap-2 text-sm mb-12 text-text-muted overflow-x-auto whitespace-nowrap scrollbar-hide">
                <a href="/" class="hover:text-primary transition-colors">Trang chủ</a>
                <svg class="w-4 h-4 opacity-30" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" />
                </svg>
                <a href="{{ route('products.index') }}" class="hover:text-primary transition-colors">Sản phẩm</a>
                @if ($product->category)
                    <svg class="w-4 h-4 opacity-30" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" />
                    </svg>
                    <a href="{{ route('products.index', ['category' => $product->category->slug]) }}"
                        class="hover:text-primary transition-colors">{{ $product->category->name }}</a>
                @endif
                <svg class="w-4 h-4 opacity-30" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" />
                </svg>
                <span class="text-text-main font-bold truncate">{{ $product->name }}</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
                <!-- Product Gallery -->
                <div x-data="{ activeImage: '{{ $product->getFirstMediaUrl('featured_image') ?: '/placeholder.jpg' }}' }" class="space-y-6">
                    <div class="glass-card overflow-hidden aspect-4/5 rounded-3xl">
                        <img :src="activeImage" alt="{{ $product->name }}"
                            class="w-full h-full object-cover transition-all duration-500">
                    </div>

                    @php $gallery = $product->getMedia('gallery'); @endphp
                    @if ($gallery->count() > 0)
                        <div class="grid grid-cols-4 gap-4">
                            @foreach ($gallery as $media)
                                <button @click="activeImage = '{{ $media->getUrl() }}'"
                                    class="glass-card aspect-square rounded-xl overflow-hidden group">
                                    <img src="{{ $media->getUrl() }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Product Info -->
                <div class="space-y-8">
                    <div>
                        <div class="flex items-center gap-4 mb-4">
                            <span
                                class="px-3 py-1 bg-primary/10 border border-primary/20 text-primary text-[10px] font-black uppercase rounded-full">
                                {{ $product->category->name ?? 'Mặc định' }}
                            </span>
                            @if ($product->brand)
                                <span class="text-text-muted text-sm font-medium">Brand: <span
                                        class="text-text-main">{{ $product->brand->name }}</span></span>
                            @endif
                        </div>
                        <h1 class="text-4xl md:text-5xl font-black tracking-tight leading-tight mb-6">{{ $product->name }}
                        </h1>

                        <div class="flex items-end gap-4">
                            @if ($product->sale_price > 0 && $product->sale_price < $product->price)
                                <span
                                    class="text-4xl font-black text-primary">{{ number_format($product->sale_price) }}đ</span>
                                <span
                                    class="text-xl text-text-muted line-through mb-1">{{ number_format($product->price) }}đ</span>
                                <span class="px-2 py-1 bg-danger text-white text-xs font-bold rounded-lg mb-2">
                                    -{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%
                                </span>
                            @else
                                <span class="text-4xl font-black text-primary">{{ number_format($product->price) }}đ</span>
                            @endif
                        </div>
                    </div>

                    <div class="prose prose-slate dark:prose-invert max-w-none text-text-muted leading-relaxed">
                        {!! $product->short_description !!}
                    </div>

                    <div class="pt-8 border-t border-border-glass flex flex-wrap gap-4">
                        <button
                            class="px-10 py-4 bg-primary text-white font-bold rounded-2xl shadow-glow-primary hover:scale-105 active:scale-95 transition-all text-lg grow md:grow-0">
                            Thêm vào giỏ hàng
                        </button>
                        <button class="p-4 glass-card hover:text-danger active:scale-90 transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </button>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="glass-card p-4 flex items-center gap-4">
                            <div class="w-10 h-10 bg-success/10 text-success rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <span class="text-sm font-bold">Còn hàng</span>
                        </div>
                        <div class="glass-card p-4 flex items-center gap-4">
                            <div class="w-10 h-10 bg-accent/10 text-accent rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            <span class="text-sm font-bold">Giao hàng 24h</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Content -->
            @if ($product->content)
                <div class="mt-24">
                    <div class="glass-card p-8 md:p-12">
                        <h2 class="text-3xl font-black mb-12 flex items-center gap-4">
                            <span class="w-2 h-8 bg-primary rounded-full"></span>
                            Thông tin chi tiết
                        </h2>
                        <div class="prose prose-lg prose-slate dark:prose-invert max-w-none">
                            {!! $product->content !!}
                        </div>
                    </div>
                </div>
            @endif

            <!-- Related Products -->
            @if ($relatedProducts->count() > 0)
                <div class="mt-24">
                    <h2 class="text-3xl font-black mb-12">Sản phẩm tương tự</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                        @foreach ($relatedProducts as $rp)
                            <div class="glass-card group hover:-translate-y-2">
                                <div class="relative aspect-4/5 rounded-t-2xl overflow-hidden">
                                    <img src="{{ $rp->getFirstMediaUrl('featured_image') ?: '/placeholder.jpg' }}"
                                        alt="{{ $rp->name }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                </div>
                                <div class="p-6">
                                    <h3 class="font-bold mb-2 truncate group-hover:text-primary transition-colors">
                                        <a href="{{ route('products.show', $rp->slug) }}">{{ $rp->name }}</a>
                                    </h3>
                                    <p class="text-primary font-black">{{ number_format($rp->price) }}đ</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
