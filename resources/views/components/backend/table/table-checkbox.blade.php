@props(['value' => null])

<input type="checkbox" 
    @isset($value) value="{{ $value }}" @endisset
    {{ $attributes->merge(['class' => 'w-4 h-4 rounded border-white/10 bg-white/5 text-primary focus:ring-primary/50 focus:ring-offset-0 transition-all cursor-pointer shadow-sm']) }}>
