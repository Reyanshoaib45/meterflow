<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Company;
use App\Models\Subdivision;
use App\Models\Application;
use App\Models\Meter;
use App\Models\ApplicationHistory;
use App\Models\ApplicationSummary;
use App\Models\ExtraSummary;

class MEPCTest extends TestCase
{
    /**
     * Test MEPCO database relationships.
     *
     * @return void
     */
    public function test_mepco_relationships()
    {
        // Create a company
        $company = Company::factory()->create();
        
        // Create a subdivision for the company
        $subdivision = Subdivision::factory()->create([
            'company_id' => $company->id,
        ]);
        
        // Create an application
        $application = Application::factory()->create([
            'company_id' => $company->id,
            'subdivision_id' => $subdivision->id,
        ]);
        
        // Create a meter for the application
        $meter = Meter::factory()->create([
            'application_id' => $application->id,
        ]);
        
        // Create application history
        $history = ApplicationHistory::factory()->create([
            'application_id' => $application->id,
            'subdivision_id' => $subdivision->id,
            'company_id' => $company->id,
        ]);
        
        // Create application summary
        $summary = ApplicationSummary::factory()->create([
            'application_id' => $application->id,
        ]);
        
        // Create extra summary
        $extraSummary = ExtraSummary::factory()->create([
            'company_id' => $company->id,
            'subdivision_id' => $subdivision->id,
        ]);
        
        // Test relationships
        $this->assertEquals(1, $company->subdivisions()->count());
        $this->assertEquals($company->id, $subdivision->company->id);
        $this->assertEquals(1, $subdivision->applications()->count());
        $this->assertEquals($subdivision->id, $application->subdivision->id);
        $this->assertEquals($company->id, $application->company->id);
        $this->assertEquals($meter->id, $application->meter->id);
        $this->assertEquals(1, $application->histories()->count());
        $this->assertEquals($application->id, $history->application->id);
        $this->assertEquals($application->id, $summary->application->id);
        $this->assertEquals($company->id, $extraSummary->company->id);
        $this->assertEquals($subdivision->id, $extraSummary->subdivision->id);
        
        // Test cascading deletes
        $companyId = $company->id;
        $subdivisionId = $subdivision->id;
        $applicationId = $application->id;
        
        $company->delete();
        
        $this->assertNull(Company::find($companyId));
        $this->assertNull(Subdivision::find($subdivisionId));
        $this->assertNull(Application::find($applicationId));
        $this->assertNull(Meter::find($meter->id));
        $this->assertNull(ApplicationHistory::find($history->id));
        $this->assertNull(ApplicationSummary::find($summary->id));
    }
}