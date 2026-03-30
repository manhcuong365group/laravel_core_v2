<section class="page space-y-8 pb-32">
    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-primary/10 flex items-center justify-center border border-primary/20 text-primary shadow-inner">
                 <x-mary-icon name="o-folder-plus" class="w-7 h-7" />
            </div>
            <div>
                <p class="text-[11px] font-black text-primary uppercase tracking-[0.2em] mb-1 opacity-80">Quản lý nội dung</p>
                <h1 class="text-3xl font-black text-text-main tracking-tight uppercase tracking-[0.1em]">{{ $title }}</h1>
            </div>
        </div>
        <x-mary-button label="Quay lại" icon="o-arrow-left" link="{{ route('backend.categories.index', $form->type) }}" class="btn-ghost rounded-2xl border-white/5 font-bold" />
    </div>

    <form wire:submit="save" class="grid grid-cols-1 xl:grid-cols-12 gap-8 items-start">
        <!-- Main Form Column (8cols) -->
        <div class="xl:col-span-8 space-y-8">
            
            <!-- Basic Information -->
            <x-mary-card title="Thông tin cơ bản" separator shadow class="glass-card rounded-3xl border-white/10 shadow-2xl">
                <div class="grid grid-cols-1 gap-8 p-2">
                    <x-mary-input 
                        wire:model.blur="form.name" 
                        label="Tên danh mục" 
                        required 
                        placeholder="VD: Điện thoại, Tin tức công nghệ..." 
                        class="text-lg font-black tracking-tight focus:ring-primary/20" 
                    />
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <x-mary-input wire:model="form.slug" label="URL tĩnh (Slug)" placeholder="tu-dong-tao-tu-ten" icon="o-link" hint="Dùng để hiển thị trên trình duyệt" />
                        <x-mary-input wire:model="form.order" label="Thứ tự hiển thị" type="number" placeholder="0" icon="o-hashtag" hint="Số nhỏ sẽ hiển thị trước" />
                    </div>

                    <x-mary-textarea wire:model="form.description" label="Mô tả chi tiết" rows="6" placeholder="Nhập mô tả tóm tắt cho danh mục này..." inline />
                </div>
            </x-mary-card>

            <!-- SEO Settings -->
            <x-mary-card title="Tối ưu SEO (Google)" separator shadow x-data="{ expanded: false }" class="glass-card rounded-3xl border-white/10 shadow-2xl overflow-hidden">
                <x-slot:menu>
                    <x-mary-button @click="expanded = !expanded" :icon="expanded ? 'o-chevron-up' : 'o-chevron-down'" class="btn-ghost btn-sm text-text-muted" />
                </x-slot:menu>
                
                <div x-show="expanded" x-collapse class="space-y-8 p-2">
                    <x-mary-input wire:model="form.meta_title" label="Tiêu đề SEO (Meta Title)" placeholder="Tối ưu cho kết quả tìm kiếm" />
                    <x-mary-textarea wire:model="form.meta_description" label="Mô tả SEO (Meta Description)" placeholder="Đoạn văn ngắn giới thiệu trên Google" />
                    <x-mary-input wire:model="form.meta_keywords" label="Từ khóa SEO" placeholder="VD: điện thoại, giá rẻ, chính hãng" />
                </div>
                
                <div x-show="!expanded" class="text-[10px] text-text-muted italic opacity-40 uppercase tracking-widest font-black p-2">
                    <x-mary-icon name="o-information-circle" class="w-3 h-3 mr-1 inline" /> Nhấn để cấu hình Meta Tags giúp tăng thứ hạng tìm kiếm...
                </div>
            </x-mary-card>
        </div>

        <!-- Sidebar Column (4cols) -->
        <div class="xl:col-span-4 space-y-8">
            
            <!-- Hierarchical Structure -->
            <x-mary-card title="Phân cấp" separator shadow class="glass-card rounded-3xl border-white/10 shadow-2xl">
                <div class="p-2 space-y-6">
                    <x-mary-select 
                        wire:model="form.parent_id" 
                        label="Danh mục cha" 
                        :options="$parentCategories" 
                        placeholder="-- Cấp độ cao nhất --"
                        icon="o-list-bullet"
                        class="font-bold"
                    />
                    <div class="bg-primary/5 border border-primary/10 rounded-2xl p-4">
                        <p class="text-[10px] text-primary font-black uppercase tracking-widest leading-relaxed">
                            Mẹo: Chọn danh mục cha nếu muốn tạo tiểu mục (sub-category).
                        </p>
                    </div>
                </div>
            </x-mary-card>

            <!-- Status & Visibility -->
            <x-mary-card title="Hiển thị" separator shadow class="glass-card rounded-3xl border-white/10 shadow-2xl">
                <div class="space-y-6 p-2">
                    <x-mary-toggle label="Công khai hiển thị" wire:model="form.is_active" class="toggle-success font-black text-xs" right />
                    <x-mary-toggle label="Hiển thị trên menu" wire:model="form.show_in_menu" class="toggle-primary font-black text-xs" right />
                </div>
            </x-mary-card>

            <!-- Image Asset -->
            <x-mary-card title="Ảnh đại diện" separator shadow class="glass-card rounded-3xl border-white/10 shadow-2xl">
                <div class="p-2">
                    <x-mary-file wire:model="form.image" label="" hint="Đề xuất: 800x600px, dung lượng < 2MB" crop-after-change>
                        <div class="relative group cursor-pointer overflow-hidden rounded-2xl border-2 border-dashed border-white/10 hover:border-primary/50 transition-all duration-300 aspect-square flex items-center justify-center bg-base-200/30">
                            @if($form->image)
                                <img src="{{ $form->image->temporaryUrl() }}" class="object-cover w-full h-full" />
                            @else
                                <div class="flex flex-col items-center gap-2">
                                    <x-mary-icon name="o-cloud-arrow-up" class="w-10 h-10 text-text-muted/20" />
                                    <span class="text-[10px] font-black uppercase tracking-widest text-text-muted/40 group-hover:text-primary transition-colors">Tải lên hình ảnh</span>
                                </div>
                            @endif
                        </div>
                    </x-mary-file>
                </div>
            </x-mary-card>
        </div>

        <!-- STICKY ACTION BAR -->
        <div class="fixed bottom-8 left-1/2 -translate-x-1/2 w-full max-w-4xl px-4 z-50">
            <div class="backdrop-blur-2xl bg-base-100/60 border border-white/20 p-4 rounded-3xl shadow-2xl flex items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary font-bold shadow-inner border border-primary/20">
                        <x-mary-icon name="o-sparkles" class="w-6 h-6 animate-pulse" />
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[9px] font-black uppercase text-primary tracking-[0.2em] opacity-80">Đang chuẩn bị</span>
                        <span class="text-base font-black text-text-main truncate max-w-[200px] tracking-tight">{{ $form->name ?: 'Danh mục mới...' }}</span>
                    </div>
                </div>
                
                <div class="flex items-center gap-3">
                    <x-mary-button label="Hủy" link="{{ route('backend.categories.index', $form->type) }}" class="btn-ghost font-bold" />
                    <x-mary-button label="Xác nhận lưu ✨" type="submit" class="btn-primary shadow-xl shadow-primary/30 font-black px-10 h-12 rounded-2xl" spinner="save" />
                </div>
            </div>
        </div>
    </form>
</section>
="btn-primary shadow-lg shadow-primary/30 font-black px-8" spinner="save" />
                </div>
            </div>
        </div>
    </form>
</div>
