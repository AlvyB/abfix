<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\RoomPresetSeeder;
use Database\Seeders\ProjectsDemoSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::firstOrCreate(
            ['email' => 'budrys.alvydas@gmail.com'],
            [
                'name' => 'Alvydas',
                'password' => 'Hipotermija1',
                'email_verified_at' => now(),
            ]
        );

        $this->call([
            RoomPresetSeeder::class,
            ProjectsDemoSeeder::class,
            CompanySettingsSeeder::class,
            WorkCatalogSeeder::class,
            ProjectsUpdateSeeder::class,
        ]);
    }
}
