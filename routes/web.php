<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfissionalController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::get('/cadastro', fn () => view('auth.cadastro'))->name('cadastro');
Route::get('/recuperar-senha', fn () => view('auth.recuperar-senha'))->name('recuperar-senha');

Route::get('/perfil', [ProfileController::class, 'select'])->name('perfil');

Route::get('/appointments', [AppointmentController::class, 'index']);
Route::post('/appointments', [AppointmentController::class, 'store']);

Route::prefix('cliente')->name('cliente.')->group(function () {
    Route::redirect('/', '/cliente/dashboard');
    Route::get('/dashboard', [ClienteController::class, 'dashboard'])->name('dashboard');
    Route::redirect('/servicos', '/cliente/dashboard');
    Route::get('/agendamentos', [ClienteController::class, 'agendamentos'])->name('agendamentos');
});

Route::prefix('profissional')->name('profissional.')->group(function () {
    Route::redirect('/', '/profissional/dashboard');
    Route::get('/dashboard', [ProfissionalController::class, 'dashboard'])->name('dashboard');
    Route::redirect('/agenda', '/profissional/dashboard');
    Route::get('/servicos', [ProfissionalController::class, 'servicos'])->name('servicos');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::redirect('/', '/admin/dashboard');
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/usuarios', [AdminController::class, 'usuarios'])->name('usuarios');
    Route::get('/agendamentos', [AdminController::class, 'agendamentos'])->name('agendamentos');
});
