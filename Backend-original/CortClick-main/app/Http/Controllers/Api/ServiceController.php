<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ServiceController extends Controller
{
    /**
     * Listar todos os serviços de um profissional
     */
    public function index(Request $request, $professionalId = null): JsonResponse
    {
        try {
            $query = Service::with('professional.user');

            if ($professionalId) {
                $query->where('professional_id', $professionalId);
            }

            if ($request->has('active')) {
                $query->where('is_active', filter_var($request->input('active'), FILTER_VALIDATE_BOOLEAN));
            }

            $services = $query->paginate(20);

            return response()->json([
                'status' => 'success',
                'data' => $services,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao listar serviços',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obter um serviço específico
     */
    public function show($id): JsonResponse
    {
        try {
            $service = Service::with('professional.user')->findOrFail($id);

            return response()->json([
                'status' => 'success',
                'data' => $service,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Serviço não encontrado',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Criar um novo serviço
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            // Verificar se o usuário é profissional
            if ($user->role !== 'professional' && $user->role !== 'admin') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Não autorizado',
                ], 403);
            }

            $validated = $request->validate([
                'professional_id' => 'required_if:role,admin|integer|exists:professionals,id',
                'name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'duration' => 'required|integer|min:15',
                'description' => 'nullable|string',
                'is_active' => 'sometimes|boolean',
            ]);

            // Se é profissional, usar seu professional_id
            if ($user->role === 'professional') {
                $professional = $user->professional;
                if (!$professional) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Profissional não encontrado',
                    ], 404);
                }
                $validated['professional_id'] = $professional->id;
            }

            $service = Service::create([
                'professional_id' => $validated['professional_id'],
                'name' => $validated['name'],
                'price' => $validated['price'],
                'duration' => $validated['duration'],
                'description' => $validated['description'] ?? null,
                'is_active' => $validated['is_active'] ?? true,
            ]);

            $this->logUserAction($user, 'create', 'Service', $service->id);

            return response()->json([
                'status' => 'success',
                'message' => 'Serviço criado com sucesso',
                'data' => $service->load('professional.user'),
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
                'message' => 'Erro ao criar serviço',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Atualizar um serviço
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $service = Service::findOrFail($id);
            $user = $request->user();

            // Verificar permissão
            if ($user->id !== $service->professional->user_id && $user->role !== 'admin') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Não autorizado',
                ], 403);
            }

            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'price' => 'sometimes|required|numeric|min:0',
                'duration' => 'sometimes|required|integer|min:15',
                'description' => 'sometimes|nullable|string',
                'is_active' => 'sometimes|boolean',
            ]);

            $service->update($validated);

            $this->logUserAction($user, 'update', 'Service', $id);

            return response()->json([
                'status' => 'success',
                'message' => 'Serviço atualizado com sucesso',
                'data' => $service->load('professional.user'),
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
                'message' => 'Erro ao atualizar serviço',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Deletar um serviço
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        try {
            $service = Service::findOrFail($id);
            $user = $request->user();

            // Verificar permissão
            if ($user->id !== $service->professional->user_id && $user->role !== 'admin') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Não autorizado',
                ], 403);
            }

            $this->logUserAction($user, 'delete', 'Service', $id);

            $service->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Serviço deletado com sucesso',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao deletar serviço',
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
