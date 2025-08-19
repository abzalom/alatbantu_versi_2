<?php

namespace App\Http\Controllers;

use App\Models\Data\Opd;
use App\Models\Data\Lokus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\RapRipppCollection;
use App\Http\Resources\RapRipppResource;
use App\Models\Data\KepalaOpd;
use App\Models\Data\Sumberdana;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Nomenklatur\NomenklaturSikd;
use App\Models\Rap\RapOtsus;
use App\Models\Tagging\Nomenklatur\OpdTagBidang;
use App\Models\Tagging\Otsus\OpdTagOtsus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class TestController extends Controller
{

    public function test(Request $request)
    {
        $opd = Opd::whereHas('tag_otsus', function ($q) use ($request) {
            $q->where('alias_dana', $request->jenis)
                ->where('pembahasan', 'setujui')
                ->where('validasi', true)
            ;
        })
            ->with([
                'tag_otsus' => fn($q) => $q->where([
                    'alias_dana' => $request->jenis,
                    'pembahasan' => 'setujui',
                    'validasi' => true,
                ])->whereHas('raps', function ($q) use ($request) {
                    $q->where('alias_dana', $request->jenis)
                        ->where([
                            'pembahasan' => 'setujui',
                            'validasi' => true,
                        ])
                        ->whereIn('pembahasan', $request->list == 'semua' ? ['setujui', 'tolak'] : [$request->list]);
                }),
                'target_aktifitas.aktifitas.program',
                'tag_otsus.raps' => fn($q) => $q->where([
                    'alias_dana' => $request->jenis,
                    'validasi' => true,
                ])
                    ->whereIn('pembahasan', $request->list == 'semua' ? ['setujui', 'tolak'] : [$request->list]),
            ])
            ->find($request->opd);
        // return $opd;
        $data = [];

        foreach ($opd->tag_otsus as $tag) {
            if (!isset($data[$tag->program->kode_program])) {
                $data[$tag->program->kode_program] = [
                    'uraian' => $tag->program->kode_program . ' ' .  $tag->program->uraian,
                    'aktifitas' => []
                ];
            }
            if (!isset($data[$tag->program->kode_program]['aktifitas'][$tag->aktifitas->kode_aktifitas])) {
                $data[$tag->program->kode_program]['aktifitas'][$tag->aktifitas->kode_aktifitas] = [
                    'uraian' => $tag->aktifitas->kode_aktifitas . ' ' .  $tag->aktifitas->uraian,
                    'target_aktifitas' => []
                ];
            }
            if (!isset($data[$tag->program->kode_program]['aktifitas'][$tag->aktifitas->kode_aktifitas]['target_aktifitas'][$tag->target_aktifitas->kode_target_aktifitas])) {
                $data[$tag->program->kode_program]['aktifitas'][$tag->aktifitas->kode_aktifitas]['target_aktifitas'][$tag->target_aktifitas->kode_target_aktifitas] = [
                    'uraian' => $tag->target_aktifitas->kode_target_aktifitas . ' ' .  $tag->target_aktifitas->uraian,
                    'raps' => []
                ];
            }

            foreach ($tag->raps as $rap) {
                if (!isset($data[$rap->kode_program]['aktifitas'][$rap->kode_aktifitas]['target_aktifitas'][$rap->kode_target_aktifitas]['raps'][$rap->id])) {
                    $data[$rap->kode_program]['aktifitas'][$rap->kode_aktifitas]['target_aktifitas'][$rap->kode_target_aktifitas]['raps'][$rap->id] = [
                        'subkegiatan' => $rap->text_subkegiatan,
                        'indikator' => $rap->indikator_subkegiatan,
                        'target' => $rap->vol_subkeg,
                        'anggaran' => $rap->anggaran,
                        'sumberdana' => $rap->sumberdana,
                        'penerima_manfaat' => $rap->penerima_manfaat,
                        'jenis_layanan' => $rap->jenis_layanan,
                        'jenis_kegiatan' => $rap->jenis_kegiatan,
                        'dana_lain' => $rap->dana_lain,
                        'mulai' => $rap->mulai,
                        'selesai' => $rap->selesai,
                        'ppsb' => $rap->ppsb,
                        'multiyears' => $rap->multiyears,
                        'keterangan' => $rap->keterangan,
                        'pembahasan' => $rap->pembahasan,
                        'validasi' => $rap->validasi,
                    ];
                }
            }
        }

        // return $opd;
        return RapOtsus::find(7);
    }

    public function test_form(Request $request, $jenis, $id_opd)
    {
        $opd = Opd::whereHas('tag_otsus', function ($q) use ($jenis) {
            $q->where('alias_dana', $jenis)
                ->where('pembahasan', 'setujui')
                ->where('validasi', true);
        });

        if ($request->has('edit')) {
            if (!$request->edit) {
                return redirect('/rap/' . $jenis . '/renja?skpd=' . $id_opd)->with('error', 'RAP Belum Dipilih!');
            }

            $opd = $opd->with([
                'raps' => fn($q) => $q->where([
                    'rap_otsuses.id' => $request->edit,
                ]),
                'raps.tagging.target_aktifitas' => fn($q) => $q->select([
                    'kode_target_aktifitas',
                    'uraian',
                    DB::raw("CONCAT(kode_target_aktifitas, ' ', uraian) as target_text")
                ]),
                'raps.nomen_sikd' => fn($q) => $q->select([
                    'kode_unik_subkegiatan',
                    'indikator',
                    'klasifikasi_belanja',
                    'satuan',
                    'text',
                ]),
            ]);

            // ✅ Eksekusi query builder menjadi instance model
            $opd = $opd->find($id_opd);

            if (!$opd || !$opd->raps || !$opd->raps->count()) {
                return redirect('/rap/' . $jenis . '/renja?skpd=' . $id_opd)->with('error', 'RAP tidak ditemukan!');
            }
        } else {
            // Jika tidak ada edit, baru panggil find di sini
            $opd = $opd->find($id_opd);

            if (!$opd) {
                return redirect('/rap/' . $jenis)->with('error', 'Perangkat Daerah tidak ditemukan!');
            }
        }

        // return gettype(json_decode($opd->raps->first()->dana_lain, true));

        $taggings = DB::table('opd_tag_otsuses as tag')
            ->select([
                'tag.id',
                'tag.volume',
                'tag.satuan',
                'tag.sumberdana',
                'tag.alias_dana',
                'tag.kode_unik_opd_tag_otsus',
                DB::raw("CONCAT(target.kode_target_aktifitas, ' ', target.uraian) as target_text")
            ])
            ->join('b5_target_aktifitas_utama_otsuses as target', 'tag.kode_target_aktifitas', '=', 'target.kode_target_aktifitas')
            ->where([
                'tag.kode_unik_opd' => $opd->kode_unik_opd,
                'tag.alias_dana' => $jenis,
                'tag.pembahasan' => 'setujui',
                'tag.validasi' => true,
            ])->get();
        $nomen_sikd = DB::table('nomenklatur_sikds')->whereIn('kode_bidang', $opd->tag_bidang->pluck('kode_bidang'))
            ->where('sumberdana', $jenis)
            ->get();

        $lokasi = DB::table('lokuses')->select(
            'id',
            DB::raw('CONCAT(kecamatan, " | ", kampung) as lokasi'),
        )->get();
        if (old()) {
            return old();
        }
        // return $opd;
        return view('testing_view.test-form-component', [
            'app' => [
                'title' => 'Test Form',
                'desc' => 'This is a test form for testing purposes.',
            ],
            'opd' => $opd,
            'jenis' => $jenis,
            'sumberdana' => $jenis == 'bg' ? 'OTSUS 1% (bersifat umum)' : ($jenis == 'sg' ? 'OTSUS 1,25% (bersifat khusus)' : 'Dana Tambahan Infrastruktur (DTI)'),
            'lokasi' => $lokasi,
            'dana_lains' => Sumberdana::whereNot('uraian', $jenis == 'bg' ? 'Otsus 1%' : ($jenis == 'sg' ? 'Otsus 1,25%' : 'DTI'))->get(),
            'taggings' => $request->has('edit') && $opd->raps->count() ? $opd->raps->first()->tagging : $taggings,
            'nomen_sikd' => $nomen_sikd,
            'edit_rap' => $opd->raps->count() ? $opd->raps->first() : null,
        ]);
    }


    public function post_test(Request $request)
    {
        // return $request->all();
        $request->merge([
            'anggaran' => clearFloatFormat($request->anggaran),
            'vol_subkeg' => clearFloatFormat($request->vol_subkeg),
        ]);
        // return $request->all();
        $validator = Validator::make(
            $request->all(),
            [
                'opd_tag_otsus' => 'required',
                'id_subkegiatan' => 'required',
                'indikator' => 'required',
                'klasifikasi_belanja' => 'required',
                'vol_subkeg' => 'required',
                'anggaran' => 'required',
                'penerima_manfaat' => 'required',
                'jenis_layanan' => 'required',
                'ppsb' => 'required',
                'multiyears' => 'required',
                'mulai' => 'required',
                'selesai' => 'required',
                'jenis_kegiatan' => 'required',
                'lokus' => 'required|array',
                'koordinat' => 'required',
                'dana_lain' => 'required|array',
                'keterangan' => 'required',
            ],
            [
                'opd_tag_otsus.required' => 'Test Komponen Tidak boleh kosong!'
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Terjadi kesalahan')->withInput()->withErrors($validator);
        }
        // return $request->all();
        return redirect()->back()->withInput();
    }

    public function preg_testing()
    {
        // $string = 'file_kak_name';
        // return str_replace(['file_', '_name'], '', $string);
    }

    public function test_file()
    {
        // $file_path = 'file-rap/uploads/2026/skpd/2026-2.12.0.00.0.00.01.0000/';
        // $file_kak_name = 'file_kak_name-rap-170-subkeg-2.12.02.2.01.0001-20250623_060650.pdf';
        // $allFiles = Storage::disk('public')->allFiles($file_path);
        // foreach ($allFiles as $itemFile) {
        //     $expFileName = explode('/', $itemFile);
        //     dump(last($expFileName));
        // }
    }


    public function error_test()
    {
        return view('testing_view.testing-error-view', [
            'app' => [
                'title' => 'Error Test',
                'desc' => 'This is a test for error handling.',
            ],
            'data' => [
                'message' => "Perangkat daerah akan muncul di sini jika 
                sebelumnya sudah menginput indikator kinerja urusan.",
                'backUrl' => url()->previous(),
                'backText' => 'Input indikator kinerja urusan',
            ],
        ]);
    }

    public function get_file_rap($tahun, $skpd, $file)
    {
        $path = "file-rap/uploads/{$tahun}/skpd/{$skpd}/";
        $name = $file;
        if (!empty($name) && is_file(Storage::disk('public')->path($path . $name))) {
            $file = Storage::disk('public')->path($path . $name);
            return response()->file($file);
        }
        return abort(404, 'File not found.');
    }

    public function quick_count_psu_papua(Request $request)
    {
        $user = Auth::user();
        $sumberdana = $request->jenis == 'bg' ? 'Otsus 1%' : ($request->jenis == 'sg' ? 'Otsus 1,25%' : 'DTI');

        $query = $user->hasRole('user')
            // jika role user: batasi ke OPD yang dimiliki user
            ? $user->opds()->where('opds.id', $request->skpd)   // pakai opds.id agar aman saat join pivot
            // jika bukan user: langsung ke model Opd
            : Opd::query()->whereKey($request->skpd);

        // filter wajib: hanya OPD yang punya tag_otsus sesuai
        $query = $query->whereHas('tag_otsus', function ($q) use ($request) {
            $q->where('alias_dana', $request->jenis)
                ->where('pembahasan', 'setujui')
                ->where('validasi', true);
        });

        // filter wajib: hanya OPD yang punya tag_otsus sesuai
        // $query = $query->with([
        //     'tag_otsus' => fn($q) => $q->where('alias_dana', $request->jenis)
        //         ->where('pembahasan', 'setujui')
        //         ->where('validasi', true),
        //     'tag_otsus.target_aktifitas' => fn($q) => $q->select(['kode_target_aktifitas', 'uraian', 'satuan']),
        //     'tag_otsus.raps.subkegiatan.kegiatan.program.bidang.urusan'
        // ]);
        $opd = $query->first();
        if (!$opd) {
            abort(404, 'Perangkat Daerah tidak ditemukan!');
        }
        // return $opd;
        $tag_bidang = OpdTagBidang::where('kode_unik_opd', $opd->kode_unik_opd)->get();
        $nomen_sikd = NomenklaturSikd::whereIn('kode_bidang', $tag_bidang->pluck('kode_bidang'))->get();
        $raps = RapOtsus::with('tagging.target_aktifitas')
            ->whereHas('tagging', function ($query) use ($request) {
                $query->where('alias_dana', $request->jenis)
                    ->where('pembahasan', 'setujui')
                    ->where('validasi', true);
            })
            ->where('alias_dana', $request->jenis)
            ->where('kode_unik_opd', $opd->kode_unik_opd)
            ->get();

        // dump(new RapRipppCollection($raps));
        return new RapRipppCollection($raps);

        // return $raps->groupBy('tagging.target_aktifitas.kode_target_aktifitas');

        // EKSEKUSI query

        // return $opd->get();


        // return $opd;
        // $opd = Opd::where('id', $opd_user->id)->get();
    }
}
