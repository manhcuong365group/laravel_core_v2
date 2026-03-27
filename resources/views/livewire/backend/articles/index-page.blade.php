<x-backend.layout.index-page
    title="Tất cả bài viết"
    subtitle="Content Library"
    description="Quản lý tin tức, bài hướng dẫn và nội dung blog. Tối ưu hóa SEO và trải nghiệm đọc cho người dùng."
    :total="$articles->total()"
    totalLabel="bài viết"
    searchPlaceholder="Tìm tiêu đề, tác giả hoặc slug..."
    :selectedCount="count($selectedItems)"
    icon="ti ti-article"
>
    {{-- Header Actions --}}
    <x-slot:headerActions>
        <x-backend.ui.button
            type="primary"
            :href="route('backend.articles.create', ['type' => $type])"
            class="!rounded-2xl shadow-xl shadow-primary/30 group h-11 px-6"
        >
            <div class="flex items-center gap-2">
                <span class="w-6 h-6 rounded-lg bg-white/20 flex items-center justify-center group-hover:rotate-90 transition-all duration-500">
                    <i class="ti ti-plus text-sm"></i>
                </span>
                <span class="font-bold tracking-tight">Viết bài mới</span>
            </div>
        </x-backend.ui.button>
    </x-slot:headerActions>

    {{-- Filters --}}
    <x-slot:filters>
        <div class="flex items-center p-1 bg-white/[0.03] border border-white/5 rounded-2xl">
            @foreach(['all' => 'Tất cả', 'published' => 'Công khai', 'draft' => 'Bản nháp'] as $key => $label)
                <button
                    wire:click="$set('statusFilter', '{{ $key }}')"
                    class="px-4 py-2 rounded-xl transition-all duration-300 text-xs font-black uppercase tracking-widest {{ $statusFilter === $key ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-text-muted hover:text-text-main hover:bg-white/5' }}"
                >
                    {{ $label }}
                </button>
            @endforeach
        </div>
        
        <div class="h-8 w-px bg-white/10 mx-3 hidden sm:block"></div>
        
        <x-backend.ui.dropdown align="left" width="64">
            <x-slot name="trigger">
                <button class="flex items-center gap-3 px-4 py-2.5 rounded-2xl bg-white/[0.03] border border-white/10 hover:border-primary/30 text-xs font-black text-text-muted hover:text-text-main transition-all duration-300">
                    <i class="ti ti-category-2 text-base text-primary/70"></i>
                    <span>{{ $categoryFilter ? ($categories->firstWhere('id', $categoryFilter)?->name ?? 'Tất cả chuyên mục') : 'Theo chuyên mục' }}</span>
                    <i class="ti ti-chevron-down text-[10px] ml-1"></i>
                </button>
            </x-slot>
            <x-slot name="content">
                <div class="max-h-80 overflow-y-auto no-scrollbar py-2">
                    <button wire:click="$set('categoryFilter', null)" class="w-full text-left px-5 py-3 text-xs font-black uppercase tracking-widest border-l-4 border-transparent text-text-muted hover:bg-white/5 hover:text-text-main transition-all">Tất cả chuyên mục</button>
                    @foreach($categories as $category)
                        <button wire:click="$set('categoryFilter', {{ $category->id }})" class="w-full text-left px-5 py-3 text-xs font-black uppercase tracking-widest border-l-4 transition-all {{ $categoryFilter == $category->id ? 'border-primary text-primary bg-primary/5' : 'border-transparent text-text-muted hover:bg-white/5 hover:text-text-main' }}">
                            {{ $category->name }}
                        </button>
                    @endforeach
                </div>
            </x-slot>
        </x-backend.ui.dropdown>
    </x-slot:filters>

    {{-- Table --}}
    <x-slot:table>
        <table class="w-full border-collapse">
            <thead>
                <tr class="text-left border-b border-white/5 bg-white/[0.01]">
                    <th class="pl-8 pr-4 py-6 w-16 text-center">
                        <x-backend.table.table-checkbox wire:click="toggleSelectAll" :checked="$selectAll" class="scale-110" />
                    </th>
                    <x-backend.table.table-th sort="title" :sortField="$sortField" :sortDirection="$sortDirection" class="px-6 py-6 min-w-[400px]">
                        Nội dung & Hình ảnh
                    </x-backend.table.table-th>
                    <th class="px-6 py-6 text-[11px] font-black text-text-muted uppercase tracking-[0.2em]">Chuyên mục</th>
                    <th class="px-6 py-6 text-[11px] font-black text-text-muted uppercase tracking-[0.2em] text-center">Thống kê</th>
                    <th class="px-6 py-6 text-[11px] font-black text-text-muted uppercase tracking-[0.2em] text-center">Trạng thái</th>
                    <th class="pr-8 pl-4 py-6 text-[11px] font-black text-text-muted uppercase tracking-[0.2em] text-right">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($articles as $article)
                    <tr class="group hover:bg-white/[0.03] transition-all duration-500" wire:key="article-{{ $article->id }}">
                        <td class="pl-8 pr-4 py-6 text-center border-b border-white/5">
                            <x-backend.table.table-checkbox value="{{ $article->id }}" wire:model.live="selectedItems" class="scale-110" />
                        </td>
                        <td class="px-6 py-6 border-b border-white/5">
                            <div class="flex items-center gap-5">
                                <div class="w-16 h-16 rounded-2xl overflow-hidden bg-white/5 border border-white/10 group-hover:border-primary/40 transition-all duration-500 shadow-lg flex-shrink-0 relative group/img">
                                    @if($article->featured_image)
                                        <img src="{{ Storage::url($article->featured_image) }}" alt="{{ $article->title }}" class="w-full h-full object-cover group-hover/img:scale-110 transition-transform duration-700">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-white/5 to-white/10">
                                            <i class="ti ti-photo text-xl text-text-muted/20"></i>
                                        </div>
                                    @endif
                                    @if($article->is_featured)
                                        <div class="absolute -top-1 -right-1 w-5 h-5 rounded-lg bg-orange-500 flex items-center justify-center shadow-lg border border-slate-900">
                                            <i class="ti ti-star-filled text-[9px] text-white"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex flex-col space-y-1.5 min-w-0">
                                    <a href="{{ route('backend.articles.edit', ['type' => $type, 'article' => $article->id]) }}" class="text-[15px] font-black text-text-main hover:text-primary transition-all duration-300 line-clamp-1 tracking-tight">
                                        {{ $article->title }}
                                    </a>
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center gap-1.5">
                                            <div class="w-5 h-5 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-[8px] font-black text-white border border-white/10">
                                                {{ strtoupper(substr($article->author?->name ?? 'A', 0, 1)) }}
                                            </div>
                                            <span class="text-[10px] font-bold text-text-muted">{{ $article->author?->name ?? 'Admin' }}</span>
                                        </div>
                                        <span class="w-1 h-1 rounded-full bg-white/10"></span>
                                        <span class="text-[10px] font-black text-text-muted/40 uppercase tracking-widest">{{ $article->created_at->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-6 border-b border-white/5">
                            <span class="px-2.5 py-1 rounded-xl bg-white/[0.03] border border-white/5 text-text-main text-[10px] font-black uppercase tracking-[0.1em] inline-flex w-fit group-hover:border-primary/20 transition-all">
                                <i class="ti ti-hash mr-1.5 text-primary/60"></i>
                                {{ $article->category?->name ?? 'Uncategorized' }}
                            </span>
                        </td>
                        <td class="px-6 py-6 border-b border-white/5 text-center">
                            <div class="inline-flex flex-col items-center gap-1 opacity-60 group-hover:opacity-100 transition-opacity">
                                <span class="text-xs font-black text-text-main">{{ number_format($article->view_count ?? 0) }}</span>
                                <span class="text-[9px] font-black text-text-muted uppercase tracking-tighter">Lượt xem</span>
                            </div>
                        </td>
                        <td class="px-6 py-6 border-b border-white/5 text-center">
                            <x-admin.status-badge 
                                :status="$article->is_active ? 'success' : 'neutral'" 
                                :label="$article->is_active ? 'Công khai' : 'Đang ẩn'" 
                            />
                        </td>
                        <td class="pr-8 pl-4 py-6 border-b border-white/5 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <x-backend.ui.action-icon
                                    variant="duplicate"
                                    wire:click="duplicate({{ $article->id }})"
                                    title="Nhân bản"
                                    class="text-emerald-500/70 hover:text-emerald-500"
                                />
                                <x-backend.ui.action-icon
                                    variant="edit"
                                    :href="route('backend.articles.edit', ['type' => $type, 'article' => $article->id])"
                                    title="Hiệu chỉnh"
                                    class="text-blue-500/70 hover:text-blue-500"
                                />
                                <x-backend.ui.action-icon
                                    variant="delete"
                                    wire:click="confirmDelete({{ $article->id }}, '{{ addslashes($article->title) }}')"
                                    title="Xóa bỏ"
                                    class="text-rose-500/70 hover:text-rose-500"
                                />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-8 py-32 text-center">
                            <div class="flex flex-col items-center justify-center gap-6">
                                <div class="w-24 h-24 rounded-[2.5rem] bg-gradient-to-br from-white/5 to-white/[0.02] border border-white/10 flex items-center justify-center animate-pulse shadow-inner">
                                    <i class="ti ti-article-off text-5xl text-text-muted/20"></i>
                                </div>
                                <div class="space-y-2">
                                    <p class="text-2xl font-black text-text-main tracking-tight">Thư viện trống</p>
                                    <p class="text-sm text-text-muted max-w-[320px] mx-auto leading-relaxed">Hãy bắt đầu tạo những nội dung đầu tiên để kết nối với khách hàng của bạn.</p>
                                </div>
                                <button wire:click="$set('search', '')" class="h-10 px-8 rounded-xl bg-white/5 border border-white/10 text-primary text-[11px] font-black uppercase tracking-[0.2em] hover:bg-primary hover:text-white transition-all">
                                    Làm mới bộ lọc
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-slot:table>

    {{-- Pagination --}}
    <x-slot:pagination>
        @if ($articles->hasPages())
            {{ $articles->links() }}
        @endif
    </x-slot:pagination>

    {{-- Bulk Actions Toolbar --}}
    <x-slot:bulkActions>
        <div class="flex items-center gap-2">
            <x-backend.ui.button 
                type="success" 
                wire:click="bulkStatus(1)" 
                size="sm"
                class="!rounded-xl px-4 py-2 border-none bg-emerald-500 hover:bg-emerald-600 shadow-lg shadow-emerald-500/20"
            >
                <i class="ti ti-eye mr-2 text-white"></i>
                <span class="font-black uppercase text-[10px] tracking-widest text-white">Hiển thị loạt</span>
            </x-backend.ui.button>

            <x-backend.ui.button 
                type="outline" 
                wire:click="bulkStatus(0)" 
                size="sm"
                class="!rounded-xl px-4 py-2 border-white/10 hover:border-white/20 bg-white/5"
            >
                <i class="ti ti-eye-off mr-2"></i>
                <span class="font-black uppercase text-[10px] tracking-widest">Ẩn bài loạt</span>
            </x-backend.ui.button>

            <div class="w-px h-6 bg-white/10 mx-1"></div>

            <x-backend.ui.button 
                size="sm"
                wire:click="confirmBulkDelete" 
                class="!rounded-xl px-4 py-2 border-none bg-rose-500 hover:bg-rose-600 shadow-lg shadow-rose-500/20"
            >
                <i class="ti ti-trash-x mr-2 text-white"></i>
                <span class="font-black uppercase text-[10px] tracking-widest text-white">Xóa vĩnh viễn</span>
            </x-backend.ui.button>
        </div>
    </x-slot:bulkActions>

    {{-- Modals --}}
    <x-slot:modals>
        <x-backend.layout.confirm-modal
            show="showDeleteModal"
            title="Cảnh báo dữ liệu"
            message="Hành động này sẽ loại bỏ hoàn toàn các bài viết đã chọn khỏi hệ thống. Bạn có chắc chắn muốn thực thi thao tác xóa không thể khôi phục này?"
        >
            <x-backend.ui.button
                type="outline"
                size="md"
                wire:click="$set('showDeleteModal', false)"
                class="h-12 px-8 !rounded-2xl border-white/10 font-bold"
            >
                Hủy bỏ
            </x-backend.ui.button>
            <x-backend.ui.button
                type="danger"
                size="md"
                wire:click="executeDelete"
                class="h-12 px-8 !rounded-2xl bg-rose-500 hover:bg-rose-600 shadow-2xl shadow-rose-500/40 font-bold text-white"
            >
                Xác nhận xóa
            </x-backend.ui.button>
        </x-backend.layout.confirm-modal>
    </x-slot:modals>
</x-backend.layout.index-page>
