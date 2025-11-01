<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Meter;
use App\Models\Application;
use App\Models\Consumer;

class MeterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🔌 Creating meters...');

        $approvedApplications = Application::with('subdivision')
            ->where('status', 'approved')
            ->get();
        
        if ($approvedApplications->isEmpty()) {
            $this->command->warn('   ⚠ No approved applications found. Meters are only created for approved applications.');
            return;
        }

        $progressBar = $this->command->getOutput()->createProgressBar($approvedApplications->count());
        $progressBar->setFormat(' %current%/%max% [%bar%] %percent:3s%% | Meter: %message%');
        $progressBar->setMessage('Starting...');
        $progressBar->start();

        $meterCount = 0;

        foreach ($approvedApplications as $application) {
            $meterNo = $application->meter_number ?? 'MTR-' . fake()->numerify('########');
            $progressBar->setMessage($meterNo);
            
            Meter::create([
                'meter_no' => $meterNo,
                'meter_make' => fake()->randomElement(['ABB', 'Siemens', 'Schneider', 'GE', 'Landis+Gyr']),
                'reading' => fake()->numberBetween(0, 1000),
                'status' => fake()->randomElement(['active', 'faulty', 'disconnected']),
                'application_id' => $application->id,
                'consumer_id' => Consumer::where('cnic', $application->cnic)->first()?->id,
                'subdivision_id' => $application->subdivision_id,
                'installed_on' => now()->subDays(fake()->numberBetween(1, 365)),
                'last_reading' => fake()->numberBetween(0, 1000),
                'last_reading_date' => now()->subDays(fake()->numberBetween(1, 30)),
            ]);
            $meterCount++;
            $progressBar->advance();
        }

        $progressBar->setMessage('Completed!');
        $progressBar->finish();
        $this->command->newLine();
        $this->command->info("   ✓ Created {$meterCount} meters");
    }
}

