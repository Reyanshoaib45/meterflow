<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemSetting;

class SystemSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('⚙️ Creating system settings...');

        $settings = [
            ['key' => 'company_name', 'value' => 'MEPCO - Multan Electric Power Company'],
            ['key' => 'company_email', 'value' => 'info@mepco.gov.pk'],
            ['key' => 'company_phone', 'value' => '03006380386'],
            ['key' => 'company_phone_secondary', 'value' => '03009615771'],
            ['key' => 'company_address', 'value' => 'Multan, Punjab, Pakistan'],
            ['key' => 'company_website', 'value' => 'https://meterflownation.com'],
            ['key' => 'gst_percentage', 'value' => '17'],
            ['key' => 'tv_fee', 'value' => '35'],
            ['key' => 'meter_rent', 'value' => '20'],
            ['key' => 'late_payment_surcharge', 'value' => '5'],
            ['key' => 'application_fee', 'value' => '500'],
            ['key' => 'maintenance_mode', 'value' => '0'],
            ['key' => 'currency', 'value' => 'PKR'],
            ['key' => 'timezone', 'value' => 'Asia/Karachi'],
        ];

        foreach ($settings as $setting) {
            SystemSetting::firstOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        $this->command->info('   ✓ Created ' . count($settings) . ' system settings');
    }
}

