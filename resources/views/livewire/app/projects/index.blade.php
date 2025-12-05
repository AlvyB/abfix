<?php

use Livewire\Volt\Component;
use App\Models\Project;
use Livewire\WithPagination;

new class extends Component {

    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';
    public string $dueFrom = '';
    public string $dueTo = '';
    public bool $overdueOnly = false;
    public int $perPage = 10;

    public array $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'dueFrom' => ['except' => ''],
        'dueTo' => ['except' => ''],
        'overdueOnly' => ['except' => false],
        'perPage' => ['except' => 10],
    ];

    public function mount(): void
    {
        $this->perPage = (int) session('projects_per_page', $this->perPage);
    }

    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedStatusFilter(): void { $this->resetPage(); }
    public function updatedDueFrom(): void { $this->resetPage(); }
    public function updatedDueTo(): void { $this->resetPage(); }
    public function updatedOverdueOnly(): void { $this->resetPage(); }
    public function updatedPerPage(): void
    {
        session(['projects_per_page' => $this->perPage]);
        $this->resetPage();
    }

    public function getProjectsProperty()
    {
        return Project::query()
            ->where('user_id', auth()->id())
            ->when($this->search !== '', function ($q) {
                $search = $this->search;
                $q->where(function ($qq) use ($search) {
                    $qq->where('title', 'like', "%{$search}%")
                       ->orWhere('client_name', 'like', "%{$search}%")
                       ->orWhere('address', 'like', "%{$search}%");
                });
            })
            ->when($this->statusFilter !== '', fn ($q) => $q->where('status', $this->statusFilter))
            ->when($this->dueFrom !== '', fn ($q) => $q->whereDate('due_date', '>=', $this->dueFrom))
            ->when($this->dueTo !== '', fn ($q) => $q->whereDate('due_date', '<=', $this->dueTo))
            ->when($this->overdueOnly, fn ($q) => $q->whereDate('due_date', '<', now()->toDateString()))
            ->orderByDesc('created_at')
            ->paginate($this->perPage);
    }

    public function deleteProject(int $id)
    {
        Project::where('user_id', auth()->id())
            ->where('id', $id)
            ->delete();
    }

    public function duplicateProject(int $id)
    {
        $original = Project::where('user_id', auth()->id())->find($id);

        if (! $original) {
            return;
        }

        $copy = $original->replicate();
        $copy->title = $original->title . ' (kopija)';
        $copy->status = 'draft';
        $copy->save();
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->dueFrom = '';
        $this->dueTo = '';
        $this->overdueOnly = false;
    }

};

?>

