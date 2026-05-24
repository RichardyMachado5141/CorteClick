<?php

namespace App\Http\Controllers;

use App\Data\MockData;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard', [
            'stats' => MockData::dashboardStats(),
            'atividades' => MockData::atividadesRecentes(),
        ]);
    }

    public function usuarios()
    {
        return view('admin.usuarios', [
            'pageData' => [
                'usuarios' => MockData::usuarios(),
            ],
        ]);
    }

    public function agendamentos()
    {
        return view('admin.agendamentos', [
            'pageData' => [
                'agendamentos' => MockData::agendamentosAdmin(),
            ],
        ]);
    }
}
