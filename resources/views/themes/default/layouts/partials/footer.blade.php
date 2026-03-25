<footer class="bg-bg-surface border-t border-border-glass py-12">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
            <!-- Brand -->
            <div class="space-y-4">
                <a href="/" class="flex items-center gap-2 group">
                    <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center shadow-glow-primary">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold tracking-tight">
                        {{ app(\App\Services\TenantManager::class)->getTenant()?->name ?? config('app.name') }}
                    </span>
                </a>
                <p class="text-sm text-text-muted leading-relaxed">
                    Giải pháp quản trị nội dung đa nền tảng, mạnh mẽ và linh hoạt dành cho doanh nghiệp hiện đại.
                </p>
                <div class="flex items-center gap-4">
                    <a href="#" class="text-text-muted hover:text-primary transition-colors">
                        <span class="sr-only">Facebook</span>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54v-2.185c0-2.505 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" />
                        </svg>
                    </a>
                    <!-- More social icons... -->
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider mb-6">Liên kết nhanh</h3>
                <ul class="space-y-3">
                    <li><a href="/" class="text-sm text-text-muted hover:text-primary transition-colors">Trang
                            chủ</a></li>
                    <li><a href="/san-pham" class="text-sm text-text-muted hover:text-primary transition-colors">Sản
                            phẩm</a></li>
                    <li><a href="/tin-tuc" class="text-sm text-text-muted hover:text-primary transition-colors">Tin
                            tức</a></li>
                    <li><a href="/lien-he" class="text-sm text-text-muted hover:text-primary transition-colors">Liên
                            hệ</a></li>
                </ul>
            </div>

            <!-- Legal -->
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider mb-6">Chính sách</h3>
                <ul class="space-y-3">
                    <li><a href="/chinh-sach-bao-mat"
                            class="text-sm text-text-muted hover:text-primary transition-colors">Bảo mật</a></li>
                    <li><a href="/dieu-khoan-su-dung"
                            class="text-sm text-text-muted hover:text-primary transition-colors">Điều khoản</a></li>
                    <li><a href="/chinh-sach-doi-tra"
                            class="text-sm text-text-muted hover:text-primary transition-colors">Đổi trả</a></li>
                </ul>
            </div>

            <!-- Newsletter -->
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider mb-6">Đăng ký bản tin</h3>
                <p class="text-sm text-text-muted mb-4">Nhận thông báo về sản phẩm và tin tức mới nhất.</p>
                <form action="#" class="flex gap-2">
                    <input type="email" placeholder="Email của bạn"
                        class="grow px-4 py-2 bg-bg-main border border-border-glass rounded-xl text-sm focus:outline-none focus:border-primary transition-colors">
                    <button type="submit"
                        class="p-2 bg-primary text-white rounded-xl shadow-glow-primary hover:opacity-90 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7-7 7M3 12h18" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        <div
            class="mt-12 pt-8 border-t border-border-glass flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-xs text-text-muted">
                &copy; {{ date('Y') }}
                {{ app(\App\Services\TenantManager::class)->getTenant()?->name ?? config('app.name') }}. All rights
                reserved.
            </p>
            <p class="text-xs text-text-muted">
                Design by <a href="#" class="text-primary hover:underline">Antigravity</a>
            </p>
        </div>
    </div>
</footer>
