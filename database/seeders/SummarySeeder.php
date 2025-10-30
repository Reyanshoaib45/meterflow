<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GlobalSummary;
use App\Models\ExtraSummary;
use App\Models\Application;
use App\Models\Subdivision;

class SummarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('📊 Creating summaries...');

        // Create Global Summaries
        $this->createGlobalSummaries();
        
        // Create Extra Summaries
        $this->createExtraSummaries();
    }

    private function createGlobalSummaries(): void
    {
        $approvedApplications = Application::where('status', 'approved')
            ->limit(20)
            ->get();

        if ($approvedApplications->isEmpty()) {
            $this->command->warn('   ⚠ No approved applications found for Global Summaries.');
            return;
        }

        $globalCount = 0;

        foreach ($approvedApplications as $application) {
            GlobalSummary::create([
                'application_id' => $application->id,
                'application_no' => $application->application_no,
                'customer_name' => $application->customer_name,
                'meter_no' => $application->meter_number,
                'sim_date' => now()->subDays(fake()->numberBetween(1, 30)),
                'date_on_draft_store' => now()->subDays(fake()->numberBetween(1, 25)),
                'date_received_lm_consumer' => now()->subDays(fake()->numberBetween(1, 20)),
                'customer_mobile_no' => $application->phone,
                'customer_sc_no' => fake()->numerify('SC-########'),
                'date_return_sdc_billing' => now()->subDays(fake()->numberBetween(1, 15)),
            ]);
            $globalCount++;
        }

        $this->command->info("   ✓ Created {$globalCount} global summaries");
    }

    private function createExtraSummaries(): void
    {
        $subdivisions = Subdivision::with(['applications' => function($q) {
            $q->limit(2);
        }])->get();

        if ($subdivisions->isEmpty()) {
            $this->command->warn('   ⚠ No subdivisions found for Extra Summaries.');
            return;
        }

        $extraCount = 0;

        foreach ($subdivisions as $subdivision) {
            foreach ($subdivision->applications as $application) {
                ExtraSummary::create([
                    'company_id' => $subdivision->company_id,
                    'subdivision_id' => $subdivision->id,
                    'application_no' => $application->application_no,
                    'customer_name' => $application->customer_name,
                    'meter_no' => $application->meter_number,
                    'sim_date' => now()->subDays(fake()->numberBetween(1, 30)),
                    'date_on_draft_store' => now()->subDays(fake()->numberBetween(1, 25)),
                    'date_received_lm_consumer' => now()->subDays(fake()->numberBetween(1, 20)),
                    'customer_mobile_no' => $application->phone,
                    'customer_sc_no' => fake()->numerify('SC-########'),
                    'date_return_sdc_billing' => now()->subDays(fake()->numberBetween(1, 15)),
                    'application_id' => $application->id,
                ]);
                $extraCount++;
            }
        }

        $this->command->info("   ✓ Created {$extraCount} extra summaries");
    }
}

