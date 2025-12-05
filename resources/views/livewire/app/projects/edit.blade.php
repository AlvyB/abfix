<?php

use Livewire\Volt\Component;
use App\Models\Project;
use App\Models\Room;
use App\Models\RoomItem;
use App\Models\WorkCatalog;
use App\Models\RoomPreset;

new class extends Component {

    public Project $project;
    public bool $infoEditing = false;
    public string $title = '';
    public string $clientName = '';
    public ?string $address = '';
    public ?string $contactPhone = '';
    public ?string $contactEmail = '';
    public ?string $notes = '';
    public ?string $dueDate = '';

    public string $newRoomName = '';
    public $presets;

    // Sąmatos eilutės formos laukeliai per room_id
    public array $itemName = [];
    public array $itemQty = [];
    public array $itemUnit = [];
    public array $itemUnitPrice = [];
    public array $editItems = [];

    // Paieška kataloge per kambarį: [roomId => 'tekstas']
    public array $searchCatalog = [];

    public function mount(Project $project)
    {
        abort_unless($project->user_id === auth()->id(), 403);
        $this->project = $project;
        $this->presets = RoomPreset::orderBy('sort_order')->get();

        $this->fillProjectInfo();
    }

    protected function fillProjectInfo(): void
    {
        $this->title        = $this->project->title;
        $this->clientName   = $this->project->client_name ?? '';
        $this->address      = $this->project->address ?? '';
        $this->contactPhone = $this->project->contact_phone ?? '';
        $this->contactEmail = $this->project->contact_email ?? '';
        $this->notes        = $this->project->notes ?? '';
        $this->dueDate      = $this->project->due_date ? \Illuminate\Support\Carbon::parse($this->project->due_date)->format('Y-m-d') : '';
    }

    public function saveProjectInfo(): void
    {
        $this->validate([
            'title'        => 'required|string|max:255',
            'clientName'   => 'nullable|string|max:255',
            'address'      => 'nullable|string|max:255',
            'contactPhone' => 'nullable|string|max:100',
            'contactEmail' => 'nullable|email|max:255',
            'notes'        => 'nullable|string',
            'dueDate'      => 'nullable|date',
        ]);

        $this->project->update([
            'title'         => $this->title,
            'client_name'   => $this->clientName ?: null,
            'address'       => $this->address ?: null,
            'contact_phone' => $this->contactPhone ?: null,
            'contact_email' => $this->contactEmail ?: null,
            'notes'         => $this->notes ?: null,
            'due_date'      => $this->dueDate ?: null,
        ]);

        $this->infoEditing = false;
    }

    public function cancelProjectInfo(): void
    {
        $this->fillProjectInfo();
        $this->infoEditing = false;
    }

    public function deleteProject(): void
    {
        abort_unless($this->project->user_id === auth()->id(), 403);
        $this->project->delete();
        $this->redirect(route('app.projects'));
    }

    public function downloadPdf(): void
    {
        $this->redirect(route('app.projects.pdf', $this->project));
    }

    /** Kambariai su įkeltais items */
    public function getRoomsProperty()
    {
        $rooms = $this->project
            ->rooms()
            ->with('items')
            ->orderBy('sort_order')
            ->get();

        foreach ($rooms as $room) {
            foreach ($room->items as $item) {
                if (! isset($this->editItems[$item->id])) {
                    $this->editItems[$item->id] = [
                        'name'       => $item->name,
                        'quantity'   => $item->quantity,
                        'unit'       => $item->unit,
                        'unit_price' => $item->unit_price,
                    ];
                }
            }
        }

        return $rooms;
    }

    /** Grąžina katalogo rezultatus konkrečiam kambariui */
    public function getCatalogResults(int $roomId)
    {
        $search = trim($this->searchCatalog[$roomId] ?? '');

        if (strlen($search) < 2) {
            return collect();
        }

        return WorkCatalog::query()
            ->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->limit(10)
            ->get();
    }


    /** Pasirinkti poziciją iš katalogo konkrečiam kambariui */
    public function selectCatalogItem(int $roomId, int $catalogId): void
    {
        $item = WorkCatalog::find($catalogId);

        if (! $item) {
            return;
        }

        $this->itemName[$roomId]      = $item->name;
        $this->itemUnit[$roomId]      = $item->default_unit;
        $this->itemUnitPrice[$roomId] = $item->default_price;

        // norim, kad dropdown'as dingtų po pasirinkimo
        $this->searchCatalog[$roomId] = '';
    }

    /** Greiti kambarių presetai iš DB */
    public function addRoomPreset(int $presetId): void
    {
        $preset = RoomPreset::find($presetId);

        if (! $preset) {
            return;
        }

        $name = $preset->slug === 'bedroom'
            ? $this->nextBedroomName()
            : $preset->name;

        $this->createRoom($name);
    }

    /** Rankinis kambario pavadinimas iš input'o */
    public function addRoom(): void
    {
        $name = trim($this->newRoomName);
        if ($name === '') {
            return;
        }

        $this->createRoom($name);
        $this->newRoomName = '';
    }

    protected function createRoom(string $name): Room
    {
        $maxSort = $this->project->rooms()->max('sort_order') ?? 0;

        $room = Room::create([
            'project_id' => $this->project->id,
            'name'       => $name,
            'sort_order' => $maxSort + 1,
        ]);

        $this->dispatch('scroll-to-room', id: $room->id);

        return $room;
    }

    public function updateStatus(string $status): void
    {
        $allowed = ['draft', 'sent', 'approved', 'finished'];
        if (! in_array($status, $allowed, true)) {
            return;
        }

        $this->project->update(['status' => $status]);
    }

    protected function nextBedroomName(): string
    {
        $count = $this->project->rooms()
            ->where('name', 'like', 'Miegamasis%')
            ->count();

        if ($count === 0) {
            return 'Miegamasis 1';
        }

        return 'Miegamasis ' . ($count + 1);
    }

    public function deleteRoom(int $roomId): void
    {
        $room = $this->project->rooms()->where('id', $roomId)->first();

        if ($room) {
            $room->delete();
        }
    }

    /** Pridėti sąmatos eilutę kambariui */
    public function addRoomItem(int $roomId): void
    {
        $room = $this->project->rooms()->where('id', $roomId)->first();
        if (! $room) {
            return;
        }

        $name       = trim($this->itemName[$roomId] ?? '');
        $qty        = $this->itemQty[$roomId] ?? null;
        $unit       = trim($this->itemUnit[$roomId] ?? '');
        $unitPrice  = $this->itemUnitPrice[$roomId] ?? null;

        if ($name === '') {
            return;
        }

        RoomItem::create([
            'room_id'    => $room->id,
            'name'       => $name,
            'quantity'   => $qty ?: null,
            'unit'       => $unit ?: null,
            'unit_price' => $unitPrice ?: null,
        ]);

        // išvalom formos laukus tam kambariui
        unset(
            $this->itemName[$roomId],
            $this->itemQty[$roomId],
            $this->itemUnit[$roomId],
            $this->itemUnitPrice[$roomId],
        );
    }

    public function deleteRoomItem(int $itemId): void
    {
        $item = RoomItem::whereHas('room', function ($q) {
            $q->where('project_id', $this->project->id);
        })->where('id', $itemId)->first();

        if ($item) {
            $item->delete();
        }
    }

    public function updateRoomItem(int $itemId): void
    {
        $item = RoomItem::whereHas('room', function ($q) {
            $q->where('project_id', $this->project->id);
        })->where('id', $itemId)->first();

        if (! $item) {
            return;
        }

        $this->validate([
            "editItems.$itemId.name"       => 'required|string|max:255',
            "editItems.$itemId.quantity"   => 'nullable|numeric|min:0',
            "editItems.$itemId.unit"       => 'nullable|string|max:50',
            "editItems.$itemId.unit_price" => 'nullable|numeric|min:0',
        ]);

        $data = $this->editItems[$itemId];

        $item->update([
            'name'       => $data['name'],
            'quantity'   => $data['quantity'] ?? null,
            'unit'       => $data['unit'] ?? null,
            'unit_price' => $data['unit_price'] ?? null,
        ]);
    }

};

