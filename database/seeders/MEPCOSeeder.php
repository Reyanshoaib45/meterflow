<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\Subdivision;
use App\Models\Application;
use App\Models\Meter;
use App\Models\ApplicationHistory;
use App\Models\ApplicationSummary;
use App\Models\ExtraSummary;

class MEPCOSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create companies
        $companies = Company::factory()->count(3)->create();
        
        // Create subdivisions for each company
        foreach ($companies as $company) {
            $subdivisions = Subdivision::factory()->count(2)->create([
                'company_id' => $company->id,
            ]);
            
            // Create applications for each subdivision
            foreach ($subdivisions as $subdivision) {
                $applications = Application::factory()->count(3)->create([
                    'company_id' => $company->id,
                    'subdivision_id' => $subdivision->id,
                ]);
                
                // Create meters, histories, and summaries for each application
                foreach ($applications as $application) {
                    // Create meter for application
                    Meter::factory()->create([
                        'application_id' => $application->id,
                    ]);
                    
                    // Create application history
                    ApplicationHistory::factory()->count(2)->create([
                        'application_id' => $application->id,
                        'subdivision_id' => $subdivision->id,
                        'company_id' => $company->id,
                    ]);
                    
                    // Create application summary
                    ApplicationSummary::factory()->create([
                        'application_id' => $application->id,
                    ]);
                }
            }
            
            // Create extra summaries for company and subdivisions
            foreach ($subdivisions as $subdivision) {
                ExtraSummary::factory()->create([
                    'company_id' => $company->id,
                    'subdivision_id' => $subdivision->id,
                ]);
            }
        }
    }
}