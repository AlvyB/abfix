<?php

use Livewire\Volt\Component;
use App\Models\Project;
use App\Models\RoomItem;

new class extends Component {

    public Project $project;
    public array $comments = [];

    public function downloadPdf(): void
    {
        $this->redirect(route('app.projects.pdf', $this->project));
    }

    public function updateStatus(string $status): void
    {
        $allowed = ['draft', 'sent', 'approved', 'finished'];
        if (! in_array($status, $allowed, true)) {
            return;
        }

        $this->project->update(['status' => $status]);
    }

    public function mount(Project $project): void
    {
        abort_unless($project->user_id === auth()->id(), 403);
        $this->project = $project;

        // Užpildome komentarus esamomis reikšmėmis
        foreach ($this->rooms as $room) {
            foreach ($room->items as $item) {
                $this->comments[$item->id] = $item->comment ?? '';
            }
        }
    }

    public function getRoomsProperty()
    {
        return $this->project
            ->rooms()
            ->with('items')
            ->orderBy('sort_order')
            ->get();
    }

    public function toggleItem(int $itemId): void
    {
        $item = RoomItem::whereHas('room', function ($q) {
            $q->where('project_id', $this->project->id);
        })->find($itemId);

        if (! $item) {
            return;
        }

        $item->update(['is_completed' => ! $item->is_completed]);
    }

    public function saveComment(int $itemId): void
    {
        $item = RoomItem::whereHas('room', function ($q) {
            $q->where('project_id', $this->project->id);
        })->find($itemId);

        if (! $item) {
            return;
        }

        $text = trim($this->comments[$itemId] ?? '');
        $item->update(['comment' => $text !== '' ? $text : null]);
    }
};

?>

@php
    $breadcrumbs = [
        ['label' => __('app.projects.list'), 'href' => route('app.projects')],
        ['label' => $project->title],
        ['label' => __('app.projects.tasks')],
    ];
@endphp

