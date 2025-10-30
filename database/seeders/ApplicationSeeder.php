<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Application;
use App\Models\ApplicationHistory;
use App\Models\Consumer;
use App\Models\Subdivision;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('📋 Creating applications...');

        $consumers = Consumer::with('subdivision')->get();
        
        if ($consumers->isEmpty()) {
            $this->command->error('   ✗ No consumers found! Run ConsumerSeeder first.');
            return;
        }

        $progressBar = $this->command->getOutput()->createProgressBar($consumers->count());
        $progressBar->setFormat(' %current%/%max% [%bar%] %percent:3s%% | Application: %message%');
        $progressBar->setMessage('Initializing...');
        $progressBar->start();

        $applicationCount = 0;

        foreach ($consumers as $consumer) {
            $status = fake()->randomElement(['pending', 'approved', 'rejected', 'closed']);
            $appNo = 'APP-' . $consumer->subdivision->code . '-' . fake()->unique()->numerify('####');
            $progressBar->setMessage($appNo);
            
            $application = Application::create([
                'application_no' => $appNo,
                'customer_name' => $consumer->name,
                'cnic' => $consumer->cnic,
                'phone' => $consumer->phone,
                'address' => $consumer->address,
                'company_id' => $consumer->subdivision->company_id,
                'subdivision_id' => $consumer->subdivision_id,
                'connection_type' => $consumer->connection_type,
                'status' => $status,
                'fee_amount' => $status === 'approved' ? fake()->numberBetween(5000, 20000) : null,
                'meter_number' => $status === 'approved' ? 'MTR-' . fake()->numerify('########') : null,
            ]);

            // Create application history
            ApplicationHistory::create([
                'application_id' => $application->id,
                'subdivision_id' => $consumer->subdivision_id,
                'company_id' => $consumer->subdivision->company_id,
                'action_type' => 'submitted',
                'remarks' => 'Application submitted by customer',
            ]);

            if ($status !== 'pending') {
                ApplicationHistory::create([
                    'application_id' => $application->id,
                    'subdivision_id' => $consumer->subdivision_id,
                    'company_id' => $consumer->subdivision->company_id,
                    'action_type' => 'status_changed',
                    'remarks' => "Status changed to {$status}",
                ]);
            }

            $applicationCount++;
            $progressBar->advance();
        }

        $progressBar->setMessage('Done!');
        $progressBar->finish();
        $this->command->newLine();
        $this->command->info("   ✓ Created {$applicationCount} applications with history");
    }
}

