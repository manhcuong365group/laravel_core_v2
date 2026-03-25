<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Recent Products/Articles Table --}}
    <div class="lg:col-span-2 glass-card overflow-hidden">
        <div class="flex items-center justify-between p-6 border-b border-white/5">
            <h3 class="text-lg font-black text-text-main tracking-tight">Hoạt động gần đây</h3>
            <div class="flex gap-2">
                <button wire:click="loadActivity"
                    class="text-xs font-bold text-primary hover:text-primary/80 uppercase tracking-widest flex items-center gap-1">
                    <i class="ti ti-refresh" wire:loading.class="animate-spin" wire:target="loadActivity"></i> Làm mới
                </button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr
                        class="bg-bg-surface/5 border-b border-white/5 text-left text-[11px] font-black text-text-muted uppercase tracking-[0.15em]">
                        <th class="px-6 py-4">Loại</th>
                        <th class="px-6 py-4">Tên</th>
                        <th class="px-6 py-4">Danh mục</th>
                        <th class="px-6 py-4">Ngày tạo</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($recentProducts as $product)
                        <tr class="hover:bg-bg-surface/5 transition-colors">
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-success/10 text-success border border-success/20">Sản
                                    phẩm</span>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('backend.products.edit', $product) }}"
                                    class="text-sm font-bold text-text-main hover:text-primary transition-colors">{{ Str::limit($product->name, 40) }}</a>
                            </td>
                            <td class="px-6 py-4 text-sm text-text-muted">{{ $product->category?->name ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm text-text-muted">{{ $product->created_at->diffForHumans() }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-text-muted">Chưa có sản phẩm nào.</td>
                        </tr>
                    @endforelse

                    @forelse($recentArticles as $article)
                        <tr class="hover:bg-bg-surface/5 transition-colors">
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-accent/10 text-accent border border-accent/20">Bài
                                    viết</span>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('backend.articles.edit', ['type' => 'post', 'article' => $article]) }}"
                                    class="text-sm font-bold text-text-main hover:text-primary transition-colors">{{ Str::limit($article->title, 40) }}</a>
                            </td>
                            <td class="px-6 py-4 text-sm text-text-muted">{{ $article->category?->name ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm text-text-muted">{{ $article->created_at->diffForHumans() }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-text-muted">Chưa có bài viết nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Quick Links + New Users --}}
    <div class="space-y-6">
        {{-- Quick Links --}}
        <div class="glass-card p-6">
            <h3 class="text-lg font-black text-text-main tracking-tight mb-4">Truy cập nhanh</h3>
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('backend.users.index') }}"
                    class="flex flex-col items-center gap-2 p-4 bg-bg-surface/5 rounded-2xl hover:bg-bg-surface/10 transition-all group border border-white/5">
                    <div
                        class="w-12 h-12 bg-linear-to-br from-primary to-accent rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform shadow-lg shadow-primary/20">
                        <i class="ti ti-users text-xl text-white"></i>
                    </div>
                    <span
                        class="text-[11px] font-black uppercase tracking-widest text-text-muted group-hover:text-primary transition-colors">Thành
                        viên</span>
                </a>
                <a href="{{ route('backend.products.index') }}"
                    class="flex flex-col items-center gap-2 p-4 bg-bg-surface/5 rounded-2xl hover:bg-bg-surface/10 transition-all group border border-white/5">
                    <div
                        class="w-12 h-12 bg-linear-to-br from-success to-teal-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform shadow-lg shadow-success/20">
                        <i class="ti ti-box text-xl text-white"></i>
                    </div>
                    <span
                        class="text-[11px] font-black uppercase tracking-widest text-text-muted group-hover:text-success transition-colors">Sản
                        phẩm</span>
                </a>
                <a href="{{ route('backend.articles.index', 'post') }}"
                    class="flex flex-col items-center gap-2 p-4 bg-bg-surface/5 rounded-2xl hover:bg-bg-surface/10 transition-all group border border-white/5">
                    <div
                        class="w-12 h-12 bg-linear-to-br from-accent to-orange-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform shadow-lg shadow-accent/20">
                        <i class="ti ti-news text-xl text-white"></i>
                    </div>
                    <span
                        class="text-[11px] font-black uppercase tracking-widest text-text-muted group-hover:text-accent transition-colors">Bài
                        viết</span>
                </a>
                <a href="{{ route('backend.settings.general') }}"
                    class="flex flex-col items-center gap-2 p-4 bg-bg-surface/5 rounded-2xl hover:bg-bg-surface/10 transition-all group border border-white/5">
                    <div
                        class="w-12 h-12 bg-linear-to-br from-danger to-rose-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform shadow-lg shadow-danger/20">
                        <i class="ti ti-settings text-xl text-white"></i>
                    </div>
                    <span
                        class="text-[11px] font-black uppercase tracking-widest text-text-muted group-hover:text-danger transition-colors">Cấu
                        hình</span>
                </a>
            </div>
        </div>

        {{-- Recent Users --}}
        <div class="glass-card p-6">
            <h3 class="text-lg font-black text-text-main tracking-tight mb-4">Người dùng mới</h3>
            <div class="space-y-4">
                @forelse($recentUsers as $user)
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 bg-linear-to-br from-primary to-accent rounded-xl flex items-center justify-center text-white text-xs font-black shadow-lg shadow-primary/20">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-bold text-text-main truncate">{{ $user->name }}</div>
                            <div class="text-[11px] text-text-muted/50 truncate">{{ $user->email }}</div>
                        </div>
                        <span
                            class="text-[10px] font-bold text-text-muted/50">{{ $user->created_at->diffForHumans() }}</span>
                    </div>
                @empty
                    <p class="text-sm text-text-muted text-center py-4">Chưa có người dùng mới.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>


