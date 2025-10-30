<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Company;
use App\Models\Subdivision;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('👤 Creating users...');

        // Create Admin User
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@mepco.gov.pk',
            'username' => 'admin',
            'password' => Hash::make('password@123'),
            'role' => 'admin',
        ]);
        $this->command->info('   ✓ Admin user created: admin / password@123');

        // Create LS Users for existing subdivisions
        $subdivisions = Subdivision::with('company')->get();
        
        if ($subdivisions->isEmpty()) {
            $this->command->warn('   ⚠ No subdivisions found. Run SubdivisionSeeder first to create LS users with subdivisions.');
            return;
        }

        $lsCount = 0;
        foreach ($subdivisions as $subdivision) {
            if (!$subdivision->ls_id) {
                $lsUser = User::create([
                    'name' => "LS User {$subdivision->code}",
                    'email' => strtolower(str_replace([' ', '-'], '_', $subdivision->code)) . "@mepco.gov.pk",
                    'username' => strtolower(str_replace([' ', '-'], '_', $subdivision->code)) . "_ls",
                    'password' => Hash::make('password'),
                    'role' => 'ls',
                ]);

                // Assign LS to subdivision
                $subdivision->update(['ls_id' => $lsUser->id]);
                $lsCount++;
            }
        }

        $this->command->info("   ✓ Created {$lsCount} LS users (password: password)");
    }
}

