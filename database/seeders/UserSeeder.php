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
        $existingAdmin = User::where('username', 'admin')->orWhere('role', 'admin')->first();
        if (!$existingAdmin) {
            $admin = User::create([
                'name' => 'Admin User',
                'email' => 'admin@mepco.gov.pk',
                'username' => 'admin',
                'password' => Hash::make('password@123'),
                'role' => 'admin',
            ]);
            $this->command->info('   ✓ Admin user created: admin / password@123');
        } else {
            $this->command->warn('   ⚠ Admin user already exists');
        }

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

        // Create SDC Users
        $sdcCount = 0;
        $sdcUsers = [
            ['name' => 'SDC User 1', 'username' => 'sdc1', 'email' => 'sdc1@mepco.gov.pk'],
            ['name' => 'SDC User 2', 'username' => 'sdc2', 'email' => 'sdc2@mepco.gov.pk'],
        ];

        foreach ($sdcUsers as $sdcData) {
            $existingSdc = User::where('username', $sdcData['username'])->first();
            if (!$existingSdc) {
                User::create([
                    'name' => $sdcData['name'],
                    'email' => $sdcData['email'],
                    'username' => $sdcData['username'],
                    'password' => Hash::make('password'),
                    'role' => 'sdc',
                ]);
                $sdcCount++;
            }
        }

        if ($sdcCount > 0) {
            $this->command->info("   ✓ Created {$sdcCount} SDC users (password: password)");
        }

        // Create RO Users
        $roCount = 0;
        $roUsers = [
            ['name' => 'RO User 1', 'username' => 'ro1', 'email' => 'ro1@mepco.gov.pk'],
            ['name' => 'RO User 2', 'username' => 'ro2', 'email' => 'ro2@mepco.gov.pk'],
        ];

        foreach ($roUsers as $roData) {
            $existingRo = User::where('username', $roData['username'])->first();
            if (!$existingRo) {
                User::create([
                    'name' => $roData['name'],
                    'email' => $roData['email'],
                    'username' => $roData['username'],
                    'password' => Hash::make('password'),
                    'role' => 'ro',
                ]);
                $roCount++;
            }
        }

        if ($roCount > 0) {
            $this->command->info("   ✓ Created {$roCount} RO users (password: password)");
        }
    }
}

