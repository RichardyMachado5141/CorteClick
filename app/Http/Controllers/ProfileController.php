<?php

namespace App\Http\Controllers;

use App\Data\MockData;

class ProfileController extends Controller
{
    public function select()
    {
        return view('profile.select', [
            'perfis' => MockData::perfis(),
        ]);
    }
}
