<?php

namespace App\Http\Controllers\Ref;

use App\Models\Data\Lokus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Data\Sumberdana;

class ReferensiDataController extends Controller
{
    public function ref_data(Request $request)
    {
        $data = [
            'lokasi' => Lokus::get(),
            'sumberdana' => Sumberdana::get(),
        ];

        return view('referensi.ref-lokasi', [
            'app' => [
                'title' => 'Referensi',
                'desc' => 'Referensi Data',
            ],
            'data' => $data,
        ]);
    }
}
