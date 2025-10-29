<?php

namespace App\Http\Controllers\Rakortek;

use App\Http\Controllers\Controller;
use App\Models\Config\Schedule;
use App\Models\Data\Opd;
use App\Models\Data\Perencanaan\IndikatorUrusanPemda;
use App\Models\TargetIndikatorUrusan;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class RakortekUrusanController extends Controller
{
    public function rakortek_indikator_urusan(Request $request)
    {
        $user = auth()->user();
        $query = $user->hasRole('user') ? $user->opds() : new Opd();
        // return $query->get();
        $opds = $query->with([
            'tag_bidang.bidang',
            'tag_bidang.indikators',
        ])->get()->map(function ($opd) {
            return [
                'id' => $opd->id,
                'kode_unik_opd' => $opd->kode_unik_opd,
                'kode_opd' => $opd->kode_opd,
                'nama_opd' => $opd->nama_opd,
                'tahun' => $opd->tahun,
                'count_bidang' => $opd->tag_bidang->count(),
                'count_indikator' => $opd->tag_bidang->map(fn($tag) => $tag->indikators->count())->sum(),
            ];
        })->sortBy('kode_opd');
        // return $opds;
        return view('v1-1.rakortek.indikator_bidang_urusan.rakortek-urusan', [
            'app' => [
                'title' => 'Rakortek',
                'desc' => 'Rakortek Target Kinerja Urusan Tahun ' . session()->get('tahun'),
            ],
            'opds' => $opds,
        ]);
    }

    public function opd_rakortek_indikator_urusan(Request $request, $id_opd = null)
    {
        // return $id_opd;
        $opd = auth()->user()->hasRole('user') ? auth()->user()->opds()->with([
            'tag_bidang.bidang' => fn($q) => $q->select([
                'kode_bidang',
                'uraian',
                DB::raw("CONCAT(kode_bidang, ' ' , uraian) as text_bidang")
            ]),
            'tag_bidang.indikators.target',
        ])->find($id_opd) : Opd::with([
            'tag_bidang.bidang' => fn($q) => $q->select([
                'kode_bidang',
                'uraian',
                DB::raw("CONCAT(kode_bidang, ' ' , uraian) as text_bidang")
            ]),
            'tag_bidang.indikators.target',
        ])->find($id_opd);
        if (!$opd) {
            return redirect()->to('/rakortek')->with('error', 'Perangkat Daerah tidak ditemukan!');
        }
        $jadwal_rap = Schedule::where('status', true)->first();
        $check_indikators = 0;
        foreach ($opd->tag_bidang as $tagCheck) {
            if ($tagCheck->indikators->count() > 0) {
                $check_indikators++;
            }
        }
        if ($check_indikators == 0) {
            return redirect()->to('/rakortek/urusan')->with('error', 'Perangkat Daerah tidak memiliki indikator pada bidang urusan!');
        }
        return view('v1-1.rakortek.indikator_bidang_urusan.rakortek-urusan-opd', [
            'app' => [
                'title' => 'Rakortek',
                'desc' => 'Rakortek | Usulan Target Kinerja Indikator',
            ],
            'opd' => $opd,
        ]);
    }

    public function opd_save_target_daerah_rakortek_indikator_urusan(Request $request, $id_opd)
    {
        // return $request->all();
        $jadwal = jadwal_rap();

        if (auth()->user()->hasRole('user')) {
            if (!$jadwal) {
                return redirect()->back()->with('error', 'Jadwal tidak aktif.');
            }

            if ($jadwal->tahapan !== 'rakortek') {
                return redirect()->back()->with('error', 'Tahapan tidak sesuai (bukan rakortek).');
            }

            if (!$jadwal->penginputan) {
                return redirect()->back()->with('error', 'Penginputan tidak aktif. Hubungi administrator.');
            }

            if (jadwal_monev()) {
                return redirect()->back()->with('error', 'Jadwal Monev masih aktif. Penginputan Rakortek terkunci!');
            }
            if (!$request->has('id_indikator') || !$request->id_indikator) {
                return redirect()->back()->with('error', 'Terjadi kesalahan! Indikator Kosong!');
            }
        }
        $indikator = IndikatorUrusanPemda::with('bidang')->find($request->id_indikator);
        if (!$indikator) {
            return redirect()->back()->with('error', 'Terjadi kesalahan! Indikator tidak ditemukan!');
        }
        $target = TargetIndikatorUrusan::find($request->id_target_indikator);
        $request->merge([
            'target_daerah' => str_replace(',', '.', str_replace('.', '', $request->target_daerah)),
        ]);
        $vol_target_daerah = $request->target_daerah >= 0 ? $request->target_daerah : null;
        $vol_target_nasional = null;
        if ($request->has('target_nasional') || $request->target_nasional) {
            $vol_target_nasional = $request->target_nasional ? str_replace(',', '.', str_replace('.', '', $request->target_nasional)) : null;
        }
        if ($target) {
            if (!$target->validasi || !$target->pembahasan || $target->pembahasan == 'perbaikan') {
                $target->usulan_target_daerah = $vol_target_daerah;
            }
            if (auth()->user()->hasRole('admin')) {
                $target->target_nasional = $vol_target_nasional;
            }

            // return redirect()->back()->with('error', 'Indikator telah dibahas dan divalidasi, tidak bisa diubah!');

            $target->save();
        } else {
            $data = [
                'indikator_urusan_pemda_id' => $indikator->id,
                'a2_bidang_id' => optional($indikator->bidang)->id, // Hindari error jika bidang null
                'kode_bidang' => optional($indikator->bidang)->kode_bidang, // Hindari error jika bidang null
                'kode_indikator' => $indikator->kode_indikator,
                'usulan_target_daerah' => $vol_target_daerah,
                'satuan' => $indikator->satuan,
                'tahun' => session('tahun'), // Simpler way to get session
            ];

            if (auth()->user()->hasRole('admin')) {
                $data['target_nasional'] = $vol_target_nasional;
            }

            TargetIndikatorUrusan::create($data);
        }
        $rtn_msg = '';
        $rtn_stst = '';
        if ($target) {
            if (!$target->validasi) {
                if ($target->pembahasan && $target->pembahasan != 'perbaikan') {
                    $rtn_msg = 'indikator tidak dapat diubah, karena telah dibahas dan status bukan perbaikan!';
                    $rtn_stst = 'warning';
                } else {
                    $rtn_msg = 'Data berhasil disimpan!';
                    $rtn_stst = 'success';
                }
            } else {
                $rtn_msg = 'Indikator telah dibahas dan divalidasi! Hanya target nasional yang disimpan!';
                $rtn_stst = 'warning';
            }
        }
        // $rtn_msg = !$target->validasi  || !$target->pembahasan || $target->pembahasan == 'perbaikan' ? 'Data berhasil disimpan!' : 'Indikator telah dibahas dan divalidasi! Hanya target nasional yang disimpan!';
        // $rtn_stst = !$target->validasi  || !$target->pembahasan || $target->pembahasan == 'perbaikan' ? 'success' : 'warning';
        return redirect()->back()->with($rtn_stst, $rtn_msg);
    }
}
