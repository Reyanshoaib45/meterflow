<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tariff;

class TariffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('💰 Creating tariffs...');

        $tariffs = [
            [
                'name' => 'Domestic - Slab 1 (0-100 units)',
                'category' => 'Domestic',
                'from_units' => 0,
                'to_units' => 100,
                'rate_per_unit' => 5.50,
                'fixed_charges' => 100,
                'is_active' => true,
            ],
            [
                'name' => 'Domestic - Slab 2 (101-300 units)',
                'category' => 'Domestic',
                'from_units' => 101,
                'to_units' => 300,
                'rate_per_unit' => 8.50,
                'fixed_charges' => 150,
                'is_active' => true,
            ],
            [
                'name' => 'Domestic - Slab 3 (301-500 units)',
                'category' => 'Domestic',
                'from_units' => 301,
                'to_units' => 500,
                'rate_per_unit' => 12.00,
                'fixed_charges' => 200,
                'is_active' => true,
            ],
            [
                'name' => 'Commercial',
                'category' => 'Commercial',
                'from_units' => 0,
                'to_units' => null,
                'rate_per_unit' => 15.50,
                'fixed_charges' => 500,
                'is_active' => true,
            ],
            [
                'name' => 'Industrial',
                'category' => 'Industrial',
                'from_units' => 0,
                'to_units' => null,
                'rate_per_unit' => 10.00,
                'fixed_charges' => 1000,
                'is_active' => true,
            ],
            [
                'name' => 'Agricultural',
                'category' => 'Agricultural',
                'from_units' => 0,
                'to_units' => null,
                'rate_per_unit' => 6.50,
                'fixed_charges' => 200,
                'is_active' => true,
            ],
        ];

        foreach ($tariffs as $tariffData) {
            Tariff::firstOrCreate(
                [
                    'name' => $tariffData['name'],
                    'category' => $tariffData['category'],
                ],
                $tariffData
            );
        }

        $this->command->info('   ✓ Created ' . count($tariffs) . ' tariffs');
    }
}

