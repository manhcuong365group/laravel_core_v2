<div class="rounded-2xl border border-border-glass bg-bg-surface/60 p-1 flex flex-wrap gap-1">
    <a href="{{ route('backend.settings.general') }}"
        class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('backend.settings.general') || request()->routeIs('backend.settings.index') ? 'bg-primary text-white shadow-glow-primary' : 'text-text-muted hover:bg-white/5 hover:text-text-main' }}"
        wire:navigate>
        <i class="ti ti-settings"></i>
        Cấu hình chung
    </a>
    <a href="{{ route('backend.settings.contact') }}"
        class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('backend.settings.contact') ? 'bg-primary text-white shadow-glow-primary' : 'text-text-muted hover:bg-white/5 hover:text-text-main' }}"
        wire:navigate>
        <i class="ti ti-mail"></i>
        Liên hệ
    </a>
    <a href="{{ route('backend.settings.social') }}"
        class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('backend.settings.social') ? 'bg-primary text-white shadow-glow-primary' : 'text-text-muted hover:bg-white/5 hover:text-text-main' }}"
        wire:navigate>
        <i class="ti ti-brand-facebook"></i>
        Mạng xã hội
    </a>
    <a href="{{ route('backend.settings.seo-pages') }}"
        class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('backend.settings.seo-pages*') ? 'bg-primary text-white shadow-glow-primary' : 'text-text-muted hover:bg-white/5 hover:text-text-main' }}"
        wire:navigate>
        <i class="ti ti-seo"></i>
        SEO Trang
    </a>
    <a href="{{ route('backend.settings.redirects') }}"
        class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('backend.settings.redirects') ? 'bg-primary text-white shadow-glow-primary' : 'text-text-muted hover:bg-white/5 hover:text-text-main' }}"
        wire:navigate>
        <i class="ti ti-arrow-forward-up"></i>
        Redirects
    </a>
</div>


