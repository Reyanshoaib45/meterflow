<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Meter;
use App\Models\Application;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Meter>
 */
class MeterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Meter::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'meter_no' => $this->faker->unique()->numerify('MTR-######'),
            'meter_make' => $this->faker->company(),
            'reading' => $this->faker->randomFloat(2, 0, 1000),
            'remarks' => $this->faker->sentence(),
            'sim_number' => $this->faker->unique()->numerify('SIM-##########'),
            'application_id' => Application::factory(),
        ];
    }
}