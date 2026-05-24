<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Professional;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    /**
     * Obter horários disponíveis de um profissional para uma data específica
     */
    public function getAvailableSlots(Request $request, $professionalId): JsonResponse
    {
        try {
            $request->validate([
                'date' => 'required|date_format:Y-m-d|after_or_equal:today',
                'service_id' => 'required|integer|exists:services,id',
                'duration' => 'nullable|integer|min:15',
            ]);

            $professional = Professional::findOrFail($professionalId);
            $date = $request->input('date');
            $service = Service::findOrFail($request->input('service_id'));

            // Verificar se o serviço pertence ao profissional
            if ($service->professional_id != $professionalId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Serviço não pertence ao profissional',
                ], 422);
            }

            $duration = $request->input('duration', $service->duration);

            // Obter slots disponíveis
            $slots = $this->getAvailableTimeslots($professional, $date, $duration);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'professional_id' => $professionalId,
                    'date' => $date,
                    'service_id' => $service->id,
                    'duration' => $duration,
                    'available_slots' => $slots,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao obter horários disponíveis',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obter horários disponíveis para múltiplos dias
     */
    public function getAvailableRange(Request $request, $professionalId): JsonResponse
    {
        try {
            $request->validate([
                'from_date' => 'required|date_format:Y-m-d|after_or_equal:today',
                'to_date' => 'required|date_format:Y-m-d|after_or_equal:from_date',
                'service_id' => 'required|integer|exists:services,id',
                'duration' => 'nullable|integer|min:15',
            ]);

            $professional = Professional::findOrFail($professionalId);
            $fromDate = Carbon::createFromFormat('Y-m-d', $request->input('from_date'));
            $toDate = Carbon::createFromFormat('Y-m-d', $request->input('to_date'));
            $service = Service::findOrFail($request->input('service_id'));

            // Verificar se o serviço pertence ao profissional
            if ($service->professional_id != $professionalId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Serviço não pertence ao profissional',
                ], 422);
            }

            $duration = $request->input('duration', $service->duration);
            $availability = [];

            // Iterar por cada dia no intervalo
            while ($fromDate <= $toDate) {
                $dateStr = $fromDate->format('Y-m-d');
                $slots = $this->getAvailableTimeslots($professional, $dateStr, $duration);

                if (!empty($slots)) {
                    $availability[$dateStr] = $slots;
                }

                $fromDate->addDay();
            }

            return response()->json([
                'status' => 'success',
                'data' => [
                    'professional_id' => $professionalId,
                    'duration' => $duration,
                    'availability' => $availability,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao obter horários disponíveis',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obter slots disponíveis para um profissional em uma data específica
     */
    private function getAvailableTimeslots($professional, $date, $duration = 30)
    {
        // Verificar se o dia da semana é disponível
        $dayOfWeek = strtolower(Carbon::createFromFormat('Y-m-d', $date)->format('l'));

        $dayMap = [
            'monday' => 'monday',
            'tuesday' => 'tuesday',
            'wednesday' => 'wednesday',
            'thursday' => 'thursday',
            'friday' => 'friday',
            'saturday' => 'saturday',
            'sunday' => 'sunday',
        ];

        $availableDays = $professional->available_days ?? [];
        
        if (!in_array($dayMap[$dayOfWeek], $availableDays)) {
            return [];
        }

        $startTime = Carbon::createFromFormat('Y-m-d H:i', $date . ' ' . $professional->start_time);
        $endTime = Carbon::createFromFormat('Y-m-d H:i', $date . ' ' . $professional->end_time);

        // Se a data é hoje, começar do próximo slot disponível
        $now = Carbon::now();
        if ($startTime->isSameDay($now) && $startTime < $now) {
            // Encontrar o próximo slot após agora
            $startTime = $now->copy()->addMinutes($duration - ($now->minute % $duration));
        }

        $slots = [];

        // Obter agendamentos já marcados
        $bookedAppointments = $professional->appointments()
            ->whereDate('appointment_date', $date)
            ->where('status', '!=', 'cancelled')
            ->get(['appointment_date']);

        $currentTime = $startTime->copy();

        while ($currentTime->addMinutes($duration) <= $endTime) {
            $slotEnd = $currentTime->copy()->addMinutes($duration);

            // Verificar se o slot está livre
            $isBooked = false;
            foreach ($bookedAppointments as $appointment) {
                $appointmentStart = Carbon::createFromFormat('Y-m-d H:i:s', $appointment->appointment_date);
                $appointmentEnd = $appointmentStart->copy()->addMinutes($duration);

                if ($currentTime < $appointmentEnd && $slotEnd > $appointmentStart) {
                    $isBooked = true;
                    break;
                }
            }

            if (!$isBooked) {
                $slots[] = $currentTime->format('H:i');
            }

            $currentTime = $slotEnd;
        }

        return $slots;
    }
}
