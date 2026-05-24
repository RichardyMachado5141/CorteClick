<?php

namespace App\Http\Controllers;

use App\Data\MockData;

class ClienteController extends Controller
{
    public function dashboard()
    {
        return view('cliente.dashboard', [
            'stats' => MockData::clienteDashboardStats(),
            'pageData' => [
                'servicos' => MockData::servicos(),
                'profissionais' => MockData::profissionais(),
                'agendamentos' => MockData::agendamentosCliente(),
            ],
        ]);
    }

    public function agendamentos()
    {
        return view('cliente.agendamentos', [
            'pageData' => [
                'agendamentos' => MockData::agendamentosCliente(),
            ],
        ]);
    }
}
