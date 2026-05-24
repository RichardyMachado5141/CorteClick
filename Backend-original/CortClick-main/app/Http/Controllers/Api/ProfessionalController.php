<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Professional;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProfessionalController extends Controller
{
    /**
     * Listar todos os profissionais
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Professional::with(['user', 'services']);

            // Filtrar por especialidade
            if ($request->has('specialty')) {
                $query->where('specialty', 'like', "%{$request->input('specialty')}%");
            }

            // Filtrar por nome do profissional
            if ($request->has('name')) {
                $query->whereHas('user', function ($q) {
                    $q->where('name', 'like', "%{$this->request->input('name')}%");
                });
            }

            $professionals = $query->paginate(20);

            return response()->json([
                'status' => 'success',
                'data' => $professionals,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao listar profissionais',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obter um profissional específico
     */
    public function show($id): JsonResponse
    {
        try {
            $professional = Professional::with(['user', 'services'])->findOrFail($id);

            return response()->json([
                'status' => 'success',
                'data' => $professional,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Profissional não encontrado',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Criar um novo profissional
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Verificar se o usuário é professional
            if ($request->user()->role !== 'professional' && $request->user()->role !== 'admin') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Não autorizado',
                ], 403);
            }

            $validated = $request->validate([
                'specialty' => 'required|string|max:255',
                'description' => 'nullable|string',
                'phone' => 'nullable|string|max:20',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i|after:start_time',
                'available_days' => 'nullable|array',
            ]);

            // Se o usuário é professional, usar seu próprio ID
            $userId = $request->user()->role === 'professional' ? $request->user()->id : $request->input('user_id');

            $professional = Professional::create([
                'user_id' => $userId,
                'specialty' => $validated['specialty'],
                'description' => $validated['description'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'available_days' => $validated['available_days'] ?? ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'],
            ]);

            $this->logUserAction($request->user(), 'create', 'Professional', $professional->id);

            return response()->json([
                'status' => 'success',
                'message' => 'Profissional criado com sucesso',
                'data' => $professional->load('user', 'services'),
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
                'message' => 'Erro ao criar profissional',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Atualizar um profissional
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $professional = Professional::findOrFail($id);

            // Verificar permissão
            if ($request->user()->id !== $professional->user_id && $request->user()->role !== 'admin') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Não autorizado',
                ], 403);
            }

            $validated = $request->validate([
                'specialty' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|nullable|string',
                'phone' => 'sometimes|nullable|string|max:20',
                'start_time' => 'sometimes|required|date_format:H:i',
                'end_time' => 'sometimes|required|date_format:H:i|after:start_time',
                'available_days' => 'sometimes|nullable|array',
            ]);

            $professional->update($validated);

            $this->logUserAction($request->user(), 'update', 'Professional', $id);

            return response()->json([
                'status' => 'success',
                'message' => 'Profissional atualizado com sucesso',
                'data' => $professional->load('user', 'services'),
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
                'message' => 'Erro ao atualizar profissional',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Deletar um profissional
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        try {
            $professional = Professional::findOrFail($id);

            // Verificar permissão
            if ($request->user()->id !== $professional->user_id && $request->user()->role !== 'admin') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Não autorizado',
                ], 403);
            }

            $this->logUserAction($request->user(), 'delete', 'Professional', $id);

            $professional->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Profissional deletado com sucesso',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao deletar profissional',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Log de ações do usuário
     */
    private function logUserAction($user, $action, $model = null, $modelId = null)
    {
        try {
            \App\Models\UserLog::create([
                'user_id' => $user->id,
                'action' => $action,
                'model' => $model,
                'model_id' => $modelId,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Exception $e) {
            // Silenciosamente falhar no log
        }
    }
}
