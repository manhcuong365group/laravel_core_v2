<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

@if (isset($seoModel) || isset($seo))
    <x-seo :model="$seoModel ?? null" :seo="$seo ?? null" />
@else
    <title>{{ $pageTitle ?? config('app.name', 'Laravel Core') }}</title>
@endif

<script>
    (function() {
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        }
    })();
</script>

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link
    href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
    rel="stylesheet">

<!-- Scripts & Styles -->
@vite(['resources/css/app.css', 'resources/js/app.js'])

<!-- Tenant Dynamic Styles -->
@php
    $tenant = app(\App\Services\TenantManager::class)->getTenant();
    $settings = $tenant?->settings ?? [];
    $primary = $settings['primary_color'] ?? null;
@endphp

@if ($primary)
    <style>
        :root {
            --primary: {{ $primary }};
            --primary-glow: {{ $primary }}33;
        }
    </style>
@endif

@stack('styles')
