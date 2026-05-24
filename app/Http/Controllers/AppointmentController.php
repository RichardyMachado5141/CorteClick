<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;

class AppointmentController extends Controller
{
    public function store(Request $request)
    {
        Appointment::create([
            'cliente' => $request->cliente,
            'profissional' => $request->profissional,
            'servico' => $request->servico,
            'data' => $request->data,
            'horario' => $request->horario,
            'status' => 'pendente'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Agendamento criado'
        ]);
    }

    public function index()
    {
        return response()->json(
            Appointment::all()
        );
    }
}