<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    {{-- Recent Products/Articles Table --}}
    <div class="lg:col-span-2 glass-card overflow-hidden group/card relative">
        <div class="absolute -top-24 -left-24 w-64 h-64 bg-primary/5 rounded-full blur-3xl group-hover/card:bg-primary/10 transition-colors duration-700"></div>
        
        <div class="flex items-center justify-between p-8 border-b border-white/5 relative z-10">
            <div>
                <h3 class="text-xl font-black text-text-main tracking-tight flex items-center gap-3">
                    <span class="w-1.5 h-6 bg-primary rounded-full"></span>
                    Hoạt động gần đây
                </h3>
                <p class="text-sm text-text-muted font-medium mt-1">Sản phẩm và bài viết mới cập nhật</p>
            </div>
            <div class="flex gap-4">
                <button wire:click="loadActivity"
                    class="group/btn relative px-5 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 transition-all duration-300 overflow-hidden">
                    <span class="relative z-10 text-[11px] font-black uppercase tracking-widest text-primary flex items-center gap-2">
                        <i class="ti ti-refresh text-sm @if($isLoading) animate-spin @endif"></i>
                        Làm mới
                    </span>
                    <div class="absolute inset-0 bg-primary/10 translate-y-full group-hover/btn:translate-y-0 transition-transform duration-300"></div>
                </button>
            </div>
        </div>

        <div class="overflow-x-auto relative z-10">
            <table class="w-full">
                <thead>
                    <tr class="bg-white/[0.02] border-b border-white/5 text-left text-[11px] font-black text-text-muted uppercase tracking-[0.2em]">
                        <th class="px-8 py-5">Phân loại</th>
                        <th class="px-8 py-5">Nội dung</th>
                        <th class="px-8 py-5">Danh mục</th>
                        <th class="px-8 py-5">Thời gian</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($recentProducts as $product)
                        <tr class="group/row hover:bg-white/[0.03] transition-all duration-300">
                            <td class="px-8 py-5">
                                <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest bg-primary/10 text-primary border border-primary/20 group-hover/row:scale-105 transition-transform duration-300">
                                    Sản phẩm
                                </span>
                            </td>
                            <td class="px-8 py-5">
                                <a href="{{ route('backend.products.edit', $product) }}"
                                    class="text-sm font-bold text-text-main hover:text-primary transition-all flex items-center gap-2 group/link">
                                    {{ Str::limit($product->name, 45) }}
                                    <svg class="w-4 h-4 opacity-0 -translate-x-2 group-hover/link:opacity-100 group-hover/link:translate-x-0 transition-all text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                    </svg>
                                </a>
                            </td>
                            <td class="px-8 py-5">
                                <span class="text-sm font-medium text-text-muted bg-white/5 px-3 py-1 rounded-lg">
                                    {{ $product->category?->name ?? '—' }}
                                </span>
                            </td>
                            <td class="px-8 py-5">
                                <span class="text-sm font-bold text-text-muted group-hover/row:text-text-main transition-colors italic">
                                    {{ $product->created_at->diffForHumans() }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-16 text-center">
                                <div class="w-20 h-20 rounded-full bg-white/5 flex items-center justify-center mx-auto mb-4 border border-white/5">
                                    <i class="ti ti-package text-3xl text-text-muted opacity-20"></i>
                                </div>
                                <p class="text-sm font-bold text-text-muted">Chưa có sản phẩm nào</p>
                            </td>
                        </tr>
                    @endforelse

                    @forelse($recentArticles as $article)
                        <tr class="group/row hover:bg-white/[0.03] transition-all duration-300">
                            <td class="px-8 py-5">
                                <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest bg-accent/10 text-accent border border-accent/20 group-hover/row:scale-105 transition-transform duration-300">
                                    Bài viết
                                </span>
                            </td>
                            <td class="px-8 py-5">
                                <a href="{{ route('backend.articles.edit', ['type' => 'post', 'article' => $article]) }}"
                                    class="text-sm font-bold text-text-main hover:text-accent transition-all flex items-center gap-2 group/link">
                                    {{ Str::limit($article->title, 45) }}
                                    <svg class="w-4 h-4 opacity-0 -translate-x-2 group-hover/link:opacity-100 group-hover/link:translate-x-0 transition-all text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                    </svg>
                                </a>
                            </td>
                            <td class="px-8 py-5">
                                <span class="text-sm font-medium text-text-muted bg-white/5 px-3 py-1 rounded-lg">
                                    {{ $article->category?->name ?? '—' }}
                                </span>
                            </td>
                            <td class="px-8 py-5">
                                <span class="text-sm font-bold text-text-muted group-hover/row:text-text-main transition-colors italic">
                                    {{ $article->created_at->diffForHumans() }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-16 text-center">
                                <div class="w-20 h-20 rounded-full bg-white/5 flex items-center justify-center mx-auto mb-4 border border-white/5">
                                    <i class="ti ti-news text-3xl text-text-muted opacity-20"></i>
                                </div>
                                <p class="text-sm font-bold text-text-muted">Chưa có bài viết nào</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Quick Links + New Users --}}
    <div class="space-y-8">
        {{-- Quick Links --}}
        <div class="glass-card p-8 group/quick relative overflow-hidden">
            <div class="absolute -bottom-12 -right-12 w-48 h-48 bg-accent/5 rounded-full blur-2xl group-hover/quick:bg-accent/10 transition-colors duration-500"></div>
            
            <h3 class="text-xl font-black text-text-main tracking-tight mb-8 flex items-center gap-3">
                <span class="w-1.5 h-6 bg-accent rounded-full"></span>
                Truy cập nhanh
            </h3>
            
            <div class="grid grid-cols-2 gap-4 relative z-10">
                <a href="{{ route('backend.users.index') }}"
                    class="group/link flex flex-col items-center gap-4 p-6 rounded-3xl bg-white/[0.03] hover:bg-white/[0.08] transition-all duration-300 border border-white/5 hover:border-white/10 shadow-lg hover:shadow-primary/5">
                    <div class="w-14 h-14 bg-linear-to-br from-primary via-primary/80 to-accent rounded-2xl flex items-center justify-center group-hover/link:scale-110 group-hover/link:rotate-6 transition-all duration-500 shadow-xl shadow-primary/20">
                        <i class="ti ti-users text-2xl text-white"></i>
                    </div>
                    <span class="text-[11px] font-black uppercase tracking-[0.15em] text-text-muted group-hover/link:text-primary transition-colors">Thành viên</span>
                </a>

                <a href="{{ route('backend.products.index') }}"
                    class="group/link flex flex-col items-center gap-4 p-6 rounded-3xl bg-white/[0.03] hover:bg-white/[0.08] transition-all duration-300 border border-white/5 hover:border-white/10 shadow-lg hover:shadow-success/5">
                    <div class="w-14 h-14 bg-linear-to-br from-success via-success/80 to-teal-600 rounded-2xl flex items-center justify-center group-hover/link:scale-110 group-hover/link:-rotate-6 transition-all duration-500 shadow-xl shadow-success/20">
                        <i class="ti ti-box text-2xl text-white"></i>
                    </div>
                    <span class="text-[11px] font-black uppercase tracking-[0.15em] text-text-muted group-hover/link:text-success transition-colors">Sản phẩm</span>
                </a>

                <a href="{{ route('backend.articles.index', 'post') }}"
                    class="group/link flex flex-col items-center gap-4 p-6 rounded-3xl bg-white/[0.03] hover:bg-white/[0.08] transition-all duration-300 border border-white/5 hover:border-white/10 shadow-lg hover:shadow-accent/5">
                    <div class="w-14 h-14 bg-linear-to-br from-accent via-accent/80 to-orange-600 rounded-2xl flex items-center justify-center group-hover/link:scale-110 group-hover/link:rotate-6 transition-all duration-500 shadow-xl shadow-accent/20">
                        <i class="ti ti-news text-2xl text-white"></i>
                    </div>
                    <span class="text-[11px] font-black uppercase tracking-[0.15em] text-text-muted group-hover/link:text-accent transition-colors">Bài viết</span>
                </a>

                <a href="{{ route('backend.settings.general') }}"
                    class="group/link flex flex-col items-center gap-4 p-6 rounded-3xl bg-white/[0.03] hover:bg-white/[0.08] transition-all duration-300 border border-white/5 hover:border-white/10 shadow-lg hover:shadow-danger/5">
                    <div class="w-14 h-14 bg-linear-to-br from-danger via-danger/80 to-rose-600 rounded-2xl flex items-center justify-center group-hover/link:scale-110 group-hover/link:-rotate-6 transition-all duration-500 shadow-xl shadow-danger/20">
                        <i class="ti ti-settings text-2xl text-white"></i>
                    </div>
                    <span class="text-[11px] font-black uppercase tracking-[0.15em] text-text-muted group-hover/link:text-danger transition-colors">Cấu hình</span>
                </a>
            </div>
        </div>

        {{-- Recent Users --}}
        <div class="glass-card p-8 group/users relative overflow-hidden">
            <div class="absolute -top-12 -right-12 w-48 h-48 bg-primary/5 rounded-full blur-2xl group-hover/users:bg-primary/10 transition-colors duration-500"></div>
            
            <div class="flex items-center justify-between mb-8 relative z-10">
                <div>
                    <h3 class="text-xl font-black text-text-main tracking-tight flex items-center gap-3">
                        <span class="w-1.5 h-6 bg-primary rounded-full"></span>
                        Người dùng mới
                    </h3>
                    <p class="text-sm text-text-muted font-medium mt-1">Đăng ký mới nhất</p>
                </div>
            </div>

            <div class="space-y-6 relative z-10">
                @forelse($recentUsers as $user)
                    <div class="group/user flex items-center gap-4 p-4 rounded-2xl hover:bg-white/[0.04] transition-all duration-300 border border-transparent hover:border-white/5">
                        <div class="relative">
                            <div class="w-12 h-12 bg-linear-to-br from-primary via-primary/80 to-accent rounded-xl flex items-center justify-center text-white text-lg font-black shadow-lg shadow-primary/20 group-hover/user:scale-110 transition-transform duration-500">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-success border-2 border-background rounded-full"></div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-black text-text-main truncate group-hover/user:text-primary transition-colors">{{ $user->name }}</div>
                            <div class="text-[11px] text-text-muted font-bold truncate opacity-60 flex items-center gap-1.5 mt-0.5">
                                <i class="ti ti-mail"></i>
                                {{ $user->email }}
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] font-black text-text-muted italic group-hover/user:text-text-main transition-colors">{{ $user->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <div class="w-16 h-16 rounded-full bg-white/5 flex items-center justify-center mx-auto mb-4 border border-white/5">
                            <i class="ti ti-users text-2xl text-text-muted opacity-20"></i>
                        </div>
                        <p class="text-sm font-bold text-text-muted">Chưa có người dùng mới</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-8 pt-6 border-t border-white/5 relative z-10">
                <a href="{{ route('backend.users.index') }}" class="group/btn-all flex items-center justify-center gap-3">
                    <span class="text-[11px] font-black uppercase tracking-[0.2em] text-text-muted group-hover/btn-all:text-primary transition-colors">Quản lý người dùng</span>
                    <div class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center group-hover/btn-all:bg-primary/20 group-hover/btn-all:text-primary transition-all">
                        <i class="ti ti-arrow-right"></i>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
