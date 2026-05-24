<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Professional;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Criar usuários de teste
        $clientUsers = User::factory()
            ->count(5)
            ->create(['role' => 'client']);

        $professionalUsers = User::factory()
            ->count(3)
            ->create(['role' => 'professional']);

        $adminUser = User::factory()
            ->create([
                'name' => 'Admin User',
                'email' => 'admin@corteclick.com',
                'role' => 'admin',
            ]);

        // Criar profissionais com serviços
        foreach ($professionalUsers as $user) {
            $professional = Professional::create([
                'user_id' => $user->id,
                'specialty' => collect([
                    'Corte de Cabelo',
                    'Barba',
                    'Coloração',
                    'Alisamento',
                    'Tratamento Capilar',
                ])->random(),
                'description' => fake()->text(200),
                'phone' => fake()->phoneNumber(),
                'start_time' => '09:00',
                'end_time' => '18:00',
                'available_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'],
            ]);

            // Criar serviços para o profissional
            Service::factory()
                ->count(3)
                ->create(['professional_id' => $professional->id]);
        }

        // Criar alguns agendamentos de teste
        $professionals = Professional::all();
        $clients = $clientUsers;

        foreach (range(1, 10) as $i) {
            $professional = $professionals->random();
            $service = $professional->services->random();
            $client = $clients->random();

            Appointment::create([
                'client_id' => $client->id,
                'professional_id' => $professional->id,
                'service_id' => $service->id,
                'appointment_date' => fake()->dateTimeBetween('+1 days', '+30 days'),
                'status' => collect(['pending', 'confirmed', 'completed'])->random(),
                'notes' => fake()->sentence(),
            ]);
        }
    }
}
