<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use Illuminate\Support\Str;

class ProjectsUpdateSeeder extends Seeder
{
    public function run(): void
    {
        $projects = Project::orderBy('id')->get();

        foreach ($projects as $index => $project) {
            $project->update([
                'due_date' => $project->due_date ?: now()->addDays($index + 7)->toDateString(),
                'contact_phone' => $project->contact_phone ?: '+3706' . str_pad((string) ($index + 1000), 6, '0', STR_PAD_LEFT),
                'contact_email' => $project->contact_email ?: 'kontaktas' . ($index + 1) . '@demo.lt',
                'notes' => $project->notes ?: 'Demo projekto pastabos',
            ]);
        }
    }
}
