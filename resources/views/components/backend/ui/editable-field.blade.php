@props([
    'value' => '',
    'displayValue' => null,
    'field' => '',
    'id' => null,
    'suffix' => '',
    'prefix' => '',
])

<div 
    x-data="{ 
        isEditing: false, 
        value: '{{ $value }}',
        originalValue: '{{ $value }}',
        save() {
            if (this.value !== this.originalValue) {
                $wire.updateField({{ $id }}, '{{ $field }}', this.value);
                this.originalValue = this.value;
            }
            this.isEditing = false;
        },
        cancel() {
            this.value = this.originalValue;
            this.isEditing = false;
        }
    }" 
    {{ $attributes->merge(['class' => 'inline-flex items-center group relative']) }}
>
    {{-- Display State --}}
    <div 
        x-show="!isEditing" 
        @click="isEditing = true; $nextTick(() => $refs.input.focus())"
        class="cursor-pointer border-b border-dashed border-white/20 hover:border-primary/50 transition-all duration-300 px-1 py-0.5 rounded-lg hover:bg-primary/5 min-w-[2rem] flex items-center gap-1.5"
    >
        <span class="truncate">{{ $prefix }}{{ $displayValue ?? $value }}{{ $suffix }}</span>
        <i class="ti ti-edit-circle opacity-0 group-hover:opacity-100 scale-90 group-hover:scale-100 text-primary transition-all duration-300"></i>
    </div>

    {{-- Edit State --}}
    <div 
        x-show="isEditing" 
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        class="flex items-center z-10"
    >
        <input 
            x-ref="input"
            type="text" 
            x-model="value"
            @click="$el.select()"
            @keydown.enter="save()"
            @keydown.escape="cancel()"
            @blur="save()"
            class="h-8 w-24 bg-bg-surface/95 border border-primary/50 rounded-xl text-xs font-black text-text-main px-3 py-1.5 focus:ring-4 focus:ring-primary/10 shadow-2xl backdrop-blur-xl transition-all outline-none"
        >
        
        <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-primary text-white text-[10px] font-black px-2 py-1 rounded-md shadow-lg pointer-events-none whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity">
            Nhấn Enter để lưu
        </div>
    </div>
</div>
