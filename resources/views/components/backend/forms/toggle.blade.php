@props([
    'label' => '',
    'name',
    'checked' => false,
    'value' => '1',
    'hint' => '',
])

<div class="mb-4">
    <label class="relative inline-flex items-center cursor-pointer">
        <input type="checkbox" name="{{ $name }}" value="{{ $value }}"
            {{ old($name, $checked) ? 'checked' : '' }} class="sr-only peer" {{ $attributes }}>
        <div
            class="w-11 h-6 bg-white/10 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary/30
                    rounded-full peer
                    peer-checked:after:translate-x-full peer-checked:after:border-white
                    after:content-[''] after:absolute after:top-[2px] after:left-[2px]
                    after:bg-white after:border-white/5 after:border after:rounded-full
                    after:h-5 after:w-5 after:transition-all
                    peer-checked:bg-primary shadow-inner">
        </div>
        @if ($label)
            <span
                class="ml-3 text-sm font-bold text-text-muted transition-colors peer-checked:text-text-main">{{ $label }}</span>
        @endif
    </label>

    @if ($hint)
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $hint }}</p>
    @endif

    @error($name)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>