<div class="max-w-8xl mx-auto py-8 space-y-8 text-gray-900 dark:text-gray-100">
    <x-page.header
        :title="$project->title"
        description=""
        :breadcrumbs="$breadcrumbs"
    >
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('app.projects') }}"
               class="text-sm text-gray-200 bg-gray-800 border border-gray-700 hover:border-indigo-500 rounded-lg px-3 py-2 inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
                {{ __('app.projects.back_to_list') }}
            </a>
            <a href="{{ route('app.projects.edit', $project) }}"
               class="text-sm text-gray-200 bg-gray-800 border border-gray-700 hover:border-indigo-500 rounded-lg px-3 py-2 inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75v-10.5A2.25 2.25 0 015.25 6H10" />
                </svg>
                {{ __('app.projects.back_to_estimate') }}
            </a>

            @php
                $statusLabels = [
                    'draft' => 'Juodraštis',
                    'sent' => 'Pateikta',
                    'approved' => 'Patvirtinta',
                    'finished' => 'Užbaigta',
                ];
                $statusColors = [
                    'draft' => 'text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-700',
                    'sent' => 'text-amber-700 dark:text-amber-300 border-amber-300 dark:border-amber-700',
                    'approved' => 'text-emerald-700 dark:text-emerald-300 border-emerald-300 dark:border-emerald-700',
                    'finished' => 'text-indigo-700 dark:text-indigo-300 border-indigo-300 dark:border-indigo-700',
                ];
                $currentColor = $statusColors[$project->status] ?? 'text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-700';
            @endphp
                <div class="relative inline-block text-left" x-data="{ open: false }">
                    <button type="button"
                            class="text-sm bg-gray-200 dark:bg-gray-800 hover:border-indigo-500 rounded-lg px-3 py-2 inline-flex items-center gap-2 border {{ $currentColor }}"
                            @click="open = !open">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5 12 6l-4.5 4.5m0 3L12 18l4.5-4.5" />
                        </svg>
                        {{ __('app.projects.status') }}: {{ $statusLabels[$project->status] ?? ucfirst($project->status) }} ▾
                    </button>
                <div x-show="open"
                     @click.outside="open = false"
                     @keydown.escape.window="open = false"
                     x-transition
                     class="absolute right-0 mt-1 w-48 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-lg z-20">
                    <div class="py-1 text-sm text-gray-800 dark:text-gray-100">
                        @foreach($statusLabels as $key => $label)
                            <button type="button"
                                    wire:click="updateStatus('{{ $key }}')"
                                    @click="open = false"
                                    class="w-full text-left px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-800 {{ $project->status === $key ? 'font-semibold' : '' }}">
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <button type="button"
                    class="text-sm text-gray-200 bg-gray-800 border border-gray-700 hover:border-indigo-500 rounded-lg px-3 py-2 inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 6.75h15m-15 4.5h15m-15 4.5h15M8.25 6.75v13.5M12 6.75v13.5m3.75-13.5v13.5" />
                </svg>
                {{ __('app.projects.export_excel') }}
            </button>

            <div x-data="{ openPdf: false, pdfType: 'full' }" class="relative">
                <button type="button"
                        @click.stop="openPdf = true"
                        class="text-sm text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg px-3 py-2 inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.5l8.25 4.5 1.5-3.75L3 13.5zM3 13.5l8.25-7.5L21 9l-8.25 9m0 0V9" />
                    </svg>
                    {{ __('app.projects.export_pdf') }}
                </button>

                <div x-show="openPdf"
                     x-cloak
                     class="fixed inset-0 z-40 flex items-center justify-center bg-black/40 backdrop-blur">
                    <div class="bg-gray-900 border border-gray-700 rounded-xl p-5 w-full max-w-md text-gray-100 shadow-lg">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-lg font-semibold">Pasirinkite PDF tipą</h3>
                            <button class="text-gray-400 hover:text-gray-200" @click="openPdf = false">✕</button>
                        </div>
                        <div class="space-y-3 text-sm">
                            <label class="flex items-start gap-2">
                                <input type="radio" value="client" x-model="pdfType" class="mt-1 text-indigo-500">
                                <div>
                                    <div class="font-semibold">Pilna sąmata klientui</div>
                                    <div class="text-gray-400">Visos pozicijos, kiekiai, vienetai, kainos, kambariai, bendra projekto suma. (Tinka klientui.)</div>
                                </div>
                            </label>
                            <label class="flex items-start gap-2">
                                <input type="radio" value="summary" x-model="pdfType" class="mt-1 text-indigo-500">
                                <div>
                                    <div class="font-semibold">Sutrumpinta sąmata</div>
                                    <div class="text-gray-400">Tik kambarių pavadinimai + kiekvieno kaina + bendra projekto suma. (Tinka klientui.)</div>
                                </div>
                            </label>
                            <label class="flex items-start gap-2">
                                <input type="radio" value="full" x-model="pdfType" class="mt-1 text-indigo-500">
                                <div>
                                    <div class="font-semibold">Vidinė sąmata</div>
                                    <div class="text-gray-400">Pilna detalė (pozicijos, kiekiai, vienetai, kainos) vidiniam naudojimui.</div>
                                </div>
                            </label>
                            <label class="flex items-start gap-2">
                                <input type="radio" value="offer" x-model="pdfType" class="mt-1 text-indigo-500">
                                <div>
                                    <div class="font-semibold">Kliento pasiūlymas</div>
                                    <div class="text-gray-400">Gražus PDF su logotipu, kontaktais, terminu ir bendra kaina, be techninių detalių.</div>
                                </div>
                            </label>
                        </div>
                        <div class="mt-4 flex items-center justify-end gap-2">
                            <button class="px-4 py-2 text-sm rounded-lg border border-gray-700 text-gray-200 hover:border-gray-500" @click="openPdf = false">Atšaukti</button>
                            <button class="px-4 py-2 text-sm rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white"
                                    @click.prevent="window.open('{{ route('app.projects.pdf', $project) }}' + '?type=' + pdfType, '_blank'); openPdf = false;">
                                Generuoti
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-page.header>

    {{-- Projekto informacija (išskleidžiama) --}}
    <div x-data="{ open: true }" class="bg-gray-800 border border-gray-700 rounded-xl shadow p-5">
        <div class="flex items-center justify-between gap-3">
            <div>
                <p class="text-xs uppercase text-gray-400">{{ __('app.projects.info_heading') }}</p>
                <p class="text-base font-semibold text-white">{{ $project->title }}</p>
            </div>
            <button type="button"
                    class="inline-flex items-center gap-2 px-3 py-2 rounded border border-gray-700 bg-gray-900 text-gray-100 hover:border-indigo-500"
                    @click="open = !open">
                <svg x-show="open" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25 12 15.75 4.5 8.25" />
                </svg>
                <svg x-show="!open" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75 12 8.25l7.5 7.5" />
                </svg>
            </button>
        </div>

        <div x-show="open" x-cloak class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm text-gray-200">
            @php
                $statusLabels = [
                    'draft' => 'Juodraštis',
                    'sent' => 'Pateikta',
                    'approved' => 'Patvirtinta',
                    'finished' => 'Užbaigta',
                ];
            @endphp
            <div>
                <p class="text-gray-400 text-xs uppercase mb-1">{{ __('app.projects.client') }}</p>
                <p class="text-gray-100">{{ $project->client_name ?: '—' }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs uppercase mb-1">{{ __('app.projects.address') }}</p>
                <p class="text-gray-100">{{ $project->address ?: '—' }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs uppercase mb-1">{{ __('app.projects.status_label') }}</p>
                <p class="text-gray-100">{{ $statusLabels[$project->status] ?? ucfirst($project->status) }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs uppercase mb-1">{{ __('app.projects.deadline') }}</p>
                <p class="text-gray-100">{{ $project->due_date ? \Illuminate\Support\Carbon::parse($project->due_date)->format('Y-m-d') : '—' }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs uppercase mb-1">{{ __('app.projects.contacts') }}</p>
                <p class="text-gray-100">
                    {{ $project->contact_phone ?: '—' }}
                    @if($project->contact_email)
                        <span class="text-gray-500"> / </span>{{ $project->contact_email }}
                    @endif
                </p>
            </div>
            <div class="md:col-span-2 lg:col-span-3">
                <p class="text-gray-400 text-xs uppercase mb-1">{{ __('app.projects.notes') }}</p>
                <p class="text-gray-100">{{ $project->notes ?: '—' }}</p>
            </div>
        </div>
    </div>

    <div class="bg-gray-800 border border-gray-700 rounded-xl shadow divide-y divide-gray-700">
        @foreach($this->rooms as $room)
            @php
                $total = $room->items->count();
                $done = $room->items->where('is_completed', true)->count();
                $progress = $total > 0 ? round(($done / $total) * 100) : 0;
            @endphp
            <div class="p-6 space-y-4" x-data="{ openRoom: true }">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <button type="button"
                                class="inline-flex items-center justify-center h-8 w-8 rounded border border-gray-700 bg-gray-900 text-gray-100 hover:border-indigo-500"
                                @click="openRoom = !openRoom">
                            <svg x-show="openRoom" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25 12 15.75 4.5 8.25" />
                            </svg>
                            <svg x-show="!openRoom" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75 12 8.25l7.5 7.5" />
                            </svg>
                        </button>
                        <div>
                            <p class="text-sm uppercase text-gray-400">{{ __('app.projects.room') }}</p>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $room->name }}</h3>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="text-sm text-gray-300">{{ $done }} / {{ $total }} {{ __('app.projects.tasks_count') }}</div>
                        <div class="w-40 bg-gray-900 rounded-full h-2 overflow-hidden">
                            <div class="h-2 bg-emerald-500" style="width: {{ $progress }}%"></div>
                        </div>
                        <span class="text-sm text-gray-300">{{ $progress }}%</span>
                    </div>
                </div>

                <div class="overflow-hidden rounded-lg border border-gray-700" x-show="openRoom" x-cloak>
                    <table class="w-full text-sm text-gray-200">
                        <thead>
                            <tr class="bg-gray-900 text-left text-gray-300 uppercase text-xs">
                                <th class="px-3 py-3 w-12 text-center">Atlikta</th>
                                <th class="px-3 py-3">Pozicija</th>
                                <th class="px-3 py-3">Kiekis</th>
                                <th class="px-3 py-3">Vnt</th>
                                <th class="px-3 py-3">Kaina/vnt</th>
                                <th class="px-3 py-3">Suma</th>
                                <th class="px-3 py-3">Komentaras</th>
                                <th class="px-3 py-3 w-28"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @forelse($room->items as $item)
                                @php
                                    $sum = ($item->quantity ?? 0) * ($item->unit_price ?? 0);
                                @endphp
                                <tr class="{{ $item->is_completed ? 'bg-emerald-900/20 dark:bg-emerald-900/20 bg-emerald-100/60' : '' }}">
                                    <td class="px-3 py-2 text-center">
                                        <input type="checkbox"
                                               wire:click="toggleItem({{ $item->id }})"
                                               @checked($item->is_completed)
                                               class="h-4 w-4 rounded border-gray-600 bg-gray-900 text-emerald-500 focus:ring-emerald-500">
                                    </td>
                                    <td class="px-3 py-2">
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $item->name }}</div>
                                    </td>
                                    <td class="px-3 py-2 text-gray-300">{{ $item->quantity ?? '—' }}</td>
                                    <td class="px-3 py-2 text-gray-300">{{ $item->unit ?? '—' }}</td>
                                    <td class="px-3 py-2 text-gray-300">{{ $item->unit_price ? number_format($item->unit_price, 2, ',', ' ') . ' €' : '—' }}</td>
                                    <td class="px-3 py-2 text-gray-100 font-semibold">{{ $sum ? number_format($sum, 2, ',', ' ') . ' €' : '—' }}</td>
                                    <td class="px-3 py-2 relative">
                                        <div x-data="{ open: false }" class="space-y-1">
                                            <div class="flex items-center gap-2">
                                                <button type="button"
                                                        class="inline-flex items-center justify-center h-8 w-8 rounded border border-gray-700 bg-gray-900 text-gray-100 hover:border-indigo-500"
                                                        @click="open = !open">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75v-10.5A2.25 2.25 0 015.25 6H10" />
                                                    </svg>
                                                </button>
                                                <div class="text-sm max-w-[220px] truncate {{ $item->comment ? 'text-gray-200' : 'text-gray-500 italic' }}">
                                                    {{ $item->comment ?: __('app.projects.no_comment') }}
                                                </div>
                                            </div>

                                            <div x-show="open" x-cloak
                                                 @click.outside="open = false"
                                                 @keydown.escape.window="open = false"
                                                 class="absolute z-20 mt-2 w-72 bg-gray-900 border border-gray-700 rounded-lg shadow-lg p-3 space-y-2">
                                                <textarea
                                                    wire:model.defer="comments.{{ $item->id }}"
                                                    rows="3"
                                                    class="w-full rounded-lg bg-gray-800 border border-gray-700 text-gray-100 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                    placeholder="{{ __('app.projects.comment') }}..."></textarea>
                                                <div class="flex items-center justify-end gap-2">
                                                    <button type="button"
                                                            wire:click="saveComment({{ $item->id }})"
                                                            @click="open = false"
                                                            class="px-3 py-2 text-xs rounded bg-indigo-600 hover:bg-indigo-700 text-white">
                                                        {{ __('app.projects.save') }}
                                                    </button>
                                                    <button type="button"
                                                            @click="open = false"
                                                            class="px-3 py-2 text-xs rounded border border-gray-700 text-gray-200 hover:border-gray-500">
                                                        {{ __('app.projects.cancel') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-2 text-right"></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-3 py-3 text-center text-gray-400">
                                        Darbų nėra.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    </div>
</div>
