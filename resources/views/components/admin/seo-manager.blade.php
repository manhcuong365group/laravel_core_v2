@props([
    'title' => 'Tối ưu hóa tìm kiếm (SEO)',
    'icon' => 'ti-search',
    'color' => 'emerald',
    'nameModel' => 'name',
    'descModel' => 'short_description'
])

<x-admin.form-section 
    :title="$title" 
    :icon="$icon" 
    :color="$color"
    {{ $attributes }}
>
    <x-slot:action>
        <button type="button" 
            x-on:click="
                $wire.meta_title = $wire.{{ $nameModel }};
                $wire.meta_description = ($wire.{{ $descModel }} || '').replace(/<[^>]*>?/gm, '').substring(0, 160);
                $dispatch('toast', { message: 'Đã tự động tối ưu hóa từ nội dung!', type: 'success' })
            "
            class="flex items-center gap-2 px-4 py-2 rounded-2xl bg-primary/10 text-primary text-[11px] font-black uppercase tracking-widest border border-primary/20 hover:bg-primary hover:text-white transition-all shadow-md group active:scale-95"
        >
            <i class="ti ti-wand text-base group-hover:rotate-12 transition-transform"></i>
            Tự động đề xuất
        </button>
    </x-slot:action>

    <div class="space-y-8 pt-2">
        <!-- Google Preview -->
        <div class="p-6 rounded-[2rem] bg-indigo-950/20 border border-white/5 relative overflow-hidden group shadow-2xl" 
            x-data="{ 
                get displayTitle() { return $wire.meta_title || $wire.{{ $nameModel }} || 'Tiêu đề kết quả tìm kiếm Google' },
                get displayDesc() { return $wire.meta_description || 'Mô tả hấp dẫn giúp tăng tỷ lệ nhấp chuột vào nội dung của bạn...' }
            }">
            <div class="absolute -top-12 -right-12 w-48 h-48 bg-primary/10 blur-[80px] rounded-full"></div>
            
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-2xl bg-white flex items-center justify-center text-[#202124] shadow-sm ring-4 ring-white/5">
                    <i class="ti ti-brand-google text-2xl"></i>
                </div>
                <span class="text-xs text-text-muted/80 font-black uppercase tracking-widest">Google Preview</span>
            </div>

            <div class="space-y-1.5 relative z-10">
                <div class="text-[14px] text-[#202124] dark:text-[#bdc1c6] mb-0.5 opacity-80 flex items-center gap-1">
                    <span>{{ url('/') }}</span>
                    <i class="ti ti-chevron-right text-[10px]"></i>
                    <span class="font-medium">...</span>
                </div>
                <div class="text-[20px] text-[#1a0dab] dark:text-[#8ab4f8] font-medium leading-tight hover:underline cursor-pointer decoration-2 underline-offset-4" x-text="displayTitle"></div>
                <div class="text-[14px] text-[#4d5156] dark:text-[#bdc1c6] leading-relaxed line-clamp-2" x-text="displayDesc"></div>
            </div>
        </div>

        <!-- SEO Inputs -->
        <div class="grid grid-cols-1 gap-6">
            <x-backend.forms.input 
                wire:model="meta_title" 
                name="meta_title" 
                label="SEO Title (Tiêu đề SEO)" 
                placeholder="Tiêu đề hiển thị trên Google" 
                hint="Tốt nhất là dưới 60 ký tự"
            />
            
            <x-backend.forms.textarea 
                wire:model="meta_description" 
                name="meta_description" 
                label="Meta Description (Mô tả SEO)" 
                rows="3" 
                placeholder="Mô tả nội dung tóm tắt cho công cụ tìm kiếm" 
                hint="Tốt nhất từ 150-160 ký tự"
            />

            <x-backend.forms.input 
                wire:model="meta_keywords" 
                name="meta_keywords" 
                label="Keywords (Từ khóa)" 
                placeholder="Từ khóa 1, từ khóa 2, từ khóa 3..." 
            />
        </div>
    </div>
</x-admin.form-section>
