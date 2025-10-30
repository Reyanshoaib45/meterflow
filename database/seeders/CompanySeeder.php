<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🏢 Creating companies...');

        $companies = [
            ['name' => 'MEPCO - Multan Electric Power Company', 'code' => 'MEPCO'],
            ['name' => 'LESCO - Lahore Electric Supply Company', 'code' => 'LESCO'],
            ['name' => 'FESCO - Faisalabad Electric Supply Company', 'code' => 'FESCO'],
            ['name' => 'GEPCO - Gujranwala Electric Power Company', 'code' => 'GEPCO'],
            ['name' => 'IESCO - Islamabad Electric Supply Company', 'code' => 'IESCO'],
        ];

        foreach ($companies as $companyData) {
            Company::firstOrCreate(
                ['code' => $companyData['code']],
                $companyData
            );
        }

        $this->command->info('   ✓ Created ' . count($companies) . ' companies');
    }
}

