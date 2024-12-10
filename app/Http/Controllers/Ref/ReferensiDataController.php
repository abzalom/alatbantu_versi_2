<?php

namespace App\Http\Controllers\Ref;

use App\Models\Data\Lokus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Data\Sumberdana;
use App\Models\Nomenklatur\A2Bidang;
use App\Models\Nomenklatur\A3Program;
use App\Models\Nomenklatur\A4Kegiatan;
use App\Models\Nomenklatur\A5Subkegiatan;
use App\Models\Nomenklatur\NomenklaturSikd;
use Illuminate\Support\Facades\DB;

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

    public function ref_nomenklatur(Request $request)
    {
        $data = DB::table('a2_bidangs')->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'text_bidang' => $item->kode_bidang . ' ' . $item->uraian,
                'kode_bidang' => $item->kode_bidang,
                'uraian' => $item->uraian,
            ];
        });
        // return $data;
        return view('referensi.ref-nomenklatur', [
            'app' => [
                'title' => 'Referensi',
                'desc' => 'Renferensi Nomenklatur',
            ],
            'data' => $data,
        ]);
    }

    public function ref_cetak_nomenklatur(Request $request)
    {
        $data = DB::table('a2_bidangs')
            ->whereIn('kode_bidang', [$request->bidang1, $request->bidang2, $request->bidang3])
            ->orderBy('kode_bidang')
            ->get()
            ->map(function ($bidang) {
                return [
                    'kode_bidang' => $bidang->kode_bidang,
                    'uraian' => $bidang->uraian,
                    'programs' => DB::table('a3_programs')->where('kode_bidang', $bidang->kode_bidang)->orderBy('kode_program')
                        ->get()
                        ->map(function ($program) {
                            return [
                                'kode_program' => $program->kode_program,
                                'uraian' => $program->uraian,
                                'kegiatans' =>  DB::table('a4_kegiatans')->where('kode_program', $program->kode_program)->orderBy('kode_kegiatan')
                                    ->get()
                                    ->map(function ($kegiatan) {
                                        return [
                                            'kode_kegiatan' => $kegiatan->kode_kegiatan,
                                            'uraian' => $kegiatan->uraian,
                                            'subkegiatans' =>  DB::table('a5_subkegiatans')->where('kode_kegiatan', $kegiatan->kode_kegiatan)->orderBy('kode_subkegiatan')
                                                ->get()
                                                ->map(function ($subkegiatan) {
                                                    return [
                                                        'klasifikasi_belanja' => $subkegiatan->klasifikasi_belanja,
                                                        'kode_subkegiatan' => $subkegiatan->kode_subkegiatan,
                                                        'uraian' => $subkegiatan->uraian,
                                                        'indikator' => $subkegiatan->indikator,
                                                        'satuan' => $subkegiatan->satuan,
                                                    ];
                                                })
                                        ];
                                    })
                            ];
                        })
                ];
            });
        $title = count($data) > 0 ? $data[0]['kode_bidang'] : 'Cetak Referensi';
        if (isset($data[1])) {
            $title = $title . ' | ' . $data[1]['kode_bidang'];
        }
        if (isset($data[2])) {
            $title = $title . ' | ' . $data[2]['kode_bidang'];
        }
        // return $title;
        return view('referensi.ref-cetak-nomenklatur', [
            'title' => $title,
            'data' => $data,
        ]);
    }

    public function update_nomenklatur_sikd(Request $request)
    {
        $nomenSikd = NomenklaturSikd::all();

        $notFound = [];

        foreach ($nomenSikd as $sikd) {
            // $lokal = A4Kegiatan::where('kode_kegiatan', $sikd->kode_program)->get();
            $lokal = A5Subkegiatan::where('kode_subkegiatan', $sikd->kode_subkegiatan)->first();
            if (!$lokal) {
                $kode_urusan = explode('.', $sikd->kode_bidang)[0];
                A5Subkegiatan::updateOrCreate(
                    [
                        'kode_subkegiatan' => $sikd->kode_subkegiatan,
                    ],
                    [
                        'kode_urusan' => $kode_urusan,
                        'kode_bidang' => $sikd->kode_bidang,
                        'kode_program' => $sikd->kode_program,
                        'kode_kegiatan' => $sikd->kode_kegiatan,
                        'uraian' => $sikd->nama_subkegiatan,
                        'indikator' => $sikd->indikator,
                        'kinerja' => 'kosong',
                        'satuan' => $sikd->satuan,
                        'klasifikasi_belanja' => $sikd->klasifikasi_belanja,
                        'rutin' => $sikd->kode_program == 'X.XX.01' ? 1 : 0,
                        'gaji' => 0,
                        'referensi' => 'kosong',
                        'prioritas_pendidikan' => 0,
                        'pendukung_pendidikan' => 0,
                        'prioritas_kesehatan' => 0,
                        'pendukung_kesehatan' => 0,
                        'prioritas_pu' => 0,
                        'pendukung_pu' => 0,
                        'tahun' => session()->get('tahun'),
                    ]
                );
            }
            if ($lokal) {
                $lokal->klasifikasi_belanja = $sikd->klasifikasi_belanja;
                $lokal->save();
            }
        }
        // return $notFound;
        return redirect()->to('/ref/nomenklatur')->with('success', 'Data berhasil di update');
    }
}
