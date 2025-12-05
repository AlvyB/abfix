<?php

use Livewire\Volt\Component;

new class extends Component {
    public string $theme = 'light';
    public string $language = 'lt';
    public string $dateFormat = 'Y-m-d';
    public string $numberFormat = '1 234,56';

    public function save(): void
    {
        session()->flash('status', 'Sistemos nustatymai išsaugoti (demonstracinis veiksmas).');
    }
};

?>

<div class="max-w-8xl mx-auto py-8 space-y-6 text-gray-900 dark:text-gray-100">
    <x-page.header
        title="Sistema ir tema"
        description="Bendri temos, kalbos ir formatų nustatymai"
        :breadcrumbs="[['label' => 'Nustatymai', 'href' => route('profile.edit')], ['label' => 'Sistema ir tema']]"
    />

    <div class="bg-gray-800 border border-gray-700 rounded-xl shadow p-6 space-y-4">
        @if (session('status'))
            <div class="rounded-lg bg-emerald-900/50 border border-emerald-700 text-emerald-100 px-3 py-2 text-sm">
                {{ session('status') }}
            </div>
        @endif

        <form wire:submit.prevent="save" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-300 mb-1">Tema</label>
                    <select wire:model="theme" class="w-full rounded-lg bg-gray-900 border border-gray-700 px-3 py-2 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="light">Šviesi</option>
                        <option value="dark">Tamsi</option>
                        <option value="auto">Automatinė</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-300 mb-1">Kalba</label>
                    <select wire:model="language" class="w-full rounded-lg bg-gray-900 border border-gray-700 px-3 py-2 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="lt">LT</option>
                        <option value="en">EN</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-300 mb-1">Datos formatas</label>
                    <select wire:model="dateFormat" class="w-full rounded-lg bg-gray-900 border border-gray-700 px-3 py-2 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="Y-m-d">YYYY-MM-DD</option>
                        <option value="d.m.Y">DD.MM.YYYY</option>
                        <option value="Y.m.d">YYYY.MM.DD</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-300 mb-1">Skaičių formatas</label>
                    <select wire:model="numberFormat" class="w-full rounded-lg bg-gray-900 border border-gray-700 px-3 py-2 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="1 234,56">1 234,56</option>
                        <option value="1,234.56">1,234.56</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2">
                <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm">Išsaugoti</button>
            </div>
        </form>
    </div>
</div>
