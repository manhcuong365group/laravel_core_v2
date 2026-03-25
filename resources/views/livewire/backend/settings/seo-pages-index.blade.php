<div class="space-y-8">
    <!-- Breadcrumbs & Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        @include('backend.layouts.partials.breadcrumbs', [
            'items' => [
                ['label' => 'Cài đặt hệ thống', 'url' => route('backend.settings.general')],
                ['label' => 'SEO Trang'],
            ],
        ])
    </div>

    <!-- Header Section -->
    <div class="relative glass-card p-8 overflow-hidden">
        <div class="absolute top-0 right-0 p-12 opacity-5">
            <i class="ti ti-seo text-9xl"></i>
        </div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center gap-8">
            <div
                class="w-20 h-20 bg-linear-to-br from-primary to-accent rounded-3xl flex items-center justify-center text-white text-4xl shadow-glow-primary">
                <i class="ti ti-world-search"></i>
            </div>
            <div>
                <h1 class="text-3xl font-black text-text-main tracking-tight uppercase">SEO Trang</h1>
                <p class="mt-2 text-text-muted max-w-2xl leading-relaxed">
                    Quản lý và tối ưu hóa cách website của bạn xuất hiện trên các công cụ tìm kiếm bài viết, sản phẩm và
                    trang chủ.
                </p>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    @include('livewire.backend.settings._tabs')

    <!-- Grid Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @php
            $defaultPages = [
                'home' => [
                    'name' => 'Trang chủ',
                    'icon' => 'ti ti-home-2',
                    'color' => 'indigo',
                    'desc' => 'Tối ưu hóa trang đầu tiên khách hàng nhìn thấy.',
                ],
                'products' => [
                    'name' => 'Sản phẩm',
                    'icon' => 'ti ti-shopping-cart',
                    'color' => 'blue',
                    'desc' => 'Danh sách sản phẩm và bộ lọc tìm kiếm.',
                ],
                'articles' => [
                    'name' => 'Bài viết',
                    'icon' => 'ti ti-news',
                    'color' => 'emerald',
                    'desc' => 'Trang tin tức và các bài viết blog.',
                ],
                'contact' => [
                    'name' => 'Liên hệ',
                    'icon' => 'ti ti-headset',
                    'color' => 'amber',
                    'desc' => 'Thông tin liên hệ và bản đồ.',
                ],
            ];
        @endphp

        @foreach ($defaultPages as $pageType => $pageInfo)
            @php
                $seoPage = $seoPages->where('page_type', $pageType)->first();
                $color = $pageInfo['color'];
                $colorMap = [
                    'indigo' => 'from-primary to-accent shadow-primary/20',
                    'blue' => 'from-blue-500 to-cyan-600 shadow-blue-500/20',
                    'emerald' => 'from-emerald-500 to-teal-600 shadow-emerald-500/20',
                    'amber' => 'from-amber-500 to-orange-600 shadow-amber-500/20',
                ];
                $gradient = $colorMap[$color] ?? $colorMap['indigo'];
            @endphp

            <div
                class="group relative glass-card hover:shadow-2xl hover:shadow-primary/10 hover:-translate-y-2 transition-all duration-500 overflow-hidden flex flex-col h-full">
                <!-- Top Gradient Accent -->
                <div class="absolute top-0 left-0 w-full h-1.5 bg-linear-to-r {{ $gradient }}"></div>

                <div class="p-8 flex-1 flex flex-col">
                    <!-- Icon & Status -->
                    <div class="flex items-start justify-between mb-8">
                        <div
                            class="w-16 h-16 rounded-3xl bg-linear-to-br {{ $gradient }} flex items-center justify-center text-3xl text-white shadow-xl">
                            <i class="{{ $pageInfo['icon'] }}"></i>
                        </div>

                        @if ($seoPage && $seoPage->title)
                            <div
                                class="flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full border border-emerald-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span class="text-[10px] font-bold uppercase tracking-wider">Optimized</span>
                            </div>
                        @else
                            <div
                                class="flex items-center gap-1.5 px-3 py-1 bg-amber-50 text-amber-600 rounded-full border border-amber-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                <span class="text-[10px] font-bold uppercase tracking-wider">Pending</span>
                            </div>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="mb-8">
                        <h3 class="text-xl font-black text-text-main tracking-tight">{{ $pageInfo['name'] }}</h3>
                        <p class="text-[10px] text-text-muted font-bold mt-1 uppercase tracking-widest">
                            {{ $pageType }}
                        </p>
                    </div>

                    <!-- Stats/Preview -->
                    <div class="space-y-4 flex-1">
                        <div
                            class="p-4 rounded-3xl bg-bg-main border border-border-glass group-hover:bg-bg-surface group-hover:border-primary/20 transition-colors duration-500">
                            <label
                                class="block text-[10px] font-black text-text-muted uppercase tracking-widest mb-1.5">Meta
                                Title</label>
                            <p class="text-sm font-bold text-text-main line-clamp-1">
                                {{ $seoPage->title ?? 'Chưa thiết lập' }}
                            </p>
                        </div>

                        <div
                            class="p-4 rounded-3xl bg-bg-main border border-border-glass group-hover:bg-bg-surface group-hover:border-primary/20 transition-colors duration-500">
                            <label
                                class="block text-[10px] font-black text-text-muted uppercase tracking-widest mb-1.5">Mô
                                tả</label>
                            <p class="text-xs text-text-muted line-clamp-2 leading-relaxed h-8 italic">
                                {{ $seoPage->meta_description ?? 'Vui lòng bổ sung mô tả SEO để tăng tỷ lệ nhấp chuột.' }}
                            </p>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="mt-8">
                        <a href="{{ route('backend.settings.seo-pages.edit', $pageType) }}"
                            class="w-full py-4 bg-text-main group-hover:bg-primary text-bg-surface text-[10px] font-black uppercase tracking-[0.2em] rounded-2xl flex items-center justify-center gap-2 shadow-xl group-hover:shadow-glow-primary transition-all duration-300"
                            wire:navigate>
                            <span>Cấu hình SEO</span>
                            <i class="ti ti-chevron-right text-lg transition-transform group-hover:translate-x-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>


