<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * 
     * You can enable/disable specific seeders by setting them to true/false
     */
    public function run(): void
    {
        $this->command->info('');
        $this->command->info('🌱 ========================================');
        $this->command->info('🌱   MEPCO Database Seeding Started      ');
        $this->command->info('🌱 ========================================');
        $this->command->info('');

        // ===================================================================
        // SEEDER CONFIGURATION - Enable/Disable Seeders Here
        // Set to true to run, false to skip
        // ===================================================================
        
        $seeders = [
            'admin_user'      => true,   // Admin user (separate seeder)
            'companies'       => true,   // Companies (MEPCO, LESCO, etc.)
            'subdivisions'    => true,   // Subdivisions with LS users
            'users'           => true,   // SDC, RO, and additional LS users
            'tariffs'         => true,   // Tariff rates
            'consumers'       => true,   // Consumer accounts
            'applications'    => true,   // New meter applications
            'meters'          => true,   // Installed meters
            'bills'           => true,   // Electricity bills
            'complaints'      => true,   // Customer complaints
            'summaries'       => true,   // Global & Extra summaries
            'audit_logs'      => true,   // System audit logs
            'system_settings' => true,   // System configuration
        ];

        // ===================================================================
        // EXECUTION ORDER - DO NOT CHANGE (Dependencies matter!)
        // ===================================================================
        
        $executionOrder = [
            'admin_user' => [
                'enabled' => $seeders['admin_user'],
                'class' => AdminUserSeeder::class,
                'name' => 'Admin User',
            ],
            'companies' => [
                'enabled' => $seeders['companies'],
                'class' => CompanySeeder::class,
                'name' => 'Companies',
            ],
            'subdivisions' => [
                'enabled' => $seeders['subdivisions'],
                'class' => SubdivisionSeeder::class,
                'name' => 'Subdivisions & LS Users',
            ],
            'users' => [
                'enabled' => $seeders['users'],
                'class' => UserSeeder::class,
                'name' => 'Additional Users (SDC, RO, LS)',
            ],
            'tariffs' => [
                'enabled' => $seeders['tariffs'],
                'class' => TariffSeeder::class,
                'name' => 'Tariffs',
            ],
            'consumers' => [
                'enabled' => $seeders['consumers'],
                'class' => ConsumerSeeder::class,
                'name' => 'Consumers',
            ],
            'applications' => [
                'enabled' => $seeders['applications'],
                'class' => ApplicationSeeder::class,
                'name' => 'Applications',
            ],
            'meters' => [
                'enabled' => $seeders['meters'],
                'class' => MeterSeeder::class,
                'name' => 'Meters',
            ],
            'bills' => [
                'enabled' => $seeders['bills'],
                'class' => BillSeeder::class,
                'name' => 'Bills',
            ],
            'complaints' => [
                'enabled' => $seeders['complaints'],
                'class' => ComplaintSeeder::class,
                'name' => 'Complaints',
            ],
            'summaries' => [
                'enabled' => $seeders['summaries'],
                'class' => SummarySeeder::class,
                'name' => 'Summaries',
            ],
            'audit_logs' => [
                'enabled' => $seeders['audit_logs'],
                'class' => AuditLogSeeder::class,
                'name' => 'Audit Logs',
            ],
            'system_settings' => [
                'enabled' => $seeders['system_settings'],
                'class' => SystemSettingSeeder::class,
                'name' => 'System Settings',
            ],
        ];

        // ===================================================================
        // RUN SEEDERS
        // ===================================================================
        
        $startTime = microtime(true);
        $seederCount = 0;
        $skippedCount = 0;

        foreach ($executionOrder as $key => $config) {
            if ($config['enabled']) {
                $this->command->info('');
                $this->command->info("📦 Running: {$config['name']}");
                $this->command->info(str_repeat('-', 50));
                
                try {
                    $this->call($config['class']);
                    $seederCount++;
                } catch (\Exception $e) {
                    $this->command->error("   ✗ Error in {$config['name']}: {$e->getMessage()}");
                }
            } else {
                $this->command->warn("⊘  Skipped: {$config['name']} (disabled)");
                $skippedCount++;
            }
        }

        // ===================================================================
        // SUMMARY
        // ===================================================================
        
        $endTime = microtime(true);
        $executionTime = round($endTime - $startTime, 2);

        $this->command->info('');
        $this->command->info('🌱 ========================================');
        $this->command->info('🌱   Seeding Summary                      ');
        $this->command->info('🌱 ========================================');
        $this->command->info('');
        
        $this->displayStats();
        
        $this->command->info('');
        $this->command->info("✅ Seeders Executed: {$seederCount}");
        $this->command->info("⊘  Seeders Skipped: {$skippedCount}");
        $this->command->info("⏱  Execution Time: {$executionTime} seconds");
        $this->command->info('');
        
        $this->displayCredentials();
        
        $this->command->info('');
        $this->command->info('🌱 ========================================');
        $this->command->info('🌱   Database Seeding Completed!         ');
        $this->command->info('🌱 ========================================');
        $this->command->info('');
    }

    /**
     * Display database statistics
     */
    private function displayStats(): void
    {
        $stats = [
            ['Model' => 'Companies', 'Count' => \App\Models\Company::count()],
            ['Model' => 'Subdivisions', 'Count' => \App\Models\Subdivision::count()],
            ['Model' => 'Users (Total)', 'Count' => \App\Models\User::count()],
            ['Model' => '├─ Admin Users', 'Count' => \App\Models\User::where('role', 'admin')->count()],
            ['Model' => '├─ LS Users', 'Count' => \App\Models\User::where('role', 'ls')->count()],
            ['Model' => '├─ SDC Users', 'Count' => \App\Models\User::where('role', 'sdc')->count()],
            ['Model' => '└─ RO Users', 'Count' => \App\Models\User::where('role', 'ro')->count()],
            ['Model' => 'Tariffs', 'Count' => \App\Models\Tariff::count()],
            ['Model' => 'Consumers', 'Count' => \App\Models\Consumer::count()],
            ['Model' => 'Applications', 'Count' => \App\Models\Application::count()],
            ['Model' => 'Meters', 'Count' => \App\Models\Meter::count()],
            ['Model' => 'Bills', 'Count' => \App\Models\Bill::count()],
            ['Model' => 'Complaints', 'Count' => \App\Models\Complaint::count()],
            ['Model' => 'Global Summaries', 'Count' => \App\Models\GlobalSummary::count()],
            ['Model' => 'Extra Summaries', 'Count' => \App\Models\ExtraSummary::count()],
            ['Model' => 'Audit Logs', 'Count' => \App\Models\AuditLog::count()],
            ['Model' => 'System Settings', 'Count' => \App\Models\SystemSetting::count()],
        ];

        $this->command->table(
            ['Entity', 'Records'],
            $stats
        );
    }

    /**
     * Display login credentials
     */
    private function displayCredentials(): void
    {
        $this->command->info('🔐 Login Credentials:');
        $this->command->info(str_repeat('-', 50));
        
        // Admin credentials
        $admin = \App\Models\User::where('role', 'admin')->first();
        if ($admin) {
            $this->command->info("   Admin:");
            $this->command->info("   └─ Username: {$admin->username}");
            $this->command->info("   └─ Password: password@123");
            $this->command->info('');
        }

        // LS users
        $lsUsers = \App\Models\User::where('role', 'ls')->limit(5)->get();
        if ($lsUsers->isNotEmpty()) {
            $this->command->info("   LS Users (showing first 5):");
            foreach ($lsUsers as $ls) {
                $subdivision = \App\Models\Subdivision::where('ls_id', $ls->id)->first();
                $subName = $subdivision ? $subdivision->name : 'N/A';
                $this->command->info("   ├─ {$ls->username} / password - ({$subName})");
            }
            
            if (\App\Models\User::where('role', 'ls')->count() > 5) {
                $remaining = \App\Models\User::where('role', 'ls')->count() - 5;
                $this->command->info("   └─ ... and {$remaining} more LS users");
            }
            $this->command->info('');
        }

        // SDC users
        $sdcUsers = \App\Models\User::where('role', 'sdc')->get();
        if ($sdcUsers->isNotEmpty()) {
            $this->command->info("   SDC Users:");
            foreach ($sdcUsers as $sdc) {
                $this->command->info("   ├─ {$sdc->username} / password");
            }
            $this->command->info('');
        }

        // RO users
        $roUsers = \App\Models\User::where('role', 'ro')->get();
        if ($roUsers->isNotEmpty()) {
            $this->command->info("   RO Users:");
            foreach ($roUsers as $ro) {
                $this->command->info("   ├─ {$ro->username} / password");
            }
            $this->command->info('');
        }
    }
}
