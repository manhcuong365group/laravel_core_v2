<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">

<head>
    @includeIf('theme::layouts.partials.head', [
        'seoModel' => $article ?? ($product ?? ($page ?? null)),
        'seo' => $seo ?? null,
    ])
</head>

<body class="font-sans antialiased bg-bg-main text-text-main transition-colors duration-300">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        @includeIf('theme::layouts.partials.header')

        <!-- Main Content -->
        <main class="grow">
            @if (isset($slot))
                {{ $slot }}
            @else
                @yield('content')
            @endif
        </main>

        <!-- Footer -->
        @includeIf('theme::layouts.partials.footer')
    </div>

    @stack('scripts')
</body>

</html>
