<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Consumer;
use App\Models\Subdivision;

class ConsumerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('👥 Creating consumers...');

        $subdivisions = Subdivision::all();
        
        if ($subdivisions->isEmpty()) {
            $this->command->error('   ✗ No subdivisions found! Run SubdivisionSeeder first.');
            return;
        }

        $consumersPerSubdivision = 15; // Configurable
        $totalConsumers = $subdivisions->count() * $consumersPerSubdivision;
        
        $progressBar = $this->command->getOutput()->createProgressBar($totalConsumers);
        $progressBar->setFormat(' %current%/%max% [%bar%] %percent:3s%% | Creating: %message%');
        $progressBar->setMessage('Starting...');
        $progressBar->start();

        $consumerCount = 0;

        foreach ($subdivisions as $subdivision) {
            for ($i = 1; $i <= $consumersPerSubdivision; $i++) {
                $name = fake()->name();
                $progressBar->setMessage($name);
                
                Consumer::create([
                    'name' => $name,
                    'cnic' => fake()->numerify('#####-#######-#'),
                    'phone' => fake()->phoneNumber(),
                    'email' => fake()->optional()->email(),
                    'address' => fake()->address(),
                    'subdivision_id' => $subdivision->id,
                    'connection_type' => fake()->randomElement(['Domestic', 'Commercial', 'Industrial', 'Agricultural']),
                    'status' => fake()->randomElement(['active', 'inactive', 'suspended']),
                ]);
                $consumerCount++;
                $progressBar->advance();
            }
        }

        $progressBar->setMessage('Completed!');
        $progressBar->finish();
        $this->command->newLine();
        $this->command->info("   ✓ Created {$consumerCount} consumers ({$consumersPerSubdivision} per subdivision)");
    }
}

