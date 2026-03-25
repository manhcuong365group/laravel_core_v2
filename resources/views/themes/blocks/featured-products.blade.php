{{-- Featured Products Block --}}
@php
    $query = \App\Models\Product::with(['category', 'media'])->where('is_active', true);
    if (!empty($data['category_id'])) {
        $query->where('category_id', $data['category_id']);
    }
    if (!empty($data['show_badge'])) {
        $query->where('is_featured', true);
    }
    /** @var \App\Models\Product[]|\Illuminate\Database\Eloquent\Collection $products */
    $products = $query
        ->latest()
        ->take($data['limit'] ?? 8)
        ->get();
    $cols = $data['columns'] ?? 4;
@endphp

<section id="{{ $blockId }}" class="py-24 px-6 bg-slate-50 relative overflow-hidden" x-data="{ show: false }"
    x-intersect.once="show = true">

    {{-- Decorative Background Gradients --}}
    <div
        class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[500px] bg-linear-to-b from-white to-transparent pointer-events-none">
    </div>
    <div
        class="absolute -top-[300px] -right-[300px] w-[600px] h-[600px] bg-indigo-50 rounded-full blur-3xl opacity-50 pointer-events-none">
    </div>

    <div class="max-w-7xl mx-auto relative z-10 transition-all duration-1000 transform translate-y-8 opacity-0"
        :class="show ? 'translate-y-0 opacity-100' : ''">

        @if (!empty($data['title']))
            <div class="text-center mb-16">
                <h2 class="text-4xl lg:text-5xl font-black text-slate-900 mb-4 tracking-tight">{{ $data['title'] }}</h2>
                @if (!empty($data['subtitle']))
                    <p class="text-slate-500 text-lg lg:text-xl max-w-2xl mx-auto font-medium">{{ $data['subtitle'] }}
                    </p>
                @endif
            </div>
        @endif

        <div class="grid grid-cols-2 md:grid-cols-{{ $cols }} gap-6 lg:gap-8">
            @foreach ($products as $index => $product)
                <div class="group bg-white rounded-3xl shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 overflow-hidden border border-slate-100/60 relative flex flex-col h-full"
                    style="transition-delay: {{ $index * 50 }}ms;">

                    {{-- Badge --}}
                    @if (!empty($data['show_badge']) && $product->is_featured)
                        <div class="absolute top-4 right-4 z-20">
                            <span
                                class="px-3 py-1 bg-linear-to-r from-rose-500 to-pink-500 text-white text-xs font-bold rounded-full shadow-lg shadow-rose-500/30">Nổi
                                Bật</span>
                        </div>
                    @endif

                    <div class="aspect-4/5 relative overflow-hidden bg-slate-50">
                        @if ($product->getFirstMediaUrl('featured_image'))
                            <img src="{{ $product->getFirstMediaUrl('featured_image') }}" alt="{{ $product->name }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-in-out">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-16 h-16 text-slate-200" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif

                        {{-- Hover Overlay - Quick Add Action --}}
                        <div
                            class="absolute inset-x-0 bottom-0 p-4 opacity-0 group-hover:opacity-100 translate-y-4 group-hover:translate-y-0 transition-all duration-300 z-10 bg-linear-to-t from-black/50 to-transparent flex justify-center pb-6">
                            <button
                                class="px-6 py-2.5 bg-white text-slate-900 font-bold rounded-xl shadow-lg hover:bg-slate-50 hover:scale-105 transition-all text-sm flex items-center gap-2">
                                <i class="ti ti-shopping-cart-plus text-lg"></i> Thêm nhanh
                            </button>
                        </div>
                    </div>

                    <div class="p-5 flex flex-col grow">
                        @if ($product->category)
                            <span
                                class="text-xs font-bold uppercase tracking-wider text-indigo-500 mb-2">{{ $product->category->name }}</span>
                        @endif
                        <h3
                            class="font-bold text-slate-900 text-base leading-snug line-clamp-2 group-hover:text-indigo-600 transition-colors mb-auto">
                            <a href="#" class="after:absolute after:inset-0">{{ $product->name }}</a>
                        </h3>

                        @if ($data['show_price'] ?? true)
                            <div class="mt-4 flex items-center gap-2">
                                <span class="text-indigo-600 font-black text-lg">{{ $product->formatted_price }}</span>
                                @if ($product->compare_price)
                                    <span
                                        class="text-slate-400 text-sm line-through decoration-slate-300">{{ number_format($product->compare_price) }}đ</span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
