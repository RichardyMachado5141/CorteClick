<?php

namespace App\Http\Controllers;

use App\Data\MockData;

class ProfissionalController extends Controller
{
    public function dashboard()
    {
        return view('profissional.dashboard', [
            'pageData' => [
                'profissionalId' => 1,
                'agendamentos' => MockData::agendamentosCliente(),
            ],
        ]);
    }

    public function servicos()
    {
        return view('profissional.servicos', [
            'pageData' => [
                'servicos' => MockData::servicosProfissional(),
            ],
        ]);
    }
}
