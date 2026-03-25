<header class="sticky top-0 z-50 glass-header">
    <div class="container mx-auto px-4 h-16 flex items-center justify-between">
        <!-- Logo -->
        <a href="/" class="flex items-center gap-2 group">
            <div
                class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center shadow-glow-primary transition-transform group-hover:scale-105">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <span class="text-xl font-bold tracking-tight text-text-main">
                {{ app(\App\Services\TenantManager::class)->getTenant()->name ?? config('app.name') }}
            </span>
        </a>

        <!-- Desktop Navigation -->
        <nav class="hidden md:flex items-center gap-8">
            <a href="/" class="text-sm font-medium hover:text-primary transition-colors">Trang chủ</a>
            <a href="/san-pham" class="text-sm font-medium hover:text-primary transition-colors">Sản phẩm</a>
            <a href="/tin-tuc" class="text-sm font-medium hover:text-primary transition-colors">Tin tức</a>
            <a href="/lien-he" class="text-sm font-medium hover:text-primary transition-colors">Liên hệ</a>
        </nav>

        <!-- Search & Actions -->
        <div class="flex items-center gap-4">
            <button class="p-2 text-text-muted hover:text-primary hover:bg-primary/10 rounded-lg transition-all"
                aria-label="Tìm kiếm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>

            <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)"
                class="p-2 text-text-muted hover:text-primary hover:bg-primary/10 rounded-lg transition-all"
                aria-label="Chế độ tối">
                <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
                <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M16.071 16.071l.707.707M7.757 7.757l.707.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
                </svg>
            </button>

            <a href="/login"
                class="px-5 py-2 bg-primary text-white text-sm font-semibold rounded-xl shadow-glow-primary hover:opacity-90 transition-all">
                Đăng nhập
            </a>
        </div>
    </div>
</header>
