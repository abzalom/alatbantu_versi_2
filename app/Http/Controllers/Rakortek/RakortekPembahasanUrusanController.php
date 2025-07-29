<?php

namespace App\Http\Controllers\Rakortek;

use App\Models\Data\Opd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\TargetIndikatorUrusan;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Validator;

class RakortekPembahasanUrusanController extends Controller
{
    public function pembahasan_urusan()
    {
        $opds = auth()->user()->hasRole('user')
            ? auth()->user()->opds()
            : (new Opd());

        $opds = $opds->with([
            'tag_bidang.bidang' => fn($q) => $q->select([
                'kode_bidang',
                'uraian',
                DB::raw("CONCAT(kode_bidang, ' ', uraian) as text_bidang")
            ]),
            'tag_bidang.indikators.target'
        ])->get();

        $data = [];
        $info_card = [
            'opd' => [
                'title' => 'Perangkat Daerah',
                'icon' => 'fa-solid fa-building',
                'count' => 0,
            ],
            'bidang' => [
                'title' => 'Bidang sudah terisi',
                'icon' => 'fa-solid fa-layer-group',
                'count' => 0,
            ],
            'indikator' => [
                'title' => 'Indikator',
                'icon' => 'fa-solid fa-chart-line',
                'count' => 0,
            ],
            'indikator_setujui' => [
                'title' => 'Disetujui',
                'icon' => 'fa-solid fa-circle-check',
                'count' => 0,
            ],
            'indikator_perbaikan' => [
                'title' => 'Perlu Perbaikan',
                'icon' => 'fa-solid fa-wrench',
                'count' => 0,
            ],
            'indikator_tolak' => [
                'title' => 'Ditolak',
                'icon' => 'fa-solid fa-times',
                'count' => 0,
            ],
        ];
        $countIndikator = 0;

        foreach ($opds as $itemOpd) {
            $hasIndikators = false;
            $bidangs = [];

            foreach ($itemOpd->tag_bidang as $tag_bidang) {
                $kode_bidang = $tag_bidang->kode_bidang;

                // Inisialisasi bidang jika belum ada
                if (!isset($bidangs[$kode_bidang])) {
                    $bidangs[$kode_bidang] = [
                        'kode_bidang' => $tag_bidang->bidang->kode_bidang,
                        'nama_bidang' => $tag_bidang->bidang->uraian,
                        'text_bidang' => $tag_bidang->bidang->text_bidang,
                        'indikators_count' => [
                            'jumlah' => 0,
                            'setujui' => 0,
                            'perbaikan' => 0,
                            'tolak' => 0,
                        ],
                    ];
                }

                if ($tag_bidang->indikators && $tag_bidang->indikators->isNotEmpty()) {
                    $hasIndikators = true;

                    foreach ($tag_bidang->indikators as $indikator) {
                        $target = $indikator->target;

                        if ($target && $target->usulan_target_daerah !== null && $target->usulan_target_daerah !== '') {
                            $countIndikator++;
                            $info_card['opd']['count']++ . ' Organisasi';
                            $info_card['bidang']['count']++;
                            $info_card['indikator']['count']++;
                            $bidangs[$kode_bidang]['indikators_count']['jumlah']++;

                            switch ($target->pembahasan) {
                                case 'setujui':
                                    $bidangs[$kode_bidang]['indikators_count']['setujui']++;
                                    $info_card['indikator_setujui']['count']++;
                                    break;
                                case 'perbaikan':
                                    $bidangs[$kode_bidang]['indikators_count']['perbaikan']++;
                                    $info_card['indikator_perbaikan']['count']++;
                                    break;
                                case 'tolak':
                                    $bidangs[$kode_bidang]['indikators_count']['tolak']++;
                                    $info_card['indikator_tolak']['count']++;
                                    break;
                            }
                        }
                    }
                }
            }


            $data[] = [
                'id' => $itemOpd->id,
                'kode_unik_opd' => $itemOpd->kode_unik_opd,
                'kode_opd' => $itemOpd->kode_opd,
                'nama_opd' => $itemOpd->nama_opd,
                'text_opd' => $itemOpd->text,
                'bidangs' => array_values($bidangs),
                'has_indikator' => [
                    'status' => $hasIndikators,
                    'count' => $countIndikator,
                ],
                'tahun' => $itemOpd->tahun,
            ];
        }

        // Sort data
        $data = collect($data)->sortBy('kode_opd')->map(function ($item) {
            return (object) $item;
        })->all();


        // return $data;
        // return $info_card;
        return view('v1-1.rakortek.pembahasan.urusan.rakortek-pembahasan-urusan', [
            'app' => [
                'title' => 'Rakortek',
                'desc' => 'Pembahasan Urusan Tahun ' . session()->get('tahun'),
            ],
            'data' => $data,
            'info_card' => $info_card,
        ]);
    }

