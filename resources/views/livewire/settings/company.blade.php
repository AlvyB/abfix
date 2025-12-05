<?php

use Livewire\Volt\Component;
use App\Models\CompanySetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public ?CompanySetting $setting = null;
    public string $name = '';
    public string $code = '';
    public string $vat = '';
    public string $address = '';
    public string $city = '';
    public string $postal = '';
    public string $phone = '';
    public string $email = '';
    public string $legalType = 'company';
    public string $director = '';
    public $logo;
    public bool $removeExistingLogo = false;

    public function mount(): void
    {
        $this->setting = CompanySetting::firstOrNew(['user_id' => Auth::id()]);
        $this->fillFromSetting();
    }

    protected function fillFromSetting(): void
    {
        $this->legalType = $this->setting->legal_type ?? 'company';
        $this->name      = $this->setting->name ?? '';
        $this->code      = $this->setting->code ?? '';
        $this->vat       = $this->setting->vat ?? '';
        $this->address   = $this->setting->address ?? '';
        $this->city      = $this->setting->city ?? '';
        $this->postal    = $this->setting->postal ?? '';
        $this->phone     = $this->setting->phone ?? '';
        $this->email     = $this->setting->email ?? '';
        $this->director  = $this->setting->director ?? '';
        $this->logo      = null;
        $this->removeExistingLogo = false;
    }

    public function updatedLogo(): void
    {
        $this->removeExistingLogo = false;
    }

    public function save(): void
    {
        $validated = $this->validate([
            'legalType' => 'required|in:company,individual,person',
            'name'      => 'nullable|string|max:255',
            'code'      => 'nullable|string|max:255',
            'vat'       => 'nullable|string|max:255',
            'address'   => 'nullable|string|max:255',
            'city'      => 'nullable|string|max:255',
            'postal'    => 'nullable|string|max:50',
            'phone'     => 'nullable|string|max:50',
            'email'     => 'nullable|email|max:255',
            'director'  => 'nullable|string|max:255',
            'logo'      => 'nullable|image|max:2048',
        ]);

        $data = [
            'legal_type' => $validated['legalType'],
            'name'       => $validated['name'] ?? null,
            'code'       => $validated['code'] ?? null,
            'vat'        => $validated['vat'] ?? null,
            'address'    => $validated['address'] ?? null,
            'city'       => $validated['city'] ?? null,
            'postal'     => $validated['postal'] ?? null,
            'phone'      => $validated['phone'] ?? null,
            'email'      => $validated['email'] ?? null,
            'director'   => $validated['director'] ?? null,
            'user_id'    => Auth::id(),
        ];

        if ($this->removeExistingLogo && $this->setting && $this->setting->logo_path) {
            Storage::disk('public')->delete($this->setting->logo_path);
            $data['logo_path'] = null;
        }

        if ($this->logo) {
            $path = $this->logo->store('logos', 'public');
            $data['logo_path'] = $path;
            $this->removeExistingLogo = false;
        }

        if ($this->setting && $this->setting->exists) {
            $this->setting->update($data);
        } else {
            $this->setting = CompanySetting::create($data);
        }

        session()->flash('status', 'Įmonės duomenys išsaugoti.');
    }

    public function cancel(): void
    {
        $this->fillFromSetting();
    }

    public function removeLogo(): void
    {
        $this->logo = null;
        $this->removeExistingLogo = true;
    }
};

?>

