<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\GlobalSummary;

class GlobalSummaryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_display_global_summaries_index_page()
    {
        // Create a user and authenticate
        $user = User::factory()->create();
        
        // Create some global summaries
        GlobalSummary::factory()->count(3)->create();

        // Act as the user and visit the index page
        $response = $this->actingAs($user)->get(route('admin.global-summaries.index'));

        // Assert the response is successful
        $response->assertStatus(200);
        
        // Assert that the view contains the global summaries
        $response->assertViewHas('globalSummaries');
    }

    /** @test */
    public function it_can_create_a_new_global_summary()
    {
        // Create a user and authenticate
        $user = User::factory()->create();
        
        // Create an application for the global summary
        $application = \App\Models\Application::factory()->create();

        // Data for the new global summary
        $data = [
            'application_id' => $application->id,
            'sim_date' => '2025-10-22',
            'date_on_draft_store' => '2025-10-23',
            'date_received_lm_consumer' => '2025-10-24',
            'customer_mobile_no' => '03001234567',
            'customer_sc_no' => 'SC-12345',
            'date_return_sdc_billing' => '2025-10-25',
        ];

        // Act as the user and submit the form
        $response = $this->actingAs($user)->post(route('admin.global-summaries.store'), $data);

        // Assert the response redirects to the index page
        $response->assertRedirect(route('admin.global-summaries.index'));
        
        // Assert a success message is displayed
        $response->assertSessionHas('success');
        
        // Assert the global summary was created in the database
        $this->assertDatabaseHas('global_summaries', [
            'application_id' => $application->id,
            'customer_mobile_no' => '03001234567',
        ]);
    }
}