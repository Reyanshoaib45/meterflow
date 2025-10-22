<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ExtraSummary;
use App\Models\Company;
use App\Models\Subdivision;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExtraSummary>
 */
class ExtraSummaryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ExtraSummary::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'subdivision_id' => Subdivision::factory(),
            'total_applications' => $this->faker->numberBetween(0, 1000),
            'approved_count' => $this->faker->numberBetween(0, 500),
            'rejected_count' => $this->faker->numberBetween(0, 100),
            'pending_count' => $this->faker->numberBetween(0, 400),
            'last_updated' => $this->faker->dateTime(),
        ];
    }
}