<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Models\Project;
use App\Http\Controllers\ProjectPdfController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->prefix('app')->group(function () {

    // projektų sąrašas
    Volt::route('/projects', 'app.projects.index')
        ->name('app.projects');

    // naujo projekto kūrimas
    Volt::route('/projects/create', 'app.projects.create')
        ->name('app.projects.create');

    // projekto darbų peržiūra/žymėjimas
    Volt::route('/projects/{project}/tasks', 'app.projects.tasks')
        ->name('app.projects.tasks');

    // eksporto PDF
    Route::get('/projects/{project}/pdf', ProjectPdfController::class)
        ->name('app.projects.pdf');

    // projekto sąmata/redagavimas
    Volt::route('/projects/{project}/edit', 'app.projects.edit')
        ->name('app.projects.edit');

    // senas adresas -> nukreipiam į /edit
    Route::get('/projects/{project}', function (Project $project) {
        return redirect()->route('app.projects.edit', $project);
    })->name('app.projects.show');

});

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');
    Volt::route('settings/company', 'settings.company')->name('settings.company');
    Volt::route('settings/pdf', 'settings.pdf')->name('settings.pdf');
    Volt::route('settings/system', 'settings.system')->name('settings.system');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});
