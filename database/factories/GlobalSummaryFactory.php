<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\GlobalSummary;
use App\Models\Application;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GlobalSummary>
 */
class GlobalSummaryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GlobalSummary::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'application_no' => $this->faker->unique()->numerify('APP-#####'),
            'customer_name' => $this->faker->name(),
            'meter_no' => $this->faker->unique()->numerify('MTR-#####'),
            'sim_date' => $this->faker->date(),
            'date_on_draft_store' => $this->faker->date(),
            'date_received_lm_consumer' => $this->faker->date(),
            'customer_mobile_no' => $this->faker->phoneNumber(),
            'customer_sc_no' => $this->faker->unique()->numerify('SC-#####'),
            'date_return_sdc_billing' => $this->faker->date(),
            'application_id' => Application::factory(),
        ];
    }
}