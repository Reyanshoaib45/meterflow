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
        $this->command->info('👑 Creating admin user...');

        // Create Admin User
        $existingAdmin = User::where('username', 'admin')->orWhere('role', 'admin')->first();
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

