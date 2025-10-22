<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ApplicationHistory;
use App\Models\Application;
use App\Models\Subdivision;
use App\Models\Company;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ApplicationHistory>
 */
class ApplicationHistoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ApplicationHistory::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'application_id' => Application::factory(),
            'meter_number' => $this->faker->numerify('MTR-######'),
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'phone_number' => $this->faker->phoneNumber(),
            'subdivision_id' => Subdivision::factory(),
            'company_id' => Company::factory(),
            'action_type' => $this->faker->randomElement(['submitted', 'verified', 'approved', 'rejected']),
            'remarks' => $this->faker->optional()->sentence(),
        ];
    }
}