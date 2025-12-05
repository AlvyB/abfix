<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Room;
use App\Models\RoomItem;
use Illuminate\Database\Seeder;

class ProjectsDemoSeeder extends Seeder
{
    public function run(): void
    {
        $userId = 1; // pritaikyk jei reikia kito userio

        $baseItems = [
            ['name' => 'Dėžučių montavimas', 'quantity' => 4, 'unit' => 'vnt', 'unit_price' => 7.00],
            ['name' => 'Rozečių montavimas', 'quantity' => 6, 'unit' => 'vnt', 'unit_price' => 11.00],
            ['name' => 'Jungiklių montavimas', 'quantity' => 4, 'unit' => 'vnt', 'unit_price' => 8.50],
            ['name' => 'Šviestuvai LED', 'quantity' => 4, 'unit' => 'vnt', 'unit_price' => 55.00],
            ['name' => 'Sieniniai šviestuvai', 'quantity' => 2, 'unit' => 'vnt', 'unit_price' => 65.00],
            ['name' => 'Lubinis šviestuvas', 'quantity' => 1, 'unit' => 'vnt', 'unit_price' => 90.00],
            ['name' => 'Kabelių trasa', 'quantity' => 10, 'unit' => 'm', 'unit_price' => 8.50],
            ['name' => 'TV zona kabelių trasa', 'quantity' => 1, 'unit' => 'vnt', 'unit_price' => 40.00],
            ['name' => 'LED juosta', 'quantity' => 5, 'unit' => 'm', 'unit_price' => 10.00],
            ['name' => 'Projektoriaus maitinimas', 'quantity' => 1, 'unit' => 'vnt', 'unit_price' => 60.00],
        ];

        $projects = [
            [
                'title' => 'Butas Savanorių pr. 12',
                'client_name' => 'UAB Elektra',
                'address' => 'Savanorių pr. 12, Vilnius',
                'due_date' => '2026-01-15',
                'contact_phone' => '+37061200001',
                'contact_email' => 'info@elektra.lt',
                'notes' => 'Virtuvės ir svetainės elektros taškai',
                'status' => 'approved',
                'rooms' => [
                    ['name' => 'Virtuvė'],
                    ['name' => 'Svetainė'],
                    ['name' => 'Miegamasis'],
                ],
            ],
            [
                'title' => 'Individualus namas Bijūnų g. 5',
                'client_name' => 'MB Statyba',
                'address' => 'Bijūnų g. 5, Kaunas',
                'due_date' => '2026-02-10',
                'contact_phone' => '+37061200002',
                'contact_email' => 'projektai@statyba.lt',
                'notes' => 'Vidaus elektros instaliacija',
                'status' => 'sent',
                'rooms' => [
                    ['name' => 'Garažas'],
                    ['name' => 'Koridorius'],
                    ['name' => 'Virtuvė'],
                ],
            ],
            [
                'title' => 'Biuras Technopolis',
                'client_name' => 'UAB TechOffice',
                'address' => 'J. Balčikonio g. 7, Vilnius',
                'due_date' => '2026-03-01',
                'contact_phone' => '+37061200003',
                'contact_email' => 'it@techoffice.lt',
                'notes' => 'Open space apšvietimas ir darbo vietos',
                'status' => 'approved',
                'rooms' => [
                    ['name' => 'Open space'],
                    ['name' => 'Posėdžių kambarys'],
                    ['name' => 'Priimamasis'],
                ],
            ],
            [
                'title' => 'Kotedžas Pušyno g. 9',
                'client_name' => 'Privatus klientas',
                'address' => 'Pušyno g. 9, Klaipėda',
                'due_date' => '2026-01-25',
                'contact_phone' => '+37061200004',
                'contact_email' => 'pusynas@gmail.com',
                'notes' => 'Baziniai taškai, apšvietimas',
                'status' => 'draft',
                'rooms' => [
                    ['name' => 'Miegamasis'],
                    ['name' => 'Virtuvė'],
                    ['name' => 'Koridorius'],
                ],
            ],
            [
                'title' => 'Sandėlis Žirmūnų g. 30',
                'client_name' => 'UAB Logistika',
                'address' => 'Žirmūnų g. 30, Vilnius',
                'due_date' => '2026-02-20',
                'contact_phone' => '+37061200005',
                'contact_email' => 'logistika@logi.lt',
                'notes' => 'Pramoniniai šviestuvai ir rozėtės',
                'status' => 'sent',
                'rooms' => [
                    ['name' => 'Sandėliavimo zona'],
                    ['name' => 'Administracija'],
                    ['name' => 'Biuras'],
                ],
            ],
            [
                'title' => 'Butas Basanavičiaus g. 15',
                'client_name' => 'Jonas Petrauskas',
                'address' => 'J. Basanavičiaus g. 15, Vilnius',
                'due_date' => '2026-03-05',
                'contact_phone' => '+37061200006',
                'contact_email' => 'jonas@pastas.lt',
                'notes' => 'Šviestuvų pakeitimas ir naujos dėžutės',
                'status' => 'approved',
                'rooms' => [
                    ['name' => 'Koridorius'],
                    ['name' => 'Miegamasis'],
                    ['name' => 'Virtuvė'],
                ],
            ],
            [
                'title' => 'Biuras Konstitucijos pr. 26',
                'client_name' => 'UAB Finansai',
                'address' => 'Konstitucijos pr. 26, Vilnius',
                'due_date' => '2026-01-30',
                'contact_phone' => '+37061200007',
                'contact_email' => 'ofisas@finansai.lt',
                'notes' => 'Konferencijų salės apšvietimas',
                'status' => 'approved',
                'rooms' => [
                    ['name' => 'Konferencijų salė'],
                    ['name' => 'Priimamasis'],
                    ['name' => 'Open space'],
                ],
            ],
            [
                'title' => 'Namas Vytauto g. 8',
                'client_name' => 'Austėja',
                'address' => 'Vytauto g. 8, Panevėžys',
                'due_date' => '2026-02-12',
                'contact_phone' => '+37061200008',
                'contact_email' => 'austeja@mail.lt',
                'notes' => 'Pilna elektros instaliacija',
                'status' => 'draft',
                'rooms' => [
                    ['name' => 'Virtuvė'],
                    ['name' => 'Svetainė'],
                    ['name' => 'Miegamasis'],
                ],
            ],
            [
                'title' => 'Butas Taikos pr. 77',
                'client_name' => 'Karolis',
                'address' => 'Taikos pr. 77, Klaipėda',
                'due_date' => '2026-03-18',
                'contact_phone' => '+37061200009',
                'contact_email' => 'karolis@pastas.lt',
                'notes' => 'Šviestuvų keitimas, naujos rozetės',
                'status' => 'sent',
                'rooms' => [
                    ['name' => 'Koridorius'],
                    ['name' => 'Miegamasis'],
                    ['name' => 'Svetainė'],
                ],
            ],
            [
                'title' => 'Sandėlis Pramonės g. 45',
                'client_name' => 'UAB Pramonė',
                'address' => 'Pramonės g. 45, Šiauliai',
                'due_date' => '2026-04-05',
                'contact_phone' => '+37061200010',
                'contact_email' => 'info@pramone.lt',
                'notes' => 'Galingi šviestuvai, kabelių trasos',
                'status' => 'approved',
                'rooms' => [
                    ['name' => 'Sandėliavimo zona'],
                    ['name' => 'Biuras'],
                    ['name' => 'Priimamasis'],
                ],
            ],
            [
                'title' => 'Biuras Žalgirio g. 112',
                'client_name' => 'UAB Konsultacijos',
                'address' => 'Žalgirio g. 112, Vilnius',
                'due_date' => '2026-02-05',
                'contact_phone' => '+37061200011',
                'contact_email' => 'kontaktai@konsult.lt',
                'notes' => 'Dėžučių montavimas ir šviestuvai',
                'status' => 'draft',
                'rooms' => [
                    ['name' => 'Open space'],
                    ['name' => 'Posėdžių kambarys'],
                    ['name' => 'Virtuvėlė'],
                ],
            ],
            [
                'title' => 'Ofisas Gedimino pr. 1',
                'client_name' => 'UAB Konsultantai',
                'address' => 'Gedimino pr. 1, Vilnius',
                'due_date' => '2026-03-25',
                'contact_phone' => '+37061200012',
                'contact_email' => 'gediminas@konsult.lt',
                'notes' => 'Dekoratyvinis apšvietimas',
                'status' => 'approved',
                'rooms' => [
                    ['name' => 'Open space'],
                    ['name' => 'Posėdžių kambarys'],
                    ['name' => 'Priimamasis'],
                ],
            ],
        ];

        foreach ($projects as $projectData) {
            $project = Project::create([
                'user_id' => $userId,
                'title' => $projectData['title'],
                'client_name' => $projectData['client_name'],
                'address' => $projectData['address'],
                'notes' => $projectData['notes'] ?? null,
                'status' => $projectData['status'],
            ]);

            $sort = 1;
            foreach ($projectData['rooms'] as $roomData) {
                $room = Room::create([
                    'project_id' => $project->id,
                    'name' => $roomData['name'],
                    'sort_order' => $sort++,
                ]);

                $items = collect($baseItems)->shuffle()->take(10);

                foreach ($items as $item) {
                    RoomItem::create([
                        'room_id' => $room->id,
                        'name' => $item['name'],
                        'quantity' => $item['quantity'],
                        'unit' => $item['unit'],
                        'unit_price' => $item['unit_price'],
                    ]);
                }
            }
        }
    }
}
