<?php

use Livewire\Volt\Component;
use App\Models\Project;
use App\Models\Room;
use App\Models\RoomItem;
use App\Models\WorkCatalog;

new class extends Component {

    public Project $project;

    public string $newRoomName = '';

    // Sąmatos eilutės formos laukeliai per room_id
    public array $itemName = [];
    public array $itemQty = [];
    public array $itemUnit = [];
    public array $itemUnitPrice = [];

    // Paieška kataloge per kambarį: [roomId => 'tekstas']
    public array $searchCatalog = [];

    public function mount(Project $project)
    {
        abort_unless($project->user_id === auth()->id(), 403);
        $this->project = $project;
    }

    /** Kambariai su įkeltais items */
    public function getRoomsProperty()
    {
        return $this->project
            ->rooms()
            ->with('items')
            ->orderBy('sort_order')
            ->get();
    }

    /** Grąžina katalogo rezultatus konkrečiam kambariui */
    public function getCatalogResults(int $roomId)
{
    // LAIKINAS TESTAS: ignoruojam paiešką, imam pirmus 10 įrašų
    return WorkCatalog::limit(10)->get();
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

    /** Greiti kambarių presetai: Virtuvė, Svetainė ir t.t. */
    public function addRoomPreset(string $preset): void
    {
        $name = match ($preset) {
            'kitchen'     => 'Virtuvė',
            'living'      => 'Svetainė',
            'bedroom'     => $this->nextBedroomName(),
            'bathroom'    => 'Vonios kambarys',
            'corridor'    => 'Koridorius',
            'garage'      => 'Garažas',
            default       => 'Kambarys',
        };

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

    protected function createRoom(string $name): void
    {
        $maxSort = $this->project->rooms()->max('sort_order') ?? 0;

        Room::create([
            'project_id' => $this->project->id,
            'name'       => $name,
            'sort_order' => $maxSort + 1,
        ]);
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
        $name       = trim($this->itemName[$roomId] ?? '');
        $qty        = $this->itemQty[$roomId] ?? null;
        $unit       = trim($this->itemUnit[$roomId] ?? '');
        $unitPrice  = $this->itemUnitPrice[$roomId] ?? null;

        if ($name === '') {
            return;
        }

        RoomItem::create([
            'room_id'    => $roomId,
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

};

?>

<div class="max-w-5xl mx-auto py-10 space-y-8 text-gray-100">

    {{-- HEADERIS --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-semibold text-white">
                {{ $project->title }}
            </h1>
            <p class="text-sm text-gray-400">
                Klientas: {{ $project->client_name ?: '—' }}
            </p>
        </div>

        <a href="{{ route('app.projects') }}"
           class="text-sm text-gray-400 hover:text-gray-200">
            ← Atgal
        </a>
    </div>

    {{-- PROJEKTO INFO --}}
    <div class="bg-gray-800 border border-gray-700 shadow rounded-xl p-6 space-y-3">
        <h2 class="text-lg font-medium text-white">Projekto informacija</h2>

        <div class="text-sm text-gray-300 space-y-1">
            <p><span class="text-gray-400">Pastabos:</span> {{ $project->notes ?: '—' }}</p>
            <p><span class="text-gray-400">Sukurta:</span> {{ $project->created_at->format('Y-m-d H:i') }}</p>
        </div>
    </div>

    {{-- KAMBARIŲ KŪRIMAS --}}
    <div class="bg-gray-800 border border-gray-700 shadow rounded-xl p-6 space-y-4">
        <div class="flex items-center justify-between flex-wrap gap-3">
            <h2 class="text-lg font-medium text-white">Kambariai / zonos</h2>

            <div class="flex flex-wrap gap-2">
                <button wire:click="addRoomPreset('kitchen')" class="px-3 py-1 text-xs rounded-full bg-gray-900 border border-gray-700 hover:border-indigo-500">
                    Virtuvė
                </button>
                <button wire:click="addRoomPreset('living')" class="px-3 py-1 text-xs rounded-full bg-gray-900 border border-gray-700 hover:border-indigo-500">
                    Svetainė
                </button>
                <button wire:click="addRoomPreset('bedroom')" class="px-3 py-1 text-xs rounded-full bg-gray-900 border border-gray-700 hover:border-indigo-500">
                    Miegamasis
                </button>
                <button wire:click="addRoomPreset('bathroom')" class="px-3 py-1 text-xs rounded-full bg-gray-900 border border-gray-700 hover:border-indigo-500">
                    Vonios kambarys
                </button>
                <button wire:click="addRoomPreset('corridor')" class="px-3 py-1 text-xs rounded-full bg-gray-900 border border-gray-700 hover:border-indigo-500">
                    Koridorius
                </button>
                <button wire:click="addRoomPreset('garage')" class="px-3 py-1 text-xs rounded-full bg-gray-900 border border-gray-700 hover:border-indigo-500">
                    Garažas
                </button>
            </div>
        </div>

        <div class="flex gap-2 mt-2">
            <input type="text"
                   wire:model.defer="newRoomName"
                   class="flex-1 rounded-lg bg-gray-900 border border-gray-700 text-sm text-gray-100 px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500"
                   placeholder="Kitas kambarys, pvz.: Vaikų kambarys, Ofisas...">
            <button wire:click="addRoom"
                    class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm hover:bg-indigo-700">
                + Pridėti
            </button>
        </div>
    </div>

    {{-- KAMBARIAI SU SĄMATA --}}
    @php
        $projectTotal = 0;
    @endphp

    @if ($this->rooms->isEmpty())
        <div class="bg-gray-800 border border-gray-700 rounded-xl p-6 text-sm text-gray-400">
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

                <div class="bg-gray-800 border border-gray-700 shadow rounded-xl p-5 space-y-4"
                     wire:key="room-{{ $room->id }}">
                    <div class="flex items-center justify-between gap-2">
                        <h3 class="text-base font-semibold text-white">
                            {{ $room->name }}
                        </h3>

                        <div class="flex items-center gap-3 text-xs text-gray-400">
                            <span>Kambario suma: <span class="text-gray-100 font-semibold">{{ number_format($roomTotal, 2, ',', ' ') }} €</span></span>

                            <button wire:click="deleteRoom({{ $room->id }})"
                                    class="text-red-400 hover:text-red-300">
                                Šalinti kambarį
                            </button>
                        </div>
                    </div>

                    {{-- Sąmatos eilutės --}}
                    @if ($room->items->isEmpty())
                        <p class="text-xs text-gray-400">
                            Šiame kambaryje dar nėra pozicijų. Pridėk žemiau.
                        </p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-xs text-gray-200">
                                <thead>
                                    <tr class="border-b border-gray-700 text-left text-gray-400 uppercase">
                                        <th class="py-2">Pozicija</th>
                                        <th class="py-2 w-20">Kiekis</th>
                                        <th class="py-2 w-16">Vnt</th>
                                        <th class="py-2 w-24 text-right">Kaina/vnt</th>
                                        <th class="py-2 w-24 text-right">Suma</th>
                                        <th class="py-2 w-16"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-700">
                                    @foreach ($room->items as $item)
                                        @php
                                            $lineTotal = ($item->quantity ?? 0) * ($item->unit_price ?? 0);
                                        @endphp
                                        <tr>
                                            <td class="py-1 pr-2 text-gray-100">
                                                {{ $item->name }}
                                            </td>
                                            <td class="py-1 pr-2">
                                                {{ $item->quantity ?? '—' }}
                                            </td>
                                            <td class="py-1 pr-2">
                                                {{ $item->unit ?? '—' }}
                                            </td>
                                            <td class="py-1 pr-2 text-right">
                                                {{ $item->unit_price !== null ? number_format($item->unit_price, 2, ',', ' ') . ' €' : '—' }}
                                            </td>
                                            <td class="py-1 pr-2 text-right">
                                                {{ number_format($lineTotal, 2, ',', ' ') }} €
                                            </td>
                                            <td class="py-1 text-right">
                                                <button wire:click="deleteRoomItem({{ $item->id }})"
                                                        class="text-red-400 hover:text-red-300">
                                                    x
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    {{-- Naujos eilutės forma --}}
                    <div class="mt-3 border-t border-gray-700 pt-3">
                        <div class="text-xs text-gray-400 mb-2">
                            Nauja pozicija kambaryje: {{ $room->name }}
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-5 gap-2 text-xs">
                            {{-- 1) Paieška kataloge --}}
                            <div class="relative md:col-span-2">
                               <input type="text"
       wire:model.live="searchCatalog.{{ $room->id }}"
       class="w-full rounded-lg bg-gray-900 border border-gray-700 px-2 py-1 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500"
       placeholder="Ieškoti darbo kataloge (pavadinimas ar kodas)...">

                                {{-- DEBUG: rodome, kas įvesta ir kiek rasta --}}
                                @if(strlen($search) > 0)
                                    <div class="mt-1 text-[10px] text-gray-500">
                                        Paieška: "{{ $search }}", rasta: {{ $catalogResults->count() }} pozicijų
                                    </div>
                                @endif

                                {{-- Dropdown'as su rezultatais --}}
                                @if ($catalogResults->isNotEmpty())
                                    <div class="absolute z-20 mt-1 w-full bg-gray-900 border border-gray-700 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                        @foreach ($catalogResults as $catalogItem)
                                            <button type="button"
                                                    wire:click="selectCatalogItem({{ $room->id }}, {{ $catalogItem->id }})"
                                                    class="w-full text-left px-3 py-1.5 hover:bg-gray-800">
                                                <div class="flex justify-between items-center">
                                                    <span class="text-xs text-gray-100">
                                                        {{ $catalogItem->name }}
                                                    </span>
                                                    <span class="text-[10px] text-gray-400 ml-2">
                                                        {{ $catalogItem->code }}
                                                    </span>
                                                </div>
                                                <div class="text-[10px] text-gray-500">
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
                            <div>
                                <input type="text"
                                       wire:model.defer="itemName.{{ $room->id }}"
                                       class="w-full rounded-lg bg-gray-900 border border-gray-700 px-2 py-1 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="Pozicijos pavadinimas">
                            </div>

                            {{-- 3) Kiekis --}}
                            <div>
                                <input type="number" step="0.01"
                                       wire:model.defer="itemQty.{{ $room->id }}"
                                       class="w-full rounded-lg bg-gray-900 border border-gray-700 px-2 py-1 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="Kiekis">
                            </div>

                            {{-- 4) Vnt --}}
                            <div>
                                <input type="text"
                                       wire:model.defer="itemUnit.{{ $room->id }}"
                                       class="w-full rounded-lg bg-gray-900 border border-gray-700 px-2 py-1 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="vnt, m...">
                            </div>

                            {{-- 5) Kaina + pridėti --}}
                            <div class="flex gap-2">
                                <input type="number" step="0.01"
                                       wire:model.defer="itemUnitPrice.{{ $room->id }}"
                                       class="w-full rounded-lg bg-gray-900 border border-gray-700 px-2 py-1 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="Kaina/vnt">
                                <button wire:click="addRoomItem({{ $room->id }})"
                                        class="px-3 py-1 rounded-lg bg-indigo-600 text-white text-xs hover:bg-indigo-700 whitespace-nowrap">
                                    + Pridėti
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

        {{-- BENDRA PROJEKTO SUMA --}}
        <div class="bg-gray-900 border border-gray-700 rounded-xl p-4 flex items-center justify-between mt-2">
            <span class="text-sm text-gray-400">
                Bendra projekto suma
            </span>
            <span class="text-lg font-semibold text-white">
                {{ number_format($projectTotal, 2, ',', ' ') }} €
            </span>
        </div>
    @endif

</div>
