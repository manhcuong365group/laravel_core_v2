@extends('theme::layouts.app')

@section('title', 'Sản phẩm - ' . (app(\App\Services\TenantManager::class)->getTenant()->name ?? config('app.name')))

@section('content')
    <div class="bg-bg-main py-12">
        <div class="container mx-auto px-4">
            <h1 class="text-4xl md:text-5xl font-black mb-12 tracking-tight">Sản phẩm</h1>
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-12">
                <!-- Sidebar Filters -->
                <div class="lg:col-span-1 space-y-8">
                    <div class="glass-card p-8 sticky top-24">
                        <div>
                            <h3 class="font-bold mb-6 uppercase tracking-wider text-xs text-text-muted">Danh mục</h3>
                            <ul class="space-y-4">
                                <li>
                                    <a href="{{ route('products.index') }}"
                                        class="text-sm font-medium hover:text-primary transition-colors {{ !request('category') ? 'text-primary' : 'text-text-main' }}">Tất
                                        cả</a>
                                </li>
                                @foreach ($categories as $category)
                                    <li>
                                        <a href="?category={{ $category->slug }}"
                                            class="text-sm font-medium hover:text-primary transition-colors {{ request('category') == $category->slug ? 'text-primary' : 'text-text-main' }}">
                                            {{ $category->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="mt-10 pt-10 border-t border-border-glass">
                            <h3 class="font-bold mb-6 uppercase tracking-wider text-xs text-text-muted">Thương hiệu</h3>
                            <ul class="space-y-4">
                                @foreach ($brands as $brand)
                                    <li>
                                        <a href="?brand={{ $brand->slug }}"
                                            class="text-sm font-medium hover:text-primary transition-colors {{ request('brand') == $brand->slug ? 'text-primary' : 'text-text-main' }}">
                                            {{ $brand->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Product Grid -->
                <div class="lg:col-span-3">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                        @forelse($products as $product)
                            <div class="glass-card group hover:-translate-y-2">
                                <div class="relative aspect-4/5 rounded-t-2xl overflow-hidden bg-bg-surface">
                                    @if ($product->getFirstMediaUrl('featured_image'))
                                        <img src="{{ $product->getFirstMediaUrl('featured_image') }}"
                                            alt="{{ $product->name }}"
                                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                    @else
                                        <div
                                            class="w-full h-full flex items-center justify-center text-text-muted italic bg-primary/5">
                                            No Image</div>
                                    @endif

                                    <div
                                        class="absolute inset-0 bg-linear-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-6">
                                        <a href="{{ route('products.show', $product->slug) }}"
                                            class="w-full py-3 bg-white text-black text-center font-bold rounded-xl shadow-lg hover:bg-primary hover:text-white transition-all transform translate-y-4 group-hover:translate-y-0 duration-300">
                                            Xem chi tiết
                                        </a>
                                    </div>

                                    @if ($product->sale_price > 0 && $product->sale_price < $product->price)
                                        <div
                                            class="absolute top-4 left-4 px-3 py-1 bg-danger text-white text-[10px] font-black uppercase rounded-full shadow-lg">
                                            Sale</div>
                                    @endif
                                </div>
                                <div class="p-6">
                                    <p class="text-[10px] uppercase font-bold text-primary mb-2 tracking-widest">
                                        {{ $product->category->name ?? 'General' }}</p>
                                    <h3
                                        class="font-bold text-xl mb-3 line-clamp-1 group-hover:text-primary transition-colors">
                                        <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                                    </h3>
                                    <div class="flex items-center gap-3">
                                        @if ($product->sale_price > 0 && $product->sale_price < $product->price)
                                            <span
                                                class="text-primary font-black text-xl">{{ number_format($product->sale_price) }}đ</span>
                                            <span
                                                class="text-text-muted line-through text-sm">{{ number_format($product->price) }}đ</span>
                                        @else
                                            <span
                                                class="text-primary font-black text-xl">{{ number_format($product->price) }}đ</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-32 text-center glass-card">
                                <svg class="w-16 h-16 mx-auto text-text-muted mb-4 opacity-20" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                <p class="text-text-muted text-lg">Không tìm thấy sản phẩm nào phù hợp.</p>
                                <a href="{{ route('products.index') }}"
                                    class="mt-6 inline-block text-primary font-bold hover:underline">Xóa bộ lọc</a>
                            </div>
                        @endforelse
                    </div>
                    <div class="mt-16">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
