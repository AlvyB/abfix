<?php

namespace Database\Seeders;

use App\Models\RoomPreset;
use Illuminate\Database\Seeder;

class RoomPresetSeeder extends Seeder
{
    public function run(): void
    {
        $presets = [
            ['name' => 'Virtuvė',          'slug' => 'kitchen',   'sort_order' => 1],
            ['name' => 'Svetainė',         'slug' => 'living',    'sort_order' => 2],
            ['name' => 'Miegamasis',       'slug' => 'bedroom',   'sort_order' => 3],
            ['name' => 'Vonios kambarys',  'slug' => 'bathroom',  'sort_order' => 4],
            ['name' => 'Koridorius',       'slug' => 'corridor',  'sort_order' => 5],
            ['name' => 'Garažas',          'slug' => 'garage',    'sort_order' => 6],
        ];

        foreach ($presets as $preset) {
            RoomPreset::updateOrCreate(['slug' => $preset['slug']], $preset);
        }
    }
}
