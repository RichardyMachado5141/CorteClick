<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Professional;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $professional = Professional::inRandomOrder()->first() ?? Professional::factory()->create();
        $service = $professional->services->first() ?? Service::factory()->create(['professional_id' => $professional->id]);
        $client = User::where('role', 'client')->inRandomOrder()->first() ?? User::factory()->create(['role' => 'client']);

        return [
            'client_id' => $client->id,
            'professional_id' => $professional->id,
            'service_id' => $service->id,
            'appointment_date' => $this->faker->dateTimeBetween('+1 days', '+30 days'),
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'completed', 'cancelled']),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
