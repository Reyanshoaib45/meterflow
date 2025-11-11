<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('👑 Creating users...');

        // Create Owner User (Super Admin with Settings Access)
        $existingOwner = User::where('email', 'Reyanshoaib@develpor.com')->first();
        if (!$existingOwner) {
            $owner = User::create([
                'name' => 'Reyan Shoaib',
                'email' => 'Reyanshoaib@develpor.com',
                'username' => 'reyanshoaib',
                'password' => Hash::make('112233qq'),
                'role' => 'owner',
                'is_active' => true,
            ]);
            $this->command->info('   ✓ Owner user created: Reyanshoaib@develpor.com / 112233qq');
        } else {
            $this->command->warn('   ⚠ Owner user already exists');
        }

        // Create Admin User (Regular Admin - No Settings Access)
        $existingAdmin = User::where('username', 'admin')->first();
        if (!$existingAdmin) {
            $admin = User::create([
                'name' => 'Admin User',
                'email' => 'admin@mepco.gov.pk',
                'username' => 'admin',
                'password' => Hash::make('password@123'),
                'role' => 'admin',
                'is_active' => true,
            ]);
            $this->command->info('   ✓ Admin user created: admin / password@123');
        } else {
            $this->command->warn('   ⚠ Admin user already exists');
        }
    }
}

