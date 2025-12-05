@props([
    'title',
    'description' => null,
    'breadcrumbs' => [],
])

<div class="w-full rounded-xl border border-gray-200 dark:border-gray-800 bg-gradient-to-r from-slate-50 to-slate-100 dark:from-gray-900/90 dark:to-gray-950/90 px-4 py-4 shadow-sm">
    @if(!empty($breadcrumbs))
        <nav class="flex flex-wrap gap-2 text-xs text-gray-500 dark:text-gray-400 mb-2" aria-label="Breadcrumb">
            @foreach($breadcrumbs as $index => $crumb)
                @if(!empty($crumb['href']) && $index < count($breadcrumbs) - 1)
                    <a href="{{ $crumb['href'] }}" class="hover:text-indigo-500">{{ $crumb['label'] }}</a>
                    <span>/</span>
                @else
                    <span class="text-gray-700 dark:text-gray-300">{{ $crumb['label'] }}</span>
                @endif
            @endforeach
        </nav>
    @endif

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="space-y-1">
            <h1 class="text-3xl font-semibold text-gray-900 dark:text-white">
                {{ $title }}
            </h1>
            @if($description)
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $description }}</p>
            @endif
        </div>

        @if(trim($slot))
            <div class="flex flex-wrap items-center gap-2">
                {{ $slot }}
            </div>
        @endif
    </div>
</div>
