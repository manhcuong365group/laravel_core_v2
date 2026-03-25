{{-- Hero Banner Block --}}
<section id="{{ $blockId }}" class="relative overflow-hidden min-h-[80vh] flex items-center bg-slate-900"
    x-data="{ show: false }" x-intersect.once="show = true">

    {{-- Background Image & Gradient Overlays --}}
    @if (!empty($data['image_url']))
        <div class="absolute inset-0 z-0">
            <img src="{{ $data['image_url'] }}" alt="{{ $data['title'] ?? '' }}"
                class="w-full h-full object-cover origin-center transition-transform duration-[20s] ease-linear"
                :class="show ? 'scale-110' : 'scale-100'">
            {{-- Dark/Tint overlay --}}
            <div class="absolute inset-0 bg-slate-900" style="opacity: {{ ($data['overlay_opacity'] ?? 60) / 100 }}">
            </div>
            {{-- Subtle gradient from bottom for blending if needed --}}
            <div class="absolute inset-0 bg-linear-to-t from-slate-900 via-transparent to-transparent opacity-80"></div>
        </div>
    @else
        {{-- Fallback Gradient if no image --}}
        <div class="absolute inset-0 z-0 bg-linear-to-br from-indigo-900 via-slate-900 to-slate-800"></div>
    @endif

    {{-- Content --}}
    <div
        class="relative z-10 max-w-7xl mx-auto px-6 py-24 md:py-32 w-full text-center lg:text-left grid lg:grid-cols-2 gap-12 items-center">

        <div class="transition-all duration-1000 transform translate-y-12 opacity-0"
            :class="show ? 'translate-y-0 opacity-100' : ''">

            {{-- Badge (Optional but adds a premium touch) --}}
            <div
                class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/10 mb-8 overflow-hidden group">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="text-white/90 text-sm font-bold tracking-wide">Bộ Sưu Tập Mới 2026</span>
            </div>

            @if (!empty($data['title']))
                <h1 class="text-5xl md:text-6xl lg:text-7xl font-black mb-6 leading-[1.1] text-white tracking-tight">
                    {{ $data['title'] }}
                </h1>
            @endif

            @if (!empty($data['subtitle']))
                <p
                    class="text-lg md:text-xl text-slate-300 max-w-2xl lg:max-w-none mx-auto lg:mx-0 mb-10 leading-relaxed font-medium">
                    {{ $data['subtitle'] }}
                </p>
            @endif

            @if (!empty($data['button_text']))
                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                    <a href="{{ $data['button_url'] ?? '#' }}"
                        class="inline-flex items-center justify-center gap-3 px-8 py-4 bg-white text-slate-900 font-black rounded-2xl hover:bg-slate-50 shadow-xl shadow-white/10 hover:shadow-2xl hover:shadow-white/20 hover:-translate-y-1 transition-all duration-300 w-full sm:w-auto text-lg">
                        {{ $data['button_text'] }}
                        <i class="ti ti-arrow-right text-xl"></i>
                    </a>
                </div>
            @endif
        </div>

        {{-- Right Side Decorative Card (Only if you want a 2-col hero) --}}
        <div class="hidden lg:block relative transition-all duration-1000 delay-300 transform translate-x-12 opacity-0"
            :class="show ? 'translate-x-0 opacity-100' : ''">
            <div
                class="relative w-full aspect-4/5 rounded-3xl overflow-hidden glass-card border border-white/20 shadow-2xl skew-y-3 -rotate-3 hover:rotate-0 hover:skew-y-0 transition-transform duration-700 p-2">
                @if (!empty($data['image_url']))
                    <img src="{{ $data['image_url'] }}" alt="Hero featured"
                        class="w-full h-full object-cover rounded-2xl">
                @else
                    <div class="w-full h-full bg-slate-800 rounded-2xl flex items-center justify-center">
                        <i class="ti ti-photo-off text-6xl text-slate-600"></i>
                    </div>
                @endif
            </div>
            {{-- Decorative blur blob --}}
            <div
                class="absolute -z-10 top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-indigo-500/30 blur-[100px] rounded-full">
            </div>
        </div>
    </div>
</section>
