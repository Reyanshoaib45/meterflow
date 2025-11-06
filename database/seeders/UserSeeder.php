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

        // Create SDC Users and assign them to subdivisions
        $sdcCount = 0;
        $sdcUsers = [
            ['name' => 'SDC User 1', 'username' => 'sdc1', 'email' => 'sdc1@mepco.gov.pk'],
            ['name' => 'SDC User 2', 'username' => 'sdc2', 'email' => 'sdc2@mepco.gov.pk'],
        ];

        foreach ($sdcUsers as $sdcData) {
            $existingSdc = User::where('username', $sdcData['username'])->first();
            $sdcUser = null;
            
            if (!$existingSdc) {
                $sdcUser = User::create([
                    'name' => $sdcData['name'],
                    'email' => $sdcData['email'],
                    'username' => $sdcData['username'],
                    'password' => Hash::make('password'),
                    'role' => 'sdc',
                ]);
                $sdcCount++;
            } else {
                $sdcUser = $existingSdc;
            }

            // Assign SDC user to all subdivisions that have LS users
            if ($sdcUser) {
                $subdivisionsToAssign = Subdivision::whereNotNull('ls_id')->get();
                $alreadyAssigned = $sdcUser->subdivisions->pluck('id')->toArray();
                
                foreach ($subdivisionsToAssign as $subdivision) {
                    if (!in_array($subdivision->id, $alreadyAssigned)) {
                        $sdcUser->subdivisions()->attach($subdivision->id);
                    }
                }
            }
        }

        if ($sdcCount > 0) {
            $this->command->info("   ✓ Created {$sdcCount} SDC users (password: password)");
        }
        
        // Assign existing SDC users to subdivisions if not already assigned
        $existingSdcUsers = User::where('role', 'sdc')->get();
        foreach ($existingSdcUsers as $sdcUser) {
            $subdivisionsToAssign = Subdivision::whereNotNull('ls_id')->get();
            $alreadyAssigned = $sdcUser->subdivisions->pluck('id')->toArray();
            
            foreach ($subdivisionsToAssign as $subdivision) {
                if (!in_array($subdivision->id, $alreadyAssigned)) {
                    $sdcUser->subdivisions()->attach($subdivision->id);
                }
            }
        }
        
        if ($existingSdcUsers->count() > 0) {
            $totalSubdivisions = Subdivision::whereNotNull('ls_id')->count();
            $this->command->info("   ✓ Assigned SDC users to {$totalSubdivisions} subdivisions");
        }

        // Create RO Users and assign them to subdivisions
        $roCount = 0;
        $roUsers = [
            ['name' => 'RO User 1', 'username' => 'ro1', 'email' => 'ro1@mepco.gov.pk'],
            ['name' => 'RO User 2', 'username' => 'ro2', 'email' => 'ro2@mepco.gov.pk'],
        ];

        foreach ($roUsers as $roData) {
            $existingRo = User::where('username', $roData['username'])->first();
            $roUser = null;
            
            if (!$existingRo) {
                $roUser = User::create([
                    'name' => $roData['name'],
                    'email' => $roData['email'],
                    'username' => $roData['username'],
                    'password' => Hash::make('password'),
                    'role' => 'ro',
                ]);
                $roCount++;
            } else {
                $roUser = $existingRo;
            }

            // Assign RO user to all subdivisions that have LS users
            if ($roUser) {
                $subdivisionsToAssign = Subdivision::whereNotNull('ls_id')->get();
                $alreadyAssigned = $roUser->subdivisions->pluck('id')->toArray();
                
                foreach ($subdivisionsToAssign as $subdivision) {
                    if (!in_array($subdivision->id, $alreadyAssigned)) {
                        $roUser->subdivisions()->attach($subdivision->id);
                    }
                }
            }
        }

        if ($roCount > 0) {
            $this->command->info("   ✓ Created {$roCount} RO users (password: password)");
        }
        
        // Assign existing RO users to subdivisions if not already assigned
        $existingRoUsers = User::where('role', 'ro')->get();
        foreach ($existingRoUsers as $roUser) {
            $subdivisionsToAssign = Subdivision::whereNotNull('ls_id')->get();
            $alreadyAssigned = $roUser->subdivisions->pluck('id')->toArray();
            
            foreach ($subdivisionsToAssign as $subdivision) {
                if (!in_array($subdivision->id, $alreadyAssigned)) {
                    $roUser->subdivisions()->attach($subdivision->id);
                }
            }
        }
        
        if ($existingRoUsers->count() > 0) {
            $totalSubdivisions = Subdivision::whereNotNull('ls_id')->count();
            $this->command->info("   ✓ Assigned RO users to {$totalSubdivisions} subdivisions");
        }
    }
}

