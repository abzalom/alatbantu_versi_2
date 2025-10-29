<?php

namespace App\Http\Controllers\Monev;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MonevRakortekController extends Controller
{
    public function rakortek_monev()
    {
        return view('v1-1.monev.monev_rakortek.monev-rakortek', [
            'app' => [
                'title' => 'Monev Rakortek',
                'desc' => 'Halaman ini digunakan untuk memantau dan mengevaluasi Rakortek.',
            ],
        ]);
    }
}
