<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ApplicationSummary;
use App\Models\Application;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ApplicationSummary>
 */
class ApplicationSummaryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ApplicationSummary::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'application_id' => Application::factory(),
            'total_meters' => $this->faker->numberBetween(1, 10),
            'total_load' => $this->faker->randomFloat(2, 10, 1000),
            'avg_reading' => $this->faker->randomFloat(2, 50, 500),
            'remarks' => $this->faker->optional()->sentence(),
        ];
    }
}