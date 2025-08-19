<?php

namespace App\Http\Controllers\Config;

use App\Enums\PangkatEnums;
use App\Http\Controllers\Controller;
use App\Models\Data\Opd;
use App\Models\Tagging\Nomenklatur\OpdTagBidang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
// use Illuminate\Http\Client\ConnectionException;

class ConfigOpdController extends Controller
{
    public function config_opd()
    {
        $opds = Auth::user()->hasRole('user') ? Auth::user()->opds() : new Opd();
        $opds = $opds->with([
            'kepala_aktif',
        ])->get();
        // return $opds;
        return view('app.pengaturan.opd.pengaturan-opd', [
            'app' => [
                'title' => 'Pengaturan',
                'desc' => 'Pengaturan Perangkat Daerah',
            ],
            'opds' => $opds,
            'pangkats' => PangkatEnums::cases(),
        ]);
    }

    public function config_sinkron_opd(Request $request)
    {
        if (Auth::user()->hasRole('user')) {
            return redirect()->to('/config/opd')->with('error', 'Unauthorized');
        }
        try {
            $response = Http::get('http://localhost:3000/api/local/get/data/skpd', [
                'tahun' => session()->get('tahun')
            ]);

            if ($response->failed()) {
                return redirect()->to('/config/opd')->with('error', 'Gagal mengambil data SKPD');
            }

            // Ambil data json
            $data = $response->json();

            $data_tag = [];

            foreach ($data as $opd) {
                $expKode = explode('.', $opd['kode_opd']);
                $urusan1 = $expKode[0] ?? null;
                $bidang1 = ($expKode[0] ?? '') . '.' . ($expKode[1] ?? '');
                $unik1 = $opd['tahun'] . '-' . $opd['kode_opd'] . '-' . $bidang1;

                $urusan2 = $expKode[2] ?? null;
                $bidang2 = ($expKode[2] ?? '') . '.' . ($expKode[3] ?? '');
                $unik2 = $opd['tahun'] . '-' . $opd['kode_opd'] . '-' . $bidang2;

                $urusan3 = $expKode[4] ?? null;
                $bidang3 = ($expKode[4] ?? '') . '.' . ($expKode[5] ?? '');
                $unik3 = $opd['tahun'] . '-' . $opd['kode_opd'] . '-' . $bidang3;

                // Tagging urusan pertama
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

                // Urusan kedua (jika ada)
                if ($urusan2 && $urusan2 !== '0' && $urusan2 !== '0.00') {
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

                // Urusan ketiga (jika ada)
                if ($urusan3 && $urusan3 !== '0' && $urusan3 !== '0.00') {
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

                // Simpan OPD
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

            // Simpan tagging bidang
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
        } catch (ConnectionException $e) {
            return redirect()->to('/config/opd')->with('error', 'Tidak dapat terhubung ke server API');
        }
    }
}