<div class="max-w-8xl mx-auto py-8 space-y-6 text-gray-900 dark:text-gray-100">
    <x-page.header
        title="Įmonės duomenys"
        description="Pagrindinė informacija sąmatoms ir pasiūlymams"
        :breadcrumbs="[['label' => 'Nustatymai', 'href' => route('profile.edit')], ['label' => 'Įmonės duomenys']]"
    />

    <div class="bg-gray-800 border border-gray-700 rounded-xl shadow p-6 space-y-4">
        @if (session('status'))
            <div class="rounded-lg bg-emerald-900/50 border border-emerald-700 text-emerald-100 px-3 py-2 text-sm">
                {{ session('status') }}
            </div>
        @endif

        <form wire:submit.prevent="save" class="space-y-4">
            <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-4 space-y-2 bg-gray-100 dark:bg-gray-800/40">
                <p class="text-sm text-gray-800 dark:text-gray-300 font-semibold">Juridinis statusas</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm text-gray-900 dark:text-gray-100">
                    <label class="flex items-center gap-2 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 hover:border-indigo-500 transition">
                        <input type="radio" wire:model.live="legalType" value="company" class="text-indigo-500">
                        <span>Įmonė (UAB/MB/IĮ ir pan.)</span>
                    </label>
                    <label class="flex items-center gap-2 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 hover:border-indigo-500 transition">
                        <input type="radio" wire:model.live="legalType" value="individual" class="text-indigo-500">
                        <span>Individuali veikla</span>
                    </label>
                    <label class="flex items-center gap-2 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 hover:border-indigo-500 transition">
                        <input type="radio" wire:model.live="legalType" value="person" class="text-indigo-500">
                        <span>Fizinis asmuo (be veiklos)</span>
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4" wire:key="legal-block-{{ $legalType }}">
                @if($legalType === 'person')
                    <div class="md:col-span-2">
                        <label class="block text-sm text-gray-300 mb-1">Vardas, pavardė</label>
                        <input type="text" wire:model.defer="name" class="w-full rounded-lg bg-gray-900 border border-gray-700 px-3 py-2 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                @elseif($legalType === 'individual')
                    <div>
                        <label class="block text-sm text-gray-300 mb-1">Veiklos pavadinimas</label>
                        <input type="text" wire:model.defer="name" class="w-full rounded-lg bg-gray-900 border border-gray-700 px-3 py-2 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-300 mb-1">Asmens / veiklos kodas</label>
                        <input type="text" wire:model.defer="code" class="w-full rounded-lg bg-gray-900 border border-gray-700 px-3 py-2 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-300 mb-1">PVM kodas (neprivaloma)</label>
                        <input type="text" wire:model.defer="vat" class="w-full rounded-lg bg-gray-900 border border-gray-700 px-3 py-2 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Jei netaikoma, palik tuščią">
                    </div>
                @else
                    <div>
                        <label class="block text-sm text-gray-300 mb-1">Įmonės pavadinimas</label>
                        <input type="text" wire:model.defer="name" class="w-full rounded-lg bg-gray-900 border border-gray-700 px-3 py-2 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-300 mb-1">Įmonės kodas</label>
                        <input type="text" wire:model.defer="code" class="w-full rounded-lg bg-gray-900 border border-gray-700 px-3 py-2 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-300 mb-1">PVM mokėtojo kodas</label>
                        <input type="text" wire:model.defer="vat" class="w-full rounded-lg bg-gray-900 border border-gray-700 px-3 py-2 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Jei netaikoma, palik tuščią">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-300 mb-1">Vadovo vardas ir pavardė</label>
                        <input type="text" wire:model.defer="director" class="w-full rounded-lg bg-gray-900 border border-gray-700 px-3 py-2 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                @endif
                <div>
                    <label class="block text-sm text-gray-300 mb-1">Adresas</label>
                    <input type="text" wire:model.defer="address" class="w-full rounded-lg bg-gray-900 border border-gray-700 px-3 py-2 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm text-gray-300 mb-1">Miestas</label>
                    <input type="text" wire:model.defer="city" class="w-full rounded-lg bg-gray-900 border border-gray-700 px-3 py-2 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm text-gray-300 mb-1">Pašto kodas</label>
                    <input type="text" wire:model.defer="postal" class="w-full rounded-lg bg-gray-900 border border-gray-700 px-3 py-2 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm text-gray-300 mb-1">Telefonas</label>
                    <input type="text" wire:model.defer="phone" class="w-full rounded-lg bg-gray-900 border border-gray-700 px-3 py-2 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm text-gray-300 mb-1">El. paštas</label>
                    <input type="email" wire:model.defer="email" class="w-full rounded-lg bg-gray-900 border border-gray-700 px-3 py-2 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>

            <div>
                <label class="block text-sm text-gray-300 mb-1">Logotipas</label>
                <div class="space-y-2">
                    @if($logo)
                        <div class="flex items-center gap-3">
                            <img src="{{ $logo->temporaryUrl() }}" alt="Logo preview" class="h-12 rounded border border-gray-700">
                            <button type="button" wire:click="removeLogo" class="text-sm text-red-400 hover:text-red-300">Pašalinti</button>
                        </div>
                    @elseif($setting && $setting->logo_path && ! $removeExistingLogo)
                        <div class="flex items-center gap-3">
                            <span class="text-xs text-gray-400">Dabartinis: {{ $setting->logo_path }}</span>
                            <button type="button" wire:click="removeLogo" class="text-sm text-red-400 hover:text-red-300">Pašalinti</button>
                        </div>
                    @endif
                    <input type="file" wire:model="logo" class="w-full text-sm text-gray-200">
                    <p class="text-xs text-gray-500">Priimami paveikslėliai iki 2 MB. Jei nepildai, paliekamas senas logotipas.</p>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2">
                <button type="button" wire:click="cancel" class="px-4 py-2 rounded-lg border border-gray-700 text-gray-200 text-sm hover:border-gray-500">Atšaukti</button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm">Išsaugoti</button>
            </div>
        </form>
    </div>
</div>
