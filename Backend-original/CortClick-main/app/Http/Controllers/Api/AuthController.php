<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Cadastrar um novo usuário
     */
    public function register(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
                'phone' => 'nullable|string|max:20',
                'role' => 'required|in:client,professional,admin',
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'phone' => $validated['phone'] ?? null,
                'role' => $validated['role'],
            ]);

            // Log de registro
            $this->logUserAction($user, 'register', 'User', $user->id);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'Usuário registrado com sucesso',
                'data' => [
                    'user' => $user,
                    'token' => $token,
                ],
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro na validação',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Autenticar um usuário
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string|min:8',
            ]);

            $user = User::where('email', $validated['email'])->first();

            if (!$user || !Hash::check($validated['password'], $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['As credenciais fornecidas estão incorretas.'],
                ]);
            }

            // Log de login
            $this->logUserAction($user, 'login', 'User', $user->id, [
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'Login realizado com sucesso',
                'data' => [
                    'user' => $user,
                    'token' => $token,
                ],
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro na autenticação',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Fazer logout
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            
            // Log de logout
            $this->logUserAction($user, 'logout', 'User', $user->id, [
                'ip_address' => $request->ip(),
            ]);

            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Logout realizado com sucesso',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao fazer logout',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obter perfil do usuário autenticado
     */
    public function profile(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            
            if ($user->role === 'professional') {
                $user->load('professional.services');
            }

            return response()->json([
                'status' => 'success',
                'data' => $user,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao obter perfil',
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
