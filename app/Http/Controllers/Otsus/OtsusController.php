<?php

namespace App\Http\Controllers\Otsus;

use App\Http\Controllers\Controller;
use App\Models\Data\Opd;
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
            'program.keluaran.aktifitas.target_aktifitas.taggings' => fn($q) => $q->withCount('raps')->where('tahun', session()->get('tahun')),
            'program.keluaran.aktifitas.target_aktifitas.taggings.opd' => fn($q) => $q->where('tahun', session()->get('tahun')),
        ])->get();
        $sumberdanas = Sumberdana::get();
        $opds = Opd::get();
        // return $opds;
        return view('otsus.otsus-indikator', [
            'app' => [
                'title' => 'Indikator Otsus',
                'desc' => 'Indikator Otsus',
            ],
            'data' => $data,
            'sumberdanas' => $sumberdanas,
            'opds' => $opds,
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
