<?php

use Livewire\Volt\Component;
use App\Models\Project;

new class extends Component {

    public string $title = '';
    public string $client_name = '';
    public string $notes = '';

    protected array $rules = [
        'title' => 'required|string|max:255',
        'client_name' => 'nullable|string|max:255',
        'notes' => 'nullable|string',
    ];

    public function getProjectsProperty()
    {
        return Project::where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->get();
    }

    public function createProject()
    {
        $this->validate();

        Project::create([
            'user_id' => auth()->id(),
            'title' => $this->title,
            'client_name' => $this->client_name,
            'notes' => $this->notes,
            'status' => 'draft',
        ]);

        $this->reset(['title', 'client_name', 'notes']);
    }

    public function deleteProject(int $id)
    {
        Project::where('user_id', auth()->id())
            ->where('id', $id)
            ->delete();
    }

};

?>

<div class="max-w-5xl mx-auto py-10 space-y-8 text-gray-100">

    {{-- Antraštė --}}
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-semibold text-white">Projektai</h1>
        <span class="text-gray-400">ABFix projektų valdymas</span>
    </div>

    {{-- Naujo projekto forma --}}
    <div class="bg-gray-800 border border-gray-700 shadow rounded-xl p-6 space-y-4">
        <h2 class="text-lg font-medium text-white">Naujas projektas</h2>

        <form wire:submit.prevent="createProject" class="space-y-4">

            <div>
                <label class="block text-sm font-medium text-gray-300">Projekto pavadinimas *</label>
                <input type="text"
                       wire:model.defer="title"
                       class="mt-1 block w-full rounded-lg bg-gray-900 border border-gray-700 text-sm text-gray-100 px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500"
                       placeholder="Pvz.: Drobės g. 22C – renovacija">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300">Klientas</label>
                <input type="text"
                       wire:model.defer="client_name"
                       class="mt-1 block w-full rounded-lg bg-gray-900 border border-gray-700 text-sm text-gray-100 px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500"
                       placeholder="Įmonė arba vardas pavardė">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300">Pastabos</label>
                <textarea
                    wire:model.defer="notes"
                    rows="3"
                    class="mt-1 block w-full rounded-lg bg-gray-900 border border-gray-700 text-sm text-gray-100 px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="Objektas, darbai, terminai..."></textarea>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 text-sm">
                    + Sukurti projektą
                </button>
            </div>

        </form>
    </div>

    {{-- Projektų sąrašas --}}
    <div class="bg-gray-800 border border-gray-700 shadow rounded-xl p-6">
        <h2 class="text-lg font-medium text-white mb-4">Mano projektai</h2>

        @if ($this->projects->isEmpty())
            <p class="text-gray-400 text-sm">Kol kas projektų nėra. Sukurk pirmą viršuje.</p>
        @else
            <table class="w-full text-sm text-gray-200">
                <thead>
                    <tr class="border-b border-gray-700 text-left text-gray-400 uppercase text-xs">
                        <th class="py-2">Projekto pavadinimas</th>
                        <th class="py-2">Klientas</th>
                        <th class="py-2">Data</th>
                        <th class="py-2 text-right">Veiksmai</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @foreach ($this->projects as $project)
                        <tr>
                            <td class="py-2 text-gray-100">{{ $project->title }}</td>
                            <td class="py-2 text-gray-300">{{ $project->client_name ?? '—' }}</td>
                            <td class="py-2 text-gray-500">{{ $project->created_at->format('Y-m-d') }}</td>
                            <td class="py-2 text-right">
                                <a href="{{ route('app.projects.show', $project) }}"
       class="text-indigo-400 text-xs hover:underline">
        Atidaryti
    </a>
                                <button wire:click="deleteProject({{ $project->id }})"
                                        class="text-red-400 text-xs hover:underline">
                                    Šalinti
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

</div>
