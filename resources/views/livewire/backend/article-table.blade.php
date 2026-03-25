<div class="space-y-6">
    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition
            class="bg-success/10 border border-success/20 text-success p-4 rounded-xl flex items-center gap-2">
            <i class="ti ti-check"></i>
            {{ session('message') }}
        </div>
    @endif

    <div class="glass-card p-4">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <i class="ti ti-search absolute left-3 top-1/2 -translate-y-1/2 text-text-muted"></i>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Tìm theo tiêu đề..."
                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-border-glass bg-bg-surface text-text-main placeholder-text-muted/50 focus:ring-2 focus:ring-primary/50 focus:border-primary/50 transition-all">
            </div>
            <select wire:model.live="statusFilter"
                class="px-4 py-2.5 rounded-xl border border-border-glass bg-bg-surface text-text-main focus:ring-2 focus:ring-primary/50 focus:border-primary/50">
                <option value="">Tất cả trạng thái</option>
                <option value="draft">Nháp</option>
                <option value="published">Đã đăng</option>
                <option value="scheduled">Lên lịch</option>
            </select>
        </div>
    </div>

    @if (count($selectedItems) > 0)
        <div class="bg-primary/10 border border-primary/20 rounded-xl p-3 flex items-center justify-between gap-2 flex-wrap">
            <span class="text-sm text-primary font-bold">Đã chọn {{ count($selectedItems) }} bài viết</span>
            <div class="flex items-center gap-2">
                <button wire:click="bulkStatus('published')" class="px-3 py-1.5 bg-success hover:bg-success/80 text-white text-sm font-bold rounded-lg">Đăng</button>
                <button wire:click="bulkStatus('draft')" class="px-3 py-1.5 bg-warning hover:bg-warning/80 text-white text-sm font-bold rounded-lg">Nháp</button>
                <button wire:click="bulkStatus('scheduled')" class="px-3 py-1.5 bg-info hover:bg-info/80 text-white text-sm font-bold rounded-lg">Lên lịch</button>
                <button wire:click="deleteSelected" wire:confirm="Xóa hàng loạt bài viết?" class="px-3 py-1.5 bg-danger hover:bg-danger/80 text-white text-sm font-bold rounded-lg">Xóa</button>
                <button wire:click="$set('selectedItems', []); $set('selectAll', false)" class="px-3 py-1.5 bg-bg-surface border border-border-glass text-text-muted text-sm font-bold rounded-lg">Bỏ chọn</button>
            </div>
        </div>
    @endif

    <div class="glass-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-white/5 border-b border-white/5 text-left text-[11px] font-black text-text-muted uppercase tracking-[0.15em]">
                        <th class="px-6 py-4"><input type="checkbox" wire:click="toggleSelectAll" @checked($selectAll) class="w-4 h-4 rounded border-border-glass text-primary focus:ring-primary/50"></th>
                        <th class="px-6 py-4">Tiêu đề</th>
                        <th class="px-6 py-4">Tác giả</th>
                        <th class="px-6 py-4">Trạng thái</th>
                        <th class="px-6 py-4">Đã đăng</th>
                        <th class="px-6 py-4 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($articles as $article)
                        <tr class="hover:bg-white/5 transition-colors group" wire:key="article-{{ $article->id }}">
                            <td class="px-6 py-4"><input type="checkbox" value="{{ $article->id }}" wire:model.live="selectedItems" class="w-4 h-4 rounded border-border-glass text-primary focus:ring-primary/50"></td>
                            <td class="px-6 py-4 text-sm font-semibold text-text-main">{{ $article->title }}</td>
                            <td class="px-6 py-4 text-sm text-text-muted">{{ $article->author?->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-text-muted">{{ ucfirst($article->status) }}</td>
                            <td class="px-6 py-4 text-sm text-text-muted">{{ $article->published_at?->format('d/m/Y H:i') ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <x-backend.ui.action-icon variant="edit" :href="route('backend.articles.edit', [$type, $article])" title="Sửa" icon="ti ti-edit" />
                                    <x-backend.ui.action-icon variant="delete" htmlType="button" title="Xóa" wire:click="confirmDelete({{ $article->id }}, '{{ addslashes($article->title) }}')" icon="ti ti-trash" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-16 text-center text-text-muted">Không có bài viết nào.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($articles->hasPages())
            <div class="px-6 py-4 border-t border-white/5">{{ $articles->links() }}</div>
        @endif
    </div>

    @if ($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" x-transition>
            <div class="glass-card max-w-md w-full p-6">
                <h3 class="text-lg font-black text-text-main text-center mb-2">Xác nhận xóa</h3>
                <p class="text-text-muted text-center mb-6">Xóa "{{ $deleteTargetName }}"?</p>
                <div class="flex gap-3">
                    <button wire:click="$set('showDeleteModal', false)" class="flex-1 px-4 py-2.5 bg-bg-surface border border-border-glass text-text-muted font-bold rounded-xl">Hủy</button>
                    <button wire:click="deleteArticle" class="flex-1 px-4 py-2.5 bg-danger hover:bg-danger/80 text-white font-bold rounded-xl">Xóa</button>
                </div>
            </div>
        </div>
    @endif
</div>

