@props([
    'label' => '',
    'name' => '',
    'options' => [],
    'selected' => '',
    'placeholder' => '-- Chọn --',
    'required' => false,
    'disabled' => false,
    'hint' => '',
])

<div class="mb-4">
    @if ($label)
        <label for="{{ $name }}"
            class="block text-sm font-semibold text-text-main mb-1">
            {{ $label }}
            @if ($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    <select name="{{ $name }}" id="{{ $name }}" {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->merge([
            'class' => 'w-full px-4 py-3 rounded-2xl border border-white/5
                                       bg-white/5 text-text-main
                                       hover:bg-white/10 hover:border-white/10
                                       focus:bg-white/10 focus:ring-1 focus:ring-primary/50 focus:border-primary/50
                                       disabled:bg-white/2 disabled:cursor-not-allowed
                                       transition-all duration-300 shadow-inner appearance-none',
        ]) }}>
        @if ($placeholder)
            <option value="" class="bg-bg-surface text-text-muted">{{ $placeholder }}</option>
        @endif

        @foreach ($options as $value => $optionLabel)
            <option value="{{ $value }}" {{ old($name, $selected) == $value ? 'selected' : '' }}
                class="bg-bg-surface text-text-main">
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>

    @if ($hint)
        <p class="mt-1.5 text-xs font-medium text-text-muted/60">{{ $hint }}</p>
    @endif

    @error($name)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>
