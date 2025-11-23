<?php

namespace App\Http\Controllers;

use App\Models\Data\Opd;
use App\Models\Data\Lokus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\RapRipppCollection;
use App\Http\Resources\RapRipppResource;
use App\Models\Config\Schedule;
use App\Models\Data\KepalaOpd;
use App\Models\Data\Sumberdana;
use App\Models\Nomenklatur\A1Urusan;
use App\Models\Nomenklatur\A2Bidang;
use App\Models\Nomenklatur\A5Subkegiatan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Nomenklatur\NomenklaturSikd;
use App\Models\Rap\RapOtsus;
use App\Models\Tagging\Nomenklatur\OpdTagBidang;
use App\Models\Tagging\Otsus\OpdTagOtsus;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Spatie\Browsershot\Browsershot;

class TestController extends Controller
{

    public function test(Request $request)
    {
        $opd = Opd::withoutGlobalScopes()->whereHas('tag_otsus', function ($q) use ($request) {
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
        $opd = Opd::withoutGlobalScopes()->whereHas('tag_otsus', function ($q) use ($jenis) {
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

    public function klasifikasi_belanja(Request $request)
    {
        // return Schema::getColumnListing('nomenklatur_sikds');
        return NomenklaturSikd::where('sumberdana', 'dti')->select('klasifikasi_belanja')
            ->distinct()
            ->get();
    }

    public function indikator_rakortek_rappp(Request $request)
    {
        $opds = Auth::user()->hasRole('user')
            ? Auth::user()->opds()->with(['tag_bidang.indikators.target'])
            : Opd::withoutGlobalScopes()->with(['tag_bidang.indikators.target']);

        $opds = $opds->get();

        // return $opds;

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
        });
        return $opds;
    }

    public function getTableColumnNames()
    {
        return Schema::getColumnListing('opd_tag_otsuses');
    }

    public function test_pdf()
    {
        $dir  = storage_path('app/files/pdf');
        File::ensureDirectoryExists($dir); // <-- bikin folder jika belum ada
        Browsershot::html('<h1>Hello world</h1>')->save(storage_path('app/files/pdf/test.pdf'));
    }

    public function test_tim_pembahas()
    {
        $opd = Opd::withoutGlobalScopes()->with('tim_pembahas')->find(91);
        return $opd;
    }

    public function test_nomenklatur(Request $request)
    {
        $klasifikasi = $request->input('klasifikasi', null);
        $filter_bidang = $request->input('bidang', null);
        $nomenClass = new NomenklaturSikd();
        $klasList = DB::table('nomenklatur_sikds')->select('klasifikasi_belanja')
            ->distinct()
            ->get();
        if ($klasifikasi) {
            $nomenClass = $nomenClass->where('klasifikasi_belanja', $klasifikasi);
        }
        $nomenklatur = $nomenClass->get();
        // $rutin = $request->input('rutin', null);
        // return $nomenklatur;
        // return $kode_bidang;
        // return $bidang;

        return view('testing_view.test-nomen', [
            'app' => [
                'title' => 'Test Nomenklatur Sikd',
                'desc' => 'This is a test for Nomenklatur Sikd listing.',
            ],
            'nomenklatur' => $nomenklatur,
            'klasList' => $klasList,
            'filter' => [
                'klasifikasi' => $klasifikasi,
                // 'rutin' => $rutin,
            ],
        ]);
    }

    public function test_kepala_opd()
    {
        $kepala = KepalaOpd::first();
    }

    public function regex_test()
    {
        $opds = json_decode(Storage::disk('public')->get('data/opds/opds.json'), true);
        $tags = [];
        foreach ($opds as $opd) {
            $kode_unik_opd = $opd['tahun'] . '-' . $opd['kode_opd'];
            $expKode = explode('.', $opd['kode_opd']);
            $kode_urusan1 = $expKode[0];
            $bid1 = $expKode[0] . '.' . $expKode[1];
            $kode_urusan2 = $expKode[2];
            $bid2 = $expKode[2] . '.' . $expKode[3];
            $kode_urusan3 = $expKode[4];
            $bid3 = $expKode[4] . '.' . $expKode[5];
            $kode_unik_opd_tag_bidang1 = $kode_unik_opd . '-' . $bid1;
            $kode_unik_opd_tag_bidang2 = $kode_unik_opd . '-' . $bid2;
            $kode_unik_opd_tag_bidang3 = $kode_unik_opd . '-' . $bid3;
            if (!isset($tags[$kode_unik_opd_tag_bidang1]) && $bid1 !== '0.00') {
                $tags[$kode_unik_opd_tag_bidang1] = [
                    'kode_unik_opd' => $kode_unik_opd,
                    'kode_unik_opd_tag_bidang' => $kode_unik_opd_tag_bidang1,
                    'kode_opd' => $opd['kode_opd'],
                    'kode_urusan' => $kode_urusan1,
                    'kode_bidang' => $bid1,
                    'tahun' => $opd['tahun'],
                ];
            }
            if (!isset($tags[$kode_unik_opd_tag_bidang2]) && $bid2 !== '0.00') {
                $tags[$kode_unik_opd_tag_bidang2] = [
                    'kode_unik_opd' => $kode_unik_opd,
                    'kode_unik_opd_tag_bidang' => $kode_unik_opd_tag_bidang2,
                    'kode_opd' => $opd['kode_opd'],
                    'kode_urusan' => $kode_urusan2,
                    'kode_bidang' => $bid2,
                    'tahun' => $opd['tahun'],
                ];
            }
            if (!isset($tags[$kode_unik_opd_tag_bidang3]) && $bid3 !== '0.00') {
                $tags[$kode_unik_opd_tag_bidang3] = [
                    'kode_unik_opd' => $kode_unik_opd,
                    'kode_unik_opd_tag_bidang' => $kode_unik_opd_tag_bidang3,
                    'kode_opd' => $opd['kode_opd'],
                    'tahun' => $opd['tahun'],
                    'kode_urusan' => $kode_urusan3,
                    'kode_bidang' => $bid3,
                ];
            }
        }
        $tags = array_values($tags);
        return $tags;
    }

    public function schema_table(Request $request)
    {
        // return Schema::getTableListing();
        $rows = DB::select("
            SELECT TABLE_NAME AS `table`, COLUMN_NAME AS `column`
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
                AND COLUMN_NAME IN ('kode_unik_opd_tag_bidang')
            ORDER BY TABLE_NAME
        ");
        // return $rows;
        $collect = collect($rows)->filter(function ($item) {
            return $item->table !== 'opd_tag_bidangs';
        })
            ->groupBy('column');
        return $collect;
        $data = [];
        foreach ($collect as $db) {
            $tables = collect($db)->pluck('table')->toArray();
            $data[] = [
                'column' => 'kode_unik_opd_tag_bidang',
                'tables' => $tables,
            ];
        }
        // $data = array_values($data);
        return $data;
    }

    public function nomen_sikd()
    {
        return NomenklaturSikd::whereIn('kode_bidang', ['1.01'])
            // ->where('sumberdana', 'bg')
            ->get();
    }

    public function test_tag_bidang()
    {
        $summary = ['added' => [], 'kept' => [], 'deleted' => [], 'restored' => []];
        $bidangIds = [1, 2, 3, 6];
        $bidang = A2Bidang::whereIn('id', $bidangIds)->get();
        // return $bidang->pluck('kode_bidang');
        $opd = Opd::with([
            'tag_bidang' => fn($q) => $q->withTrashed(),
        ])->find(41);
        // return $opd;
        $existing = $opd->tag_bidang->keyBy('kode_bidang');
        $toAdd = $bidang->filter(fn($item) => !$existing->has($item->kode_bidang))->values();
        $toKeep = $bidang->filter(fn($item) => $existing->has($item->kode_bidang))->values();
        // $toDelete = $existing->filter(fn($item) => !$bidang->contains($item) && !$item->trashed())->values();
        $toDelete = $existing->filter(fn($item) => !in_array($item->kode_bidang, $bidang->pluck('kode_bidang')->toArray()) && !$item->trashed())->values();
        // return $toDelete;

        $summary['added'] = $toAdd;
        $summary['kept'] = $toKeep;
        $summary['deleted'] = $toDelete;
        foreach ($toAdd as $addBidang) {
            $dataAdd = [
                'kode_unik_opd' => $opd->kode_unik_opd,
                'kode_unik_opd_tag_bidang' => $opd->kode_unik_opd . '-' . $addBidang->kode_bidang,
                'kode_opd' => $opd->kode_opd,
                'kode_urusan' => $addBidang->kode_urusan,
                'kode_bidang' => $addBidang->kode_bidang,
                'tahun' => $opd->tahun,
            ];
            OpdTagBidang::create($dataAdd);
        }
        foreach ($toKeep as $keepBidang) {
            $tag = $existing->get($keepBidang->kode_bidang);
            if ($tag && $tag->trashed()) {
                $tag->restore();
            }
        }
        foreach ($toDelete as $delBidang) {
            if ($delBidang && !$delBidang->trashed()) {
                $delBidang->delete();
            }
        }
        return $opd;
    }

    public function result_opd_raps()
    {
        $opd = Opd::with([
            'tag_bidang' => fn($q) => $q->withTrashed(),
            'tag_bidang.raps' => fn($q) => $q->withTrashed(),
        ])->find(41);
        return $opd;
    }

    public function storage_test()
    {
        return json_decode(Storage::disk('public')->get('/data/users/user-skpd2.json'), true);
    }

    public function test_opd()
    {
        $user = User::find(2);
        return $user->opds;
    }

    public function sipd_pemuktahiran()
    {
        $data = json_decode(Storage::disk('public')->get('data/sipd-ri/sipd_pemuktahiran.json'), true);
        $programs = [];
        $kegiatans = [];
        $subkegiatans = [];
        foreach ($data as $item) {
            $prog = explode(' ', $item['program'], 2);
            $kode_urusan = explode('.', $prog[0])[0];
            $kode_bidang = explode('.', $prog[0])[0] . '.' . explode('.', $prog[0])[1];
            $programs[$prog[0]] = [
                'kode_urusan' => explode('.', $prog[0])[0],
                'kode_bidang' => explode('.', $prog[0])[0] . '.' . explode('.', $prog[0])[1],
                'kode_program' => $prog[0],
                'uraian' => isset($prog[1]) ? $prog[1] : '',
                'tahun' => session('tahun'),
            ];
            $keg = explode(' ', $item['kegiatan'], 2);
            $kegiatans[$keg[0]] = [
                'kode_urusan' => explode('.', $prog[0])[0],
                'kode_bidang' => explode('.', $prog[0])[0] . '.' . explode('.', $prog[0])[1],
                'kode_program' => $prog[0],
                'kode_kegiatan' => $keg[0],
                'uraian' => isset($keg[1]) ? $keg[1] : '',
                'tahun' => session('tahun'),
            ];
            $subkeg = explode(' ', $item['subkegiatan'], 2);
            $gaji = false;

            $kode_gaji = [
                'X.XX.01.2.02.0001',
                'X.XX.01.2.11.0001',
                'X.XX.01.3.01.0001',
                'X.XX.01.3.01.0002',
                'X.XX.01.4.01.0001',
                'X.XX.01.4.01.0002',
                'X.XX.01.2.15.0001',
            ];

            if (in_array($subkeg[0], $kode_gaji)) {
                $gaji = true;
            }

            $subkegiatans[$subkeg[0]] = [
                'kode_urusan' => explode('.', $prog[0])[0],
                'kode_bidang' => explode('.', $prog[0])[0] . '.' . explode('.', $prog[0])[1],
                'kode_program' => $prog[0],
                'kode_kegiatan' => $keg[0],
                'kode_subkegiatan' => $subkeg[0],
                'uraian' => isset($subkeg[1]) ? $subkeg[1] : '',
                'indikator' => $item['indikator'],
                'kinerja' => $item['kinerja'],
                'satuan' => $item['satuan'],
                'rutin' => $kode_urusan == 'X' ? true : false,
                'gaji' => $gaji,
                'referensi' => 'SIPD-RI Pemuktahiran Tahun 2025',
                'tag' => $item['tag'],
                'definisi' => $item['definisi_operasional'],
                'pelaksana' => $item['pelaksana'],
                'spm' => $item['spm'],
                'jenis' => $item['jenis'],
                'subkegiatan_sebelumnya' => $item['subkegiatan_sebelumnya'],
                'tahun' => session('tahun'),
            ];
        }
        return array_values($kegiatans);
        // $string = '1.01.02 PROGRAM PENGELOLAAN PENDIDIKAN';
        // preg_match('/^(\S+)/', $string, $matches);
        // return $matches[0];
    }

    public function test_cetak_sipd(Request $request)
    {
        $kode_bidang1 = '1.01';
        $kode_bidang2 = '2.11';
        $kode_bidang3 = '3.25';
        $data = A2Bidang::whereIn('kode_bidang', [$kode_bidang1, $kode_bidang2, $kode_bidang3])
            ->with([
                'program.kegiatan.subkegiatan'
            ])
            ->get();
        return $data;
    }
}
