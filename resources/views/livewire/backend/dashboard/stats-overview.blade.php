<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8" wire:poll.30s>
    {{-- Total Users --}}
    <div class="glass-card p-8 group relative overflow-hidden transition-all duration-500 hover:-translate-y-2">
        <div class="absolute -top-12 -right-12 w-32 h-32 bg-primary/10 rounded-full blur-2xl group-hover:bg-primary/20 transition-colors"></div>
        
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-6">
                <div class="w-14 h-14 bg-primary/10 text-primary rounded-2xl flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-all duration-500 shadow-xl shadow-primary/10 group-hover:scale-110 group-hover:rotate-6">
                    <i class="ti ti-users text-2xl"></i>
                </div>
                <div class="flex flex-col items-end">
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] text-text-muted opacity-60">Người dùng</span>
                    <h3 class="text-3xl font-black text-text-main mt-1 tracking-tighter">{{ number_format($totalUsers) }}</h3>
                </div>
            </div>
            
            <div class="flex items-center justify-between pt-6 border-t border-white/5">
                <div class="flex items-center gap-2 {{ $userGrowth >= 0 ? 'text-success' : 'text-danger' }}">
                    <div class="w-6 h-6 rounded-full {{ $userGrowth >= 0 ? 'bg-success/10' : 'bg-danger/10' }} flex items-center justify-center">
                        <i class="ti {{ $userGrowth >= 0 ? 'ti-trending-up' : 'ti-trending-down' }} text-xs"></i>
                    </div>
                    <span class="text-sm font-black">{{ $userGrowth >= 0 ? '+' : '' }}{{ $userGrowth }}%</span>
                </div>
                <span class="text-[10px] font-bold text-text-muted/40 uppercase tracking-widest">vs tháng trước</span>
            </div>
        </div>
    </div>

    {{-- Total Products --}}
    <div class="glass-card p-8 group relative overflow-hidden transition-all duration-500 hover:-translate-y-2">
        <div class="absolute -top-12 -right-12 w-32 h-32 bg-success/10 rounded-full blur-2xl group-hover:bg-success/20 transition-colors"></div>
        
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-6">
                <div class="w-14 h-14 bg-success/10 text-success rounded-2xl flex items-center justify-center group-hover:bg-success group-hover:text-white transition-all duration-500 shadow-xl shadow-success/10 group-hover:scale-110 group-hover:-rotate-6">
                    <i class="ti ti-box text-2xl"></i>
                </div>
                <div class="flex flex-col items-end">
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] text-text-muted opacity-60">Sản phẩm</span>
                    <h3 class="text-3xl font-black text-text-main mt-1 tracking-tighter">{{ number_format($totalProducts) }}</h3>
                </div>
            </div>
            
            <div class="flex items-center justify-between pt-6 border-t border-white/5">
                <div class="flex items-center gap-2 {{ $productGrowth >= 0 ? 'text-success' : 'text-danger' }}">
                    <div class="w-6 h-6 rounded-full {{ $productGrowth >= 0 ? 'bg-success/10' : 'bg-danger/10' }} flex items-center justify-center">
                        <i class="ti {{ $productGrowth >= 0 ? 'ti-trending-up' : 'ti-trending-down' }} text-xs"></i>
                    </div>
                    <span class="text-sm font-black">{{ $productGrowth >= 0 ? '+' : '' }}{{ $productGrowth }}%</span>
                </div>
                <span class="text-[10px] font-bold text-text-muted/40 uppercase tracking-widest">vs tháng trước</span>
            </div>
        </div>
    </div>

    {{-- Total Articles --}}
    <div class="glass-card p-8 group relative overflow-hidden transition-all duration-500 hover:-translate-y-2">
        <div class="absolute -top-12 -right-12 w-32 h-32 bg-accent/10 rounded-full blur-2xl group-hover:bg-accent/20 transition-colors"></div>
        
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-6">
                <div class="w-14 h-14 bg-accent/10 text-accent rounded-2xl flex items-center justify-center group-hover:bg-accent group-hover:text-white transition-all duration-500 shadow-xl shadow-accent/10 group-hover:scale-110 group-hover:rotate-6">
                    <i class="ti ti-news text-2xl"></i>
                </div>
                <div class="flex flex-col items-end">
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] text-text-muted opacity-60">Bài viết</span>
                    <h3 class="text-3xl font-black text-text-main mt-1 tracking-tighter">{{ number_format($totalArticles) }}</h3>
                </div>
            </div>
            
            <div class="flex items-center justify-between pt-6 border-t border-white/5">
                <div class="flex items-center gap-2 {{ $articleGrowth >= 0 ? 'text-success' : 'text-danger' }}">
                    <div class="w-6 h-6 rounded-full {{ $articleGrowth >= 0 ? 'bg-success/10' : 'bg-danger/10' }} flex items-center justify-center">
                        <i class="ti {{ $articleGrowth >= 0 ? 'ti-trending-up' : 'ti-trending-down' }} text-xs"></i>
                    </div>
                    <span class="text-sm font-black">{{ $articleGrowth >= 0 ? '+' : '' }}{{ $articleGrowth }}%</span>
                </div>
                <span class="text-[10px] font-bold text-text-muted/40 uppercase tracking-widest">vs tháng trước</span>
            </div>
        </div>
    </div>

    {{-- Total Categories --}}
    <div class="glass-card p-8 group relative overflow-hidden transition-all duration-500 hover:-translate-y-2">
        <div class="absolute -top-12 -right-12 w-32 h-32 bg-danger/10 rounded-full blur-2xl group-hover:bg-danger/20 transition-colors"></div>
        
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-6">
                <div class="w-14 h-14 bg-danger/10 text-danger rounded-2xl flex items-center justify-center group-hover:bg-danger group-hover:text-white transition-all duration-500 shadow-xl shadow-danger/10 group-hover:scale-110 group-hover:-rotate-6">
                    <i class="ti ti-category text-2xl"></i>
                </div>
                <div class="flex flex-col items-end">
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] text-text-muted opacity-60">Danh mục</span>
                    <h3 class="text-3xl font-black text-text-main mt-1 tracking-tighter">{{ number_format($totalCategories) }}</h3>
                </div>
            </div>
            
            <div class="flex items-center justify-between pt-6 border-t border-white/5">
                <div class="text-[11px] font-bold text-text-muted/40 uppercase tracking-[0.1em]">Cơ cấu hệ thống</div>
                <div class="flex -space-x-2">
                    <div class="w-6 h-6 rounded-lg bg-primary/20 border-2 border-background"></div>
                    <div class="w-6 h-6 rounded-lg bg-success/20 border-2 border-background"></div>
                    <div class="w-6 h-6 rounded-lg bg-accent/20 border-2 border-background"></div>
                </div>
            </div>
        </div>
    </div>
</div>
