<div>
    @section('title', $pageTitle ?? 'Create Article')

    <div class="space-y-6">
        @include('backend.layouts.partials.breadcrumbs', [
            'items' => [
                ['label' => 'Articles', 'url' => route('backend.articles.index', $type)],
                ['label' => 'Create'],
            ],
        ])

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-black text-text-main tracking-tight">{{ $pageTitle ?? 'Create Article' }}</h1>
                <p class="text-sm text-text-muted mt-1">Create new {{ $type }} content.</p>
            </div>
            <x-backend.ui.button type="outline" :href="route('backend.articles.index', $type)">Back</x-backend.ui.button>
        </div>

        <form wire:submit="save" class="space-y-6">
            @include('livewire.backend.articles._form')

            <div class="flex items-center justify-end gap-2">
                <x-backend.ui.button type="outline" :href="route('backend.articles.index', $type)">Cancel</x-backend.ui.button>
                <x-backend.ui.button type="primary" htmlType="submit">Create {{ ucfirst($type) }}</x-backend.ui.button>
            </div>
        </form>
    </div>
</div>