?>

<div class="max-w-8xl mx-auto py-8 space-y-8 text-gray-900 dark:text-gray-100">

    @php
        $breadcrumbs = [
            ['label' => 'Projektai', 'href' => route('app.projects')],
            ['label' => $project->title],
        ];
    @endphp

    <div class="sticky top-0 z-20 -mx-4 sm:-mx-6 px-4 sm:px-6 py-4 bg-gradient-to-r from-slate-50/90 to-slate-100/90 dark:from-gray-900/90 dark:to-gray-950/90 backdrop-blur border-b border-gray-200 dark:border-gray-800">
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
                <a href="{{ route('app.projects.tasks', $project) }}"
                   class="text-sm text-gray-200 bg-gray-800 border border-gray-700 hover:border-indigo-500 rounded-lg px-3 py-2 inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-6-6h12" />
                    </svg>
                    {{ __('app.projects.tasks') }}
                </a>
                <a href="#room-tools" class="text-sm text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg px-3 py-2 whitespace-nowrap inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-6-6h12" />
                    </svg>
                    {{ __('app.projects.add_room') }}
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
                                        class="w-full text-left px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-800 {{ $statusColors[$key] ?? '' }}">
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <a href="{{ route('app.projects.pdf', $project) }}" target="_blank" rel="noopener"
                   class="text-sm text-gray-200 bg-gray-800 border border-gray-700 hover:border-indigo-500 rounded-lg px-3 py-2 inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25H8.25A2.25 2.25 0 006 7.5v9A2.25 2.25 0 008.25 18h7.5A2.25 2.25 0 0018 16.5v-9A2.25 2.25 0 0015.75 5.25z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 9h6m-6 3h6m-6 3h3" />
                    </svg>
                    {{ __('app.projects.export_pdf') }}
                </a>

                <div x-data>
                    <button type="button"
                            @click.prevent="if(confirm('Tikrai šalinti projektą?')) { $wire.deleteProject() }"
                            class="text-sm text-white bg-red-600 hover:bg-red-700 border border-red-700 rounded-lg px-3 py-2 inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12M9 7V5.5A1.5 1.5 0 0110.5 4h3A1.5 1.5 0 0115 5.5V7m-7 0h8v11.25A1.75 1.75 0 0114.25 20H9.75A1.75 1.75 0 018 18.25V7z" />
                        </svg>
                        {{ __('app.projects.delete_project') }}
                    </button>
                </div>
            </div>
        </x-page.header>
    </div>

    {{-- PROJEKTO INFO --}}
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

        @php
            $statusLabels = [
                'draft' => 'Juodraštis',
                'sent' => 'Pateikta',
                'approved' => 'Patvirtinta',
                'finished' => 'Užbaigta',
            ];
        @endphp

        <div x-show="open" x-cloak class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm text-gray-200">
            <div class="md:col-span-2 lg:col-span-3 flex flex-col gap-1">
                <p class="text-gray-400 text-xs uppercase mb-1">{{ __('app.projects.project_title') }}</p>
                <input type="text"
                       wire:model.defer="title"
                       class="w-full rounded-lg bg-gray-900 border border-gray-700 px-3 py-2 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500"
                       placeholder="{{ __('app.projects.project_title') }}">
            </div>
            <div class="space-y-1">
                <p class="text-gray-400 text-xs uppercase mb-1">{{ __('app.projects.client') }}</p>
                <input type="text"
                       wire:model.defer="clientName"
                       class="w-full rounded-lg bg-gray-900 border border-gray-700 px-3 py-2 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500"
                       placeholder="Kliento vardas / įmonė">
            </div>
            <div class="space-y-1">
                <p class="text-gray-400 text-xs uppercase mb-1">{{ __('app.projects.address') }}</p>
                <input type="text"
                       wire:model.defer="address"
                       class="w-full rounded-lg bg-gray-900 border border-gray-700 px-3 py-2 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500"
                       placeholder="Adresas">
            </div>
            <div class="space-y-1">
                <p class="text-gray-400 text-xs uppercase mb-1">{{ __('app.projects.status_label') }}</p>
                <p class="text-gray-100">{{ $statusLabels[$project->status] ?? ucfirst($project->status) }}</p>
            </div>
            <div class="space-y-1">
                <p class="text-gray-400 text-xs uppercase mb-1">{{ __('app.projects.deadline') }}</p>
                <input type="date"
                       wire:model.defer="dueDate"
                       class="w-full rounded-lg bg-gray-900 border border-gray-700 px-3 py-2 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div class="space-y-1">
                <p class="text-gray-400 text-xs uppercase mb-1">{{ __('app.projects.contacts') }}</p>
                <div class="flex flex-col gap-2">
                    <input type="text"
                           wire:model.defer="contactPhone"
                           class="w-full rounded-lg bg-gray-900 border border-gray-700 px-3 py-2 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="Telefonas">
                    <input type="email"
                           wire:model.defer="contactEmail"
                           class="w-full rounded-lg bg-gray-900 border border-gray-700 px-3 py-2 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="El. paštas">
                </div>
            </div>
            <div class="md:col-span-2 lg:col-span-3 space-y-1">
                <p class="text-gray-400 text-xs uppercase mb-1">{{ __('app.projects.notes') }}</p>
                <textarea
                    wire:model.defer="notes"
                    rows="3"
                    class="w-full rounded-lg bg-gray-900 border border-gray-700 px-3 py-2 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="Pastabos..."></textarea>
            </div>
            <div class="md:col-span-2 lg:col-span-3 flex items-center gap-2 justify-end">
                <button type="button"
                        wire:click="saveProjectInfo"
                        class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm hover:bg-indigo-700 inline-flex items-center gap-2">
                    <svg wire:loading wire:target="saveProjectInfo" class="w-4 h-4 animate-spin text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="9" stroke-opacity="0.25"></circle>
                        <path d="M21 12a9 9 0 0 1-9 9" stroke-linecap="round"></path>
                    </svg>
                    <span>Išsaugoti</span>
                </button>
                <button type="button"
                        wire:click="cancelProjectInfo"
                        class="px-4 py-2 rounded-lg border border-gray-700 text-gray-200 text-sm hover:border-gray-500">
                    Atšaukti
                </button>
            </div>
        </div>
    </div>

    {{-- KAMBARIŲ KŪRIMAS --}}
    <div id="room-tools" class="bg-gray-800 border border-gray-700 shadow rounded-xl p-6 space-y-4 scroll-mt-28">
        <div class="flex items-center justify-between flex-wrap gap-3">
            <h2 class="text-lg font-medium text-gray-900 dark:text-white">Kambariai / zonos</h2>

            <div class="flex flex-wrap gap-2">
                @foreach($presets as $preset)
                    <button
                        wire:click="addRoomPreset({{ $preset->id }})"
                        class="group px-3 py-1 text-sm rounded-full bg-gray-900 border border-gray-700 text-gray-200 transition transform hover:-translate-y-0.5 hover:border-indigo-500 hover:shadow-md">
                        <span class="inline-flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 opacity-50 group-hover:opacity-100 transition" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-6-6h12" />
                            </svg>
                            {{ $preset->name }}
                        </span>
                    </button>
                @endforeach
            </div>
        </div>

        <div class="flex gap-2 mt-2">
            <input type="text"
                   wire:model.defer="newRoomName"
                   class="flex-1 rounded-lg bg-gray-900 border border-gray-700 text-base text-gray-100 px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500"
                   placeholder="Kitas kambarys, pvz.: Vaikų kambarys, Ofisas...">
            <button wire:click="addRoom"
                    class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-base hover:bg-indigo-700">
                + Pridėti
            </button>
        </div>
    </div>

    {{-- KAMBARIAI SU SĄMATA --}}
    @php
        $projectTotal = 0;
    @endphp

    @if ($this->rooms->isEmpty())
        <div class="bg-gray-800 border border-gray-700 rounded-xl p-6 text-base text-gray-400">
            Kambarių dar nėra. Pridėk bent vieną viršuje.
        </div>
    @else
        <div class="space-y-6">
            @foreach ($this->rooms as $room)
                @php
                    $roomTotal = $room->items->sum(function ($item) {
                        $qty = $item->quantity ?? 0;
                        $price = $item->unit_price ?? 0;
                        return $qty * $price;
                    });
                    $projectTotal += $roomTotal;

                    $search = $searchCatalog[$room->id] ?? '';
                    $catalogResults = strlen($search) >= 2
                        ? $this->getCatalogResults($room->id)
                        : collect();
                @endphp

                <div class="bg-gray-800 border border-gray-700 shadow rounded-xl p-5 space-y-4 scroll-mt-24"
                     wire:key="room-{{ $room->id }}"
                     data-room-id="{{ $room->id }}">
                    <div class="flex items-center justify-between gap-2">
                        <h3 class="text-xl font-semibold text-gray-100 dark:text-white">
                            {{ $room->name }}
                        </h3>

                        <div class="flex items-center gap-3 text-sm text-gray-400">
                            <span>Kambario suma: <span class="text-gray-100 font-semibold">{{ number_format($roomTotal, 2, ',', ' ') }} €</span></span>

                            <button wire:click="deleteRoom({{ $room->id }})"
                                    class="text-red-400 hover:text-red-300 inline-flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 7.5h10.5m-9 3v6m7.5-6v6M9 4.5h6a.75.75 0 0 1 .75.75V7.5h-7.5V5.25A.75.75 0 0 1 9 4.5Zm-1.5 3h9l-.54 12.14a.75.75 0 0 1-.74.71H8.28a.75.75 0 0 1-.74-.71L7 7.5Z" />
                                </svg>
                                Šalinti kambarį
                            </button>
                        </div>
                    </div>

                    {{-- Sąmatos eilutės --}}
                    @if ($room->items->isEmpty())
                        <p class="text-sm text-gray-400">
                            Šiame kambaryje dar nėra pozicijų. Pridėk žemiau.
                        </p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-gray-900 dark:text-gray-100">
                                <x-table.header :columns="[
                                    ['label' => 'Pozicija'],
                                    ['label' => 'Kiekis', 'class' => 'w-24'],
                                    ['label' => 'Vnt', 'class' => 'w-20'],
                                    ['label' => 'Kaina/vnt', 'class' => 'w-28 text-right'],
                                    ['label' => 'Suma', 'class' => 'w-24 text-right'],
                                    ['label' => '', 'class' => 'w-20'],
                                ]" />
                                <x-table.body>
                                    @foreach ($room->items as $item)
                                        @php
                                            $lineTotal = ($item->quantity ?? 0) * ($item->unit_price ?? 0);
                                        @endphp
                                        <tr>
                                            <td class="px-3 py-2 text-gray-900 dark:text-gray-100">
                                                <input type="text"
                                                       wire:model.defer="editItems.{{ $item->id }}.name"
                                                       class="w-full rounded-lg bg-gray-900 border border-gray-700 px-2 py-1 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500"
                                                       placeholder="Pozicija">
                                            </td>
                                            <td class="px-3 py-2">
                                                <input type="number" min="0" step="1"
                                                       wire:model.defer="editItems.{{ $item->id }}.quantity"
                                                       class="w-full rounded-lg bg-gray-900 border border-gray-700 px-2 py-1 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 text-right"
                                                       placeholder="Kiekis">
                                            </td>
                                            <td class="px-3 py-2">
                                                <input type="text"
                                                       wire:model.defer="editItems.{{ $item->id }}.unit"
                                                       class="w-full rounded-lg bg-gray-900 border border-gray-700 px-2 py-1 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500"
                                                       placeholder="Vnt">
                                            </td>
                                            <td class="px-3 py-2 text-right">
                                                <input type="number" min="0" step="0.01"
                                                       wire:model.defer="editItems.{{ $item->id }}.unit_price"
                                                       class="w-full rounded-lg bg-gray-900 border border-gray-700 px-2 py-1 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 text-right"
                                                       placeholder="Kaina/vnt">
                                            </td>
                                            <td class="px-3 py-2 text-right">
                                                {{ number_format($lineTotal, 2, ',', ' ') }} €
                                            </td>
                                            <td class="px-3 py-2 text-right flex items-center gap-2 justify-end">
                                                <button wire:click="updateRoomItem({{ $item->id }})"
                                                        class="px-3 py-1 rounded-lg bg-indigo-600 text-white text-xs hover:bg-indigo-700 inline-flex items-center gap-1">
                                                    <svg wire:loading wire:target="updateRoomItem({{ $item->id }})" class="w-4 h-4 animate-spin text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <circle cx="12" cy="12" r="9" stroke-opacity="0.25"></circle>
                                                        <path d="M21 12a9 9 0 0 1-9 9" stroke-linecap="round"></path>
                                                    </svg>
                                                    <span>Atnaujinti</span>
                                                </button>
                                                <button wire:click="deleteRoomItem({{ $item->id }})"
                                                        class="text-red-400 hover:text-red-300">
                                                    x
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </x-table.body>
                            </table>
                        </div>
                    @endif

                    {{-- Naujos eilutės forma --}}
                    <div class="mt-3 border-t border-gray-700 pt-3 space-y-2">
                        <div class="text-sm text-gray-900 dark:text-gray-100 flex flex-wrap items-center gap-2">
                            <span class="font-semibold">Nauja pozicija kambaryje:</span> <span class="text-gray-900 dark:text-gray-100">{{ $room->name }}</span>
                        </div>

                        <form wire:submit.prevent="addRoomItem({{ $room->id }})" class="grid grid-cols-1 md:grid-cols-12 gap-2 text-sm items-end">
                            {{-- 1) Paieška kataloge --}}
                            <div class="relative md:col-span-4">
                               <input type="text"
       wire:model.live="searchCatalog.{{ $room->id }}"
       class="w-full rounded-lg bg-gray-900 border border-gray-700 px-2 py-1 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500"
       placeholder="Ieškoti darbo kataloge (pavadinimas ar kodas)..."
       autocomplete="off">

                                {{-- DEBUG: rodome, kas įvesta ir kiek rasta --}}
                                @if(strlen($search) > 0)
                                    <div class="mt-1 text-sm text-gray-500">
                                        Paieška: "{{ $search }}", rasta: {{ $catalogResults->count() }} pozicijų
                                    </div>
                                @endif

                                {{-- Dropdown'as su rezultatais --}}
                                @if ($catalogResults->isNotEmpty())
                                    <div class="absolute z-20 mt-1 w-full bg-gray-900 border border-gray-700 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                        @foreach ($catalogResults as $catalogItem)
                                            <button type="button"
                                                    wire:click="selectCatalogItem({{ $room->id }}, {{ $catalogItem->id }})"
                                                    class="w-full text-left px-3 py-1.5 hover:bg-gray-200 dark:hover:bg-gray-800">
                                                <div class="flex justify-between items-center">
                                                    <span class="text-sm text-gray-100">
                                                        {{ $catalogItem->name }}
                                                    </span>
                                                    <span class="text-sm text-gray-400 ml-2">
                                                        {{ $catalogItem->code }}
                                                    </span>
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $catalogItem->default_unit ?: '–' }}
                                                    @if($catalogItem->default_price !== null)
                                                        • {{ number_format($catalogItem->default_price, 2, ',', ' ') }} €
                                                    @endif
                                                </div>
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            {{-- 2) Pozicijos pavadinimas --}}
                            <div class="md:col-span-3">
                                <input type="text"
                                       wire:model.defer="itemName.{{ $room->id }}"
                                       class="w-full rounded-lg bg-gray-900 border border-gray-700 px-2 py-1 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="Pozicijos pavadinimas">
                            </div>

                            {{-- 3) Kiekis --}}
                            <div class="md:col-span-2">
                                <input type="number" step="1" min="0" inputmode="numeric" pattern="[0-9]*"
                                       wire:model.defer="itemQty.{{ $room->id }}"
                                       class="w-full rounded-lg bg-gray-900 border border-gray-700 px-2 py-1 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="Kiekis">
                            </div>

                            {{-- 4) Vnt --}}
                            <div class="md:col-span-1">
                                <input type="text"
                                       wire:model.defer="itemUnit.{{ $room->id }}"
                                       class="w-full rounded-lg bg-gray-900 border border-gray-700 px-2 py-1 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="vnt, m...">
                            </div>

                            {{-- 5) Kaina + pridėti --}}
                            <div class="md:col-span-2 flex gap-2 md:justify-end">
                                <input type="number" step="0.01" min="0"
                                       wire:model.defer="itemUnitPrice.{{ $room->id }}"
                                       class="w-full rounded-lg bg-gray-900 border border-gray-700 px-2 py-1 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="Kaina/vnt">
                                <button type="submit"
                                        class="px-3 py-1 rounded-lg bg-indigo-600 text-white text-sm hover:bg-indigo-700 whitespace-nowrap">
                                    + Pridėti
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            @endforeach
        </div>

        {{-- BENDRA PROJEKTO SUMA --}}
        <div class="sticky bottom-3 z-10 bg-gray-900/95 backdrop-blur border border-gray-700 rounded-xl p-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between mt-4 shadow-lg shadow-black/20">
            <div class="text-base text-white">
                Bendra projekto suma
            </div>
            <div class="flex items-center gap-3">
                <span class="text-lg font-semibold text-white">
                    {{ number_format($projectTotal, 2, ',', ' ') }} €
                </span>
            </div>
        </div>
    @endif
<script>
document.addEventListener('livewire:init', () => {
    Livewire.on('scroll-to-room', (payload) => {
        const id = payload?.id ?? payload;
        if (!id) return;

        const scrollToTarget = () => {
            const target = document.querySelector(`[data-room-id="${id}"]`);
            if (target) {
                const top = target.getBoundingClientRect().top + window.scrollY - 80;
                window.scrollTo({ top, behavior: 'smooth' });
            }
        };

        // Laukiam, kol Livewire įterps naują bloką
        setTimeout(scrollToTarget, 150);
    });
});
</script>

</div>
