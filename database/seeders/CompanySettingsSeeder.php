<?php

namespace Database\Seeders;

use App\Models\CompanySetting;
use Illuminate\Database\Seeder;

class CompanySettingsSeeder extends Seeder
{
    public function run(): void
    {
        // Pradiniai Ecobaltec duomenys
        CompanySetting::updateOrCreate(
            ['user_id' => 1],
            [
                'legal_type' => 'company',
                'name'       => 'Ecobaltec, MB',
                'code'       => '305716113',
                'vat'        => null,
                'address'    => 'Draugystės g. 17-1E',
                'city'       => 'Kaunas',
                'postal'     => 'LT-51229',
                'phone'      => '+370 669 10010',
                'email'      => null,
                'director'   => null,
                'logo_path'  => null,
            ]
        );
    }
}
