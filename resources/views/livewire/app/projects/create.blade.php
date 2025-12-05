<?php

use Livewire\Volt\Component;
use App\Models\Project;

new class extends Component {

    public string $title = '';
    public string $client_name = '';
    public string $address = '';
    public string $due_date = '';
    public string $contact_phone = '';
    public string $contact_email = '';
    public string $notes = '';
    public string $status = 'draft';

    protected array $rules = [
        'title' => 'required|string|max:255',
        'client_name' => 'nullable|string|max:255',
        'address' => 'nullable|string|max:255',
        'due_date' => 'nullable|date',
        'contact_phone' => 'nullable|string|max:255',
        'contact_email' => 'nullable|email|max:255',
        'notes' => 'nullable|string',
        'status' => 'required|in:draft,sent,approved,finished',
    ];

    public function createProject()
    {
        $this->validate();

        $project = Project::create([
            'user_id' => auth()->id(),
            'title' => $this->title,
            'client_name' => $this->client_name,
            'address' => $this->address,
            'due_date' => $this->due_date ?: null,
            'contact_phone' => $this->contact_phone,
            'contact_email' => $this->contact_email,
            'notes' => $this->notes,
            'status' => $this->status,
        ]);

        return redirect()->route('app.projects.edit', $project);
    }

};

?>

<div class="max-w-8xl mx-auto py-10 space-y-8 text-gray-100">

    <x-page.header
        title="Naujas projektas"
        description="Sukurk naują projektą ir sąmatą"
        :breadcrumbs="[
            ['label' => 'Projektai', 'href' => route('app.projects')],
            ['label' => 'Naujas projektas'],
        ]"
    />

    <div class="bg-gray-800 border border-gray-700 shadow rounded-xl p-6 space-y-4">
        <h2 class="text-lg font-medium text-gray-900 dark:text-white">Projekto informacija</h2>

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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300">Objekto adresas</label>
                    <input type="text"
                           wire:model.defer="address"
                           class="mt-1 block w-full rounded-lg bg-gray-900 border border-gray-700 text-sm text-gray-100 px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="Gatvė, miestas">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300">Terminas</label>
                    <input type="date"
                           wire:model.defer="due_date"
                           class="mt-1 block w-full rounded-lg bg-gray-900 border border-gray-700 text-sm text-gray-100 px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300">Kontaktinis tel.</label>
                    <input type="text"
                           wire:model.defer="contact_phone"
                           class="mt-1 block w-full rounded-lg bg-gray-900 border border-gray-700 text-sm text-gray-100 px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="+370...">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300">Kontaktinis el. paštas</label>
                    <input type="email"
                           wire:model.defer="contact_email"
                           class="mt-1 block w-full rounded-lg bg-gray-900 border border-gray-700 text-sm text-gray-100 px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="pastas@klientas.lt">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300">Pastabos</label>
                <textarea
                    wire:model.defer="notes"
                    rows="3"
                    class="mt-1 block w-full rounded-lg bg-gray-900 border border-gray-700 text-sm text-gray-100 px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="Objektas, darbai, terminai..."></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300">Statusas</label>
                <select
                    wire:model.defer="status"
                    class="mt-1 block w-full rounded-lg bg-gray-900 border border-gray-700 text-sm text-gray-100 px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="draft">Juodraštis</option>
                    <option value="sent">Pateikta</option>
                    <option value="approved">Patvirtinta</option>
                    <option value="finished">Užbaigta</option>
                </select>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 text-sm">
                    + Sukurti projektą
                </button>
            </div>

        </form>
    </div>

</div>
