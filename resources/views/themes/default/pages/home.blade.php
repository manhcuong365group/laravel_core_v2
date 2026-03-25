@extends('theme::layouts.app')

@section('title', 'Trang chủ - ' . (app(\App\Services\TenantManager::class)->getTenant()?->name ?? config('app.name')))

@section('content')
    <div class="relative overflow-hidden bg-bg-main pt-24 pb-32">
        <!-- Hero Section -->
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl">
                <div
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary/10 border border-primary/20 text-primary text-sm font-bold mb-8 animate-fade-in">
                    <span class="relative flex h-2 w-2">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
                    </span>
                    Hệ thống Multi-tenant Laravel Core v1.0
                </div>
                <h1 class="text-5xl md:text-7xl lg:text-8xl font-black tracking-tight text-text-main mb-8 leading-[1.1]">
                    Xây dựng trải nghiệm <span class="text-transparent bg-clip-text bg-linear-to-r from-primary to-accent">đa
                        vương quốc</span> kỹ
                    thuật số.
                </h1>
                <p class="text-xl md:text-2xl text-text-muted mb-12 leading-relaxed max-w-2xl">
                    Nền tảng CMS mạnh mẽ, linh hoạt với khả năng thay đổi giao diện tức thì cho từng khách hàng.
                </p>
                <div class="flex flex-wrap gap-6">
                    <a href="/san-pham"
                        class="px-10 py-5 bg-primary text-white font-bold rounded-2xl shadow-glow-primary hover:scale-105 active:scale-95 transition-all text-lg flex items-center gap-3">
                        Bắt đầu ngay
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                        </svg>
                    </a>
                    <a href="/lien-he"
                        class="px-10 py-5 glass-card font-bold hover:bg-white/5 active:scale-95 transition-all text-lg">
                        Tìm hiểu thêm
                    </a>
                </div>
            </div>
        </div>

        <!-- Abstract Background -->
        <div
            class="absolute top-0 right-0 -translate-y-1/4 translate-x-1/4 w-[1000px] h-[1000px] bg-primary/20 blur-[150px] rounded-full">
        </div>
        <div
            class="absolute bottom-0 left-0 translate-y-1/4 -translate-x-1/4 w-[800px] h-[800px] bg-accent/15 blur-[120px] rounded-full">
        </div>
    </div>

    <!-- Features Section -->
    <div class="container mx-auto px-4 -mt-24 relative z-20 pb-24">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="glass-card p-10 group hover:-translate-y-3">
                <div
                    class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center text-primary mb-8 group-hover:bg-primary group-hover:text-white transition-all duration-500 shadow-lg">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <h3 class="text-3xl font-black mb-4">Multi-Tenancy</h3>
                <p class="text-text-muted text-lg leading-relaxed">Vận hành hàng ngàn website trên một hạ tầng duy nhất, tối
                    ưu chi phí và quản lý tập trung.</p>
            </div>

            <div class="glass-card p-10 group hover:-translate-y-3">
                <div
                    class="w-16 h-16 bg-accent/10 rounded-2xl flex items-center justify-center text-accent mb-8 group-hover:bg-accent group-hover:text-white transition-all duration-500 shadow-lg">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                    </svg>
                </div>
                <h3 class="text-3xl font-black mb-4">Theme Engine</h3>
                <p class="text-text-muted text-lg leading-relaxed">Hệ thống chuyển đổi giao diện linh hoạt dựa trên
                    Namespace, cho phép tùy biến không giới hạn.</p>
            </div>

            <div class="glass-card p-10 group hover:-translate-y-3">
                <div
                    class="w-16 h-16 bg-success/10 rounded-2xl flex items-center justify-center text-success mb-8 group-hover:bg-success group-hover:text-white transition-all duration-500 shadow-lg">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h3 class="text-3xl font-black mb-4">TALL Stack</h3>
                <p class="text-text-muted text-lg leading-relaxed">Xây dựng trên nền tảng hiện đại nhất: Tailwind 4,
                    Alpine.js, Livewire 3 và Laravel 12.</p>
            </div>
        </div>
    </div>
@endsection
