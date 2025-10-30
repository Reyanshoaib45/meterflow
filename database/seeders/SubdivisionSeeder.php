<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Company;
use App\Models\Subdivision;
use App\Models\User;

class SubdivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('📍 Creating subdivisions with LS users...');

        $companies = Company::all();
        
        if ($companies->isEmpty()) {
            $this->command->error('   ✗ No companies found! Run CompanySeeder first.');
            return;
        }

        $subdivisionsPerCompany = 3;
        $totalSubdivisions = $companies->count() * $subdivisionsPerCompany;
        
        $progressBar = $this->command->getOutput()->createProgressBar($totalSubdivisions);
        $progressBar->setFormat(' %current%/%max% [%bar%] %percent:3s%% | Subdivision: %message%');
        $progressBar->setMessage('Initializing...');
        $progressBar->start();

        $subdivisionCount = 0;
        $lsUserCount = 0;

        foreach ($companies as $company) {
            // Create 3 subdivisions per company
            for ($i = 1; $i <= $subdivisionsPerCompany; $i++) {
                $subdivisionName = "{$company->name} - Subdivision {$i}";
                $progressBar->setMessage($subdivisionName);
                
                // Create LS user for this subdivision
                $lsUser = User::create([
                    'name' => "LS User {$company->code}-{$i}",
                    'email' => strtolower($company->code) . "_ls{$i}@mepco.gov.pk",
                    'username' => strtolower($company->code) . "_ls{$i}",
                    'password' => Hash::make('password'),
                    'role' => 'ls',
                ]);
                $lsUserCount++;

                // Create subdivision
                Subdivision::firstOrCreate(
                    ['code' => "{$company->code}-SUB{$i}"],
                    [
                        'name' => $subdivisionName,
                        'company_id' => $company->id,
                        'ls_id' => $lsUser->id,
                    ]
                );
                $subdivisionCount++;
                $progressBar->advance();
            }
        }

        $progressBar->setMessage('Completed!');
        $progressBar->finish();
        $this->command->newLine();
        $this->command->info("   ✓ Created {$subdivisionCount} subdivisions");
        $this->command->info("   ✓ Created {$lsUserCount} LS users (password: password)");
    }
}

