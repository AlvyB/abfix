<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('landing.meta.brand') }} — {{ __('landing.meta.title') }}</title>
    <meta name="description" content="{{ __('landing.hero.description') }}">
    <meta property="og:title" content="{{ __('landing.meta.brand') }} — {{ __('landing.meta.title') }}">
    <meta property="og:description" content="{{ __('landing.hero.description') }}">
    <meta property="og:type" content="website">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="ui-bg bg-grid">
    {{-- Navigacija: logotipas kairėje, meniu centre, CTA dešinėje --}}
    <header class="ui-nav">
        <div class="ui-nav-container">
            {{-- Logotipo sritis --}}
            <div class="brand">
                <div
                    class="grid h-10 w-10 shrink-0 place-items-center rounded-[12px] border border-[color-mix(in_srgb,var(--primary)_35%,transparent)] bg-[linear-gradient(135deg,var(--primary),var(--primary-strong))] text-[1.35rem] font-extrabold text-[var(--text)] shadow-[var(--primary-glow)]">
                    {{ __('landing.brand_initial') }}</div>
                <div class="font-semibold">{{ config('app.name', 'Sumify') }}</div>
            </div>

            {{-- Centrinės nuorodos į sekcijas --}}
            <nav class="ui-nav-links gap-6">
                <a class="ui-nav-link" href="#features">{{ __('landing.nav.features') }}</a>
                <a class="ui-nav-link" href="#how">{{ __('landing.nav.how') }}</a>
                <a class="ui-nav-link" href="#pricing">{{ __('landing.nav.pricing') }}</a>
            </nav>

            {{-- Dešinėje: prisijungimas ir registracijos CTA --}}
            <div class="ui-nav-actions">
                <a href="{{ route('login') }}" class="ui-button-ghost">{{ __('landing.nav.login') }}</a>
                <a href="{{ route('register') }}" class="ui-button-primary">{{ __('landing.nav.cta') }}</a>
            </div>

            {{-- Mobilus meniu mygtukas --}}
            <button class="ui-nav-toggle" type="button" aria-label="Toggle navigation">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <line x1="4" y1="6" x2="20" y2="6"></line>
                    <line x1="4" y1="12" x2="20" y2="12"></line>
                    <line x1="4" y1="18" x2="20" y2="18"></line>
                </svg>
            </button>
        </div>
        <div class="ui-nav-overlay" aria-hidden="true"></div>
    </header>

    <div class="relative z-[1] mx-auto max-w-[1200px] px-[22px] pt-[26px] pb-[72px]">
        <main class="space-y-8">

            {{-- Hero: pagrindinė žinutė su antrašte ir akcentuotu antro sakinio fragmentu --}}
            <section class="px-6 pt-16 pb-10 md:pt-20 md:pb-12 min-h-screen flex flex-col justify-start">
                <div class="max-w-5xl w-full mx-auto text-center space-y-4">
                    <div class="reveal hero-delay-1 inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-500/10 border border-indigo-500/20 mb-8"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-sparkles w-4 h-4 text-indigo-400">
                            <path
                                d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z">
                            </path>
                            <path d="M20 3v4"></path>
                            <path d="M22 5h-4"></path>
                            <path d="M4 17v2"></path>
                            <path d="M5 18H3"></path>
                        </svg><span class="text-sm text-indigo-300 font-medium">Profesionaliems rangovams ir paslaugų verslams</span></div>
                    {{-- Dviejų eilučių H1 su <br> priverstiniu lūžiu ir akcentuota antra dalimi --}}
                    <h1
                        class="text-4xl sm:text-5xl md:text-6xl font-extrabold leading-tight tracking-tight text-white reveal hero-delay-2">
                        Nuo užklausos iki PDF pasiūlymo<br><span
                            class="bg-gradient-to-r from-indigo-400 via-violet-400 to-indigo-400 bg-clip-text text-transparent">ir
                            sąskaitos — vienoje sistemoje.</span>
                    </h1>
                    {{-- Aprašas po H1, ribotas plotis dėl ilgesnio LT teksto --}}
                    <p class="text-base sm:text-lg text-[#969da9] leading-relaxed max-w-3xl mx-auto reveal hero-delay-3">
                        Projektai, darbai, medžiagos ir automatinis PVM — PDF pasiūlymas per kelias minutes.
                    </p>
                    <form
                        class="mx-auto grid w-full max-w-[640px] grid-cols-[minmax(0,1fr)_max-content] items-center gap-3 reveal hero-delay-4"
                        action="{{ route('register') }}" method="GET">
                        <input name="email" type="email" inputmode="email" autocomplete="email"
                            value="{{ request('email') }}" placeholder="{{ __('landing.hero.email_placeholder') }}"
                            aria-label="{{ __('landing.hero.email_placeholder') }}"
                            class="h-[54px] w-full rounded-[16px] border border-white/10 bg-white/5 px-4 text-[0.95rem] text-white transition placeholder:text-gray-500 focus:border-indigo-400/60 focus:bg-white/5 focus:outline-none focus:ring-2 focus:ring-indigo-400/20" />
                        <button
                            class="inline-flex h-[54px] items-center justify-center whitespace-nowrap rounded-[16px] border border-[color-mix(in_srgb,var(--primary)_35%,transparent)] bg-[var(--button-bg)] px-[18px] text-[0.85rem] font-normal tracking-[0.01em] text-[var(--button-text-light)] shadow-[var(--primary-glow)] transition hover:-translate-y-[1px] hover:shadow-[0_28px_80px_rgba(100,110,255,0.4)] hover:brightness-[1.02] gap-3"
                            type="submit">
                            {{ __('landing.hero.cta') }}
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                                <path d="M5 12h14"></path>
                                <path d="m12 5 7 7-7 7"></path>
                            </svg>
                        </button>
                    </form>
                    <a href="https://www.youtube.com/watch?v=YOUR_VIDEO_ID" target="_blank" rel="noopener"
                        class="group inline-flex items-center gap-2 px-4 py-2 rounded-full text-gray-400 hover:text-white transition-colors font-medium text-base shadow-sm reveal hero-delay-4"
                        style="width: fit-content; margin: 0 auto;">
                        <span
                            class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/5 border border-white/10 group-hover:bg-white/10 transition-colors">
                            <svg width="40" height="40" viewBox="0 0 40 40" fill="none" aria-hidden="true">
                                <polygon points="27,20 15,13 15,27" fill="none" stroke="white" stroke-width="1.5"
                                    stroke-linejoin="round"
                                    class="transition group-hover:opacity-100 group-hover:drop-shadow-[0_0_8px_white]" />
                            </svg>
                        </span>
                        <span>Peržiūrėti vaizdo įrašą</span>
                    </a>
                    {{-- Visual divider separating hero content --}}
                    <div class="mx-auto mt-3 h-px w-[90vw] max-w-[900px] bg-[#1a1a1f] reveal hero-delay-4"
                        aria-hidden="true"></div>

                    {{-- Paprasta meta eilutė be ženkliukų --}}
                    <div
                        class="flex items-center justify-center gap-2 text-sm font-thin tracking-wider text-[#9ca3af] reveal hero-delay-4 py-8">
                        <span>Mažiau Excel</span>
                        <span aria-hidden="true">•</span>
                        <span>Mažiau klaidų</span>
                        <span aria-hidden="true">•</span>
                        <span>Daugiau užsakymų</span>
                    </div>
                </div>
            </section>

            {{-- Funkcijų sąrašas: kortelės su ikonėlėmis ir aprašais --}}
            <section id="features" class="scroll-mt-[var(--nav-height)] mt-[96px] mb-[20px] space-y-4">
                <h2 class="reveal text-3xl sm:text-4xl lg:text-5xl font-bold text-white text-center">Viskas, ko reikia — be pertekliaus</h2>
                <p class="reveal delay-1 text-lg text-gray-400 max-w-2xl mx-auto">Paprasti įrankiai profesionalioms sąmatoms, pasiūlymams ir dokumentams.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="reveal feature-seq">
                        <x-feature-card title="Projektai ir struktūra"
                            description="Tvarkyk darbus pagal projektus, patalpas ar sekcijas. Visa informacija vienoje sistemoje."
                            gradient="from-blue-500 to-indigo-500" overlayGradient="from-blue-500 to-indigo-500">
                            <x-slot:icon>
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-white">
                                    <path d="M12 2H6a2 2 0 0 0-2 2v6"></path>
                                    <path d="M12 8h8a2 2 0 0 1 2 2v8"></path>
                                    <path d="M8 22h8a2 2 0 0 0 2-2v-8"></path>
                                    <path d="M2 12v6a2 2 0 0 0 2 2h6"></path>
                                </svg>
                            </x-slot:icon>
                        </x-feature-card>
                    </div>
                    <div class="reveal feature-seq">
                        <x-feature-card title="Darbų katalogas"
                            description="Kartojami darbai, kainos ir kiekiai. Susikurk biblioteką vieną kartą ir naudok visada."
                            gradient="from-indigo-500 to-violet-500" overlayGradient="from-indigo-500 to-violet-500">
                            <x-slot:icon>
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-white">
                                    <path d="M11 6h10"></path>
                                    <path d="M11 12h10"></path>
                                    <path d="M11 18h10"></path>
                                    <path d="m3 6 2 2 4-4"></path>
                                    <path d="m3 12 2 2 4-4"></path>
                                    <path d="m3 18 2 2 4-4"></path>
                                </svg>
                            </x-slot:icon>
                        </x-feature-card>
                    </div>
                    <div class="reveal feature-seq">
                        <x-feature-card title="Įmonės duomenys"
                            description="Logotipas, juridinė informacija, PVM ir kontaktai kiekviename dokumente. Tvarkingai ir nuosekliai."
                            gradient="from-violet-500 to-purple-500" overlayGradient="from-violet-500 to-purple-500">
                            <x-slot:icon>
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-white">
                                    <path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18"></path>
                                    <path d="M6 12h12"></path>
                                    <path d="M6 7h12"></path>
                                    <path d="M6 17h12"></path>
                                </svg>
                            </x-slot:icon>
                        </x-feature-card>
                    </div>
                    <div class="reveal feature-seq">
                        <x-feature-card title="Profesionalūs pasiūlymai"
                            description="Švarūs, klientui paruošti PDF pasiūlymai, kuriais galima pasitikėti."
                            gradient="from-purple-500 to-pink-500" overlayGradient="from-purple-500 to-pink-500">
                            <x-slot:icon>
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-white">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <path d="M14 2v6h6"></path>
                                    <path d="M16 13H8"></path>
                                    <path d="M16 17H8"></path>
                                    <path d="M10 9H8"></path>
                                </svg>
                            </x-slot:icon>
                        </x-feature-card>
                    </div>
                    <div class="reveal feature-seq">
                        <x-feature-card title="Greitas darbo srautas"
                            description="Nuo idėjos iki sąmatos per kelias minutes. Be nereikalingų žingsnių."
                            gradient="from-amber-500 to-orange-500" overlayGradient="from-amber-500 to-orange-500">
                            <x-slot:icon>
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-white">
                                    <path d="M13 2L3 14h8l-1 8 10-12h-8l1-8z"></path>
                                </svg>
                            </x-slot:icon>
                        </x-feature-card>
                    </div>
                    <div class="reveal feature-seq">
                        <x-feature-card title="Išmanus dokumentų apdorojimas"
                            description="Automatinis čekių nuskaitymas, duomenų atpažinimas ir dokumentų tvarkymas be rankinio darbo."
                            badge="Netrukus"
                            gradient="from-emerald-500 to-teal-500" overlayGradient="from-emerald-500 to-teal-500">
                            <x-slot:icon>
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-white">
                                    <path d="M7 3h10a2 2 0 0 1 2 2v4"></path>
                                    <path d="M5 7V5a2 2 0 0 1 2-2"></path>
                                    <path d="M19 15v4a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-4"></path>
                                    <path d="M5 15H3"></path>
                                    <path d="M21 15h-2"></path>
                                    <path d="M7 15h10"></path>
                                    <path d="M7 11h4"></path>
                                </svg>
                            </x-slot:icon>
                        </x-feature-card>
                    </div>
                </div>
            </section>

            {{-- Kaip veikia: trys žingsniai su numeruotomis kortelėmis --}}
            <section id="how" class="scroll-mt-[var(--nav-height)] mt-[42px] mb-[20px] space-y-4">

                <div class="flex justify-center mb-4 mt-48 reveal">
                    <span
                        class="inline-block px-4 py-1.5 text-sm font-medium text-indigo-400 bg-indigo-500/10 rounded-full">
                        How it works
                    </span>
                </div>
                <h2 class="mb-2 text-center text-[1.75rem] reveal delay-1">{{ __('landing.steps.title') }}</h2>
                <p class="mx-auto max-w-[640px] text-center text-[0.95rem] text-[var(--muted)] reveal delay-2">{{
                    __('landing.steps.lead') }}</p>
                <div class="grid gap-4 md:grid-cols-3">
                    <div
                        class="rounded-[var(--radius-xl)] border border-[var(--border)] bg-[linear-gradient(160deg,rgba(255,255,255,0.02),rgba(0,0,0,0.15)),var(--panel)] p-4 shadow-[var(--card-shadow)] backdrop-blur-[12px] space-y-3 reveal delay-1">
                        <div
                            class="grid h-[36px] w-[36px] place-items-center rounded-[12px] border border-[var(--border)] bg-[var(--panel)] font-bold text-[var(--primary)]">
                            1</div>
                        <h3>{{ __('landing.steps.items.create.title') }}</h3>
                        <p class="text-[var(--muted)]">{{ __('landing.steps.items.create.description') }}</p>
                    </div>
                    <div
                        class="rounded-[var(--radius-xl)] border border-[var(--border)] bg-[linear-gradient(160deg,rgba(255,255,255,0.02),rgba(0,0,0,0.15)),var(--panel)] p-4 shadow-[var(--card-shadow)] backdrop-blur-[12px] space-y-3 reveal delay-2">
                        <div
                            class="grid h-[36px] w-[36px] place-items-center rounded-[12px] border border-[var(--border)] bg-[var(--panel)] font-bold text-[var(--primary)]">
                            2</div>
                        <h3>{{ __('landing.steps.items.add.title') }}</h3>
                        <p class="text-[var(--muted)]">{{ __('landing.steps.items.add.description') }}</p>
                    </div>
                    <div
                        class="rounded-[var(--radius-xl)] border border-[var(--border)] bg-[linear-gradient(160deg,rgba(255,255,255,0.02),rgba(0,0,0,0.15)),var(--panel)] p-4 shadow-[var(--card-shadow)] backdrop-blur-[12px] space-y-3 reveal delay-3">
                        <div
                            class="grid h-[36px] w-[36px] place-items-center rounded-[12px] border border-[var(--border)] bg-[var(--panel)] font-bold text-[var(--primary)]">
                            3</div>
                        <h3>{{ __('landing.steps.items.send.title') }}</h3>
                        <p class="text-[var(--muted)]">{{ __('landing.steps.items.send.description') }}</p>
                    </div>
                </div>
            </section>

            {{-- Kodėl: argumentai ir maketuota pavyzdinė sąmata --}}
            <section id="why" class="scroll-mt-[var(--nav-height)] mt-[42px] mb-[20px] space-y-6">
                <div
                    class="inline-flex items-center gap-2 rounded-full border border-[var(--border)] bg-[var(--panel)] px-[0.8rem] py-[0.4rem] text-[0.78rem] tracking-[0.02em] text-[var(--muted)] reveal">
                    {{ __('landing.why.badge') }}</div>
                <div class="grid gap-6 md:grid-cols-2 items-center">
                    <div class="space-y-4">
                        <h2 class="text-left text-[1.75rem] reveal delay-1">{{ __('landing.why.title_main') }}<br><span
                                class="bg-[linear-gradient(135deg,var(--primary),var(--primary-strong))] bg-clip-text text-transparent">{{
                                __('landing.why.title_accent') }}</span></h2>
                        <p class="max-w-[640px] text-left text-[0.95rem] text-[var(--muted)] reveal delay-2">{{ __('landing.why.lead')
                            }}</p>
                        <ul class="grid gap-[0.6rem] list-none p-0 m-0 text-[var(--muted)] reveal delay-2">
                            <li><span
                                    class="mr-2 inline-flex h-[22px] w-[22px] items-center justify-center rounded-[8px] border border-[var(--border)] bg-[var(--panel)] font-bold text-[var(--primary)]">✓</span>{{
                                __('landing.why.bullets.one_tool') }}</li>
                            <li><span
                                    class="mr-2 inline-flex h-[22px] w-[22px] items-center justify-center rounded-[8px] border border-[var(--border)] bg-[var(--panel)] font-bold text-[var(--primary)]">✓</span>{{
                                __('landing.why.bullets.teams') }}</li>
                            <li><span
                                    class="mr-2 inline-flex h-[22px] w-[22px] items-center justify-center rounded-[8px] border border-[var(--border)] bg-[var(--panel)] font-bold text-[var(--primary)]">✓</span>{{
                                __('landing.why.bullets.eu') }}</li>
                            <li><span
                                    class="mr-2 inline-flex h-[22px] w-[22px] items-center justify-center rounded-[8px] border border-[var(--border)] bg-[var(--panel)] font-bold text-[var(--primary)]">✓</span>{{
                                __('landing.why.bullets.output') }}</li>
                        </ul>
                    </div>
                    <div
                        class="rounded-[var(--radius-xl)] border border-[var(--border)] bg-[linear-gradient(160deg,rgba(255,255,255,0.02),rgba(0,0,0,0.15)),var(--panel)] p-5 shadow-[var(--card-shadow)] backdrop-blur-[12px] space-y-4 reveal delay-3">
                        <div class="flex items-center justify-between text-sm text-[var(--muted)]">
                            <span>{{ __('landing.why.mock.project_title') }}</span>
                            <span
                                class="inline-flex items-center gap-[0.4rem] rounded-full border border-[var(--border)] bg-white/5 px-[0.7rem] py-[0.35rem] text-[0.75rem] text-[var(--muted)]">{{
                                __('landing.why.mock.vat_included') }}</span>
                        </div>
                        <div
                            class="rounded-[var(--radius-xl)] border border-[var(--border)] bg-[linear-gradient(160deg,rgba(255,255,255,0.02),rgba(0,0,0,0.15)),var(--panel)] p-4 shadow-[var(--card-shadow)] backdrop-blur-[12px] space-y-3">
                            <div class="flex items-center justify-between text-sm text-[var(--muted)]">
                                <span>{{ __('landing.why.mock.project_code') }}</span>
                                <span>{{ __('landing.why.mock.total') }}</span>
                            </div>
                            <div class="flex items-start justify-between">
                                <div class="space-y-1">
                                    <div class="text-lg font-semibold">{{ __('landing.why.mock.amount') }}</div>
                                    <div class="text-sm text-[var(--muted)]">{{ __('landing.why.mock.amount_label') }}
                                    </div>
                                </div>
                                <div class="text-right space-y-2">
                                    <div class="text-sm text-[var(--muted)]">{{ __('landing.why.mock.rooms_label') }}
                                    </div>
                                    <div class="font-semibold">{{ __('landing.why.mock.rooms_value') }}</div>
                                    <div class="text-sm text-[var(--muted)]">{{ __('landing.why.mock.works_label') }}
                                    </div>
                                    <div class="font-semibold">{{ __('landing.why.mock.works_value') }}</div>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <a class="ui-button-primary !w-auto" href="#early-access">{{
                                    __('landing.why.mock.request_pdf') }}</a>
                                <a class="ui-button-ghost !w-auto" href="#early-access">{{
                                    __('landing.why.mock.share_link') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- CTA blokas: kvietimas prisijungti (ankstyva prieiga) --}}
            <section id="early-access"
                class="scroll-mt-[var(--nav-height)] mx-auto max-w-3xl rounded-[var(--radius-xl)] border border-[var(--border)] bg-[linear-gradient(160deg,rgba(255,255,255,0.02),rgba(0,0,0,0.15)),var(--panel)] p-6 text-center shadow-[var(--card-shadow)] backdrop-blur-[12px] space-y-4 md:p-8">
                <div class="mx-auto grid h-[34px] w-[34px] place-items-center rounded-[12px] bg-white/5 reveal">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8">
                        <path d="m4 6 8 7 8-7"></path>
                        <path d="M4 18h16"></path>
                    </svg>
                </div>
                <h3 class="reveal delay-1">{{ __('landing.cta.title') }}</h3>
                <p class="text-[var(--muted)] reveal delay-2">{{ __('landing.cta.description') }}</p>
                <form class="flex flex-nowrap items-center justify-center gap-3 reveal delay-3" action="{{ route('register') }}"
                    method="GET">
                    <input name="email" type="email" inputmode="email" autocomplete="email"
                        value="{{ request('email') }}" placeholder="{{ __('landing.cta.email_placeholder') }}"
                        aria-label="{{ __('landing.cta.email_placeholder') }}"
                        class="ui-input max-w-[340px] flex-1 min-w-[220px]" />
                    <button class="ui-button-primary !w-auto !px-[0.9rem] !py-[0.55rem] !text-[0.85rem] !font-bold"
                        type="submit">{{ __('landing.cta.cta') }}</button>
                </form>
                <div class="text-xs text-[var(--muted)] reveal delay-3">{{ __('landing.cta.note') }}</div>
            </section>
        </main>

        {{-- Poraštė su logotipu, nuorodomis ir teisėmis --}}
        <footer
            class="scroll-mt-[var(--nav-height)] flex flex-wrap items-center justify-between gap-3 text-[0.9rem] text-[var(--muted)] reveal"
            id="pricing">
            <div class="flex items-center gap-2">
                <div
                    class="grid h-[34px] w-[34px] shrink-0 place-items-center rounded-[12px] border border-[color-mix(in_srgb,var(--primary)_35%,transparent)] bg-[linear-gradient(135deg,var(--primary),var(--primary-strong))] text-[0.9rem] font-extrabold text-[var(--text)] shadow-[var(--primary-glow)]">
                    {{ __('landing.brand_initial') }}</div>
                <span>{{ __('landing.footer.brand', ['app' => config('app.name', 'Sumify')]) }}</span>
            </div>
            <div class="inline-flex flex-wrap items-center gap-[10px]">
                <a class="text-[var(--text)] transition-colors hover:text-[var(--primary)]"
                    href="{{ route('login') }}">{{ __('landing.footer.login') }}</a>
                <span aria-hidden="true">•</span>
                <a class="text-[var(--text)] transition-colors hover:text-[var(--primary)]"
                    href="mailto:hello@sumify.app">{{ __('landing.footer.contact') }}</a>
                <span aria-hidden="true">•</span>
                <a class="text-[var(--text)] transition-colors hover:text-[var(--primary)]" href="#privacy">{{
                    __('landing.footer.privacy') }}</a>
            </div>
            <div class="flex items-center gap-1 text-sm text-[var(--muted)]">© {{ now()->year }} {{ config('app.name',
                'Sumify') }}. {{ __('landing.footer.rights') }}</div>
        </footer>
    </div>

    <script>
        const nav = document.querySelector('.ui-nav');
        const toggleBtn = document.querySelector('.ui-nav-toggle');
        const overlay = document.querySelector('.ui-nav-overlay');
        const navLinks = nav ? nav.querySelectorAll('a') : [];

        const setNavState = () => {
            if (!nav) return;
            if (window.scrollY > 10) nav.classList.add('scrolled');
            else nav.classList.remove('scrolled');
        };

        const syncNavHeight = () => {
            if (!nav) return;
            const h = nav.offsetHeight || 0;
            document.documentElement.style.setProperty('--nav-height', `${h}px`);
        };

        const closeNav = () => {
            if (!nav) return;
            nav.classList.remove('open');
            syncNavHeight();
        };

        const toggleNav = () => {
            if (!nav) return;
            nav.classList.toggle('open');
            syncNavHeight();
        };

        // Close on overlay click
        if (overlay) overlay.addEventListener('click', closeNav);

        // Close and smooth-scroll when clicking a nav link with offset for fixed nav
        navLinks.forEach((a) => {
            a.addEventListener('click', (e) => {
                const href = a.getAttribute('href');
                if (href && href.startsWith('#')) {
                    e.preventDefault();
                    closeNav();
                    setTimeout(() => {
                        const target = document.querySelector(href);
                        if (target) {
                            const navHeight = nav?.offsetHeight || parseFloat(getComputedStyle(document.documentElement).getPropertyValue('--nav-height')) || 0;
                            const y = target.getBoundingClientRect().top + window.scrollY - navHeight - 8;
                            window.scrollTo({ top: y, behavior: 'smooth' });
                            history.replaceState(null, '', href);
                        }
                    }, 10);
                } else {
                    closeNav();
                }
            });
        });

        // Close on ESC
        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeNav();
        });

        // Toggle on button
        if (toggleBtn) toggleBtn.addEventListener('click', toggleNav);

        // Sticky blur on scroll
        syncNavHeight();
        setNavState();
        window.addEventListener('scroll', setNavState, { passive: true });

        // Close menu when resizing to desktop
        window.addEventListener('resize', () => {
            syncNavHeight();
            if (window.innerWidth > 820) closeNav();
        });

        const revealEls = document.querySelectorAll('.reveal');
        if ('IntersectionObserver' in window) {
            const revealObserver = new IntersectionObserver(
                (entries, observer) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('is-visible');
                            observer.unobserve(entry.target);
                        }
                    });
                },
                { threshold: 0.15, rootMargin: '0px 0px -10% 0px' }
            );
            revealEls.forEach((el) => revealObserver.observe(el));
        } else {
            revealEls.forEach((el) => el.classList.add('is-visible'));
        }

        // --- Features: strict 1→2→3→4→5→6 sequence ---
        const featuresSection = document.querySelector('#features');
        const featureSeqEls = featuresSection ? featuresSection.querySelectorAll('.feature-seq') : [];

        if (featuresSection && featureSeqEls.length) {
            const runFeatureSequence = () => {
                featureSeqEls.forEach((el, i) => {
                    setTimeout(() => el.classList.add('is-visible'), i * 160);
                });
            };

            if ('IntersectionObserver' in window) {
                const featuresObserver = new IntersectionObserver(
                    (entries, obs) => {
                        entries.forEach((entry) => {
                            if (entry.isIntersecting) {
                                runFeatureSequence();
                                obs.unobserve(entry.target);
                            }
                        });
                    },
                    { threshold: 0.15, rootMargin: '0px 0px -10% 0px' }
                );

                featuresObserver.observe(featuresSection);
            } else {
                runFeatureSequence();
            }
        }
    </script>
</body>

</html>
