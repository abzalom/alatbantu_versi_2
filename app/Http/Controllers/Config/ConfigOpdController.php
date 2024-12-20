<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use App\Models\Data\Opd;
use App\Models\Tagging\Nomenklatur\OpdTagBidang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ConfigOpdController extends Controller
{
    public function config_opd()
    {
        $opds = Opd::get();
        return view('app.pengaturan.pengaturan-opd', [
            'app' => [
                'title' => 'Pengaturan',
                'desc' => 'Pengaturan Perangkat Daerah',
            ],
            'opds' => $opds,
        ]);
    }

    public function config_sinkron_opd(Request $request)
    {
        $response = Http::get('http://localhost:3000/api/local/get/data/skpd', [
            'tahun' => session()->get('tahun')
        ]);
        $data = json_decode($response, true);
        // return $data;
        $data_tag = [];
        foreach ($data as $opd) {
            $expKode = explode('.', $opd['kode_opd']);
            $urusan1 = $expKode[0];
            $bidang1 = $expKode[0] . '.' . $expKode[1];
            $unik1 = $opd['tahun'] . '-' . $opd['kode_opd'] . '-' . $bidang1;

            $urusan2 = $expKode[2];
            $bidang2 = $expKode[2] . '.' . $expKode[3];
            $unik2 = $opd['tahun'] . '-' . $opd['kode_opd'] . '-' . $bidang2;

            $urusan3 = $expKode[4];
            $bidang3 = $expKode[4] . '.' . $expKode[5];
            $unik3 = $opd['tahun'] . '-' . $opd['kode_opd'] . '-' . $bidang3;

            if (!isset($data_tag[$unik1])) {
                $data_tag[$unik1] = [
                    'kode_unik_opd' => $opd['tahun'] . '-' . $opd['kode_opd'],
                    'kode_unik_opd_tag_bidang' => $unik1,
                    'kode_opd' => $opd['kode_opd'],
                    'kode_urusan' => $urusan1,
                    'kode_bidang' => $bidang1,
                    'tahun' => $opd['tahun'],
                ];
            }

            if ($urusan2 !== '0' && $urusan2 !== '0.00') {
                if (!isset($data_tag[$unik2])) {
                    $data_tag[$unik2] = [
                        'kode_unik_opd' => $opd['tahun'] . '-' . $opd['kode_opd'],
                        'kode_unik_opd_tag_bidang' =>  $unik2,
                        'kode_opd' => $opd['kode_opd'],
                        'kode_urusan' => $urusan2,
                        'kode_bidang' => $bidang2,
                        'tahun' => $opd['tahun'],
                    ];
                }
            }

            if ($urusan3 !== '0' && $urusan3 !== '0.00') {
                if (!isset($data_tag[$unik3])) {
                    $data_tag[$unik3] = [
                        'kode_unik_opd' => $opd['tahun'] . '-' . $opd['kode_opd'],
                        'kode_unik_opd_tag_bidang' =>  $unik3,
                        'kode_opd' => $opd['kode_opd'],
                        'kode_urusan' => $urusan3,
                        'kode_bidang' => $bidang3,
                        'tahun' => $opd['tahun'],
                    ];
                }
            }

            Opd::updateOrCreate(
                [
                    'kode_unik_opd' => $opd['tahun'] . '-' . $opd['kode_opd'],
                ],
                [
                    'kode_opd' => $opd['kode_opd'],
                    'nama_opd' => $opd['nama_opd'],
                    'tahun' => $opd['tahun'],
                ]
            );
        }

        foreach (array_values($data_tag) as $tagging) {
            OpdTagBidang::updateOrCreate(
                [
                    'kode_unik_opd_tag_bidang' => $tagging['kode_unik_opd_tag_bidang'],
                    'tahun' => $tagging['tahun'],
                ],
                [
                    'kode_unik_opd' => $tagging['kode_unik_opd'],
                    'kode_opd' => $tagging['kode_opd'],
                    'kode_urusan' => $tagging['kode_urusan'],
                    'kode_bidang' => $tagging['kode_bidang'],
                ]
            );
        }

        return redirect()->back()->with('success', 'SKPD berhasil di sinkron!');
    }
}
