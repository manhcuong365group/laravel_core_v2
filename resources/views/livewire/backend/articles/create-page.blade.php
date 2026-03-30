<div class="space-y-8 pb-32">
    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-text-main tracking-tight uppercase tracking-[0.1em]">{{ $pageTitle }}</h1>
            <p class="text-[11px] font-black text-text-muted mt-1 uppercase tracking-widest opacity-60">Tạo nội dung bài viết mới trong hệ thống.</p>
        </div>
        <x-backend.ui.button variant="neutral" :href="route('backend.articles.index', $type)" icon="ti ti-arrow-left" class="rounded-2xl font-black text-[10px] uppercase tracking-widest bg-white/5 border-white/10 hover:bg-white/10">
            Quay lại danh sách
        </x-backend.ui.button>
    </div>

    <form wire:submit="save" class="space-y-8">
        @include('livewire.backend.articles._form')

        <!-- Sticky Action Bar -->
        <x-admin.sticky-bar
            cancelHref="{{ route('backend.articles.index', $type) }}"
            target="save, featured_image"
            saveLabel="Tạo bài viết ngay"
            mode="Create"
        >
            <x-slot:info>
                <span class="text-[13px] font-bold text-text-main truncate max-w-[180px]" x-data="{ title: $wire.entangle('title') }" x-text="title || 'Bài viết mới...'"></span>
            </x-slot:info>
        </x-admin.sticky-bar>
    </form>
</div>
