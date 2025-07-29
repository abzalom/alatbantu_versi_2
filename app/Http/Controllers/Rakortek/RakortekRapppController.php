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
            ? auth()->user()->opds()
            : (new Opd());

        $opds = $opds->with(['tag_bidang.indikators.target'])->get();

        $opds = $opds->map(function ($opd_user) {
            $countIndikator = 0;
            $hasIndikators = false;

            foreach ($opd_user->tag_bidang as $tag_bidang) {
                if ($tag_bidang->indikators && $tag_bidang->indikators->isNotEmpty()) {
                    $hasIndikators = true;
                    foreach ($tag_bidang->indikators as $indikator) {
                        $target = $indikator->target;

                        if ($target && (
                            $target->usulan_target_daerah !== null &&
                            $target->usulan_target_daerah !== '' &&
                            $target->usulan_target_daerah !== 0
                        )) {
                            $countIndikator++;
                        }
                    }
                }
            }

            return (object) [
                'id' => $opd_user->id,
                'kode_unik_opd' => $opd_user->kode_unik_opd,
                'kode_opd' => $opd_user->kode_opd,
                'nama_opd' => $opd_user->nama_opd,
                'tahun' => $opd_user->tahun,
                'has_indikator' => [
                    'status' => $hasIndikators,
                    'count' => $countIndikator,
                ],
            ];
        });
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
                'tag.satuan',
                'tag.sumberdana',
                'tag.alias_dana',
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

    public function save_opd_rakortek_rappp(Request $request)
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
                'volume_target_aktifitas' => 'required|numeric',
                'sumberdana' => 'required|in:bg,sg,dti',
            ], [
                'target_aktifitas.required' => 'Target Program RAPPP harus diisi!',
                'target_aktifitas.exists' => 'Target Program RAPPP tidak ditemukan!',
                'volume_target_aktifitas.required' => 'Volume Target harus diisi!',
                'volume_target_aktifitas.numeric' => 'Volume harus berupa angka yang valid!',
                'sumberdana.required' => 'Sumber Pendanaan harus diisi!',
            ]);
        } elseif ($request->exists_check == "no") {
            $validator = Validator::make($request->all(), [
                'target_aktifitas' => 'required|exists:b5_target_aktifitas_utama_otsuses,id',
                'volume_target_aktifitas_satuan_not_exists' => 'required|numeric',
                'satuan_traget_aktifitas_satuan_not_exists' => 'required',
                'sumberdana' => 'required|in:bg,sg,dti',
            ], [
                'target_aktifitas.required' => 'Target Program RAPPP harus diisi!',
                'target_aktifitas.exists' => 'Target Program RAPPP tidak ditemukan!',
                'volume_target_aktifitas_satuan_not_exists.required' => 'Volume Target harus diisi!',
                'volume_target_aktifitas_satuan_not_exists.numeric' => 'Volume harus berupa angka yang valid!',
                'satuan_traget_aktifitas_satuan_not_exists.required' => 'Satuan Target harus diisi!',
                'sumberdana.required' => 'Sumber Pendanaan harus diisi!',
            ]);
        } else {
            return redirect()->back()->with('error', 'Terjadi kesalahan! data gagal tersimpan!. exists_check tidak valid!');
        }
        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Terjadi kesalahan! data gagal tersimpan!')->withErrors($validator);
        }
        $target_aktifitas = B5TargetAktifitasUtamaOtsus::where('kode_target_aktifitas', $request->target_aktifitas)->first();
        $kode_unik_opd_tag_otsus = $opd->kode_unik_opd . '-' . $target_aktifitas->kode_target_aktifitas . '-' . $request->sumberdana;
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
            'volume' => $request->exists_check == "yes" ? $request->volume_target_aktifitas : $request->volume_target_aktifitas_satuan_not_exists,
            'satuan' => $request->exists_check == "yes" ? $target_aktifitas->satuan : $request->satuan_traget_aktifitas_satuan_not_exists,
            'sumberdana' => $request->sumberdana == 'bg' ? 'Otsus 1%' : ($request->sumberdana == 'sg' ? 'Otsus 1,25%' : 'DTI'),
            'alias_dana' => $request->sumberdana,
            'tahun' => session()->get('tahun'),
        ];
        OpdTagOtsus::create($data);
        return redirect()->back()->with('success', 'Data berhasil disimpan!');
    }

    public function update_opd_rakortek_rappp(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'id_opd_tag_otsus' => 'required|exists:opd_tag_otsuses,id',
                'satuan' => 'sometimes|required|string',
                'volume' => 'required|numeric',
                'sumberdana' => 'required|in:bg,sg,dti',
            ],
            [
                'satuan.required' => 'Satuan Target harus diisi!',
                'satuan.string' => 'Satuan Target harus berupa string yang valid!',
                'volume.required' => 'Volume Target harus diisi!',
                'volume.numeric' => 'Volume harus berupa angka yang valid!',
                'sumberdana.required' => 'Sumber Pendanaan harus diisi!',
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
            'volume' => $request->volume,
            'sumberdana' => $request->sumberdana == 'bg' ? 'Otsus 1%' : ($request->sumberdana == 'sg' ? 'Otsus 1,25%' : 'DTI'),
            'alias_dana' => $request->sumberdana,
        ];
        $tag->update($data);
        return redirect()->back()->with('success', 'Data berhasil diperbarui!');
    }

    public function delete_opd_rakortek_rappp(Request $request)
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
