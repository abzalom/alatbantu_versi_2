<?php

namespace App\Http\Controllers\Ref;

use App\Models\Data\Lokus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Data\Sumberdana;
use App\Models\Nomenklatur\A5Subkegiatan;
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
}
