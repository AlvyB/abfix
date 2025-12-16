<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('landing.meta.brand') }} — {{ __('landing.meta.title') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="ui-bg bg-grid">
    <header class="ui-nav">
        <div class="ui-nav-container">
            <div class="brand">
                <div class="ui-brand-mark">{{ __('landing.brand_initial') }}</div>
                <div class="font-semibold">{{ config('app.name', 'Sumify') }}</div>
            </div>

            <nav class="ui-nav-links">
                <a href="#features">{{ __('landing.nav.features') }}</a>
                <a href="#how">{{ __('landing.nav.how') }}</a>
                <a href="#pricing">{{ __('landing.nav.pricing') }}</a>
            </nav>

            <div class="ui-nav-actions">
                <a href="{{ route('login') }}" class="ui-button-ghost ui-button-compact">{{ __('landing.nav.login') }}</a>
                <a href="{{ route('register') }}" class="ui-button-primary ui-button-compact">{{ __('landing.nav.cta') }}</a>
            </div>

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
        <main class="space-y-10">
            <section class="ui-hero ui-section-offset">

                <h1>{{ __('landing.hero.title_primary') }} <br><span class="accent-text">{{ __('landing.hero.title_accent') }}</span></h1>
                <p class="ui-muted max-w-3xl">{{ __('landing.hero.description') }}</p>
                <form class="hero-form" action="{{ route('register') }}" method="GET">
                    <input class="ui-input" type="email" name="email" placeholder="{{ __('landing.hero.email_placeholder') }}" aria-label="{{ __('landing.hero.email_placeholder') }}">
                    <button class="ui-button-primary ui-button-compact" type="submit">{{ __('landing.hero.cta') }}</button>
                    <a class="ui-button-ghost w-auto" href="#how">
                        <span class="demo-play ui-icon">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                        </span>
                        {{ __('landing.hero.demo') }}
                    </a>
                </form>
                <div class="ui-trust-line">
                    {{ __('landing.hero.trust_built') }}
                    <span aria-hidden="true">•</span>
                    {{ __('landing.hero.trust_eu') }}
                    <span aria-hidden="true">•</span>
                    {{ __('landing.hero.trust_simple') }}
                </div>
            </section>

            <section id="features" class="ui-section ui-section-offset space-y-4">
                <h2>{{ __('landing.features.title_main') }} <span class="accent-text">{{ __('landing.features.title_accent') }}</span></h2>
                <p class="ui-lead ui-muted">{{ __('landing.features.lead') }}</p>
                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                    <div class="ui-card p-4 space-y-3">
                        <div class="ui-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="4" y="5" width="16" height="14" rx="2"></rect><path d="M9 9h6"></path><path d="M9 13h6"></path></svg>
                        </div>
                        <h3>{{ __('landing.features.cards.projects.title') }}</h3>
                        <p class="ui-muted">{{ __('landing.features.cards.projects.description') }}</p>
                    </div>
                    <div class="ui-card p-4 space-y-3">
                        <div class="ui-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M6 5h12l1 4H5z"></path><path d="M6 9v10"></path><path d="M18 9v10"></path><path d="M10 13h4"></path></svg>
                        </div>
                        <h3>{{ __('landing.features.cards.catalog.title') }}</h3>
                        <p class="ui-muted">{{ __('landing.features.cards.catalog.description') }}</p>
                    </div>
                    <div class="ui-card p-4 space-y-3">
                        <div class="ui-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M4 6h16v12H4z"></path><path d="M4 10h16"></path><path d="M8 14h4"></path></svg>
                        </div>
                        <h3>{{ __('landing.features.cards.company.title') }}</h3>
                        <p class="ui-muted">{{ __('landing.features.cards.company.description') }}</p>
                    </div>
                    <div class="ui-card p-4 space-y-3">
                        <div class="flex items-center gap-2">
                            <div class="ui-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="m7 10 5 5 5-5"></path></svg>
                            </div>
                            <span class="ui-pill">{{ __('landing.features.cards.proposals.badge') }}</span>
                        </div>
                        <h3>{{ __('landing.features.cards.proposals.title') }}</h3>
                        <p class="ui-muted">{{ __('landing.features.cards.proposals.description') }}</p>
                    </div>
                    <div class="ui-card p-4 space-y-3">
                        <div class="ui-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M6 13h12"></path><path d="M6 9h12"></path><path d="M6 17h8"></path></svg>
                        </div>
                        <h3>{{ __('landing.features.cards.workflow.title') }}</h3>
                        <p class="ui-muted">{{ __('landing.features.cards.workflow.description') }}</p>
                    </div>
                    <div class="ui-card p-4 space-y-3">
                        <div class="ui-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 3v3"></path><path d="m5 8 1 2"></path><path d="M19 8l-1 2"></path><circle cx="12" cy="13" r="5"></circle></svg>
                        </div>
                        <h3>{{ __('landing.features.cards.real_work.title') }}</h3>
                        <p class="ui-muted">{{ __('landing.features.cards.real_work.description') }}</p>
                    </div>
                </div>
            </section>

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

            <section id="early-access" class="ui-card ui-section-offset max-w-3xl mx-auto p-6 md:p-8 text-center space-y-4">
                <div class="ui-icon mx-auto">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="m4 6 8 7 8-7"></path><path d="M4 18h16"></path></svg>
                </div>
                <h3>{{ __('landing.cta.title') }}</h3>
                <p class="ui-muted">{{ __('landing.cta.description') }}</p>
                <form class="cta-form" action="{{ route('register') }}" method="GET">
                    <input class="ui-input" type="email" name="email" placeholder="{{ __('landing.cta.email_placeholder') }}" aria-label="{{ __('landing.cta.email_placeholder') }}">
                    <button class="ui-button-primary ui-button-compact" type="submit">{{ __('landing.cta.cta') }}</button>
                </form>
                <div class="ui-muted text-xs">{{ __('landing.cta.note') }}</div>
            </section>
        </main>

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
