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
                <div class="ui-brand-mark">{{ __('landing.brand_initial') }}</div>
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
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="4" y1="6" x2="20" y2="6"></line>
                    <line x1="4" y1="12" x2="20" y2="12"></line>
                    <line x1="4" y1="18" x2="20" y2="18"></line>
                </svg>
            </button>
        </div>
        <div class="ui-nav-overlay" aria-hidden="true"></div>
    </header>

    <div class="ui-container">
        <main class="space-y-8">
            {{-- Hero: pagrindinė žinutė su antrašte ir akcentuotu antro sakinio fragmentu --}}
            <section class="px-6 pt-16 pb-10 md:pt-20 md:pb-12 min-h-screen flex flex-col justify-start">
                <div class="max-w-5xl w-full mx-auto text-center space-y-4">
                    {{-- Dviejų eilučių H1 su <br> priverstiniu lūžiu ir akcentuota antra dalimi --}}
                    <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold leading-tight tracking-tight text-white hero-fade">
                        Nuo užklausos iki PDF pasiūlymo<br><span class="bg-gradient-to-r from-indigo-400 via-violet-400 to-indigo-400 bg-clip-text text-transparent">ir sąskaitos — vienoje sistemoje.</span>
                    </h1>
                    {{-- Aprašas po H1, ribotas plotis dėl ilgesnio LT teksto --}}
                    <p class="text-base sm:text-lg text-[#969da9] leading-relaxed max-w-3xl mx-auto hero-fade delay-1">
                        Projektai, darbai, medžiagos ir automatinis PVM — PDF pasiūlymas per kelias minutes.
                    </p>
                    <form class="hero-form mx-auto hero-fade delay-2" action="{{ route('register') }}" method="GET">
                        <input
                            name="email"
                            type="email"
                            inputmode="email"
                            autocomplete="email"
                            value="{{ request('email') }}"
                            placeholder="{{ __('landing.hero.email_placeholder') }}"
                            aria-label="{{ __('landing.hero.email_placeholder') }}"
                            class="hero-input"
                        />
                        <button class="hero-primary" type="submit">{{ __('landing.hero.cta') }}</button>
                    </form>
                    <a href="https://www.youtube.com/watch?v=YOUR_VIDEO_ID" target="_blank" rel="noopener"
                        class="group inline-flex items-center gap-2 px-4 py-2 rounded-full text-gray-400 hover:text-white transition-colors font-medium text-base shadow-sm hero-fade delay-2"
                        style="width: fit-content; margin: 0 auto;">
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/5 border border-white/10 group-hover:bg-white/10 transition-colors">
                            <svg width="40" height="40" viewBox="0 0 40 40" fill="none" aria-hidden="true">
                                <polygon points="27,20 15,13 15,27"
                                    fill="none"
                                    stroke="white"
                                    stroke-width="1.5"
                                    stroke-linejoin="round"
                                    class="transition group-hover:opacity-100 group-hover:drop-shadow-[0_0_8px_white]" />
                            </svg>
                        </span>
                        <span>View demo</span>
                    </a>
                    {{-- Visual divider separating hero content --}}
                    <div class="hero-divider hero-fade delay-2" aria-hidden="true"></div>

                    {{-- Paprasta meta eilutė be ženkliukų --}}
                    <div class="flex items-center justify-center gap-2 text-sm font-thin tracking-wider text-[#9ca3af] hero-fade delay-2 py-8">
                        <span>Mažiau Excel</span>
                        <span aria-hidden="true">•</span>
                        <span>Mažiau klaidų</span>
                        <span aria-hidden="true">•</span>
                        <span>Daugiau užsakymų</span>
                    </div>
                </div>
            </section>

            {{-- Funkcijų sąrašas: kortelės su ikonėlėmis ir aprašais --}}
            <section id="features" class="ui-section ui-section-offset space-y-4">
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white text-center">{{ __('landing.features.title_main') }} <span class="bg-gradient-to-r from-indigo-400 to-violet-400 bg-clip-text text-transparent">{{ __('landing.features.title_accent') }}</span></h2>
                <p class="text-lg text-gray-400 max-w-2xl mx-auto">{{ __('landing.features.lead') }}</p>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="group relative">
                        <div class="relative h-full p-8 rounded-2xl bg-white/[0.02] border border-white/[0.05] hover:bg-white/[0.04] hover:border-white/[0.1] transition-all duration-300">
                            <div class="absolute inset-0 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-500 opacity-0 group-hover:opacity-5 transition-opacity duration-300"></div>
                            <div class="inline-flex p-3 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-500 mb-5">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-6 h-6 text-white"><rect x="4" y="5" width="16" height="14" rx="2"></rect><path d="M9 9h6"></path><path d="M9 13h6"></path></svg>
                            </div>
                            <h3 class="text-xl font-semibold text-white mb-3">{{ __('landing.features.cards.projects.title') }}</h3>
                            <p class="text-gray-400 leading-relaxed">{{ __('landing.features.cards.projects.description') }}</p>
                        </div>
                    </div>
                    <div class="group relative">
                        <div class="relative h-full p-8 rounded-2xl bg-white/[0.02] border border-white/[0.05] hover:bg-white/[0.04] hover:border-white/[0.1] transition-all duration-300">
                            <div class="absolute inset-0 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-500 opacity-0 group-hover:opacity-5 transition-opacity duration-300"></div>
                            <div class="inline-flex p-3 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-500 mb-5">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-6 h-6 text-white"><path d="M6 5h12l1 4H5z"></path><path d="M6 9v10"></path><path d="M18 9v10"></path><path d="M10 13h4"></path></svg>
                            </div>
                            <h3 class="text-xl font-semibold text-white mb-3">{{ __('landing.features.cards.catalog.title') }}</h3>
                            <p class="text-gray-400 leading-relaxed">{{ __('landing.features.cards.catalog.description') }}</p>
                        </div>
                    </div>
                    <div class="group relative">
                        <div class="relative h-full p-8 rounded-2xl bg-white/[0.02] border border-white/[0.05] hover:bg-white/[0.04] hover:border-white/[0.1] transition-all duration-300">
                            <div class="absolute inset-0 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-500 opacity-0 group-hover:opacity-5 transition-opacity duration-300"></div>
                            <div class="inline-flex p-3 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-500 mb-5">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-6 h-6 text-white"><path d="M4 6h16v12H4z"></path><path d="M4 10h16"></path><path d="M8 14h4"></path></svg>
                            </div>
                            <h3 class="text-xl font-semibold text-white mb-3">{{ __('landing.features.cards.company.title') }}</h3>
                            <p class="text-gray-400 leading-relaxed">{{ __('landing.features.cards.company.description') }}</p>
                        </div>
                    </div>
                    <div class="group relative">
                        <div class="relative h-full p-8 rounded-2xl bg-white/[0.02] border border-white/[0.05] hover:bg-white/[0.04] hover:border-white/[0.1] transition-all duration-300">
                            <div class="absolute inset-0 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-500 opacity-0 group-hover:opacity-5 transition-opacity duration-300"></div>
                            <div class="inline-flex p-3 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-500 mb-4">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-6 h-6 text-white"><path d="m7 10 5 5 5-5"></path></svg>
                            </div>
                            <span class="text-xs uppercase tracking-wide text-gray-400 mb-2 block">{{ __('landing.features.cards.proposals.badge') }}</span>
                            <h3 class="text-xl font-semibold text-white mb-3">{{ __('landing.features.cards.proposals.title') }}</h3>
                            <p class="text-gray-400 leading-relaxed">{{ __('landing.features.cards.proposals.description') }}</p>
                        </div>
                    </div>
                    <div class="group relative">
                        <div class="relative h-full p-8 rounded-2xl bg-white/[0.02] border border-white/[0.05] hover:bg-white/[0.04] hover:border-white/[0.1] transition-all duration-300">
                            <div class="absolute inset-0 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-500 opacity-0 group-hover:opacity-5 transition-opacity duration-300"></div>
                            <div class="inline-flex p-3 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-500 mb-5">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-6 h-6 text-white"><path d="M6 13h12"></path><path d="M6 9h12"></path><path d="M6 17h8"></path></svg>
                            </div>
                            <h3 class="text-xl font-semibold text-white mb-3">{{ __('landing.features.cards.workflow.title') }}</h3>
                            <p class="text-gray-400 leading-relaxed">{{ __('landing.features.cards.workflow.description') }}</p>
                        </div>
                    </div>
                    <div class="group relative">
                        <div class="relative h-full p-8 rounded-2xl bg-white/[0.02] border border-white/[0.05] hover:bg-white/[0.04] hover:border-white/[0.1] transition-all duration-300">
                            <div class="absolute inset-0 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-500 opacity-0 group-hover:opacity-5 transition-opacity duration-300"></div>
                            <div class="inline-flex p-3 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-500 mb-5">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-6 h-6 text-white"><path d="M12 3v3"></path><path d="m5 8 1 2"></path><path d="M19 8l-1 2"></path><circle cx="12" cy="13" r="5"></circle></svg>
                            </div>
                            <h3 class="text-xl font-semibold text-white mb-3">{{ __('landing.features.cards.real_work.title') }}</h3>
                            <p class="text-gray-400 leading-relaxed">{{ __('landing.features.cards.real_work.description') }}</p>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Kaip veikia: trys žingsniai su numeruotomis kortelėmis --}}
            <section id="how" class="ui-section ui-section-offset space-y-4">
                <div class="ui-badge">{{ __('landing.steps.badge') }}</div>
                <h2>{{ __('landing.steps.title') }}</h2>
                <p class="ui-lead ui-muted">{{ __('landing.steps.lead') }}</p>
                <div class="grid gap-4 md:grid-cols-3">
                    <div class="ui-card p-4 space-y-3">
                        <div class="step-number">1</div>
                        <h3>{{ __('landing.steps.items.create.title') }}</h3>
                        <p class="ui-muted">{{ __('landing.steps.items.create.description') }}</p>
                    </div>
                    <div class="ui-card p-4 space-y-3">
                        <div class="step-number">2</div>
                        <h3>{{ __('landing.steps.items.add.title') }}</h3>
                        <p class="ui-muted">{{ __('landing.steps.items.add.description') }}</p>
                    </div>
                    <div class="ui-card p-4 space-y-3">
                        <div class="step-number">3</div>
                        <h3>{{ __('landing.steps.items.send.title') }}</h3>
                        <p class="ui-muted">{{ __('landing.steps.items.send.description') }}</p>
                    </div>
                </div>
            </section>

            {{-- Kodėl: argumentai ir maketuota pavyzdinė sąmata --}}
            <section id="why" class="ui-section ui-section-offset space-y-6">
                <div class="ui-badge">{{ __('landing.why.badge') }}</div>
                <div class="grid gap-6 md:grid-cols-2 items-center">
                    <div class="space-y-4">
                        <h2 class="text-left">{{ __('landing.why.title_main') }}<br><span class="accent-text">{{ __('landing.why.title_accent') }}</span></h2>
                        <p class="ui-lead ui-muted text-left">{{ __('landing.why.lead') }}</p>
                        <ul class="ui-list">
                            <li><span class="check">✓</span>{{ __('landing.why.bullets.one_tool') }}</li>
                            <li><span class="check">✓</span>{{ __('landing.why.bullets.teams') }}</li>
                            <li><span class="check">✓</span>{{ __('landing.why.bullets.eu') }}</li>
                            <li><span class="check">✓</span>{{ __('landing.why.bullets.output') }}</li>
                        </ul>
                    </div>
                    <div class="ui-card p-5 space-y-4">
                        <div class="flex items-center justify-between ui-muted text-sm">
                            <span>{{ __('landing.why.mock.project_title') }}</span>
                            <span class="ui-pill">{{ __('landing.why.mock.vat_included') }}</span>
                        </div>
                        <div class="ui-card p-4 space-y-3">
                            <div class="flex items-center justify-between ui-muted text-sm">
                                <span>{{ __('landing.why.mock.project_code') }}</span>
                                <span>{{ __('landing.why.mock.total') }}</span>
                            </div>
                            <div class="flex items-start justify-between">
                                <div class="space-y-1">
                                    <div class="text-lg font-semibold">{{ __('landing.why.mock.amount') }}</div>
                                    <div class="ui-muted text-sm">{{ __('landing.why.mock.amount_label') }}</div>
                                </div>
                                <div class="text-right space-y-2">
                                    <div class="ui-muted text-sm">{{ __('landing.why.mock.rooms_label') }}</div>
                                    <div class="font-semibold">{{ __('landing.why.mock.rooms_value') }}</div>
                                    <div class="ui-muted text-sm">{{ __('landing.why.mock.works_label') }}</div>
                                    <div class="font-semibold">{{ __('landing.why.mock.works_value') }}</div>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <a class="ui-button-primary" href="#early-access">{{ __('landing.why.mock.request_pdf') }}</a>
                                <a class="ui-button-ghost" href="#early-access">{{ __('landing.why.mock.share_link') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- CTA blokas: kvietimas prisijungti (ankstyva prieiga) --}}
            <section id="early-access" class="ui-card ui-section-offset max-w-3xl mx-auto p-6 md:p-8 text-center space-y-4">
                <div class="ui-icon mx-auto">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="m4 6 8 7 8-7"></path><path d="M4 18h16"></path></svg>
                </div>
                <h3>{{ __('landing.cta.title') }}</h3>
                <p class="ui-muted">{{ __('landing.cta.description') }}</p>
                <form class="cta-form" action="{{ route('register') }}" method="GET">
                    <input
                        name="email"
                        type="email"
                        inputmode="email"
                        autocomplete="email"
                        value="{{ request('email') }}"
                        placeholder="{{ __('landing.cta.email_placeholder') }}"
                        aria-label="{{ __('landing.cta.email_placeholder') }}"
                        class="ui-input"
                    />
                    <button class="ui-button-primary ui-button-compact" type="submit">{{ __('landing.cta.cta') }}</button>
                </form>
                <div class="ui-muted text-xs">{{ __('landing.cta.note') }}</div>
            </section>
        </main>

        {{-- Poraštė su logotipu, nuorodomis ir teisėmis --}}
        <footer class="footer ui-section-offset" id="pricing">
            <div class="flex items-center gap-2">
                <div class="ui-brand-mark ui-brand-small">{{ __('landing.brand_initial') }}</div>
                <span>{{ __('landing.footer.brand', ['app' => config('app.name', 'Sumify')]) }}</span>
            </div>
            <div class="footer-links">
                <a href="{{ route('login') }}">{{ __('landing.footer.login') }}</a>
                <span aria-hidden="true">•</span>
                <a href="mailto:hello@sumify.app">{{ __('landing.footer.contact') }}</a>
                <span aria-hidden="true">•</span>
                <a href="#privacy">{{ __('landing.footer.privacy') }}</a>
            </div>
            <div class="flex items-center gap-1 ui-muted text-sm">© {{ now()->year }} {{ config('app.name', 'Sumify') }}. {{ __('landing.footer.rights') }}</div>
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
    </script>
</body>
</html>
