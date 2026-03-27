<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
    <div class="lg:col-span-8 space-y-8">
        <!-- Content Section -->
        <x-admin.form-section title="Nội dung bài viết" icon="ti-news" color="blue">
            <div class="space-y-6">
                <x-backend.forms.input wire:model.blur="title" name="title" label="Tiêu đề" required placeholder="Nhập tiêu đề bài viết..." class="text-lg font-bold" />
                <x-backend.forms.input wire:model="slug" name="slug" label="Đường dẫn tĩnh (Slug)" placeholder="tu-dong-tao-tu-tieu-de" />
                <x-backend.forms.textarea wire:model="excerpt" name="excerpt" label="Mô tả tóm tắt" rows="3" placeholder="Nhập tóm tắt ngắn gọn về bài viết..." />

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-text-main/80">Nội dung chi tiết</label>
                    <div wire:ignore class="rounded-3xl border border-white/5 overflow-hidden">
                        <textarea id="content" wire:model="content" name="content" class="editor w-full min-h-[400px] bg-white/[0.02] text-text-main focus:outline-none p-4">{{ $content }}</textarea>
                    </div>
                    @error('content') <p class="text-xs text-danger mt-1 font-medium">{{ $message }}</p> @enderror
                </div>
            </div>
        </x-admin.form-section>

        <!-- SEO Section -->
        <x-admin.seo-manager nameModel="title" descModel="excerpt" />
    </div>

    <div class="lg:col-span-4 space-y-8">
        <!-- Publish Section -->
        <x-admin.form-section title="Xuất bản" icon="ti-rocket" color="orange">
            <div class="space-y-5">
                <x-backend.forms.select wire:model="status" label="Trạng thái" :options="[
                    'draft' => 'Bản nháp',
                    'published' => 'Đã đăng',
                    'scheduled' => 'Lên lịch',
                ]" />
                
                <x-backend.forms.input wire:model="published_at" name="published_at" label="Ngày xuất bản" type="datetime-local" />
                
                <div class="grid grid-cols-2 gap-4 pt-2">
                    <label class="group relative flex flex-col items-center justify-center py-4 px-2 rounded-2xl border border-white/5 bg-white/[0.02] cursor-pointer hover:bg-amber-500/5 transition-all">
                        <input type="checkbox" wire:model="is_featured" class="peer hidden">
                        <div class="w-8 h-8 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-text-muted transition-all peer-checked:bg-amber-500 peer-checked:text-white mb-2">
                            <i class="ti ti-star-filled text-sm"></i>
                        </div>
                        <span class="text-[11px] font-bold text-text-muted uppercase peer-checked:text-amber-500">Nổi bật</span>
                    </label>

                    <label class="group relative flex flex-col items-center justify-center py-4 px-2 rounded-2xl border border-white/5 bg-white/[0.02] cursor-pointer hover:bg-blue-500/5 transition-all">
                        <input type="checkbox" wire:model="allow_comments" class="peer hidden">
                        <div class="w-8 h-8 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-text-muted transition-all peer-checked:bg-blue-500 peer-checked:text-white mb-2">
                            <i class="ti ti-message-2 text-sm"></i>
                        </div>
                        <span class="text-[11px] font-bold text-text-muted uppercase peer-checked:text-blue-500">Bình luận</span>
                    </label>
                </div>
            </div>
        </x-admin.form-section>

        <!-- Category Section -->
        <x-admin.form-section title="Phân loại" icon="ti-category" color="amber">
            <x-backend.forms.select wire:model="category_id" label="Danh mục" :options="$categories->pluck('name', 'id')->all()" placeholder="-- Chọn danh mục --" />
        </x-admin.form-section>

        <!-- Image Section -->
        <x-admin.form-section title="Ảnh đại diện" icon="ti-photo" color="purple">
            <x-backend.forms.media-uploader 
                wire:model="featured_image"
                :model="$featured_image"
                :currentImages="isset($currentImageUrl) ? [['url' => $currentImageUrl]] : []"
                label=""
                hint="Khuyên dùng: 16:9 hoặc 4:3"
            />
        </x-admin.form-section>

        @if (isset($articleId) && $articleId)
            <x-admin.form-section title="Thông số" icon="ti-chart-bar" color="emerald">
                <div class="text-xs font-medium text-text-muted space-y-2">
                    <div class="flex justify-between"><span>Tác giả:</span> <span class="text-text-main font-bold">{{ $authorName }}</span></div>
                    <div class="flex justify-between"><span>Ngày tạo:</span> <span class="text-text-main font-bold">{{ $createdAt }}</span></div>
                    <div class="flex justify-between"><span>Lượt xem:</span> <span class="text-text-main font-bold">{{ number_format($viewCount) }}</span></div>
                </div>
            </x-admin.form-section>
        @endif
    </div>
</div>