    public function pembahasan_urusan_opd(Request $request)
    {
        $id_opd = $request->id;
        if (!$id_opd) {
            return redirect()->to('/rakortek/pembahasan/urusan')->with('error', 'Perangkat Daerah tidak ditemukan!');
        }
        $opd = Opd::with([
            'tag_bidang.bidang' => fn($q) => $q->select([
                'kode_bidang',
                'uraian',
                DB::raw("CONCAT(kode_bidang, ' ' , uraian) as text_bidang")
            ]),
            'tag_bidang.indikators' => fn($q) => $q->whereHas('target', function ($q) {
                $q->whereNotNull('usulan_target_daerah')
                    ->orWhere('usulan_target_daerah', '!=', '')
                    ->orWhere('usulan_target_daerah', '!=', 0);
            }),
            'tag_bidang.indikators.target' => fn($q) => $q->whereNotNull('usulan_target_daerah')
                ->orWhere('usulan_target_daerah', '!=', '')
                ->orWhere('usulan_target_daerah', '!=', 0),
        ])->find($id_opd);

        if (!$opd) {
            return redirect()->to('/pembahasan/rakortek/urusan')->with('error', 'Perangkat Daerah tidak ditemukan!');
        }

        // redirect to /rakortek/pembahasan/urusan jika bidang tidak mempunyai indikator
        if ($opd->tag_bidang->isEmpty() || $opd->tag_bidang->every(fn($tag) => $tag->indikators->isEmpty())) {
            return redirect()->to('/rakortek/pembahasan/urusan')->with('error', 'Perangkat Daerah tidak mempunyai indikator atau belum mengisi target kinerja indikator urusan!');
        }
        // return $opd;
        return view('v1-1.rakortek.pembahasan.urusan.rakortek-pembahasan-urusan-opd', [
            'app' => [
                'title' => 'Rakortek',
                'desc' => 'Pembahasan Target Kinerja Urusan Perangkat Daerah Tahun ' . session()->get('tahun'),
            ],
            'opd' => $opd,
        ]);
    }

    public function save_pembahasan_urusan_opd(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'target_id' => 'required|numeric|exists:target_indikator_urusans,id',
                'target_daerah' => 'required|numeric',
                'pembahasan' => 'required|in:setujui,perbaikan,tolak',
                'catatan' => 'nullable|string',
            ],
            [
                'target_id.required' => 'Target ID tidak boleh kosong!',
                'target_id.numeric' => 'Target ID harus berupa angka!',
                'target_id.exists' => 'Target ID tidak ditemukan!',
                'target_daerah.required' => 'Target Daerah tidak boleh kosong!',
                'target_daerah.numeric' => 'Target Daerah harus berupa angka!',
                'pembahasan.required' => 'Status pembahasan harus dipilih!',
                'pembahasan.in' => 'Status pembahasan harus salah satu dari: setujui, perbaikan, tolak',
                'catatan.string' => 'Catatan harus berupa teks!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->with('error', 'Terjadi kesalahan! data gagal disimpan!');
        }
        $target_daerah = str_replace(['.', ','], ['', '.'], $request->target_daerah);
        // return $request->all();
        $target_indikator_urusan = TargetIndikatorUrusan::find($request->target_id);
        if (!$target_indikator_urusan) {
            return redirect()->back()->with('error', 'Target Indikator Urusan tidak ditemukan!');
        }
        $target_indikator_urusan->target_daerah = $target_daerah;
        $target_indikator_urusan->pembahasan = $request->pembahasan;
        $target_indikator_urusan->catatan = $request->catatan;
        $target_indikator_urusan->save();
        return redirect()->back()->with('success', 'Data berhasil disimpan!');
    }

    public function validasi_pembahasan_urusan_opd(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'required|numeric|exists:target_indikator_urusans,id',
            ],
            [
                'target_id.required' => 'Target ID tidak boleh kosong!',
                'target_id.numeric' => 'Target ID harus berupa angka!',
                'target_id.exists' => 'Target ID tidak ditemukan!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->with('error', 'Terjadi kesalahan! data gagal disimpan!');
        }
        $target_indikator_urusan = TargetIndikatorUrusan::find($request->id);
        if (!$target_indikator_urusan) {
            return redirect()->back()->with('error', 'Target Indikator Urusan tidak ditemukan!');
        }
        if (!$target_indikator_urusan->pembahasan || $target_indikator_urusan->pembahasan == 'perbaikan' || !$target_indikator_urusan->target_daerah || $target_indikator_urusan->target_daerah == null || $target_indikator_urusan->target_daerah == '') {
            // Jika sudah divalidasi, tidak bisa dibatalkan
            $rtn_msg = $target_indikator_urusan->pembahasan == 'perbaikan'
                ? 'Indikator hanya dapat divalidasi jika status disetujui / ditolak!'
                : 'Indikator belum dibahas. Tidak dapat divalidasi!';
            return redirect()->back()->with('error', $rtn_msg);
        }
        $target_indikator_urusan->validasi = $target_indikator_urusan->validasi ? false : true;
        $target_indikator_urusan->save();
        $redirect_msg = $target_indikator_urusan->validasi
            ? 'Data berhasil divalidasi!'
            : 'Validasi telah dibatalkan!';
        return redirect()->back()->with('success', $redirect_msg);
    }
}
