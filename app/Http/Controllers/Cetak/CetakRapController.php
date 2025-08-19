<?php

namespace App\Http\Controllers\Cetak;

use App\Models\Data\Opd;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Rap\RapOtsus;
use App\Models\Tagging\Otsus\OpdTagOtsus;
use Illuminate\Support\Facades\DB;

class CetakRapController extends Controller
{
    public function cetak_rap(Request $request)
    {
        $opd = Opd::whereHas('tag_otsus', function ($q) use ($request) {
            $q->where('alias_dana', $request->jenis)
                ->where('pembahasan', 'setujui')
                ->where('validasi', true)
            ;
        })->with([
            'tag_otsus' => function ($q) use ($request) {
                $q->where('alias_dana', $request->jenis)
                    ->where('pembahasan', 'setujui')
                    ->where('validasi', true);
            },
            'tag_otsus.target_aktifitas',
            'tag_otsus.raps' => function ($q) use ($request) {
                $q->where('rap_otsuses.alias_dana', $request->jenis)
                    ->where('rap_otsuses.pembahasan', 'setujui')
                    ->where('rap_otsuses.validasi', true)
                ;
            },
            'kepala_aktif',
        ])
            ->find($request->opd);
        // return $opd;
        return view('v1-1.cetak.cetak-rap', [
            'app' => [
                'title' => 'Cetak RAP',
            ],
            'header_sumberdana' => $request->jenis === 'bg' ? 'DANA OTNOMONI KHUSUS YANG BERSIFAT UMUM' : ($request->jenis === 'sg' ? 'DANA OTONOMI KHUSUS YANG TELAH DITENTUKAN PENGGUNAANNYA' : 'DANA TAMBAHAN INFRASTRUKTUR'),
            'opd' => $opd
        ]);
    }
}
