<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Complaint;
use App\Models\ComplaintHistory;
use App\Models\Consumer;

class ComplaintSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('📞 Creating complaints...');

        $consumers = Consumer::with('subdivision')->get();
        
        if ($consumers->isEmpty()) {
            $this->command->error('   ✗ No consumers found! Run ConsumerSeeder first.');
            return;
        }

        $complaintChance = 30; // 30% of consumers will have complaints
        $estimatedComplaints = (int)($consumers->count() * ($complaintChance / 100));
        
        $progressBar = $this->command->getOutput()->createProgressBar($consumers->count());
        $progressBar->setFormat(' %current%/%max% [%bar%] %percent:3s%% | Processing consumers...');
        $progressBar->start();

        $complaintCount = 0;

        foreach ($consumers as $consumer) {
            if (fake()->boolean($complaintChance)) {
                $status = fake()->randomElement(['pending', 'in_progress', 'resolved', 'closed']);
                
                $complaint = Complaint::create([
                    'complaint_id' => 'CMP-' . fake()->unique()->numerify('########'),
                    'consumer_id' => $consumer->id,
                    'subdivision_id' => $consumer->subdivision_id,
                    'subject' => fake()->randomElement([
                        'Meter not working properly',
                        'High bill amount - needs review',
                        'Frequent power outages',
                        'Billing error - incorrect reading',
                        'Connection issue - no power',
                        'Meter reading dispute',
                        'Bill payment not reflected',
                        'Request for meter replacement',
                        'Load shedding complaint',
                        'Voltage fluctuation problem'
                    ]),
                    'description' => fake()->paragraph(3),
                    'status' => $status,
                    'priority' => fake()->randomElement(['low', 'medium', 'high', 'urgent']),
                    'assigned_to' => $consumer->subdivision->ls_id,
                ]);

                // Add complaint history
                ComplaintHistory::create([
                    'complaint_id' => $complaint->id,
                    'action' => 'created',
                    'remarks' => 'Complaint registered by customer',
                    'user_id' => null,
                ]);

                if ($status !== 'pending') {
                    ComplaintHistory::create([
                        'complaint_id' => $complaint->id,
                        'action' => 'status_changed',
                        'remarks' => "Status updated to {$status}",
                        'user_id' => $consumer->subdivision->ls_id,
                    ]);
                }

                if ($status === 'resolved' || $status === 'closed') {
                    ComplaintHistory::create([
                        'complaint_id' => $complaint->id,
                        'action' => $status,
                        'remarks' => $status === 'resolved' ? 'Issue resolved successfully' : 'Complaint closed',
                        'user_id' => $consumer->subdivision->ls_id,
                    ]);
                }

                $complaintCount++;
            }
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->command->newLine();
        $this->command->info("   ✓ Created {$complaintCount} complaints with history");
    }
}

