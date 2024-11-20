<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use App\Models\Data\Lokus;
use Illuminate\Http\Request;

class RefLokasiController extends Controller
{
    public function ref_lokasi(Request $request)
    {
        $data = Lokus::paginate(10);

        return view('referensi.ref-lokasi', [
            'app' => [
                'title' => 'Referensi',
                'desc' => 'Referensi Data',
            ],
            'data' => $data,
        ]);
    }
}
