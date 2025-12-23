@props([
'title',
'description',
'badge' => null,
'gradient' => 'from-blue-500 to-indigo-500',
'overlayGradient' => 'from-blue-500 to-indigo-500',
])

@php
$iconMargin = $badge ? 'mb-4' : 'mb-5';
@endphp

<div {{ $attributes->merge(['class' => 'group relative h-full']) }}>
    <div
        class="relative h-full p-8 rounded-xl bg-white/[0.01] border border-white/[0.04] hover:bg-white/[0.02] hover:border-white/[0.08] transition-all duration-300">
        <div
            class="absolute inset-0 rounded-xl bg-gradient-to-br {{ $overlayGradient }} opacity-0 group-hover:opacity-5 transition-opacity duration-300">
        </div>
        <div class="inline-flex p-3 rounded-lg bg-gradient-to-br {{ $gradient }} {{ $iconMargin }}">
            {{ $icon }}
        </div>
        @if ($badge === 'Netrukus')
            <div class="absolute -top-1 -right-1 w-32 h-32 overflow-hidden pointer-events-none">
                <div class="absolute top-5 -right-12 rotate-45">
                    <div class="bg-orange-500 text-white
                        text-xs font-semibold uppercase tracking-wider
                        px-12 py-1.5 shadow-md
                        animate-ribbon">
                        Netrukus
                    </div>
                </div>
            </div>
        @elseif ($badge)

        <span class="text-medium uppercase tracking-wide text-gray-400 mb-2 block">{{ $badge }}</span>
        @endif
        <h3 class="text-xl font-semibold text-white mb-3">{{ $title }}</h3>
        <p class="text-gray-400 leading-relaxed">{{ $description }}</p>
    </div>
</div>
