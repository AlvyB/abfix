<?php

use Livewire\Volt\Component;

new class extends Component {
    public string $defaultType = 'full';
    public bool $showRoomTotals = true;
    public bool $showProjectTotal = true;
    public bool $showNotes = true;
    public bool $showContacts = true;
    public string $filenamePattern = '{{projekto_pavadinimas}}_{{data}}.pdf';

    public function save(): void
    {
        session()->flash('status', 'PDF šablonų nustatymai išsaugoti (demonstracinis veiksmas).');
    }
};

?>

<div class="max-w-8xl mx-auto py-8 space-y-6 text-gray-900 dark:text-gray-100">
    <x-page.header
        title="PDF & sąmatų šablonai"
        description="Numatyti PDF formatai ir rodoma informacija"
        :breadcrumbs="[['label' => 'Nustatymai', 'href' => route('profile.edit')], ['label' => 'PDF šablonai']]"
    />

    <div class="bg-gray-800 border border-gray-700 rounded-xl shadow p-6 space-y-4">
        @if (session('status'))
            <div class="rounded-lg bg-emerald-900/50 border border-emerald-700 text-emerald-100 px-3 py-2 text-sm">
                {{ session('status') }}
            </div>
        @endif

        <form wire:submit.prevent="save" class="space-y-5">
            <div>
                <p class="text-sm text-gray-300 font-semibold mb-2">Numatytas PDF tipas</p>
                <div class="space-y-2 text-sm text-gray-100">
                    <label class="flex items-start gap-2">
                        <input type="radio" value="full" wire:model="defaultType" class="mt-1 text-indigo-500">
                        <div>
                            <div class="font-semibold">Pilna versija</div>
                            <div class="text-gray-400">Visos pozicijos, kiekiai, vienetai, kainos.</div>
                        </div>
                    </label>
                    <label class="flex items-start gap-2">
                        <input type="radio" value="summary" wire:model="defaultType" class="mt-1 text-indigo-500">
                        <div>
                            <div class="font-semibold">Sutrumpinta</div>
                            <div class="text-gray-400">Kambariai + kiekvieno suma + bendra suma.</div>
                        </div>
                    </label>
                    <label class="flex items-start gap-2">
                        <input type="radio" value="offer" wire:model="defaultType" class="mt-1 text-indigo-500">
                        <div>
                            <div class="font-semibold">Kliento pasiūlymas</div>
                            <div class="text-gray-400">Logotipas, kontaktai, terminas ir bendra kaina.</div>
                        </div>
                    </label>
                </div>
            </div>

            <div class="space-y-2">
                <p class="text-sm text-gray-300 font-semibold">Rodyti PDF</p>
                <label class="flex items-center gap-2 text-sm text-gray-100">
                    <input type="checkbox" wire:model="showRoomTotals" class="text-indigo-500"> Kambarių sumas
                </label>
                <label class="flex items-center gap-2 text-sm text-gray-100">
                    <input type="checkbox" wire:model="showProjectTotal" class="text-indigo-500"> Bendrą projekto sumą
                </label>
                <label class="flex items-center gap-2 text-sm text-gray-100">
                    <input type="checkbox" wire:model="showNotes" class="text-indigo-500"> Pastabų bloką
                </label>
                <label class="flex items-center gap-2 text-sm text-gray-100">
                    <input type="checkbox" wire:model="showContacts" class="text-indigo-500"> Kontaktinę informaciją apačioje
                </label>
            </div>

            <div class="space-y-2">
                <p class="text-sm text-gray-300 font-semibold">Numatytas PDF pavadinimo formatas</p>
                <input type="text"
                       wire:model.defer="filenamePattern"
                       class="w-full rounded-lg bg-gray-900 border border-gray-700 px-3 py-2 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                <p class="text-xs text-gray-400">Pvz.: {{ '{{projekto_pavadinimas}}' }}_{{ '{{data}}' }}.pdf</p>
                <p class="text-xs text-gray-500">Galimi kintamieji: {{ '{{projekto_pavadinimas}}' }}, {{ '{{kliento_vardas}}' }}, {{ '{{data}}' }}, {{ '{{projekto_id}}' }}</p>
            </div>

            <div class="flex items-center justify-end gap-2">
                <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm">Išsaugoti</button>
            </div>
        </form>
    </div>
</div>
