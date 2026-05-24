<?php

namespace Database\Factories;

use App\Models\Professional;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Professional>
 */
class ProfessionalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create(['role' => 'professional'])->id,
            'specialty' => $this->faker->randomElement([
                'Corte de Cabelo',
                'Barba',
                'Coloração',
                'Alisamento',
                'Tratamento Capilar',
                'Design de Sobrancelha',
                'Limpeza de Pele',
            ]),
            'description' => $this->faker->paragraph(),
            'phone' => $this->faker->phoneNumber(),
            'start_time' => '09:00',
            'end_time' => '18:00',
            'available_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'],
        ];
    }
}
