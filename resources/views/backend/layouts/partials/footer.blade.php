<footer class="mt-auto py-6 px-8 border-t border-white/5 bg-bg-surface/30 backdrop-blur-md">
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
        <p class="text-[13px] text-text-muted font-medium">
            &copy; {{ date('Y') }} <span
                class="font-black text-primary tracking-tight">{{ config('app.name', 'Laravel Core') }}</span>.
            All rights reserved.
        </p>
        <div class="flex items-center gap-6">
            <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-text-muted uppercase tracking-widest opacity-60">
                <i class="ti ti-brand-laravel text-primary"></i>
                Laravel v{{ Illuminate\Foundation\Application::VERSION }}
            </span>
            <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-text-muted uppercase tracking-widest opacity-60">
                <i class="ti ti-brand-php text-primary"></i>
                PHP v{{ PHP_VERSION }}
            </span>
        </div>
    </div>
</footer>

