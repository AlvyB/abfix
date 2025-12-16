<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<script>
(() => {
    const storageKey = 'color-theme';
    const root = document.documentElement;
    const media = window.matchMedia('(prefers-color-scheme: dark)');

    const resolve = () => {
        const stored = localStorage.getItem(storageKey);
        if (stored === 'dark') return true;
        if (stored === 'light') return false;
        return media.matches;
    };

    const apply = (isDark) => {
        root.classList[isDark ? 'add' : 'remove']('dark');
    };

    const setTheme = (theme) => {
        if (theme === 'dark') {
            localStorage.setItem(storageKey, 'dark');
            apply(true);
        } else if (theme === 'light') {
            localStorage.setItem(storageKey, 'light');
            apply(false);
        } else {
            localStorage.removeItem(storageKey);
            apply(media.matches);
        }
    };

    // Apply immediately on load
    apply(resolve());

    document.addEventListener('DOMContentLoaded', () => apply(resolve()));

    // For back/forward cache restores
    window.addEventListener('pageshow', (event) => {
        if (event.persisted) {
            apply(resolve());
        }
    });

    // React to OS changes when using system
    media.addEventListener('change', () => {
        if (!localStorage.getItem(storageKey)) {
            apply(media.matches);
        }
    });

    // Listen globally for Livewire events
    document.addEventListener('livewire:init', () => {
        Livewire.on('theme-changed', (payload) => {
            const theme = payload?.theme ?? payload;
            setTheme(theme);
        });

        document.addEventListener('livewire:navigate', () => apply(resolve()));
        document.addEventListener('livewire:navigated', () => apply(resolve()));
    });

    // Reapply after Livewire SPA navigation
    window.addEventListener('livewire:navigate', () => {
        apply(resolve());
    });

    // Expose setter for buttons if needed
    window.abfixSetTheme = setTheme;
})();
</script>

<title>{{ $title ?? config('app.name') }}</title>

<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

@vite(['resources/css/app.css', 'resources/js/app.js'])

<style>
:root:not(.dark) body { background: #f8fafc; color: #0f172a; }
:root:not(.dark) .bg-gray-900 { background-color: #f1f5f9 !important; color: #0f172a !important; }
:root:not(.dark) .bg-gray-900\/90 { background-color: rgba(241, 245, 249, 0.92) !important; color: #0f172a !important; }
:root:not(.dark) .bg-gray-800 { background-color: #ffffff !important; color: #0f172a !important; }
:root:not(.dark) .border-gray-700 { border-color: #e2e8f0 !important; }
:root:not(.dark) .text-gray-100 { color: #0f172a !important; }
:root:not(.dark) .text-gray-200 { color: #1f2937 !important; }
:root:not(.dark) .text-gray-300 { color: #334155 !important; }
:root:not(.dark) .text-gray-400 { color: #4b5563 !important; }
:root:not(.dark) .text-gray-500 { color: #6b7280 !important; }
:root:not(.dark) table thead tr { background-color: #f8fafc; }
:root.dark .text-gray-50 { color: #e5e7eb !important; }
:root.dark .text-gray-100 { color: #f3f4f6 !important; }
</style>
