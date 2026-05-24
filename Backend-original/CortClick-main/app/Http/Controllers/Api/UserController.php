<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Listar todos os usuários (apenas admin)
     */
    public function index(Request $request): JsonResponse
    {
        try {
            if ($request->user()->role !== 'admin') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Não autorizado',
                ], 403);
            }

            $users = User::paginate(20);

            return response()->json([
                'status' => 'success',
                'data' => $users,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao listar usuários',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obter um usuário específico
     */
    public function show(Request $request, $id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);

            // Verificar permissão
            if ($request->user()->id !== $user->id && $request->user()->role !== 'admin') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Não autorizado',
                ], 403);
            }

            return response()->json([
                'status' => 'success',
                'data' => $user,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Usuário não encontrado',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Atualizar usuário
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);

            // Verificar permissão
            if ($request->user()->id !== $user->id && $request->user()->role !== 'admin') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Não autorizado',
                ], 403);
            }

            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|email|unique:users,email,' . $id,
                'phone' => 'sometimes|nullable|string|max:20',
                'role' => 'sometimes|in:client,professional,admin',
            ]);

            $user->update($validated);

            // Log de atualização
            $this->logUserAction($request->user(), 'update', 'User', $id, $validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Usuário atualizado com sucesso',
                'data' => $user,
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
                'message' => 'Erro ao atualizar usuário',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Deletar usuário
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);

            // Verificar permissão
            if ($request->user()->id !== $user->id && $request->user()->role !== 'admin') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Não autorizado',
                ], 403);
            }

            // Log de deleção
            $this->logUserAction($request->user(), 'delete', 'User', $id);

            $user->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Usuário deletado com sucesso',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao deletar usuário',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Buscar usuários por nome ou email
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $query = $request->input('q', '');
            
            if (strlen($query) < 2) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Termo de busca deve ter no mínimo 2 caracteres',
                ], 400);
            }

            $users = User::where('name', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%")
                ->paginate(20);

            return response()->json([
                'status' => 'success',
                'data' => $users,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro na busca',
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
