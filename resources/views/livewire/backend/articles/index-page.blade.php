<x-backend.layout.index-page
    title="Tất cả bài viết"
    subtitle="Quản lý bài viết"
    description="Quản lý tin tức, bài hướng dẫn và nội dung blog của bạn. Đảm bảo nội dung luôn chất lượng và phù hợp với người dùng."
    :total="$articles->total()"
    totalLabel="bài viết"
    searchPlaceholder="Tìm kiếm bài viết..."
    :selectedCount="count($selectedItems)"
    icon="ti ti-article"
>
    {{-- Header Actions --}}
    <x-slot:headerActions>
        <x-backend.ui.button
            type="primary"
            :href="route('backend.articles.create')"
            class="!rounded-2xl shadow-lg shadow-primary/20 group h-12 px-6"
        >
            <div class="flex items-center gap-2">
                <span class="w-6 h-6 rounded-lg bg-white/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="ti ti-plus text-sm"></i>
                </span>
                <span class="font-bold">Viết bài mới</span>
            </div>
        </x-backend.ui.button>
    </x-slot:headerActions>

    {{-- Filters --}}
    <x-slot:filters>
        @foreach(['all' => 'Tất cả', 'published' => 'Công khai', 'draft' => 'Bản nháp'] as $key => $label)
            <button
                wire:click="$set('filter', '{{ $key }}')"
                class="px-4 py-2 rounded-xl transition-all whitespace-nowrap text-xs font-bold {{ $filter === $key ? 'bg-white/10 border border-white/10 text-text-main' : 'hover:bg-white/5 border border-transparent hover:border-white/10 text-text-muted hover:text-text-main' }}"
            >
                {{ $label }}
            </button>
        @endforeach
        
        <div class="h-6 w-px bg-white/5 mx-2 hidden sm:block"></div>
        
        <x-backend.ui.dropdown align="left" width="56" class="z-50">
            <x-slot name="trigger">
                <button class="flex items-center gap-2 px-4 py-2 rounded-xl hover:bg-white/5 border border-transparent hover:border-white/10 text-xs font-bold text-text-muted transition-all">
                    <span>{{ $categoryFilter ? $categories->firstWhere('id', $categoryFilter)?->name : 'Tất cả chuyên mục' }}</span>
                    <i class="ti ti-chevron-down text-[10px]"></i>
                </button>
            </x-slot>
            <x-slot name="content">
                <div class="max-h-64 overflow-y-auto no-scrollbar py-2">
                    <button wire:click="$set('categoryFilter', null)" class="w-full text-left px-4 py-2 text-xs font-bold text-text-muted hover:bg-white/5 hover:text-text-main transition-colors">Tất cả chuyên mục</button>
                    @foreach($categories as $category)
                        <button wire:click="$set('categoryFilter', {{ $category->id }})" class="w-full text-left px-4 py-2 text-xs font-bold text-text-muted hover:bg-white/5 hover:text-text-main transition-colors {{ $categoryFilter == $category->id ? 'text-primary bg-primary/5' : '' }}">
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
                <tr class="text-left text-[11px] font-black text-text-muted uppercase tracking-[0.2em] border-b border-white/5 bg-white/[0.02]">
                    <th class="pl-8 pr-4 py-6 w-16">
                        <div class="flex items-center justify-center">
                            <input
                                type="checkbox"
                                wire:click="toggleSelectAll"
                                @if($selectAll) checked @endif
                                class="w-5 h-5 rounded-lg border-white/10 bg-white/5 text-primary focus:ring-offset-0 focus:ring-primary/20 transition-all cursor-pointer"
                            >
                        </div>
                    </th>
                    <th class="px-6 py-6 min-w-[350px]">
                        <button wire:click="sortBy('title')" class="flex items-center gap-2 hover:text-primary transition-colors">
                            <span>Nội dung bài viết</span>
                            <x-backend.table.sort-icon field="title" :sortField="$sortField" :sortDirection="$sortDirection" />
                        </button>
                    </th>
                    <th class="px-6 py-6">Chuyên mục</th>
                    <th class="px-6 py-6">Tác giả</th>
                    <th class="px-6 py-6">Thống kê</th>
                    <th class="px-6 py-6">Trạng thái</th>
                    <th class="pr-8 pl-4 py-6 text-right">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($articles as $article)
                    <tr class="group hover:bg-white/[0.03] transition-all duration-300" wire:key="article-{{ $article->id }}">
                        <td class="pl-8 pr-4 py-6">
                            <div class="flex items-center justify-center">
                                <input
                                    type="checkbox"
                                    value="{{ $article->id }}"
                                    wire:model.live="selectedItems"
                                    class="w-5 h-5 rounded-lg border-white/10 bg-white/5 text-primary focus:ring-offset-0 focus:ring-primary/20 transition-all cursor-pointer"
                                >
                            </div>
                        </td>
                        <td class="px-6 py-6">
                            <div class="flex items-center gap-4">
                                <div class="relative w-16 h-16 rounded-2xl overflow-hidden bg-white/5 border border-white/5 group-hover:border-primary/30 transition-all shadow-sm flex-shrink-0">
                                    @if($article->featured_image)
                                        <img src="{{ Storage::url($article->featured_image) }}" alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-white/5 to-white/10">
                                            <i class="ti ti-photo text-xl text-text-muted/30"></i>
                                        </div>
                                    @endif
                                    @if($article->is_featured)
                                        <div class="absolute top-1 right-1 w-5 h-5 rounded-lg bg-orange-500 flex items-center justify-center shadow-lg border border-white/20">
                                            <i class="ti ti-star-filled text-[10px] text-white"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex flex-col space-y-1.5 min-w-0">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('backend.articles.edit', $article->id) }}" class="text-sm font-black text-text-main hover:text-primary transition-colors line-clamp-1">
                                            {{ $article->title }}
                                        </a>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="text-[10px] text-text-muted flex items-center gap-1.5 leading-none uppercase tracking-wider font-bold">
                                            <i class="ti ti-calendar-event text-xs"></i>
                                            {{ $article->created_at->format('d/m/Y') }}
                                        </span>
                                        <span class="w-1 h-1 rounded-full bg-white/10"></span>
                                        <span class="text-[10px] text-text-muted flex items-center gap-1.5 leading-none uppercase tracking-wider font-bold">
                                            <i class="ti ti-device-laptop text-xs"></i>
                                            {{ $article->slug }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-6">
                            @if($article->category)
                                <div class="flex flex-col space-y-1">
                                    <span class="px-3 py-1 rounded-lg bg-primary/5 border border-primary/10 text-primary text-[10px] font-black uppercase tracking-wider inline-flex w-fit">
                                        {{ $article->category->name }}
                                    </span>
                                </div>
                            @else
                                <span class="text-[10px] font-black text-text-muted uppercase tracking-wider">Chưa phân loại</span>
                            @endif
                        </td>
                        <td class="px-6 py-6">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-[10px] font-black text-white border border-white/20 shadow-sm">
                                    {{ strtoupper(substr($article->author?->name ?? 'A', 0, 1)) }}
                                </div>
                                <span class="text-xs font-bold text-text-main">{{ $article->author?->name ?? 'Admin' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-6">
                            <div class="flex items-center gap-4">
                                <div class="flex flex-col gap-1">
                                    <span class="text-[10px] font-black text-text-muted uppercase tracking-wider flex items-center gap-1.5">
                                        <i class="ti ti-eye text-primary text-xs"></i>
                                        {{ number_format($article->view_count ?? 0) }}
                                    </span>
                                    <span class="text-[10px] font-black text-text-muted uppercase tracking-wider flex items-center gap-1.5">
                                        <i class="ti ti-messages text-primary text-xs"></i>
                                        0
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-6">
                            <x-backend.ui.toggle-icon 
                                type="status" 
                                :active="$article->is_active" 
                                wire:click="toggleStatus({{ $article->id }})" 
                                title="Chế độ hiển thị"
                            />
                        </td>
                        <td class="pr-8 pl-4 py-6">
                            <div class="flex items-center justify-end gap-2">
                                <x-backend.ui.action-icon
                                    variant="duplicate"
                                    wire:click="duplicate({{ $article->id }})"
                                    title="Sao chép bài viết"
                                />
                                <x-backend.ui.action-icon
                                    variant="edit"
                                    :href="route('backend.articles.edit', $article->id)"
                                    title="Chỉnh sửa nội dung"
                                />
                                <x-backend.ui.action-icon
                                    variant="delete"
                                    wire:click="confirmDelete({{ $article->id }}, '{{ addslashes($article->title) }}')"
                                    title="Xóa bài viết"
                                />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-8 py-24 text-center">
                            <div class="flex flex-col items-center justify-center gap-4">
                                <div class="w-20 h-20 rounded-3xl bg-white/5 border border-white/10 flex items-center justify-center animate-pulse">
                                    <i class="ti ti-article-off text-4xl text-text-muted/30"></i>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-lg font-black text-text-main tracking-tight">Không tìm thấy bài viết</p>
                                    <p class="text-xs text-text-muted max-w-[240px] mx-auto leading-relaxed">
                                        Chúng tôi không tìm thấy bài viết nào phù hợp với yêu cầu của bạn.
                                    </p>
                                </div>
                                <button wire:click="$set('search', '')" class="text-primary text-xs font-black uppercase tracking-widest hover:underline mt-2">
                                    Cài lại bộ lọc
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
        <div class="flex items-center gap-3">
            <x-backend.ui.button type="success" wire:click="bulkStatus(1)" size="sm">
                <i class="ti ti-eye"></i>
                <span>Xuất bản</span>
            </x-backend.ui.button>

            <x-backend.ui.button type="outline" wire:click="bulkStatus(0)" size="sm">
                <i class="ti ti-eye-off"></i>
                <span>Gỡ bài</span>
            </x-backend.ui.button>

            <x-backend.ui.button type="danger" wire:click="confirmBulkDelete" size="sm">
                <i class="ti ti-trash"></i>
                <span>Xóa bài</span>
            </x-backend.ui.button>
        </div>
    </x-slot:bulkActions>

    {{-- Modals --}}
    <x-slot:modals>
        <x-backend.layout.confirm-modal
            show="showDeleteModal"
            title="Xác nhận yêu cầu xóa"
            message="Hệ thống sẽ thực hiện xóa vĩnh viễn {{ $isBulkDelete ? 'các bài viết đã chọn' : 'bài viết [' . $deleteTargetName . ']' }}. Mọi dữ liệu liên quan sẽ bị loại bỏ và không thể khôi phục. Bạn có chắc chắn muốn tiếp tục?"
        >
            <x-backend.ui.button
                type="outline"
                size="md"
                wire:click="$set('showDeleteModal', false)"
                class="h-11 px-6 !rounded-xl"
            >
                Hủy bỏ
            </x-backend.ui.button>
            <x-backend.ui.button
                type="danger"
                size="md"
                wire:click="executeDelete"
                class="h-11 px-6 !rounded-xl shadow-lg shadow-red-500/20"
            >
                Xác nhận xóa
            </x-backend.ui.button>
        </x-backend.layout.confirm-modal>
    </x-slot:modals>
</x-backend.layout.index-page>
