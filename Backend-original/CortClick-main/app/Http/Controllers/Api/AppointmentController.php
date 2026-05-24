<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Professional;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AppointmentController extends Controller
{
    /**
     * Listar agendamentos
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $query = Appointment::with(['client', 'professional.user', 'service']);

            // Filtrar por usuário
            if ($user->role === 'client') {
                $query->where('client_id', $user->id);
            } elseif ($user->role === 'professional') {
                $query->where('professional_id', $user->professional->id);
            }

            // Filtrar por status
            if ($request->has('status')) {
                $query->where('status', $request->input('status'));
            }

            // Filtrar por data
            if ($request->has('from_date')) {
                $query->whereDate('appointment_date', '>=', $request->input('from_date'));
            }

            if ($request->has('to_date')) {
                $query->whereDate('appointment_date', '<=', $request->input('to_date'));
            }

            $appointments = $query->orderBy('appointment_date', 'asc')->paginate(20);

            return response()->json([
                'status' => 'success',
                'data' => $appointments,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao listar agendamentos',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obter um agendamento específico
     */
    public function show(Request $request, $id): JsonResponse
    {
        try {
            $appointment = Appointment::with(['client', 'professional.user', 'service'])->findOrFail($id);
            $user = $request->user();

            // Verificar permissão
            if ($user->id !== $appointment->client_id && 
                $user->id !== $appointment->professional->user_id && 
                $user->role !== 'admin') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Não autorizado',
                ], 403);
            }

            return response()->json([
                'status' => 'success',
                'data' => $appointment,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Agendamento não encontrado',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Criar um novo agendamento
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            // Apenas clientes podem criar agendamentos
            if ($user->role !== 'client' && $user->role !== 'admin') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Não autorizado',
                ], 403);
            }

            $validated = $request->validate([
                'professional_id' => 'required|integer|exists:professionals,id',
                'service_id' => 'required|integer|exists:services,id',
                'appointment_date' => 'required|date_format:Y-m-d H:i|after:now',
                'notes' => 'nullable|string',
            ]);

            // Verificar se o serviço pertence ao profissional
            $service = Service::findOrFail($validated['service_id']);
            if ($service->professional_id != $validated['professional_id']) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Serviço não pertence ao profissional selecionado',
                ], 422);
            }

            // Verificar disponibilidade
            $appointmentTime = Carbon::createFromFormat('Y-m-d H:i', $validated['appointment_date']);
            $professional = Professional::findOrFail($validated['professional_id']);

            // Verificar se o horário está disponível
            $conflictingAppointment = Appointment::where('professional_id', $validated['professional_id'])
                ->where('appointment_date', $appointmentTime)
                ->where('status', '!=', 'cancelled')
                ->first();

            if ($conflictingAppointment) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Horário não está disponível',
                ], 422);
            }

            // Criar agendamento
            $appointment = Appointment::create([
                'client_id' => $user->role === 'admin' ? $request->input('client_id', $user->id) : $user->id,
                'professional_id' => $validated['professional_id'],
                'service_id' => $validated['service_id'],
                'appointment_date' => $validated['appointment_date'],
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
            ]);

            $this->logUserAction($user, 'create', 'Appointment', $appointment->id);

            return response()->json([
                'status' => 'success',
                'message' => 'Agendamento criado com sucesso',
                'data' => $appointment->load(['client', 'professional.user', 'service']),
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro na validação',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao criar agendamento',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Atualizar status de um agendamento
     */
    public function updateStatus(Request $request, $id): JsonResponse
    {
        try {
            $appointment = Appointment::findOrFail($id);
            $user = $request->user();

            // Apenas o profissional ou admin pode alterar o status
            if ($user->id !== $appointment->professional->user_id && $user->role !== 'admin') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Não autorizado',
                ], 403);
            }

            $validated = $request->validate([
                'status' => 'required|in:pending,confirmed,completed,cancelled',
            ]);

            $appointment->update(['status' => $validated['status']]);

            $this->logUserAction($user, 'update_status', 'Appointment', $id, ['new_status' => $validated['status']]);

            return response()->json([
                'status' => 'success',
                'message' => 'Status do agendamento atualizado',
                'data' => $appointment->load(['client', 'professional.user', 'service']),
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro na validação',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao atualizar agendamento',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Atualizar um agendamento
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $appointment = Appointment::findOrFail($id);
            $user = $request->user();

            // Verificar permissão
            if ($user->id !== $appointment->client_id && $user->role !== 'admin') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Não autorizado',
                ], 403);
            }

            $validated = $request->validate([
                'appointment_date' => 'sometimes|required|date_format:Y-m-d H:i|after:now',
                'notes' => 'sometimes|nullable|string',
            ]);

            $appointment->update($validated);

            $this->logUserAction($user, 'update', 'Appointment', $id);

            return response()->json([
                'status' => 'success',
                'message' => 'Agendamento atualizado com sucesso',
                'data' => $appointment->load(['client', 'professional.user', 'service']),
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro na validação',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao atualizar agendamento',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Cancelar um agendamento
     */
    public function cancel(Request $request, $id): JsonResponse
    {
        try {
            $appointment = Appointment::findOrFail($id);
            $user = $request->user();

            // Verificar permissão
            if ($user->id !== $appointment->client_id && 
                $user->id !== $appointment->professional->user_id && 
                $user->role !== 'admin') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Não autorizado',
                ], 403);
            }

            $appointment->update(['status' => 'cancelled']);

            $this->logUserAction($user, 'cancel', 'Appointment', $id);

            return response()->json([
                'status' => 'success',
                'message' => 'Agendamento cancelado com sucesso',
                'data' => $appointment->load(['client', 'professional.user', 'service']),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao cancelar agendamento',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Deletar um agendamento
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        try {
            $appointment = Appointment::findOrFail($id);
            $user = $request->user();

            // Verificar permissão
            if ($user->id !== $appointment->client_id && $user->role !== 'admin') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Não autorizado',
                ], 403);
            }

            $this->logUserAction($user, 'delete', 'Appointment', $id);

            $appointment->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Agendamento deletado com sucesso',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao deletar agendamento',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Log de ações do usuário
     */
    private function logUserAction($user, $action, $model = null, $modelId = null, $additionalData = [])
    {
        try {
            $data = array_merge($additionalData, [
                'timestamp' => now(),
            ]);

            \App\Models\UserLog::create([
                'user_id' => $user->id,
                'action' => $action,
                'model' => $model,
                'model_id' => $modelId,
                'data' => json_encode($data),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Exception $e) {
            // Silenciosamente falhar no log
        }
    }
}
