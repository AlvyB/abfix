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

    /** @var \Livewire\Features\SupportFileUploads\TemporaryUploadedFile|null */
    public $logo = null;

    public bool $removeExistingLogo = false;

    public function mount(): void
    {
        $userId = Auth::id();

        // Svarbu: ne "first()", o tik pagal current user.
        $this->setting = CompanySetting::query()
            ->where('user_id', $userId)
            ->first() ?? new CompanySetting(['user_id' => $userId]);

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

    protected function rules(): array
    {
        $rules = [
            'legalType' => 'required|in:company,individual,person',

            // name – realiai visada reikalingas (įmonė / IV / fizinis)
            'name'      => 'required|string|max:255',

            'address'   => 'nullable|string|max:255',
            'city'      => 'nullable|string|max:255',
            'postal'    => 'nullable|string|max:50',
            'phone'     => 'nullable|string|max:50',
            'email'     => 'nullable|email|max:255',
            'director'  => 'nullable|string|max:255',

            'logo'      => 'nullable|image|max:2048',
        ];

        // code / vat priklauso nuo juridinio tipo
        if (in_array($this->legalType, ['company', 'individual'], true)) {
            $rules['code'] = 'required|string|max:255';
            $rules['vat']  = 'nullable|string|max:255';
        } else { // person
            $rules['code'] = 'nullable|string|max:255';
            $rules['vat']  = 'nullable|string|max:255';
            $rules['director'] = 'nullable|string|max:255';
        }

        return $rules;
    }

    public function save(): void
    {
        $validated = $this->validate($this->rules());

        $data = [
            'user_id'    => Auth::id(),
            'legal_type' => $validated['legalType'],
            'name'       => $validated['name'],
            'code'       => $validated['code'] ?? null,
            'vat'        => $validated['vat'] ?? null,
            'address'    => $validated['address'] ?? null,
            'city'       => $validated['city'] ?? null,
            'postal'     => $validated['postal'] ?? null,
            'phone'      => $validated['phone'] ?? null,
            'email'      => $validated['email'] ?? null,
            'director'   => $validated['director'] ?? null,
        ];

        // Remove existing logo (if requested)
        if ($this->removeExistingLogo && $this->setting && $this->setting->logo_path) {
            Storage::disk('public')->delete($this->setting->logo_path);
            $data['logo_path'] = null;
        }

        // Upload new logo
        if ($this->logo) {
            // Jei buvo senas logo – ištrinam, kad neliktų šiukšlių
            if ($this->setting && $this->setting->logo_path) {
                Storage::disk('public')->delete($this->setting->logo_path);
            }

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
        session()->forget('status');
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

                @error('legalType') <p class="text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4" wire:key="legal-block-{{ $legalType }}">
                @if($legalType === 'person')
                    <div class="md:col-span-2">
                        <label class="block text-sm text-gray-300 mb-1">Vardas, pavardė</label>
                        <input type="text" wire:model.defer="name" class="w-full rounded-lg bg-gray-900 border border-gray-700 px-3 py-2 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                        @error('name') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>
                @elseif($legalType === 'individual')
                    <div>
                        <label class="block text-sm text-gray-300 mb-1">Veiklos pavadinimas</label>
                        <input type="text" wire:model.defer="name" class="w-full rounded-lg bg-gray-900 border border-gray-700 px-3 py-2 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                        @error('name') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm text-gray-300 mb-1">Asmens / veiklos kodas</label>
                        <input type="text" wire:model.defer="code" class="w-full rounded-lg bg-gray-900 border border-gray-700 px-3 py-2 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                        @error('code') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm text-gray-300 mb-1">PVM kodas (neprivaloma)</label>
                        <input type="text" wire:model.defer="vat" class="w-full rounded-lg bg-gray-900 border border-gray-700 px-3 py-2 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Jei netaikoma, palik tuščią">
                        @error('vat') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>
                @else
                    <div>
                        <label class="block text-sm text-gray-300 mb-1">Įmonės pavadinimas</label>
                        <input type="text" wire:model.defer="name" class="w-full rounded-lg bg-gray-900 border border-gray-700 px-3 py-2 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                        @error('name') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm text-gray-300 mb-1">Įmonės kodas</label>
                        <input type="text" wire:model.defer="code" class="w-full rounded-lg bg-gray-900 border border-gray-700 px-3 py-2 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                        @error('code') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm text-gray-300 mb-1">PVM mokėtojo kodas</label>
                        <input type="text" wire:model.defer="vat" class="w-full rounded-lg bg-gray-900 border border-gray-700 px-3 py-2 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Jei netaikoma, palik tuščią">
                        @error('vat') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm text-gray-300 mb-1">Vadovo vardas ir pavardė</label>
                        <input type="text" wire:model.defer="director" class="w-full rounded-lg bg-gray-900 border border-gray-700 px-3 py-2 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                        @error('director') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>
                @endif

                <div>
                    <label class="block text-sm text-gray-300 mb-1">Adresas</label>
                    <input type="text" wire:model.defer="address" class="w-full rounded-lg bg-gray-900 border border-gray-700 px-3 py-2 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                    @error('address') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-300 mb-1">Miestas</label>
                    <input type="text" wire:model.defer="city" class="w-full rounded-lg bg-gray-900 border border-gray-700 px-3 py-2 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                    @error('city') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-300 mb-1">Pašto kodas</label>
                    <input type="text" wire:model.defer="postal" class="w-full rounded-lg bg-gray-900 border border-gray-700 px-3 py-2 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                    @error('postal') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-300 mb-1">Telefonas</label>
                    <input type="text" wire:model.defer="phone" class="w-full rounded-lg bg-gray-900 border border-gray-700 px-3 py-2 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                    @error('phone') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-300 mb-1">El. paštas</label>
                    <input type="email" wire:model.defer="email" class="w-full rounded-lg bg-gray-900 border border-gray-700 px-3 py-2 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                    @error('email') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm text-gray-300 mb-1">Logotipas</label>
                <div class="space-y-2">
                    <input id="logo-upload" type="file" wire:model.live="logo" class="sr-only">

                    <div class="flex flex-wrap items-center gap-3">
                        @if(! $logo)
                            <label for="logo-upload" class="inline-flex h-14 w-14 items-center justify-center rounded-lg border border-dashed border-gray-700 bg-gray-900 text-gray-200 cursor-pointer hover:border-indigo-400 hover:bg-gray-800">
                                <svg class="w-6 h-6 text-indigo-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M4 17.5v-11a1.5 1.5 0 0 1 1.5-1.5h6.586a1.5 1.5 0 0 1 1.06.44l4.914 4.914A1.5 1.5 0 0 1 18.5 11.5V17a1.5 1.5 0 0 1-1.5 1.5H5.5A1.5 1.5 0 0 1 4 17z"></path>
                                    <path d="M13 5v3.5a1 1 0 0 0 1 1H17"></path>
                                    <path d="M9.5 14.5 11 16l3.5-3.5"></path>
                                </svg>
                                <span class="sr-only">Pasirinkti logotipą</span>
                            </label>
                        @endif

                        @if($logo)
                            <img src="{{ $logo->temporaryUrl() }}" alt="Pasirinktas logotipas" class="h-14 w-14 rounded border border-gray-700 object-contain bg-gray-900">
                            <button type="button" wire:click="removeLogo" class="text-sm text-red-400 hover:text-red-300">Pašalinti</button>
                        @elseif($setting && $setting->logo_path && ! $removeExistingLogo)
                            <img src="{{ Storage::disk('public')->url($setting->logo_path) }}" alt="Esamas logotipas" class="h-14 w-14 rounded border border-gray-700 object-contain bg-gray-900">
                            <button type="button" wire:click="removeLogo" class="text-sm text-red-400 hover:text-red-300">Pašalinti</button>
                        @else
                            <span class="text-xs text-gray-500">Logotipas nepasirinktas</span>
                        @endif
                    </div>

                    @error('logo') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror

                    <p class="text-xs text-gray-500">
                        Priimami paveikslėliai iki 2 MB. Jei nepildai, paliekamas senas logotipas.
                    </p>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2">
                <button type="button" wire:click="cancel" class="px-4 py-2 rounded-lg border border-gray-700 text-gray-200 text-sm hover:border-gray-500">
                    Atšaukti
                </button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm">
                    Išsaugoti
                </button>
            </div>
        </form>
    </div>
</div>