<div class="max-w-8xl mx-auto py-10 space-y-8 text-gray-100">

    <x-page.header
        title="Projektai"
        description=""
        :breadcrumbs="[['label' => 'Projektai']]"
    />

    {{-- Projektų sąrašas --}}
    <div class="bg-gray-800 border border-gray-700 shadow rounded-xl p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-medium text-gray-900 dark:text-white">Projektų sąrašas</h2>
        </div>

        {{-- Filtrai --}}
        <div class="flex flex-wrap gap-3 mb-4 text-sm">
            <input type="text"
                   wire:model.live.debounce.300ms="search"
                   class="min-w-[200px] rounded-lg bg-gray-900 border border-gray-700 px-3 py-2 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500"
                   placeholder="Ieškoti (pavadinimas / klientas / adresas)">

            <select wire:model.live="statusFilter"
                    class="rounded-lg bg-gray-900 border border-gray-700 px-3 py-2 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Visi statusai</option>
                <option value="draft">Juodraštis</option>
                <option value="sent">Pateikta</option>
                <option value="approved">Patvirtinta</option>
                <option value="finished">Užbaigta</option>
            </select>

            <div class="flex items-center gap-2">
                <span class="text-gray-300">Terminas nuo</span>
                <input type="date"
                       wire:model.live="dueFrom"
                       class="rounded-lg bg-gray-900 border border-gray-700 px-3 py-2 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div class="flex items-center gap-2">
                <span class="text-gray-300">iki</span>
                <input type="date"
                       wire:model.live="dueTo"
                       class="rounded-lg bg-gray-900 border border-gray-700 px-3 py-2 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <label class="inline-flex items-center gap-2 text-gray-300">
                <input type="checkbox" wire:model.live="overdueOnly" class="rounded border-gray-600 text-indigo-600 focus:ring-indigo-500">
                Tik vėluojantys
            </label>

            <button type="button"
                    wire:click="resetFilters"
                    class="px-3 py-2 text-sm rounded-lg bg-gray-900 border border-gray-700 text-gray-200 hover:border-indigo-500">
                Išvalyti filtrus
            </button>
        </div>

        <div class="flex items-center gap-2 mb-2 text-sm text-gray-300">
            <span>Rodyti:</span>
            <select wire:model.live="perPage"
                    class="rounded-lg bg-gray-900 border border-gray-700 px-2 py-1 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="50">50</option>
            </select>
            <span>įrašų</span>
        </div>

        @if ($this->projects->isEmpty())
            <p class="text-gray-400 text-sm">Kol kas projektų nėra. Sukurk pirmą viršuje.</p>
        @else
            <div class="mb-2 text-sm text-gray-300">
                {{ $this->projects->links() }}
            </div>
            <table class="w-full text-sm text-gray-200">
                <x-table.header :columns="[
                    ['label' => 'Pavadinimas'],
                    ['label' => 'Klientas'],
                    ['label' => 'Adresas'],
                    ['label' => 'Terminas'],
                    ['label' => 'Statusas'],
                    ['label' => 'Sukurta'],
                    ['label' => 'Atnaujinta'],
                    ['label' => 'Veiksmai', 'class' => 'text-right'],
                ]" />
                <x-table.body>
                    @foreach ($this->projects as $project)
                        @php
                            $statusStyles = [
                                'draft' => 'bg-gray-900 border-gray-700 text-gray-200',
                                'sent' => 'bg-amber-900/70 border-amber-700 text-amber-200',
                                'approved' => 'bg-emerald-900/70 border-emerald-700 text-emerald-200',
                                'finished' => 'bg-indigo-900/70 border-indigo-700 text-indigo-200',
                            ];
                            $statusLabels = [
                                'draft' => 'Juodraštis',
                                'sent' => 'Pateikta',
                                'approved' => 'Patvirtinta',
                                'finished' => 'Užbaigta',
                            ];
                            $statusClass = $statusStyles[$project->status] ?? $statusStyles['draft'];
                            $statusLabel = $statusLabels[$project->status] ?? ucfirst($project->status);

                            $dueLabel = $project->due_date ? \Illuminate\Support\Carbon::parse($project->due_date)->format('Y-m-d') : '—';
                            $dueClass = 'bg-emerald-900/70 border-emerald-700 text-emerald-200';
                            if ($project->due_date) {
                                $days = now()->diffInDays(\Illuminate\Support\Carbon::parse($project->due_date), false);
                                if ($days < 0) {
                                    $dueClass = 'bg-red-900/70 border-red-700 text-red-200';
                                } elseif ($days <= 7) {
                                    $dueClass = 'bg-amber-900/70 border-amber-700 text-amber-200';
                                }
                            } else {
                                $dueClass = 'bg-gray-900 border-gray-700 text-gray-200';
                            }
                        @endphp
                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-800 cursor-pointer"
                            @click="window.location='{{ route('app.projects.tasks', $project) }}'">
                            <td class="px-3 py-2 text-gray-100">{{ $project->title }}</td>
                            <td class="px-3 py-2 text-gray-300">{{ $project->client_name ?? '—' }}</td>
                            <td class="px-3 py-2 text-gray-300">{{ $project->address ?? '—' }}</td>
                            <td class="px-3 py-2">
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs border {{ $dueClass }}">
                                    {{ $dueLabel }}
                                </span>
                            </td>
                            <td class="px-3 py-2">
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs border {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-3 py-2 text-gray-500">{{ $project->created_at->format('Y-m-d') }}</td>
                            <td class="px-3 py-2 text-gray-500">{{ $project->updated_at->format('Y-m-d') }}</td>
                            <td class="px-3 py-2 text-right">
                                <div class="relative inline-block text-left" x-data="{ open: false }" @click.stop>
                                    <button type="button"
                                            class="px-2 py-1 text-xs text-gray-200 bg-gray-900 border border-gray-700 rounded hover:border-indigo-500"
                                        @click.stop="open = !open">
                                        Parinktys ▾
                                    </button>
                                    <div x-show="open"
                                         @click.outside="open = false"
                                         @keydown.escape.window="open = false"
                                         x-transition
                                         class="absolute right-0 mt-1 w-44 rounded-lg border border-gray-700 bg-gray-900 shadow-lg z-10">
                                        <div class="py-1 text-xs text-gray-100">
                                            <a href="{{ route('app.projects.tasks', $project) }}"
                                               class="block w-full text-left px-3 py-1 hover:bg-gray-200 dark:hover:bg-gray-800"
                                               @click.stop>Peržiūrėti darbus</a>
                                            <a href="{{ route('app.projects.pdf', $project) }}" target="_blank"
                                               class="block w-full text-left px-3 py-1 hover:bg-gray-200 dark:hover:bg-gray-800"
                                               @click.stop>Eksportuoti PDF</a>
                                            <button type="button" class="w-full text-left px-3 py-1 hover:bg-gray-200 dark:hover:bg-gray-800" @click.stop>Eksportuoti Excel</button>
                                            <button type="button"
                                                    wire:click="duplicateProject({{ $project->id }})"
                                                    class="w-full text-left px-3 py-1 hover:bg-gray-200 dark:hover:bg-gray-800"
                                                    @click.stop>Dubliuoti</button>
                                            <button type="button"
                                                    wire:click="deleteProject({{ $project->id }})"
                                                    onclick="return confirm('Tikrai šalinti projektą?')"
                                                    class="w-full text-left px-3 py-1 text-red-400 hover:bg-gray-200 dark:hover:bg-gray-800"
                                                    @click.stop>Šalinti</button>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </x-table.body>
            </table>
            <div class="mt-3 text-sm text-gray-300">
                {{ $this->projects->links() }}
            </div>
        @endif
    </div>

</div>
