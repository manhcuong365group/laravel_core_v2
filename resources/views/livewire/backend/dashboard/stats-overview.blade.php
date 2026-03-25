<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6" wire:poll.30s>
    {{-- Total Users --}}
    <div class="glass-card p-6 group relative overflow-hidden">
        <div class="relative z-10 flex items-center justify-between">
            <div>
                <p class="text-sm font-bold text-text-muted uppercase tracking-widest">Người dùng</p>
                <h3 class="text-3xl font-black text-text-main mt-2">{{ number_format($totalUsers) }}</h3>
                <p class="text-sm mt-2 flex items-center gap-1 {{ $userGrowth >= 0 ? 'text-success' : 'text-danger' }}">
                    <i class="ti {{ $userGrowth >= 0 ? 'ti-trending-up' : 'ti-trending-down' }}"></i>
                    {{ $userGrowth >= 0 ? '+' : '' }}{{ $userGrowth }}%
                    <span class="text-text-muted/50">vs tháng trước</span>
                </p>
            </div>
            <div
                class="w-12 h-12 bg-primary/10 text-primary rounded-xl flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-colors">
                <i class="ti ti-users text-2xl"></i>
            </div>
        </div>
        <div
            class="absolute -bottom-4 -right-4 w-24 h-24 bg-primary/10 rounded-full group-hover:scale-150 transition-transform duration-500">
        </div>
    </div>

    {{-- Total Products --}}
    <div class="glass-card p-6 group relative overflow-hidden">
        <div class="relative z-10 flex items-center justify-between">
            <div>
                <p class="text-sm font-bold text-text-muted uppercase tracking-widest">Sản phẩm</p>
                <h3 class="text-3xl font-black text-text-main mt-2">{{ number_format($totalProducts) }}</h3>
                <p
                    class="text-sm mt-2 flex items-center gap-1 {{ $productGrowth >= 0 ? 'text-success' : 'text-danger' }}">
                    <i class="ti {{ $productGrowth >= 0 ? 'ti-trending-up' : 'ti-trending-down' }}"></i>
                    {{ $productGrowth >= 0 ? '+' : '' }}{{ $productGrowth }}%
                    <span class="text-text-muted/50">vs tháng trước</span>
                </p>
            </div>
            <div
                class="w-12 h-12 bg-success/10 text-success rounded-xl flex items-center justify-center group-hover:bg-success group-hover:text-white transition-colors">
                <i class="ti ti-box text-2xl"></i>
            </div>
        </div>
        <div
            class="absolute -bottom-4 -right-4 w-24 h-24 bg-success/10 rounded-full group-hover:scale-150 transition-transform duration-500">
        </div>
    </div>

    {{-- Total Articles --}}
    <div class="glass-card p-6 group relative overflow-hidden">
        <div class="relative z-10 flex items-center justify-between">
            <div>
                <p class="text-sm font-bold text-text-muted uppercase tracking-widest">Bài viết</p>
                <h3 class="text-3xl font-black text-text-main mt-2">{{ number_format($totalArticles) }}</h3>
                <p
                    class="text-sm mt-2 flex items-center gap-1 {{ $articleGrowth >= 0 ? 'text-success' : 'text-danger' }}">
                    <i class="ti {{ $articleGrowth >= 0 ? 'ti-trending-up' : 'ti-trending-down' }}"></i>
                    {{ $articleGrowth >= 0 ? '+' : '' }}{{ $articleGrowth }}%
                    <span class="text-text-muted/50">vs tháng trước</span>
                </p>
            </div>
            <div
                class="w-12 h-12 bg-accent/10 text-accent rounded-xl flex items-center justify-center group-hover:bg-accent group-hover:text-white transition-colors">
                <i class="ti ti-news text-2xl"></i>
            </div>
        </div>
        <div
            class="absolute -bottom-4 -right-4 w-24 h-24 bg-accent/10 rounded-full group-hover:scale-150 transition-transform duration-500">
        </div>
    </div>

    {{-- Total Categories --}}
    <div class="glass-card p-6 group relative overflow-hidden">
        <div class="relative z-10 flex items-center justify-between">
            <div>
                <p class="text-sm font-bold text-text-muted uppercase tracking-widest">Danh mục</p>
                <h3 class="text-3xl font-black text-text-main mt-2">{{ number_format($totalCategories) }}</h3>
                <p class="text-sm text-text-muted/50 mt-2">
                    Tổng danh mục hệ thống
                </p>
            </div>
            <div
                class="w-12 h-12 bg-danger/10 text-danger rounded-xl flex items-center justify-center group-hover:bg-danger group-hover:text-white transition-colors">
                <i class="ti ti-category text-2xl"></i>
            </div>
        </div>
        <div
            class="absolute -bottom-4 -right-4 w-24 h-24 bg-danger/10 rounded-full group-hover:scale-150 transition-transform duration-500">
        </div>
    </div>
</div>
