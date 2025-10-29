<?php

namespace App\Http\Controllers\Rakortek;

use App\Http\Controllers\Controller;
use App\Models\Data\Opd;
use App\Models\Data\Perencanaan\IndikatorUrusanPemda;
use App\Models\Otsus\Data\B1TemaOtsus;
use App\Models\Otsus\Data\B5TargetAktifitasUtamaOtsus;
use App\Models\Tagging\Otsus\OpdTagOtsus;
use App\Models\TargetIndikatorUrusan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class RakortekRapppController extends Controller
{
    public function rakortek_rappp(Request $request)
    {
        $opds = auth()->user()->hasRole('user')
            ? auth()->user()->opds()->with(['tag_bidang.indikators.target'])
            : Opd::with(['tag_bidang.indikators.target']);

        $opds = $opds->get();


        $opds = $opds->map(function ($opd) {
            $punya_indikator = 0;
            $punya_target = 0;
            foreach ($opd->tag_bidang as $bidang) {
                if ($bidang->indikators) {
                    $punya_indikator += $bidang->indikators->count();
                    foreach ($bidang->indikators as $indikator) {
                        if ($indikator->target && $indikator->target->pembahasan == 'setujui' && $indikator->target->validasi) {
                            $punya_target += 1;
                        }
                    }
                }
            }
            return (object) [
                'id' => $opd->id,
                'kode_unik_opd' => $opd->kode_unik_opd,
                'kode_opd' => $opd->kode_opd,
                'nama_opd' => $opd->nama_opd,
                'tahun' => $opd->tahun,
                'punya_indikator' => $punya_indikator > 0,
                'punya_target' => $punya_target > 0,
            ];
        })->sortBy('kode_opd');

        // return $opds;
        return view('v1-1.rakortek.rappp.rakortek-rappp', [
            'app' => [
                'title' => 'Rakortek',
                'desc' => 'Rakortek Program Percepatan Pada RAPPP',
            ],
            'opds' => $opds,
        ]);
    }

    public function opd_rakortek_rappp(Request $request)
    {
        $id_opd = $request->id;
        if (!$id_opd) {
            return redirect()->to('/rakortek/rappp/')->with('error', 'Perangkat Daerah tidak ditemukan!');
        }
        // $opd = Opd::with(['tag_bidang.indikators.target'])->find($id_opd);
        $opd = Opd::with('tag_bidang')->find($id_opd);

        if (!$opd) {
            return redirect()->to('/rakortek/rappp/')->with('error', 'Perangkat Daerah tidak ditemukan!');
        }

        $indikators = IndikatorUrusanPemda::whereIn('kode_bidang', $opd->tag_bidang->pluck('kode_bidang'))->get();
        $targets = TargetIndikatorUrusan::whereIn('indikator_urusan_pemda_id', $indikators->pluck('id'))->get();
        $count_target = count($targets->filter(fn($value) => $value->usulan_target_daerah));


        if (!auth()->user()->hasRole(['admin']) && $indikators->count()) {
            if ($count_target <= 0) {
                return redirect()->to('/rakortek/rappp/')->with('error', 'Perangkat Daerah belum input indikator Urusan!');
            }
        }
        $temaRappp = B1TemaOtsus::get();
        $rappps = DB::table('opd_tag_otsuses as tag')
            ->select(
                'tag.id',
                'tag.kode_target_aktifitas',
                'tag.volume',
                'tag.volume_usulan',
                'tag.satuan',
                'tag.sumberdana',
                'tag.sumberdana_usulan',
                'tag.alias_dana',
                'tag.alias_dana_usulan',
                'tag.pembahasan',
                'tag.validasi',
                'tag.deleted_at',
                DB::raw("CONCAT(tema.kode_tema, ' ', tema.uraian) as tema"),
                DB::raw("CONCAT(program.kode_program, ' ', program.uraian) as program"),
                DB::raw("CONCAT(target_aktifitas.kode_target_aktifitas, ' ', target_aktifitas.uraian) as target_aktifitas"),
                DB::raw("COUNT(rap.id) as total_rap") // menghitung jumlah rap terkait
            )
            ->where([
                'tag.kode_unik_opd' => $opd->kode_unik_opd,
                // 'tag.deleted_at' => null,
            ])
            ->leftJoin('rap_otsuses as rap', 'rap.kode_unik_opd_tag_otsus', '=', 'tag.kode_unik_opd_tag_otsus') // LEFT JOIN
            ->join('b1_tema_otsuses as tema', 'tema.kode_tema', '=', 'tag.kode_tema')
            ->join('b2_program_prioritas_otsuses as program', 'program.kode_program', '=', 'tag.kode_program')
            ->join('b5_target_aktifitas_utama_otsuses as target_aktifitas', 'target_aktifitas.kode_target_aktifitas', '=', 'tag.kode_target_aktifitas')
            ->groupBy(
                'tag.id',
                'tag.kode_target_aktifitas',
                'tag.volume',
                'tag.satuan',
                'tag.sumberdana',
                'tema.kode_tema',
                'tema.uraian',
                'program.kode_program',
                'program.uraian',
                'target_aktifitas.kode_target_aktifitas',
                'target_aktifitas.uraian'
            )
            ->get()
            ->collect();
        // return $rappps;
        return view('v1-1.rakortek.rappp.rakortek-opd-rappp', [
            'app' => [
                'title' => 'Rakortek',
                'desc' => 'Tagging Program RAPPP Pada ' . ucfirst(strtolower($opd->nama_opd)),
            ],
            'opd' => $opd,
            'temaRappp' => $temaRappp,
            'rappps' => $rappps,
        ]);
    }

    public function opd_save_rakortek_rappp(Request $request)
    {
        // return $request->all();
        $id_opd = $request->id;
        if (!$id_opd) {
            return redirect()->to('/rakortek/rappp/')->with('error', 'Perangkat Daerah tidak ditemukan!');
        }
        // return $request->all();
        if (!$request->has('exists_check') && !$request->exists_check) {
            return redirect()->back()->with('error', 'Terjadi kesalahan! data gagal tersimpan!');
        }
        $opd = Opd::find($id_opd);
        if (!$opd) {
            return redirect()->back()->with('error', 'Perangkat Daerah tidak ditemukan!');
        }
        if ($request->exists_check == 'yes') {
            $validator = Validator::make($request->all(), [
                'target_aktifitas' => 'required|exists:b5_target_aktifitas_utama_otsuses,id',
                'volume_usulan' => 'required|numeric',
                'alias_dana_usulan' => 'required|in:bg,sg,dti',
            ], [
                'target_aktifitas.required' => 'Target Program RAPPP harus diisi!',
                'target_aktifitas.exists' => 'Target Program RAPPP tidak ditemukan!',
                'volume_usulan.required' => 'Volume Target harus diisi!',
                'volume_usulan.numeric' => 'Volume harus berupa angka yang valid!',
                'alias_dana_usulan.required' => 'Sumber Pendanaan harus diisi!',
            ]);
        } elseif ($request->exists_check == "no") {
            $validator = Validator::make($request->all(), [
                'target_aktifitas' => 'required|exists:b5_target_aktifitas_utama_otsuses,id',
                'volume_usulan_satuan_not_exists' => 'required|numeric',
                'satuan_traget_aktifitas_satuan_not_exists' => 'required',
                'alias_dana_usulan' => 'required|in:bg,sg,dti',
            ], [
                'target_aktifitas.required' => 'Target Program RAPPP harus diisi!',
                'target_aktifitas.exists' => 'Target Program RAPPP tidak ditemukan!',
                'volume_usulan_satuan_not_exists.required' => 'Volume Target harus diisi!',
                'volume_usulan_satuan_not_exists.numeric' => 'Volume harus berupa angka yang valid!',
                'satuan_traget_aktifitas_satuan_not_exists.required' => 'Satuan Target harus diisi!',
                'alias_dana_usulan.required' => 'Sumber Pendanaan harus diisi!',
            ]);
        } else {
            return redirect()->back()->with('error', 'Terjadi kesalahan! data gagal tersimpan!. exists_check tidak valid!');
        }
        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Terjadi kesalahan! data gagal tersimpan!')->withErrors($validator);
        }
        $target_aktifitas = B5TargetAktifitasUtamaOtsus::where('kode_target_aktifitas', $request->target_aktifitas)->first();
        $kode_unik_opd_tag_otsus = $opd->kode_unik_opd . '-' . $target_aktifitas->kode_target_aktifitas . '-' . $request->alias_dana_usulan;
        if (OpdTagOtsus::where('kode_unik_opd_tag_otsus', $kode_unik_opd_tag_otsus)->exists()) {
            return redirect()->back()->with('error', 'Data sudah ada! Silahkan periksa kembali!');
        }
        $data = [
            'kode_unik_opd' => $opd->kode_unik_opd,
            'kode_unik_opd_tag_otsus' => $kode_unik_opd_tag_otsus,
            'kode_opd' => $opd->kode_opd,
            'kode_tema' => $target_aktifitas->kode_tema,
            'kode_program' => $target_aktifitas->kode_program,
            'kode_keluaran' => $target_aktifitas->kode_keluaran,
            'kode_aktifitas' => $target_aktifitas->kode_aktifitas,
            'kode_target_aktifitas' => $target_aktifitas->kode_target_aktifitas,
            'volume_usulan' => $request->exists_check == "yes" ? $request->volume_usulan : $request->volume_usulan_satuan_not_exists,
            'satuan' => $request->exists_check == "yes" ? $target_aktifitas->satuan : $request->satuan_traget_aktifitas_satuan_not_exists,
            'sumberdana_usulan' => $request->alias_dana_usulan == 'bg' ? 'Otsus 1%' : ($request->alias_dana_usulan == 'sg' ? 'Otsus 1,25%' : 'DTI'),
            'alias_dana_usulan' => $request->alias_dana_usulan,
            'tahun' => session()->get('tahun'),
        ];
        OpdTagOtsus::create($data);
        return redirect()->back()->with('success', 'Data berhasil disimpan!');
    }

    public function opd_update_rakortek_rappp(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'id_opd_tag_otsus' => 'required|exists:opd_tag_otsuses,id',
                'satuan' => 'sometimes|required|string',
                'volume_usulan' => 'required|numeric',
                'alias_dana_usulan' => 'required|in:bg,sg,dti',
            ],
            [
                'satuan.required' => 'Satuan Target harus diisi!',
                'satuan.string' => 'Satuan Target harus berupa string yang valid!',
                'volume_usulan.required' => 'Volume Target harus diisi!',
                'volume_usulan.numeric' => 'Volume harus berupa angka yang valid!',
                'alias_dana_usulan.required' => 'Sumber Pendanaan harus diisi!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Terjadi kesalahan! data gagal tersimpan!')->withErrors($validator);
        }
        $tag = OpdTagOtsus::find($request->id_opd_tag_otsus);
        if (!$tag) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }
        $target_aktifitas = B5TargetAktifitasUtamaOtsus::where('kode_target_aktifitas', $tag->kode_target_aktifitas)->first();
        if (!$target_aktifitas) {
            return redirect()->back()->with('error', 'Target Aktifitas tidak ditemukan!');
        }
        $data = [
            'satuan' => $target_aktifitas->satuan ? $target_aktifitas->satuan : $request->satuan,
            'volume_usulan' => $request->volume_usulan,
            'sumberdana_usulan' => $request->alias_dana_usulan == 'bg' ? 'Otsus 1%' : ($request->alias_dana_usulan == 'sg' ? 'Otsus 1,25%' : 'DTI'),
            'alias_dana_usulan' => $request->alias_dana_usulan,
        ];
        $tag->update($data);
        return redirect()->back()->with('success', 'Data berhasil diperbarui!');
    }

    public function opd_delete_rakortek_rappp(Request $request)
    {
        if (!$request->has('id') || !$request->id) {
            return redirect()->back()->with('error', 'Terjadi kesalahan! data gagal dihapus!');
        }

        $tag = OpdTagOtsus::find($request->id);
        if (!$tag) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }
        $rap = $tag->raps()->first();
        if ($rap) {
            return redirect()->back()->with('error', 'Data tidak dapat dihapus! karena sudah ada RAP yang terkait!');
        }
        $tag->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus!');
    }

    public function restore_rakortek_rappp(Request $request)
    {
        if (!$request->has('id') || !$request->id) {
            return redirect()->back()->with('error', 'Terjadi kesalahan! data gagal dipulihkan!');
        }

        $tag = OpdTagOtsus::withTrashed()->find($request->id);
        if (!$tag) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }
        $tag->restore();
        return redirect()->back()->with('success', 'Data berhasil dipulihkan!');
    }

    public function destroy_rakortek_rappp(Request $request)
    {
        if (!$request->has('id') || !$request->id) {
            return redirect()->back()->with('error', 'Terjadi kesalahan! data gagal dihapus secara permanen!');
        }

        $tag = OpdTagOtsus::withTrashed()->find($request->id);
        if (!$tag) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }
        $rap = $tag->raps()->first();
        if ($rap) {
            return redirect()->back()->with('error', 'Data tidak dapat dihapus! karena sudah ada RAP yang terkait!');
        }
        $tag->forceDelete();
        return redirect()->back()->with('success', 'Data berhasil dihapus secara permanen!');
    }
}
