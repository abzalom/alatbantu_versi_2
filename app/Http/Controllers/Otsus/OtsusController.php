<?php

namespace App\Http\Controllers\Otsus;

use App\Http\Controllers\Controller;
use App\Models\Data\Sumberdana;
use App\Models\Otsus\DanaAlokasiOtsus;
use App\Models\Otsus\Data\B1TemaOtsus;
use Illuminate\Http\Request;

class OtsusController extends Controller
{
    public function otsus()
    {
        return redirect()->to('/otsus/indikator');
    }
    public function otsus_indikator()
    {
        $data = B1TemaOtsus::with([
            'indikator',
            'program.keluaran.aktifitas.target_aktifitas',
        ])->get();
        $sumberdanas = Sumberdana::get();
        // return $data;
        return view('otsus.otsus-indikator', [
            'app' => [
                'title' => 'Indikator Otsus',
                'desc' => 'Indikator Otsus',
            ],
            'data' => $data,
            'sumberdanas' => $sumberdanas,
        ]);
    }

    public function otsus_alokasi_dana()
    {
        $data = DanaAlokasiOtsus::get();
        return view('otsus.otsus-alokasi-dana', [
            'app' => [
                'title' => 'OTSUS',
                'desc' => 'Alokasi Dana Otsus Dan DTI',
            ],
            'data' => $data,
        ]);
    }
}
