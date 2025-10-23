<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Company;
use App\Models\Subdivision;
use App\Models\Application;
use App\Models\Meter;
use App\Models\ApplicationHistory;
use App\Models\ApplicationSummary;
use App\Models\ExtraSummary;
use App\Models\GlobalSummary;
use App\Models\Consumer;
use App\Models\Bill;
use App\Models\Tariff;
use App\Models\Complaint;
use App\Models\ComplaintHistory;
use App\Models\AuditLog;
use App\Models\SystemSetting;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting database seeding...');

        // 1. Create Admin User
        $this->command->info('👤 Creating admin user...');
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@mepco.gov.pk',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 2. Create Companies
        $this->command->info('🏢 Creating companies...');
        $companies = [
            ['name' => 'MEPCO - Multan Electric Power Company', 'code' => 'MEPCO'],
            ['name' => 'LESCO - Lahore Electric Supply Company', 'code' => 'LESCO'],
            ['name' => 'FESCO - Faisalabad Electric Supply Company', 'code' => 'FESCO'],
        ];

        $companyModels = [];
        foreach ($companies as $companyData) {
            $companyModels[] = Company::create($companyData);
        }

        // 3. Create LS Users and Subdivisions
        $this->command->info('👥 Creating LS users and subdivisions...');
        $lsUsers = [];
        $subdivisions = [];
        
        foreach ($companyModels as $index => $company) {
            // Create 3 subdivisions per company
            for ($i = 1; $i <= 3; $i++) {
                // Create LS user for this subdivision
                $lsUser = User::create([
                    'name' => "LS User {$company->code}-{$i}",
                    'email' => "ls{$index}{$i}@mepco.gov.pk",
                    'username' => strtolower($company->code) . "_ls{$i}",
                    'password' => Hash::make('password'),
                    'role' => 'ls',
                ]);
                $lsUsers[] = $lsUser;

                // Create subdivision
                $subdivision = Subdivision::create([
                    'name' => "{$company->name} - Subdivision {$i}",
                    'code' => "{$company->code}-SUB{$i}",
                    'company_id' => $company->id,
                    'ls_id' => $lsUser->id,
                ]);
                $subdivisions[] = $subdivision;
            }
        }

        // 4. Create Tariffs
        $this->command->info('💰 Creating tariffs...');
        $tariffs = [
            [
                'name' => 'Domestic - Slab 1',
                'category' => 'Domestic',
                'from_units' => 0,
                'to_units' => 100,
                'rate_per_unit' => 5.50,
                'fixed_charges' => 100,
                'is_active' => true,
            ],
            [
                'name' => 'Domestic - Slab 2',
                'category' => 'Domestic',
                'from_units' => 101,
                'to_units' => 300,
                'rate_per_unit' => 8.50,
                'fixed_charges' => 150,
                'is_active' => true,
            ],
            [
                'name' => 'Commercial',
                'category' => 'Commercial',
                'from_units' => 0,
                'to_units' => null,
                'rate_per_unit' => 12.50,
                'fixed_charges' => 500,
                'is_active' => true,
            ],
            [
                'name' => 'Industrial',
                'category' => 'Industrial',
                'from_units' => 0,
                'to_units' => null,
                'rate_per_unit' => 10.00,
                'fixed_charges' => 1000,
                'is_active' => true,
            ],
        ];

        foreach ($tariffs as $tariffData) {
            Tariff::create($tariffData);
        }

        // 5. Create Consumers, Applications, and Meters
        $this->command->info('📋 Creating consumers, applications, and meters...');
        $consumers = [];
        $applications = [];
        $meters = [];

        foreach ($subdivisions as $subdivision) {
            // Create 10 consumers per subdivision
            for ($i = 1; $i <= 10; $i++) {
                $consumer = Consumer::create([
                    'name' => fake()->name(),
                    'cnic' => fake()->numerify('#####-#######-#'),
                    'phone' => fake()->phoneNumber(),
                    'address' => fake()->address(),
                    'subdivision_id' => $subdivision->id,
                    'connection_type' => fake()->randomElement(['Domestic', 'Commercial', 'Industrial']),
                    'status' => 'active',
                ]);
                $consumers[] = $consumer;

                // Create application for consumer
                $status = fake()->randomElement(['pending', 'approved', 'rejected', 'closed']);
                $application = Application::create([
                    'application_no' => 'APP-' . $subdivision->code . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                    'customer_name' => $consumer->name,
                    'cnic' => $consumer->cnic,
                    'phone' => $consumer->phone,
                    'address' => $consumer->address,
                    'company_id' => $subdivision->company_id,
                    'subdivision_id' => $subdivision->id,
                    'connection_type' => $consumer->connection_type,
                    'status' => $status,
                    'fee_amount' => $status === 'approved' ? fake()->numberBetween(5000, 15000) : null,
                ]);
                $applications[] = $application;

                // Create application history
                ApplicationHistory::create([
                    'application_id' => $application->id,
                    'subdivision_id' => $subdivision->id,
                    'company_id' => $subdivision->company_id,
                    'action_type' => 'submitted',
                    'remarks' => 'Application submitted by customer',
                ]);

                if ($status !== 'pending') {
                    ApplicationHistory::create([
                        'application_id' => $application->id,
                        'subdivision_id' => $subdivision->id,
                        'company_id' => $subdivision->company_id,
                        'action_type' => 'status_changed',
                        'remarks' => "Status changed to {$status}",
                    ]);
                }

                // Create meter for approved applications
                if ($status === 'approved') {
                    $meter = Meter::create([
                        'meter_no' => 'MTR-' . fake()->numerify('########'),
                        'meter_make' => fake()->randomElement(['ABB', 'Siemens', 'Schneider', 'GE']),
                        'reading' => fake()->numberBetween(0, 1000),
                        'status' => fake()->randomElement(['active', 'faulty', 'disconnected']),
                        'application_id' => $application->id,
                        'consumer_id' => $consumer->id,
                        'subdivision_id' => $subdivision->id,
                        'installed_on' => now()->subDays(fake()->numberBetween(1, 365)),
                        'last_reading' => fake()->numberBetween(0, 1000),
                        'last_reading_date' => now()->subDays(fake()->numberBetween(1, 30)),
                    ]);
                    $meters[] = $meter;
                }
            }

            // Create extra summaries (using first application as example)
            $firstApp = Application::where('subdivision_id', $subdivision->id)->first();
            if ($firstApp) {
                ExtraSummary::create([
                    'company_id' => $subdivision->company_id,
                    'subdivision_id' => $subdivision->id,
                    'application_no' => $firstApp->application_no,
                    'customer_name' => $firstApp->customer_name,
                    'meter_no' => $firstApp->meter_number,
                    'sim_date' => now()->subDays(fake()->numberBetween(1, 30)),
                    'date_on_draft_store' => now()->subDays(fake()->numberBetween(1, 25)),
                    'date_received_lm_consumer' => now()->subDays(fake()->numberBetween(1, 20)),
                    'customer_mobile_no' => $firstApp->phone,
                    'customer_sc_no' => fake()->numerify('SC-########'),
                    'date_return_sdc_billing' => now()->subDays(fake()->numberBetween(1, 15)),
                    'application_id' => $firstApp->id,
                ]);
            }
        }

        // 6. Create Bills
        $this->command->info('💵 Creating bills...');
        foreach ($meters as $meter) {
            if ($meter->status === 'active') {
                // Create 3 bills for each active meter
                for ($month = 1; $month <= 3; $month++) {
                    $unitsConsumed = fake()->numberBetween(50, 500);
                    $tariff = Tariff::where('category', $meter->consumer->connection_type)
                        ->where('is_active', true)
                        ->first();

                    if ($tariff) {
                        $totalAmount = ($unitsConsumed * $tariff->rate_per_unit) + $tariff->fixed_charges;
                        $paymentStatus = fake()->randomElement(['paid', 'unpaid', 'overdue']);

                        Bill::create([
                            'bill_no' => 'BILL-' . fake()->numerify('##########'),
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
                            'amount_paid' => $paymentStatus === 'paid' ? $totalAmount : 0,
                            'payment_status' => $paymentStatus,
                            'payment_date' => $paymentStatus === 'paid' ? now()->subMonths($month)->addDays(10) : null,
                        ]);
                    }
                }
            }
        }

        // 7. Create Complaints
        $this->command->info('📞 Creating complaints...');
        foreach ($consumers as $consumer) {
            if (fake()->boolean(40)) { // 40% chance of having a complaint
                $status = fake()->randomElement(['pending', 'in_progress', 'resolved']);
                $complaint = Complaint::create([
                    'complaint_id' => 'CMP-' . fake()->numerify('########'),
                    'consumer_id' => $consumer->id,
                    'subdivision_id' => $consumer->subdivision_id,
                    'subject' => fake()->randomElement([
                        'Meter not working',
                        'High bill amount',
                        'Power outage',
                        'Billing error',
                        'Connection issue'
                    ]),
                    'description' => fake()->paragraph(),
                    'status' => $status,
                    'priority' => fake()->randomElement(['low', 'medium', 'high']),
                    'assigned_to' => $consumer->subdivision->ls_id,
                ]);

                // Add complaint history
                ComplaintHistory::create([
                    'complaint_id' => $complaint->id,
                    'action' => 'created',
                    'remarks' => 'Complaint registered',
                    'user_id' => null,
                ]);

                if ($status !== 'pending') {
                    ComplaintHistory::create([
                        'complaint_id' => $complaint->id,
                        'action' => 'status_changed',
                        'remarks' => "Status changed to {$status}",
                        'user_id' => $consumer->subdivision->ls_id,
                    ]);
                }
            }
        }

        // 8. Create Audit Logs
        $this->command->info('📝 Creating audit logs...');
        foreach (array_slice($applications, 0, 20) as $application) {
            AuditLog::create([
                'user_id' => fake()->randomElement($lsUsers)->id,
                'module' => 'Applications',
                'action' => 'Updated',
                'record_type' => 'Application',
                'record_id' => $application->id,
                'old_values' => json_encode(['status' => 'pending']),
                'new_values' => json_encode(['status' => $application->status]),
                'ip_address' => fake()->ipv4(),
                'user_agent' => 'Mozilla/5.0',
            ]);
        }

        // 9. Create System Settings
        $this->command->info('⚙️ Creating system settings...');
        $settings = [
            ['key' => 'company_name', 'value' => 'MEPCO - Multan Electric Power Company'],
            ['key' => 'company_email', 'value' => 'info@mepco.gov.pk'],
            ['key' => 'company_phone', 'value' => '1800-MEPCO-HELP'],
            ['key' => 'company_address', 'value' => 'Multan, Punjab, Pakistan'],
            ['key' => 'gst_percentage', 'value' => '17'],
            ['key' => 'tv_fee', 'value' => '35'],
            ['key' => 'meter_rent', 'value' => '20'],
        ];

        foreach ($settings as $setting) {
            SystemSetting::create($setting);
        }

        // 10. Create Global Summaries
        $this->command->info('📊 Creating global summaries...');
        foreach (array_slice($applications, 0, 15) as $application) {
            if ($application->status === 'approved') {
                GlobalSummary::create([
                    'application_id' => $application->id,
                    'application_no' => $application->application_no,
                    'customer_name' => $application->customer_name,
                    'meter_no' => $application->meter_number,
                    'sim_date' => now()->subDays(fake()->numberBetween(1, 30)),
                    'date_on_draft_store' => now()->subDays(fake()->numberBetween(1, 25)),
                    'date_received_lm_consumer' => now()->subDays(fake()->numberBetween(1, 20)),
                    'customer_mobile_no' => $application->phone,
                    'customer_sc_no' => fake()->numerify('SC-########'),
                    'date_return_sdc_billing' => now()->subDays(fake()->numberBetween(1, 15)),
                ]);
            }
        }

        $this->command->info('✅ Database seeding completed successfully!');
        $this->command->info('');
        $this->command->info('📌 Login Credentials:');
        $this->command->info('   Admin: admin / password');
        $this->command->info('   LS Users: mepco_ls1, lesco_ls1, fesco_ls1 / password');
        $this->command->info('');
        $this->command->info('📊 Summary:');
        $this->command->info('   Companies: ' . Company::count());
        $this->command->info('   Subdivisions: ' . Subdivision::count());
        $this->command->info('   LS Users: ' . User::where('role', 'ls')->count());
        $this->command->info('   Consumers: ' . Consumer::count());
        $this->command->info('   Applications: ' . Application::count());
        $this->command->info('   Meters: ' . Meter::count());
        $this->command->info('   Bills: ' . Bill::count());
        $this->command->info('   Complaints: ' . Complaint::count());
        $this->command->info('   Tariffs: ' . Tariff::count());
    }
}