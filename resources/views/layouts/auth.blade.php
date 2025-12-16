<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="ui-bg">
    <div class="relative z-10 flex min-h-screen items-center justify-center px-4 py-10">
        <div class="w-full max-w-md space-y-6">
            <div class="flex items-center gap-3">
                <div class="ui-brand-mark">
                    S
                </div>
                <div>
                    <div class="text-lg font-semibold leading-tight">{{ config('app.name', 'Sumify') }}</div>
                    <div class="text-xs ui-muted">{{ __('auth.brand_tagline') }}</div>
                </div>
            </div>

            <div class="ui-card p-6 md:p-8 space-y-6">
                @yield('content')
            </div>

            <div class="ui-footer">
                © {{ now()->year }} {{ config('app.name', 'Sumify') }} · {{ __('auth.footer_note') }}
            </div>
        </div>
    </div>
</body>
</html>
