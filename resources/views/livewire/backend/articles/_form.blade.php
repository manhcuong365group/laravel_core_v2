<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <x-backend.layout.form-section title="Nội dung">
            <x-backend.forms.input wire:model="title" name="title" label="Tiêu đề" required />
            <x-backend.forms.input wire:model="slug" name="slug" label="Đường dẫn (Slug)" />
            <x-backend.forms.textarea wire:model="excerpt" name="excerpt" label="Mô tả ngắn" rows="3" />

            <div class="mb-4">
                <label for="content" class="block text-sm font-bold text-text-muted mb-1.5 uppercase tracking-widest text-[11px]">Nội dung chính</label>
                <div wire:ignore>
                    <textarea id="content" wire:model="content" name="content" rows="12" class="editor w-full px-4 py-2.5 rounded-xl border border-white/5 bg-white/5 text-text-main placeholder:text-text-muted/30 focus:ring-2 focus:ring-primary/50 focus:border-primary/50 transition-all duration-300 shadow-inner"></textarea>
                </div>
                @error('content')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </x-backend.layout.form-section>

        <x-backend.layout.form-section>
            <x-slot:title>
                <div class="flex items-center justify-between w-full">
                    <span>SEO</span>
                    <button type="button" 
                        x-on:click="
                            $wire.meta_title = $wire.title;
                            $wire.meta_description = ($wire.excerpt || '').replace(/<[^>]*>?/gm, '').substring(0, 160);
                            $dispatch('toast', { message: 'Đã gợi ý dữ liệu SEO từ nội dung bài viết!', type: 'info' })
                        "
                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-primary/10 text-primary text-[10px] font-black uppercase tracking-widest border border-primary/20 hover:bg-primary hover:text-white transition-all shadow-sm group"
                    >
                        <i class="ti ti-wand text-sm group-hover:rotate-12 transition-transform"></i>
                        Gợi ý SEO
                    </button>
                </div>
            </x-slot:title>
            <x-backend.forms.input wire:model="meta_title" name="meta_title" label="Meta Title" />
            <x-backend.forms.textarea wire:model="meta_description" name="meta_description" label="Meta Description" rows="3" />
            <x-backend.forms.input wire:model="meta_keywords" name="meta_keywords" label="Meta Keywords" />
            <x-backend.forms.input wire:model="canonical_url" name="canonical_url" label="Canonical URL" />
        </x-backend.layout.form-section>
    </div>

    <div class="space-y-6">
        <x-backend.layout.form-section title="Xuất bản">
            <div class="mb-4">
                <label class="block text-sm font-bold text-text-muted mb-1.5 uppercase tracking-widest text-[11px]">Trạng thái</label>
                <select wire:model="status" name="status" class="w-full px-4 py-2.5 rounded-xl border border-white/5 bg-white/5 text-text-main focus:ring-2 focus:ring-primary/50 focus:border-primary/50">
                    <option value="draft">Bản nháp</option>
                    <option value="published">Đã đăng</option>
                    <option value="scheduled">Lên lịch</option>
                </select>
                @error('status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            
            <x-backend.forms.input wire:model="published_at" name="published_at" label="Ngày xuất bản" type="datetime-local" />
            
            <div class="space-y-2 text-sm mt-4">
                <label class="inline-flex items-center gap-2 text-text-main">
                    <input type="checkbox" wire:model="is_featured" value="1" class="rounded border-white/20 bg-white/5">
                    <span>Nổi bật</span>
                </label>
                <label class="flex items-center gap-2 text-text-main mt-2">
                    <input type="checkbox" wire:model="allow_comments" value="1" class="rounded border-white/20 bg-white/5">
                    <span>Cho phép bình luận</span>
                </label>
            </div>
        </x-backend.layout.form-section>

        <x-backend.layout.form-section title="Danh mục">
            <div class="mb-4">
                <label class="block text-sm font-bold text-text-muted mb-1.5 uppercase tracking-widest text-[11px]">Danh mục</label>
                <select wire:model="category_id" class="w-full px-4 py-2.5 rounded-xl border border-white/5 bg-white/5 text-text-main focus:ring-2 focus:ring-primary/50 focus:border-primary/50">
                    <option value="">-- Chọn danh mục --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </x-backend.layout.form-section>

        <x-backend.layout.form-section title="Ảnh đại diện">
            @if (isset($currentImageUrl) && $currentImageUrl)
                <div class="mb-3">
                    <p class="text-xs text-text-muted mb-2">Ảnh hiện tại</p>
                    <img src="{{ $currentImageUrl }}" alt="featured"
                        class="w-full h-40 object-cover rounded-xl border border-white/10">
                </div>
            @endif
            @if ($featured_image)
                <div class="mb-3">
                    <p class="text-xs text-success mb-2">Ảnh mới sẽ cập nhật:</p>
                    <img src="{{ $featured_image->temporaryUrl() }}" class="w-full h-40 object-cover rounded-xl border border-success/30">
                </div>
            @endif
            <input type="file" wire:model="featured_image" accept="image/*"
                class="w-full rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-sm text-text-main">
            @error('featured_image')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </x-backend.layout.form-section>

        @if (isset($articleId) && $articleId)
            <x-backend.layout.form-section title="Thông tin hệ thống">
                <div class="text-sm text-text-muted space-y-1">
                    <div>Tác giả: <span class="text-text-main">{{ $authorName }}</span></div>
                    <div>Ngày tạo: <span class="text-text-main">{{ $createdAt }}</span></div>
                    <div>Lượt xem: <span class="text-text-main">{{ number_format($viewCount) }}</span></div>
                </div>
            </x-backend.layout.form-section>
        @endif
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:initialized', () => {
        // Wait for CKEditor to be ready
        setTimeout(() => {
            let editor = CKEDITOR.instances['content'];
            if(editor) {
                editor.on('change', function() {
                    @this.set('content', editor.getData());
                });
            } else {
                CKEDITOR.on('instanceReady', function(evt) {
                    if (evt.editor.name === 'content') {
                        evt.editor.on('change', function() {
                            @this.set('content', evt.editor.getData());
                        });
                    }
                });
            }
        }, 100);
    });
</script>
@endpush
