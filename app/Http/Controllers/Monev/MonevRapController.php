<?php

namespace App\Http\Controllers\Monev;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MonevRapController extends Controller
{
    public function rap_monev()
    {
        return view('v1-1.monev.monev_rap.monev-rap', [
            'app' => [
                'title' => 'Monev RAP',
                'desc' => 'Halaman ini digunakan untuk memantau dan mengevaluasi RAP.',
            ],
        ]);
    }
}
