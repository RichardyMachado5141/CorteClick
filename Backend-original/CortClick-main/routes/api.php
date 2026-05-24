<?php

use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AvailabilityController;
use App\Http\Controllers\Api\ProfessionalController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

// Rotas de autenticação (públicas)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rotas públicas de consulta
Route::get('/professionals', [ProfessionalController::class, 'index']);
Route::get('/professionals/{id}', [ProfessionalController::class, 'show']);
Route::get('/services', [ServiceController::class, 'index']);
Route::get('/services/{id}', [ServiceController::class, 'show']);
Route::get('/professionals/{professionalId}/services', [ServiceController::class, 'index']);
Route::get('/availability/slots/{professionalId}', [AvailabilityController::class, 'getAvailableSlots']);
Route::get('/availability/range/{professionalId}', [AvailabilityController::class, 'getAvailableRange']);

// Rotas protegidas (requerem autenticação)
Route::middleware('auth:sanctum')->group(function () {
    // Autenticação
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);

    // Usuários
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    Route::get('/users/search', [UserController::class, 'search']);

    // Profissionais
    Route::post('/professionals', [ProfessionalController::class, 'store']);
    Route::put('/professionals/{id}', [ProfessionalController::class, 'update']);
    Route::delete('/professionals/{id}', [ProfessionalController::class, 'destroy']);

    // Serviços
    Route::post('/services', [ServiceController::class, 'store']);
    Route::put('/services/{id}', [ServiceController::class, 'update']);
    Route::delete('/services/{id}', [ServiceController::class, 'destroy']);

    // Agendamentos
    Route::get('/appointments', [AppointmentController::class, 'index']);
    Route::post('/appointments', [AppointmentController::class, 'store']);
    Route::get('/appointments/{id}', [AppointmentController::class, 'show']);
    Route::put('/appointments/{id}', [AppointmentController::class, 'update']);
    Route::put('/appointments/{id}/status', [AppointmentController::class, 'updateStatus']);
    Route::put('/appointments/{id}/cancel', [AppointmentController::class, 'cancel']);
    Route::delete('/appointments/{id}', [AppointmentController::class, 'destroy']);
});
