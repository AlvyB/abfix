@extends('layouts.auth')

@section('title', __('auth.login_title'))

@section('content')
    <div class="space-y-2">
        <h1 class="text-2xl font-semibold">{{ __('auth.login_title') }}</h1>
        <p class="text-sm ui-muted">{{ __('auth.login_subtitle') }}</p>
    </div>

    @if (session('status'))
        <div class="ui-card p-3 text-sm">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="ui-card p-3 text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login.store') }}" class="space-y-4">
        @csrf

        <div class="space-y-1.5">
            <label for="email" class="text-sm">{{ __('auth.email') }}</label>
            <input
                id="email"
                name="email"
                type="email"
                value="{{ old('email') }}"
                required
                autocomplete="email"
                autofocus
                placeholder="{{ __('auth.email') }}"
                class="ui-input"
            />
        </div>

        <div class="space-y-1.5">
            <div class="flex items-center justify-between text-sm">
                <label for="password">{{ __('auth.password') }}</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">
                        {{ __('auth.forgot_password') }}
                    </a>
                @endif
            </div>
            <input
                id="password"
                name="password"
                type="password"
                required
                autocomplete="current-password"
                placeholder="{{ __('auth.password') }}"
                class="ui-input"
            />
        </div>

        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="remember" class="h-4 w-4 rounded border bg-transparent" {{ old('remember') ? 'checked' : '' }}>
            <span>{{ __('auth.remember') }}</span>
        </label>

        <button type="submit" class="ui-button-primary">
            {{ __('auth.login_button') }}
        </button>
    </form>

    <div class="ui-separator flex justify-center">
        <span>{{ __('auth.or') }}</span>
    </div>

    <a href="{{ route('auth.google.redirect') }}" class="ui-button-ghost">
        <svg class="h-5 w-5" viewBox="0 0 24 24" aria-hidden="true">
            <path fill="#EA4335" d="M12 10.2v3.8h5.3c-.2 1.2-.9 2.2-1.8 2.9l2.9 2.3c1.7-1.6 2.6-4 2.6-6.9 0-.7-.1-1.4-.2-2.1z"/>
            <path fill="#34A853" d="M6.6 14.3l-.9.7-2.3 1.8C4.9 19.6 8.2 21 12 21c2.6 0 4.9-.9 6.5-2.4l-2.9-2.3c-.8.6-1.8 1-3 1-2.3 0-4.3-1.6-5-3.7z"/>
            <path fill="#4A90E2" d="M3.4 7.9C2.5 9.2 2 10.8 2 12.5c0 1.7.5 3.3 1.4 4.6l3.2-2.8c-.2-.6-.4-1.3-.4-2 0-.7.1-1.4.4-2z"/>
            <path fill="#FBBC05" d="M12 6.5c1.4 0 2.6.5 3.5 1.4l2.6-2.6C16.9 3.8 14.6 3 12 3 8.2 3 4.9 4.4 3 7.1l3.2 2.8c.7-2.1 2.7-3.4 5.8-3.4z"/>
        </svg>
        <span>{{ __('auth.login_with_google') }}</span>
    </a>

    @if (Route::has('register'))
        <div class="text-center text-sm ui-muted">
            <span>{{ __('auth.no_account') }}</span>
            <a href="{{ route('register') }}">{{ __('auth.register') }}</a>
        </div>
    @endif
@endsection
