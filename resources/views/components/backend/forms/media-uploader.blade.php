@props([
    'model',
    'label' => 'Hình ảnh',
    'multiple' => false,
    'currentImages' => [],
    'deleteAction' => null,
    'removeTempAction' => null,
    'hint' => '',
])

<div class="space-y-4">
    <label class="block text-sm font-semibold text-text-main mb-2 tracking-tight">{{ $label }}</label>
    
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
        {{-- Existing Images (from Spatie Media Library) --}}
        @foreach($currentImages as $media)
            <div class="relative group aspect-square rounded-3xl overflow-hidden border border-white/10 bg-white/5 shadow-2xl transition-all duration-500 hover:scale-[1.02]">
                <img src="{{ $media->getUrl('thumb') }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center gap-3 backdrop-blur-sm">
                    @if($deleteAction)
                        <button type="button" 
                            wire:click="{{ $deleteAction }}({{ $media->id }})"
                            wire:confirm="Bạn có chắc chắn muốn xóa ảnh này vĩnh viễn không?"
                            class="w-10 h-10 rounded-2xl bg-danger/20 text-danger hover:bg-danger hover:text-white border border-danger/30 flex items-center justify-center transition-all shadow-lg active:scale-95">
                            <i class="ti ti-trash-x text-xl"></i>
                        </button>
                    @endif
                </div>
            </div>
        @endforeach

        {{-- Temporary Uploaded Images --}}
        @if($multiple)
            @if(is_array($model) || $model instanceof \Illuminate\Support\Collection)
                @foreach($model as $index => $tempImage)
                    @if($tempImage instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)
                        <div class="relative group aspect-square rounded-3xl overflow-hidden border border-primary/30 bg-primary/5 shadow-2xl transition-all duration-500 hover:scale-[1.02] ring-4 ring-primary/5 animate-in zoom-in duration-300">
                            <img src="{{ $tempImage->temporaryUrl() }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            <div class="absolute top-2 right-2 bg-primary text-white text-[8px] font-black px-2 py-0.5 rounded-full uppercase tracking-widest shadow-lg shadow-primary/40">Mới</div>
                            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-sm">
                                <button type="button" 
                                    wire:click="{{ $removeTempAction }}({{ $index }})"
                                    class="w-10 h-10 rounded-2xl bg-white/10 text-white hover:bg-danger border border-white/20 flex items-center justify-center transition-all shadow-lg active:scale-95">
                                    <i class="ti ti-x text-xl font-black"></i>
                                </button>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif
        @else
            @if($model instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)
                <div class="relative group aspect-square rounded-3xl overflow-hidden border border-primary/30 bg-primary/5 shadow-2xl transition-all duration-500 hover:scale-[1.02] ring-4 ring-primary/5 animate-in zoom-in duration-300">
                    <img src="{{ $model->temporaryUrl() }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-sm font-black">
                        <button type="button" 
                            wire:click="{{ $removeTempAction }}"
                            class="w-10 h-10 rounded-2xl bg-white/10 text-white hover:bg-danger border border-white/20 flex items-center justify-center transition-all shadow-lg active:scale-95">
                            <i class="ti ti-x text-xl font-black"></i>
                        </button>
                    </div>
                </div>
            @endif
        @endif

        {{-- Upload Trigger --}}
        @if(!$multiple && $model)
            {{-- Hide upload if single and already have model --}}
        @else
            <div {{ $attributes->merge(['class' => 'relative group aspect-square rounded-3xl border-2 border-dashed border-white/10 bg-white/[0.02] hover:bg-white/[0.05] hover:border-primary/50 transition-all duration-500 flex flex-col items-center justify-center gap-3 cursor-pointer overflow-hidden shadow-inner']) }}>
                <div class="w-14 h-14 rounded-2xl bg-white/5 flex items-center justify-center text-text-muted transition-all duration-500 group-hover:scale-110 group-hover:bg-primary/10 group-hover:text-primary group-hover:rotate-6 border border-white/5">
                    <i class="ti ti-photo-plus text-3xl opacity-40 group-hover:opacity-100 transition-opacity"></i>
                </div>
                <div class="text-center">
                    <span class="block text-[10px] font-black uppercase text-text-muted tracking-widest mb-0.5 group-hover:text-primary transition-colors">Tải ảnh lên</span>
                    <span class="block text-[10px] text-text-muted/40 font-bold max-w-[100px] leading-tight">Max 2MB per file</span>
                </div>
                <input type="file" 
                    wire:model="{{ $attributes->get('wire:model') }}" 
                    {{ $multiple ? 'multiple' : '' }} 
                    accept="image/*"
                    class="absolute inset-0 opacity-0 cursor-pointer z-10 w-full h-full">
            </div>
        @endif
    </div>

    @if($hint)
        <p class="text-[11px] font-bold text-text-muted/60 mt-2 ml-1 italic">{{ $hint }}</p>
    @endif
</div>
