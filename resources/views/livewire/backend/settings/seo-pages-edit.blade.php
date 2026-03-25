<div class="space-y-6">
    @include('backend.layouts.partials.breadcrumbs', [
        'items' => [
            ['label' => 'Cài đặt hệ thống', 'url' => route('backend.settings.general')],
            ['label' => 'SEO trang', 'url' => route('backend.settings.seo-pages')],
            ['label' => 'Chỉnh sửa SEO: ' . $this->getPageTypeName($pageType)],
        ],
    ])

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-text-main tracking-tight">SEO: {{ $this->getPageTypeName($pageType) }}</h1>
            <p class="mt-1 text-sm text-text-muted">Tối ưu hóa công cụ tìm kiếm cho trang {{ $this->getPageTypeName($pageType) }}.</p>
        </div>
    </div>

    <form wire:submit.prevent="update">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <x-backend.layout.form-section title="Meta Tags" description="Tiêu đề và mô tả hiển thị trên Google.">
                <x-backend.forms.input wire:model="title" name="title" label="Meta Title" placeholder="Tiêu đề trang" />
                <x-backend.forms.textarea wire:model="meta_description" name="meta_description" label="Meta Description" rows="3" placeholder="Mô tả ngắn về trang" />
                <x-backend.forms.input wire:model="meta_keywords" name="meta_keywords" label="Meta Keywords" placeholder="Từ khóa, cách nhau bằng dấu phẩy" />
            </x-backend.layout.form-section>

            <x-backend.layout.form-section title="Social (Open Graph)" description="Cách trang hiển thị khi chia sẻ trên Facebook, Zalo.">
                <x-backend.forms.input wire:model="og_title" name="og_title" label="OG Title" />
                <x-backend.forms.textarea wire:model="og_description" name="og_description" label="OG Description" rows="2" />
                
                <div class="space-y-4">
                    <x-backend.forms.input wire:model="og_image_url" name="og_image_url" label="OG Image URL" placeholder="https://..." />
                    
                    <div>
                        <label class="block text-sm font-bold text-text-muted mb-1.5 uppercase tracking-widest text-[11px]">Hoặc tải lên ảnh</label>
                        <div class="relative group">
                            <div class="w-full h-32 rounded-xl border border-dashed border-border-glass bg-white/5 flex items-center justify-center overflow-hidden">
                                @if ($og_image_file)
                                    <img src="{{ $og_image_file->temporaryUrl() }}" class="max-h-full object-contain p-2">
                                @elseif ($current_og_image)
                                    <img src="{{ $current_og_image }}" class="max-h-full object-contain p-2">
                                @else
                                    <i class="ti ti-photo-plus text-3xl text-text-muted"></i>
                                @endif
                            </div>
                            <input type="file" wire:model="og_image_file" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer">
                        </div>
                        @error('og_image_file') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <x-backend.forms.select wire:model="twitter_card" name="twitter_card" label="Twitter Card"
                    :options="[
                        'summary' => 'Summary',
                        'summary_large_image' => 'Summary Large Image',
                        'app' => 'App',
                        'player' => 'Player',
                    ]" />
            </x-backend.layout.form-section>

            <x-backend.layout.form-section title="Nâng cao" description="Schema markup và custom scripts.">
                <x-backend.forms.textarea wire:model="schema_markup" name="schema_markup" label="Schema Markup (JSON-LD)" rows="5" placeholder='{ "@context": "https://schema.org", ... }' />
                <x-backend.forms.textarea wire:model="custom_head" name="custom_head" label="Custom Head Scripts" rows="3" placeholder="<script>...</script>" />
                <x-backend.forms.textarea wire:model="custom_body" name="custom_body" label="Custom Body Scripts" rows="3" placeholder="<script>...</script>" />
            </x-backend.layout.form-section>

            <div class="flex items-center justify-end space-x-3">
                <a href="{{ route('backend.settings.seo-pages') }}" class="px-4 py-2 border border-border-glass rounded-xl text-sm font-semibold text-text-main hover:bg-white/5 transition-colors">
                    Hủy
                </a>
                <x-backend.ui.button type="primary" htmlType="submit" wire:loading.attr="disabled">
                    <span wire:loading.remove>Lưu thay đổi</span>
                    <span wire:loading>Đang lưu...</span>
                </x-backend.ui.button>
            </div>
        </div>
    </form>
</div>


