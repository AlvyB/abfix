<?php

use Livewire\Volt\Component;

new class extends Component {

    public string $theme = 'system';

    public function mount(): void
    {
        $this->theme = auth()->user()->theme ?? 'system';
    }

    public function updatedTheme(): void
    {
        $this->saveTheme();
    }

    public function saveTheme(): void
    {
        $allowed = ['light', 'dark', 'system'];
        if (! in_array($this->theme, $allowed, true)) {
            $this->theme = 'system';
        }

        auth()->user()->forceFill(['theme' => $this->theme])->save();

        $this->dispatch('theme-changed', theme: $this->theme);
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout heading="Tema" subheading="Pasirink šviesią, tamsią arba sisteminę temą šiai paskyrai">
        <flux:radio.group variant="segmented" wire:model.live="theme" wire:change="saveTheme">
            <flux:radio value="light" icon="sun">Šviesi</flux:radio>
            <flux:radio value="dark" icon="moon">Tamsi</flux:radio>
            <flux:radio value="system" icon="computer-desktop">Sistema</flux:radio>
        </flux:radio.group>
    </x-settings.layout>
</section>

<script>
(() => {
    const current = @json($theme);
    const media = window.matchMedia('(prefers-color-scheme: dark)');
    if (current === 'light' || current === 'dark') {
        localStorage.setItem('color-theme', current);
    } else {
        localStorage.removeItem('color-theme');
    }
    const isDark = current === 'dark' || (current === 'system' && media.matches);
    document.documentElement.classList[isDark ? 'add' : 'remove']('dark');
})();

document.addEventListener('livewire:init', () => {
    Livewire.on('theme-changed', (payload) => {
        const theme = payload?.theme ?? payload;
        if (theme === 'light' || theme === 'dark') {
            localStorage.setItem('color-theme', theme);
        } else {
            localStorage.removeItem('color-theme');
        }
    });
});
</script>
