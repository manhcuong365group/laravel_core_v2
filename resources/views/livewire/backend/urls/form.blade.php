<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-text-main tracking-tight">{{ $pageTitle }}</h1>
            <p class="text-sm text-text-muted mt-1.5">{{ $isEdit ? 'Chỉnh sửa thông tin URL.' : 'Thêm URL mới vào hệ thống.' }}</p>
        </div>
        <x-backend.ui.button variant="neutral" :href="route('backend.urls.index')" icon="ti ti-arrow-left">
            Quay lại
        </x-backend.ui.button>
    </div>

    <form wire:submit="save" class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start">
        <div class="xl:col-span-8 space-y-6">
            <x-backend.layout.card title="Thông tin URL">
                <div class="space-y-4">
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label class="block text-sm font-semibold text-text-main">Tiêu đề <span class="text-rose-500">*</span></label>
                            <button type="button" wire:click="fetchTitle" wire:loading.attr="disabled"
                                class="text-[10px] font-bold text-primary hover:text-primary-hover flex items-center gap-1 transition-colors">
                                <i class="ti ti-wand" wire:loading.remove wire:target="fetchTitle"></i>
                                <i class="ti ti-loader animate-spin" wire:loading wire:target="fetchTitle"></i>
                                Lấy tiêu đề tự động
                            </button>
                        </div>
                        <x-backend.forms.input wire:model.blur="title" name="title" required placeholder="Nhập tiêu đề gợi nhớ..." />
                    </div>
                    
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label class="block text-sm font-semibold text-text-main">URL gốc <span class="text-rose-500">*</span></label>
                            <button type="button" wire:click="toggleInternalSearch"
                                class="text-[10px] font-bold text-purple-400 hover:text-purple-300 flex items-center gap-1 transition-colors">
                                <i class="ti ti-search"></i> Chọn từ Website
                            </button>
                        </div>
                        <x-backend.forms.input wire:model.blur="original_url" name="original_url" required placeholder="https://example.com/very-long-url..." />
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="block text-sm font-semibold text-text-main mb-1">Mã rút gọn (Tùy chọn)</label>
                            <div class="flex items-center">
                                <span class="px-3 py-2.5 bg-white/5 border border-r-0 border-white/5 rounded-l-xl text-text-muted text-sm">{{ url('/') }}/</span>
                                <input type="text" wire:model="short_url" placeholder="slug-rut-gon"
                                    class="flex-1 px-4 py-2.5 rounded-r-xl border border-white/5 bg-white/5 text-text-main placeholder-text-muted/40 focus:ring-1 focus:ring-primary/50 focus:border-primary/50 transition-all">
                            </div>
                            @error('short_url') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        
                        <x-backend.forms.select wire:model="is_active" label="Trạng thái" :options="[
                            1 => 'Hiển thị',
                            0 => 'Tạm ẩn'
                        ]" />
                    </div>
                </div>
            </x-backend.layout.card>

            <x-backend.layout.card title="Cấu hình nâng cao">
                <div class="space-y-4">
                    <x-backend.forms.textarea wire:model="description" name="description" label="Ghi chú / Mô tả" rows="3" placeholder="Ghi chú nội dung URL này..." />
                </div>
            </x-backend.layout.card>
        </div>

        <div class="xl:col-span-4 space-y-6">
            <x-backend.layout.card title="Thao tác">
                <div class="space-y-4">
                    @if($isEdit)
                        <div class="p-4 rounded-2xl bg-primary/5 border border-primary/10">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold text-text-muted uppercase">Thống kê nhanh</span>
                                <i class="ti ti-chart-bar text-primary"></i>
                            </div>
                            <div class="text-2xl font-black text-text-main">{{ number_format($url->click_count) }}</div>
                            <div class="text-[10px] text-text-muted font-medium">Tổng lượt truy cập</div>
                        </div>
                    @endif

                    <div class="relative group">
                        <div class="absolute -inset-[1px] bg-gradient-to-r from-primary to-purple-500 rounded-2xl opacity-50 group-hover:opacity-100 blur-sm transition-opacity duration-500 pointer-events-none"></div>
                        <button type="submit" wire:loading.attr="disabled" 
                            class="relative w-full px-6 py-3 flex items-center justify-center gap-2 rounded-2xl bg-bg-surface border border-white/10 hover:bg-white/5 text-text-main font-bold transition-all duration-300">
                            <span wire:loading.remove>
                                <i class="ti ti-device-floppy"></i> {{ $isEdit ? 'Cập nhật URL' : 'Tạo URL rút gọn' }}
                            </span>
                            <span wire:loading class="flex items-center gap-2 text-primary">
                                <i class="ti ti-loader animate-spin"></i> Đang lưu...
                            </span>
                        </button>
                    </div>
                </div>
            </x-backend.layout.card>
        </div>
    </form>

    {{-- Internal Search Modal --}}
    @if($showInternalSearch)
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-bg-main/60 backdrop-blur-md" wire:click="toggleInternalSearch"></div>
            
            <div class="relative w-full max-w-2xl bg-bg-surface border border-white/10 rounded-3xl shadow-2xl overflow-hidden animate-in zoom-in duration-300">
                <div class="p-6 border-b border-white/5 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-text-main">Chọn từ Website</h3>
                        <p class="text-xs text-text-muted mt-0.5">Tìm kiếm Sản phẩm hoặc Tin bài để tạo link rút gọn.</p>
                    </div>
                    <button type="button" wire:click="toggleInternalSearch" class="p-2 rounded-xl hover:bg-white/5 transition-colors">
                        <i class="ti ti-x text-lg"></i>
                    </button>
                </div>

                <div class="p-6 space-y-6">
                    <div class="relative">
                        <i class="ti ti-search absolute left-4 top-1/2 -translate-y-1/2 text-text-muted"></i>
                        <input type="text" wire:model.live.debounce.300ms="internalSearch" autofocus
                            placeholder="Nhập tên sản phẩm hoặc tin bài..."
                            class="w-full pl-12 pr-4 py-4 rounded-2xl border border-white/5 bg-white/5 text-text-main placeholder-text-muted/40 focus:ring-1 focus:ring-primary/50 transition-all">
                    </div>

                    <div class="max-h-[400px] overflow-y-auto space-y-2 pr-2 custom-scrollbar">
                        @php $results = $this->getInternalResults(); @endphp
                        
                        @forelse($results as $item)
                            <button type="button" 
                                wire:click="selectInternal('{{ $item['type'] }}', {{ $item['id'] }}, '{{ addslashes($item['title']) }}', '{{ $item['url'] }}')"
                                class="w-full p-4 flex items-center justify-between rounded-2xl bg-white/5 border border-transparent hover:border-primary/30 hover:bg-primary/5 transition-all group text-left">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-text-muted group-hover:bg-primary group-hover:text-white transition-all">
                                        <i class="{{ $item['icon'] }}"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-text-main line-clamp-1 italic">{{ $item['title'] }}</div>
                                        <div class="text-[10px] uppercase font-bold text-text-muted mt-1 flex items-center gap-2">
                                            <span class="px-1.5 py-0.5 rounded bg-white/10">{{ $item['sub'] }}</span>
                                            <span class="text-[8px] opacity-40">{{ $item['url'] }}</span>
                                        </div>
                                    </div>
                                </div>
                                <i class="ti ti-chevron-right text-text-muted group-hover:translate-x-1 group-hover:text-primary transition-all"></i>
                            </button>
                        @empty
                            @if(empty($internalSearch))
                                <div class="py-12 flex flex-col items-center justify-center text-text-muted opacity-50">
                                    <i class="ti ti-search text-4xl mb-4"></i>
                                    <p class="text-sm font-medium">Nhập từ khóa để bắt đầu tìm kiếm...</p>
                                </div>
                            @else
                                <div class="py-12 flex flex-col items-center justify-center text-text-muted opacity-50">
                                    <i class="ti ti-search-off text-4xl mb-4 text-warning"></i>
                                    <p class="text-sm font-medium italic">Không tìm thấy nội dung nào phù hợp.</p>
                                </div>
                            @endif
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
