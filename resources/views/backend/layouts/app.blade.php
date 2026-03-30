<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
    x-data="{
        sidebarOpen: localStorage.getItem('adminSidebarOpen') !== 'false',
        darkMode: localStorage.getItem('darkMode') === 'true',
        adminTheme: localStorage.getItem('adminTheme') || 'default',
        configOpen: false
    }" 
    x-init="
        $watch('darkMode', val => {
            localStorage.setItem('darkMode', val);
            document.documentElement.classList.toggle('dark', val);
            window.dispatchEvent(new Event('theme-changed'));
        });
        $watch('adminTheme', val => {
            localStorage.setItem('adminTheme', val);
            // Remove old theme classes
            document.documentElement.classList.forEach(cls => {
                if (cls.startsWith('theme-')) document.documentElement.classList.remove(cls);
            });
            if (val !== 'default') document.documentElement.classList.add('theme-' + val);
            window.dispatchEvent(new Event('theme-changed'));
        });
        $watch('sidebarOpen', val => localStorage.setItem('adminSidebarOpen', val ? 'true' : 'false'));
    "
    @toggle-sidebar.window="sidebarOpen = !sidebarOpen"
    @toggle-config.window="configOpen = !configOpen">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Admin' }} - {{ config('app.name', 'Laravel Core') }}</title>

    <!-- PREVENT THEME/DARK MODE FLICKER: Must be blocking and at the top -->
    <script>
        const applyTheme = () => {
            const html = document.documentElement;
            const isDark = localStorage.getItem('darkMode') === 'true';
            const theme = localStorage.getItem('adminTheme') || 'default';
            const customColor = localStorage.getItem('themeColor');
            
            // 1. Handle Dark Mode
            if (isDark && !html.classList.contains('dark')) html.classList.add('dark');
            else if (!isDark && html.classList.contains('dark')) html.classList.remove('dark');
            
            // 2. Handle Theme Class
            const currentThemeClass = Array.from(html.classList).find(c => c.startsWith('theme-'));
            const newThemeClass = theme !== 'default' ? 'theme-' + theme : null;

            if (currentThemeClass !== newThemeClass) {
                if (currentThemeClass) html.classList.remove(currentThemeClass);
                if (newThemeClass) html.classList.add(newThemeClass);
            }

            // 3. Handle Custom Hex Color (The key fix for your issue)
            if (customColor) {
                html.style.setProperty('--primary', customColor);
                // Simple helper to generate glow color (20% opacity)
                if (customColor.startsWith('#')) {
                    const r = parseInt(customColor.slice(1, 3), 16);
                    const g = parseInt(customColor.slice(3, 5), 16);
                    const b = parseInt(customColor.slice(5, 7), 16);
                    html.style.setProperty('--primary-glow', `rgba(${r}, ${g}, ${b}, 0.2)`);
                }
            } else {
                html.style.removeProperty('--primary');
                html.style.removeProperty('--primary-glow');
            }
        };

        // Run immediately on first load
        applyTheme();

        // Run every time Livewire finishes navigating
        document.addEventListener('livewire:navigated', applyTheme);
    </script>

    @livewireStyles

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" />

    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- CKEditor 4 -->
    <script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>

    <!-- Cropper.js for MaryUI crop -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>

    <!-- SortableJS for Drag & Drop -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 20px;
        }

        .custom-scrollbar:hover::-webkit-scrollbar-thumb {
            background: var(--color-border-glass);
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
    @stack('styles')
</head>

<body class="font-sans antialiased bg-bg-main text-text-main selection:bg-primary/30">
    <div class="admin-nav-progress"></div>
    @auth
        <div class="flex min-h-screen">
            <!-- Sidebar -->
            @include('backend.layouts.partials.sidebar')

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col transition-all duration-500" :class="sidebarOpen ? 'lg:ml-72' : 'lg:ml-24'">

                <!-- Navbar -->
                @include('backend.layouts.partials.navbar')

                <!-- Page Content -->
                <main class="flex-1 p-4 lg:p-8 2xl:p-10 w-full max-w-[1920px] mx-auto">
                    @isset($slot)
                        {{ $slot }}
                    @else
                        @yield('content')
                    @endisset
                </main>

                <!-- Footer -->
                @include('backend.layouts.partials.footer')
            </div>
        </div>

        <!-- Mobile Overlay -->
        <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" @click="sidebarOpen = false"
            class="fixed inset-0 bg-black/50 z-30 lg:hidden">
        </div>

        <!-- Configurator Overlay -->
        <div x-show="configOpen" x-cloak class="fixed inset-0 z-60"></div>

        <!-- Components -->
        <x-mary-toast />
        @include('backend.layouts.partials.toast')
        @include('backend.layouts.partials.command-palette')
        @include('backend.layouts.partials.theme-configurator')
    @else
        @yield('content')
    @endauth

    @livewireScripts
    @stack('scripts')

    <script>
        window.initEditors = window.initEditors || function() {
            const editors = document.querySelectorAll('.editor');
            editors.forEach(editorElement => {
                // Skip if already initialized or if CKEDITOR is not defined
                if (!window.CKEDITOR || editorElement.dataset.ckeditorInitialized) return;

                const editor = CKEDITOR.replace(editorElement, {
                    filebrowserUploadUrl: "{{ route('backend.editor.upload', ['_token' => csrf_token()]) }}",
                    filebrowserUploadMethod: 'form',
                    height: 400,
                    allowedContent: true,
                    versionCheck: false
                });

                // Mark as initialized
                editorElement.dataset.ckeditorInitialized = true;

                // Sync data with Livewire wire:model
                editor.on('change', function() {
                    const content = editor.getData();
                    // Find the closest Livewire component and set the property
                    const componentId = editorElement.closest('[wire\\:id]')?.getAttribute('wire:id');
                    const wireModel = editorElement.getAttribute('wire:model');
                    
                    if (componentId && wireModel) {
                        Livewire.find(componentId).set(wireModel, content);
                    } else if (editorElement.id === 'editor' || editorElement.id === 'content') {
                        // Fallback for common IDs if wire:model is not directly on the element
                        const component = editorElement.closest('[wire\\:id]') ? Livewire.find(editorElement.closest('[wire\\:id]').getAttribute('wire:id')) : null;
                        if (component) component.set('content', content);
                    }
                });
            });
        };

        document.addEventListener('DOMContentLoaded', window.initEditors);
        document.addEventListener('livewire:navigated', window.initEditors);

        (function() {
            let prefetchTimeout;
            const html = document.documentElement;
            const recentStorageKey = 'adminRecentPages';

            const shouldHandleLink = (link, event) => {
                if (!link || !link.href) return false;
                if (event && (event.metaKey || event.ctrlKey || event.shiftKey || event.altKey)) return false;
                if (link.target && link.target !== '_self') return false;
                if (link.hasAttribute('download')) return false;
                if (link.getAttribute('href')?.startsWith('#')) return false;

                const url = new URL(link.href, window.location.origin);
                if (url.origin !== window.location.origin) return false;
                if (url.pathname === window.location.pathname && url.search === window.location.search) return false;
                return true;
            };

            const saveRecentPage = () => {
                if (!window.location.pathname.startsWith('/admin')) return;

                const current = {
                    title: document.title.replace(/\s*-\s*[^-]+$/, '').trim() || 'Admin',
                    url: `${window.location.pathname}${window.location.search}`,
                    icon: 'ti ti-history',
                    visitedAt: Date.now(),
                };

                try {
                    const existing = JSON.parse(localStorage.getItem(recentStorageKey) || '[]');
                    const normalized = Array.isArray(existing) ? existing.filter((item) => item?.url && item.url !== current.url) : [];
                    normalized.unshift(current);
                    localStorage.setItem(recentStorageKey, JSON.stringify(normalized.slice(0, 8)));
                } catch (_) {
                    localStorage.setItem(recentStorageKey, JSON.stringify([current]));
                }
            };

            const markNavigating = () => {
                html.classList.add('is-navigating');
            };

            // Smooth route transition hint on menu/page link clicks
            document.addEventListener('click', function(event) {
                const link = event.target.closest('a[href]');
                if (!shouldHandleLink(link, event)) return;
                markNavigating();
            }, true);

            // Lightweight prefetch on hover for internal links
            const prefetch = (href) => {
                if (!href) return;
                const link = document.createElement('link');
                link.rel = 'prefetch';
                link.href = href;
                link.as = 'document';
                document.head.appendChild(link);
            };

            document.addEventListener('mouseover', function(event) {
                const link = event.target.closest('a[href]');
                if (!shouldHandleLink(link)) return;
                clearTimeout(prefetchTimeout);
                prefetchTimeout = setTimeout(() => prefetch(link.href), 60);
            }, true);

            document.addEventListener('touchstart', function(event) {
                const link = event.target.closest('a[href]');
                if (!shouldHandleLink(link)) return;
                prefetch(link.href);
            }, {
                passive: true,
                capture: true
            });

            document.addEventListener('keydown', function(event) {
                if (!(event.ctrlKey || event.metaKey) || event.key.toLowerCase() !== 'b') return;
                if (['INPUT', 'TEXTAREA', 'SELECT'].includes(event.target.tagName) || event.target.isContentEditable) return;
                event.preventDefault();
                window.dispatchEvent(new CustomEvent('toggle-sidebar'));
            });

            // Reset state if browser restores page from cache
            window.addEventListener('pageshow', function() {
                html.classList.remove('is-navigating');
            });

            const triggerThemeAnimation = () => {
                html.classList.add('theme-animating');
                setTimeout(() => html.classList.remove('theme-animating'), 380);
            };

            window.addEventListener('theme-changed', triggerThemeAnimation);

            saveRecentPage();
        })();
    </script>
</body>


</html>



