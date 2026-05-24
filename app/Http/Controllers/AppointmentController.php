<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;

class AppointmentController extends Controller
{
    public function store(Request $request)
    {
        $appointment = Appointment::create([
            'cliente' => $request->cliente,
            'profissional' => $request->profissional,
            'servico' => $request->servico,
            'data' => $request->data,
            'horario' => $request->horario,
            'status' => 'pendente',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Agendamento criado',
            'data' => $appointment,
        ]);
    }

    public function index()
    {
        return response()->json(
            Appointment::all()
        );
    }
}