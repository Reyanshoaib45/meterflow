<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bill;
use App\Models\Meter;
use App\Models\Tariff;

class BillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('💵 Creating bills...');

        $activeMeters = Meter::with(['consumer', 'subdivision'])
            ->where('status', 'active')
            ->get();
        
        if ($activeMeters->isEmpty()) {
            $this->command->warn('   ⚠ No active meters found. Bills are only created for active meters.');
            return;
        }

        $monthsToGenerate = 3; // Generate 3 months of bills
        $totalBills = $activeMeters->count() * $monthsToGenerate;
        
        $progressBar = $this->command->getOutput()->createProgressBar($totalBills);
        $progressBar->setFormat(' %current%/%max% [%bar%] %percent:3s%% | Bill: %message%');
        $progressBar->setMessage('Initializing...');
        $progressBar->start();

        $billCount = 0;

        foreach ($activeMeters as $meter) {
            if (!$meter->consumer) continue;

            // Get appropriate tariff
            $tariff = Tariff::where('category', $meter->consumer->connection_type)
                ->where('is_active', true)
                ->first();

            if (!$tariff) {
                $tariff = Tariff::where('is_active', true)->first();
            }

            if ($tariff) {
                for ($month = 1; $month <= $monthsToGenerate; $month++) {
                    $billNo = 'BILL-' . fake()->unique()->numerify('##########');
                    $progressBar->setMessage($billNo);
                    
                    $unitsConsumed = fake()->numberBetween(50, 800);
                    $totalAmount = ($unitsConsumed * $tariff->rate_per_unit) + $tariff->fixed_charges;
                    $paymentStatus = fake()->randomElement(['paid', 'unpaid', 'overdue', 'partial']);
                    $amountPaid = $paymentStatus === 'paid' ? $totalAmount : 
                                 ($paymentStatus === 'partial' ? $totalAmount * 0.5 : 0);

                    Bill::create([
                        'bill_no' => $billNo,
                        'consumer_id' => $meter->consumer_id,
                        'meter_id' => $meter->id,
                        'subdivision_id' => $meter->subdivision_id,
                        'billing_month' => now()->subMonths($month)->format('F'),
                        'billing_year' => now()->subMonths($month)->format('Y'),
                        'issue_date' => now()->subMonths($month)->startOfMonth(),
                        'due_date' => now()->subMonths($month)->addDays(15),
                        'units_consumed' => $unitsConsumed,
                        'rate_per_unit' => $tariff->rate_per_unit,
                        'total_amount' => $totalAmount,
                        'amount_paid' => $amountPaid,
                        'payment_status' => $paymentStatus,
                        'payment_date' => $paymentStatus === 'paid' ? now()->subMonths($month)->addDays(10) : null,
                    ]);
                    $billCount++;
                    $progressBar->advance();
                }
            }
        }

        $progressBar->setMessage('Done!');
        $progressBar->finish();
        $this->command->newLine();
        $this->command->info("   ✓ Created {$billCount} bills ({$monthsToGenerate} months per meter)");
    }
}

