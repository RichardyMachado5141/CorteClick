<?php

namespace Database\Factories;

use App\Models\Professional;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'professional_id' => Professional::factory(),
            'name' => $this->faker->sentence(2),
            'price' => $this->faker->randomFloat(2, 30, 200),
            'duration' => $this->faker->randomElement([15, 30, 45, 60, 90, 120]),
            'description' => $this->faker->paragraph(),
            'is_active' => true,
        ];
    }
}
