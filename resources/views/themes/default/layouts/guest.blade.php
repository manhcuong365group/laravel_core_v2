<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">

<head>
    @includeIf('theme::layouts.partials.head', [
        'pageTitle' => config('app.name', 'Laravel Core'),
    ])
</head>

<body class="font-sans antialiased bg-bg-main text-text-main transition-colors duration-300">
    <div class="min-h-screen flex flex-col items-center justify-center pt-6 sm:pt-0">
        <div>
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-primary" />
            </a>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-bg-surface glass-card overflow-hidden sm:rounded-2xl">
            {{ $slot }}
        </div>
    </div>

    @stack('scripts')
</body>

</html>
