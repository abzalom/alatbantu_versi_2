<?php

namespace App\Http\Controllers\Otsus;

use App\Models\Data\Opd;
use App\Models\Data\Lokus;
use App\Models\Rap\RapOtsus;
use Illuminate\Http\Request;
use App\Models\Data\Sumberdana;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\InsertRapRequest;
use App\Http\Requests\UpdateRapRequest;
use App\Imports\OpdTagOtsusImport;
use App\Imports\Rap\RapSubkegiatanImport;
use App\Models\Otsus\DanaAlokasiOtsus;
use App\Models\Otsus\Data\B1TemaOtsus;
use App\Models\Nomenklatur\A5Subkegiatan;
use App\Models\Nomenklatur\NomenklaturSikd;
use App\Models\Tagging\Nomenklatur\OpdTagBidang;
use App\Models\Tagging\Otsus\OpdTagOtsus;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Validators\ValidationException;

class RapOtsusController extends Controller
{
    public function rap_old()
    {
        $opds = Opd::with('raps')
            ->withSum([
                'raps as alokasi_bg' => function ($q) {
                    $q->where([
                        'rap_otsuses.sumberdana' => 'Otsus 1%',
                        'rap_otsuses.tahun' => session()->get('tahun'),
                    ]);
                }
            ], 'anggaran')
            ->withSum([
                'raps as alokasi_sg' => function ($q) {
                    $q->where([
                        'rap_otsuses.sumberdana' => 'Otsus 1,25%',
                        'rap_otsuses.tahun' => session()->get('tahun'),
                    ]);
                }
            ], 'anggaran')
            ->withSum([
                'raps as alokasi_dti' => function ($q) {
                    $q->where([
                        'rap_otsuses.sumberdana' => 'DTI',
                        'rap_otsuses.tahun' => session()->get('tahun'),
                    ]);
                }
            ], 'anggaran')
            ->withSum(['raps as pagu' => function ($q) {
                $q->where([
                    'rap_otsuses.tahun' => session()->get('tahun'),
                ]);
            }], 'anggaran')
            ->get();

        $tkdd = DanaAlokasiOtsus::where('tahun', tahun())->first();
        $rap = new RapOtsus;

        $rapKlasBelanja = $rap::select('sumberdana', 'klasifikasi_belanja', 'anggaran')->where(['tahun' => tahun()])->get();
        $rap_bg = $rap::where(['tahun' => tahun(), 'sumberdana' => 'Otsus 1%'])->sum('anggaran');
        $rap_sg = $rap::where(['tahun' => tahun(), 'sumberdana' => 'Otsus 1,25%'])->sum('anggaran');
        $rap_dti = $rap::where(['tahun' => tahun(), 'sumberdana' => 'DTI'])->sum('anggaran');
        $rap_unknow = $rap::where(['tahun' => tahun(), 'sumberdana' => null])->sum('anggaran');
        // return $rapKlasBelanja;

        $alokasi_otsus = [
            'Otsus 1%' => [
                'nama' => 'BG 1%',
                'alokasi' => $tkdd ? $tkdd->alokasi_bg : 0,
                'pagu' => $rap_bg,
                'selisih' => $tkdd ? $tkdd->alokasi_bg - $rap_bg : 0,
            ],
            'Otsus 1,25%' => [
                'nama' => 'SG 1%',
                'alokasi' => $tkdd ? $tkdd->alokasi_sg : 0,
                'pagu' => $rap_sg,
                'selisih' => $tkdd ? $tkdd->alokasi_sg - $rap_sg : 0,
            ],
            'DTI' => [
                'nama' => 'DTI',
                'alokasi' => $tkdd ? $tkdd->alokasi_dti : 0,
                'pagu' => $rap_dti,
                'selisih' => $tkdd ? $tkdd->alokasi_dti - $rap_dti : 0,
            ],
            'unknow' => [
                'nama' => 'UNKNOW',
                'alokasi' => 0,
                'pagu' => $rap_unknow,
                'selisih' => 0 - $rap_unknow,
            ],
        ];

        $dataKlasBel = [];

        foreach ($rapKlasBelanja as $klasBelVal) {
            $sumberdana = $klasBelVal->sumberdana ? $klasBelVal->sumberdana : 'unknow';
            if (!isset($dataKlasBel[$klasBelVal->klasifikasi_belanja][$sumberdana])) {
                $dataKlasBel[$klasBelVal->klasifikasi_belanja][$sumberdana] = [
                    'nama' => $klasBelVal->klasifikasi_belanja,
                    'sumberdana' => $sumberdana,
                    'persentase' => 0,
                    'pagu' => 0,
                ];
            }
            $dataKlasBel[$klasBelVal->klasifikasi_belanja][$sumberdana]['pagu'] += $klasBelVal->anggaran;
            $new_pagu = $dataKlasBel[$klasBelVal->klasifikasi_belanja][$sumberdana]['pagu'];
            $dataKlasBel[$klasBelVal->klasifikasi_belanja][$sumberdana]['persentase'] = $alokasi_otsus[$sumberdana]['alokasi'] !== 0 ? formatIdr($new_pagu / $alokasi_otsus[$sumberdana]['alokasi'] * 100, 2) . '%' : 0;
        }

        // return $dataKlasBel;

        return view('rap.rap', [
            'app' => [
                'title' => 'RAP',
                'desc' => 'RAP Perangkat Daerah',
            ],
            'opds' => $opds,
            'alokasi_otsus' => $alokasi_otsus,
            'dataKlasBel' => $dataKlasBel,
        ]);
    }

    public function rap(Request $request, $jenis)
    {
        $sumberdana = $jenis == 'bg' ? 'Otsus 1%' : ($jenis == 'sg' ? 'Otsus 1,25%' : 'DTI');
        $data = Opd::whereHas('tag_otsus', function ($query) use ($jenis) {
            $query->where([
                'alias_dana' => $jenis,
                'validasi' => true,
                'pembahasan' => 'setujui',
            ]);
        })->with('pagu')
            ->withSum(['raps as alokasi' => function ($query) use ($jenis, $sumberdana) {
                $query->whereHas('tagging', function ($query) use ($jenis) {
                    $query->where([
                        'alias_dana' => $jenis,
                        'validasi' => true,
                        'pembahasan' => 'setujui',
                    ]);
                })
                    ->where([
                        'rap_otsuses.sumberdana' => $sumberdana,
                    ]);
            }], 'anggaran')
            ->orderBy('kode_opd');
        if (auth()->user()->opds()->count() > 0) {
            $data = $data->whereIn('id', auth()->user()->opds()->pluck('id'));
        }
        $data = $data->get();

        // return $data;

        $alokasiKolom = 'alokasi_' . $jenis;
        $alokasi_otsus = DanaAlokasiOtsus::where('tahun', session()->get('tahun'))
            ->first();
        // return $alokasi_otsus;
        $pagu_alokasi = $alokasi_otsus ? $alokasi_otsus->$alokasiKolom : 0;

        if (auth()->user()->hasRole('user')) {
            $pagu_alokasi = 0;
            foreach ($data as $itemOpd) {
                $pagu_alokasi += $itemOpd->pagu ? $itemOpd->pagu->$jenis : 0;
            }
        }

        // return $pagu_alokasi;

        $dataKlasBel = RapOtsus::whereHas('tagging', function ($query) use ($jenis) {
            $query->where([
                'alias_dana' => $jenis,
                'validasi' => true,
                'pembahasan' => 'setujui',
            ]);
        })
            ->where('tahun', session()->get('tahun'))
            ->where('sumberdana', $sumberdana)
            ->select(
                'klasifikasi_belanja as nama',
                DB::raw('SUM(anggaran) as anggaran'),
                DB::raw("SUM(anggaran) / $pagu_alokasi as persen")
            ) // Menggunakan SUM dengan alias
            ->where([
                'tahun' => session()->get('tahun'),
                'sumberdana' => $sumberdana,
                'deleted_at' => null
            ]);
        if (auth()->user()->hasRole('user')) {
            $dataKlasBel = $dataKlasBel->whereIn('kode_unik_opd', auth()->user()->opds->pluck('kode_unik_opd'));
        }
        $dataKlasBel = $dataKlasBel->groupBy('klasifikasi_belanja') // Grup berdasarkan klasifikasi belanja
            ->get();

        // return $dataKlasBel;

        $total_input_rap = RapOtsus::whereHas('tagging', function ($query) use ($jenis) {
            $query->where([
                'alias_dana' => $jenis,
                'validasi' => true,
                'pembahasan' => 'setujui',
            ]);
        })
            ->where([
                'tahun' => session()->get('tahun'),
                'sumberdana' => $sumberdana,
                'deleted_at' => null
            ]);
        if (auth()->user()->opds->count() > 0) {
            $total_input_rap =  $total_input_rap->whereIn('kode_unik_opd', auth()->user()->opds->pluck('kode_unik_opd'));
        }
        $total_input_rap =  $total_input_rap->sum('anggaran');

        // return $total_input_rap;

        return view('v1-1.rap.rap', [
            'app' => [
                'title' => 'RAP',
                'desc' => 'RAP Perangkat Daerah',
            ],
            'jenis' => $jenis,
            'data' => $data,
            'dataKlasBel' => $dataKlasBel,
            'pagu_alokasi' =>  $pagu_alokasi,
            'total_input_rap' => $total_input_rap,
            'selisih_input' => $pagu_alokasi - $total_input_rap,
            'sumberdana' => $sumberdana,
        ]);
    }

    public function renja_rap(Request $request, $jenis)
    {
        $sumberdana = $jenis == 'bg' ? 'Otsus 1%' : ($jenis == 'sg' ? 'Otsus 1,25%' : 'DTI');
        $opd = Opd::whereHas('tag_otsus', function ($q) use ($jenis) {
            $q->where('alias_dana', $jenis)
                ->where('pembahasan', 'setujui')
                ->where('validasi', true)
            ;
        })->with([
            'tag_bidang',
            'target_aktifitas.taggings',
            'raps' => fn($q) => $q->whereHas('tagging', function ($query) use ($jenis) {
                $query->where('alias_dana', $jenis)
                    ->where('pembahasan', 'setujui')
                    ->where('validasi', true)
                ;
            })
                ->where([
                    'rap_otsuses.sumberdana' => $sumberdana,
                    'rap_otsuses.tahun' => session()->get('tahun'),
                ])->withTrashed()->orderBy('kode_subkegiatan'),
            'raps.tagging' => fn($q) => $q->select('kode_unik_opd_tag_otsus', 'sumberdana', 'kode_target_aktifitas', 'volume', 'satuan'),
            'raps.tagging.target_aktifitas' => fn($q) => $q->select('kode_target_aktifitas', 'uraian'),
        ])
            ->withSum([
                'raps as alokasi' => function ($q) use ($sumberdana, $jenis) {
                    $q->whereHas('tagging', function ($query) use ($jenis) {
                        $query->where('alias_dana', $jenis)
                            ->where('pembahasan', 'setujui')
                            ->where('validasi', true)
                        ;
                    })
                        ->where([
                            'rap_otsuses.sumberdana' => $sumberdana,
                            'rap_otsuses.tahun' => session()->get('tahun'),
                        ]);
                }
            ], 'anggaran')
            ->where('id', $request->skpd)
            ->first();
        if (!$opd) {
            return redirect('/rap/' . $jenis)->with('error', 'Perangkat Daerah tidak ditemukan!');
        }

        // return $opd;

        $nomen_sikd = NomenklaturSikd::whereIn('kode_bidang', $opd->tag_bidang->pluck('kode_bidang'))
            ->where('sumberdana', $jenis)
            ->get();
        // return $jenis;
        // dd($nomen_sikd);
        // return $opd->tag_bidang->pluck('kode_bidang');

        $taggings = DB::table('opd_tag_otsuses as tag')
            ->select([
                'tag.id',
                'tag.volume',
                'tag.satuan',
                'tag.sumberdana',
                'tag.alias_dana',
                DB::raw("CONCAT(target.kode_target_aktifitas, ' ', target.uraian) as target_text")
            ])
            ->join('b5_target_aktifitas_utama_otsuses as target', 'tag.kode_target_aktifitas', '=', 'target.kode_target_aktifitas')
            ->where([
                'tag.kode_unik_opd' => $opd->kode_unik_opd,
                'tag.alias_dana' => $jenis,
                'tag.pembahasan' => 'setujui',
                'tag.validasi' => true,
            ])->get();

        $jumlah_program = $opd->raps->groupBy('kode_program')->count();
        $jumlah_kegiatan = $opd->raps->groupBy('kode_keluaran')->count();
        $jumlah_subkegiatan = $opd->raps->count();

        $alokasiKolom = 'alokasi_' . $jenis;
        $alokasi_otsus = DanaAlokasiOtsus::where('tahun', session()->get('tahun'))
            ->first();
        $pagu_alokasi = $alokasi_otsus ? $alokasi_otsus->$alokasiKolom : 0;
        $dataKlasBel = RapOtsus::whereHas('tagging', function ($query) use ($jenis) {
            $query->where('alias_dana', $jenis)
                ->where('pembahasan', 'setujui')
                ->where('validasi', true)
            ;
        })->select(
            'klasifikasi_belanja as nama',
            DB::raw('SUM(anggaran) as anggaran'),
            DB::raw("SUM(anggaran) / $pagu_alokasi as persen")
        )->where([ // Menggunakan SUM dengan alias
            'tahun' => session()->get('tahun'),
            'sumberdana' => $sumberdana,
            'kode_unik_opd' => $opd->kode_unik_opd,
            'deleted_at' => null
        ])->groupBy('klasifikasi_belanja')->get(); // Grup berdasarkan klasifikasi belanja

        $lokasi = Lokus::select(
            'id',
            DB::raw('CONCAT(kecamatan, " | ", kampung) as lokasi'),
        )->get();

        $dana_lain = Sumberdana::whereNot('uraian', $sumberdana)->get();

        // return $dataKlasBel;

        // $view = auth()->user()->opds->count() > 0 ? 'v1-1.user.rap.user-rap-opd' : 'v1-1.admin.rap.admin-rap-opd';

        return view('v1-1.rap.rap-opd', [
            'app' => [
                'title' => 'RAP',
                'desc' => 'RAP ' . $opd->text,
            ],
            'opd' => $opd,
            'jenis' => $jenis,
            'sumberdana' => $sumberdana,
            'dataKlasBel' => $dataKlasBel,
            'jumlah_program' => $jumlah_program,
            'jumlah_kegiatan' => $jumlah_kegiatan,
            'jumlah_subkegiatan' => $jumlah_subkegiatan,
            'lokasi' => $lokasi,
            'dana_lain' => $dana_lain,
            'taggings' => $taggings,
            'nomen_sikd' => $nomen_sikd,
        ]);
    }

    public function renja_form_rap(Request $request, $jenis, $id_opd)
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

        return view('v1-1.rap.renja-rap.renja-rap-form', [
            'app' => [
                'title' => 'RAP',
                'desc' => 'Form Input RAP',
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

    public function insert_new_rap(InsertRapRequest $request, $jenis, $id_opd)
    {
        $opd = Opd::with([
            'tag_otsus' => fn($q) => $q->where('id', $request->input('opd_tag_otsus'))
        ])
            ->find($id_opd);
        if (!$opd) {
            return redirect()->back()->with('error', 'Perangkat Daerah tidak ditemukan! Hubungi Administrator');
        }
        if (!$opd->tag_otsus || !$opd->tag_otsus->count()) {
            return redirect()->back()->with('error', 'Target Aktifitas Utama tidak ditemukan! Hubungi Administrator');
        }
        $opd_tag_otsus = $opd->tag_otsus->first();
        $nomen_sikd = NomenklaturSikd::find($request->input('id_subkegiatan'));
        if (!$nomen_sikd) {
            return redirect()->back()->with('error', 'Subkegiatan tidak ditemukan! Hubungi Administrator');
        }
        $opd_tag_bidang = OpdTagBidang::where('kode_unik_opd_tag_bidang', $opd->kode_unik_opd . '-' . $nomen_sikd->kode_bidang)->first();
        if (!$opd_tag_bidang || !$opd_tag_bidang->count()) {
            return redirect()->back()->with('error', 'Bidang OPD tidak ditemukan! Hubungi Administrator');
        }
        $sumberdana = $jenis == 'bg' ? 'otsus 1%' : ($jenis == 'sg' ? 'otsus 1,25' : 'dti');
        $dana_lain = Sumberdana::whereIn('id', $request->input('dana_lain'))
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'uraian' => $item->uraian,
                ];
            })->toJson();
        $lokus = Lokus::whereIn('id', $request->input('lokus'))
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'kecamatan' => $item->kecamatan,
                    'kampung' => $item->kampung,
                ];
            })->toJson();
        $rap = [
            'kode_unik_opd' => $opd->kode_unik_opd,
            'kode_unik_opd_tag_bidang' => $opd_tag_bidang->kode_unik_opd_tag_bidang,
            'kode_unik_opd_tag_otsus' => $opd_tag_otsus->kode_unik_opd_tag_otsus,
            'kode_unik_sikd' => $nomen_sikd->kode_unik_subkegiatan,
            'kode_opd' => $opd->kode_opd,
            'kode_tema' => $opd_tag_otsus->kode_tema,
            'kode_program' => $opd_tag_otsus->kode_program,
            'kode_keluaran' => $opd_tag_otsus->kode_keluaran,
            'kode_aktifitas' => $opd_tag_otsus->kode_aktifitas,
            'kode_target_aktifitas' => $opd_tag_otsus->kode_target_aktifitas,

            'kode_kegiatan' => $nomen_sikd->kode_kegiatan,
            'nama_kegiatan' => $nomen_sikd->nama_kegiatan,
            'kode_subkegiatan' => $nomen_sikd->kode_subkegiatan,

            'indikator_subkegiatan' => $nomen_sikd->indikator,
            'klasifikasi_belanja' => $nomen_sikd->klasifikasi_belanja,
            'satuan_subkegiatan' => $nomen_sikd->satuan,
            'nama_subkegiatan' => $nomen_sikd->nama_subkegiatan,
            'text_subkegiatan' => $nomen_sikd->text,
            'sumberdana' => $sumberdana,
            'alias_dana' => $jenis,
            'penerima_manfaat' => $request->input('penerima_manfaat'),
            'jenis_layanan' => $request->input('jenis_layanan'),
            'jenis_kegiatan' => $request->input('jenis_kegiatan'),
            'dana_lain' => $dana_lain,
            'lokus' => $lokus,
            'vol_subkeg' => $request->input('vol_subkeg'),
            'anggaran' => $request->input('anggaran'),
            'mulai' => $request->input('mulai'),
            'selesai' => $request->input('selesai'),
            'keterangan' => $request->input('keterangan'),
            'ppsb' => $request->input('ppsb'),
            'multiyears' => $request->input('multiyears'),
            'koordinat' => $request->input('koordinat'),
            'file_path' => 'file-rap/upload/' . session('tahun') . '/skpd/' . $opd->kode_unik_opd . '/',
            'link_file_dukung_lain' => $request->input('link_file_dukung_lain'),
            'tahun' => session()->get('tahun'),
        ];
        try {
            DB::beginTransaction();
            $rap = RapOtsus::create($rap);
            if ($rap) {
                // Simpan file upload KAK dan RAB buat menjadi satu execute
                $inputName = [
                    [
                        'name' => 'file_kak_name',
                        'fileName' => 'kak'
                    ],
                    [
                        'name' => 'file_rab_name',
                        'fileName' => 'rab'
                    ],
                    [
                        'name' => 'file_pendukung1_name',
                        'fileName' => 'pendukung1'
                    ],
                    [
                        'name' => 'file_pendukung2_name',
                        'fileName' => 'pendukung2'
                    ],
                    [
                        'name' => 'file_pendukung3_name',
                        'fileName' => 'pendukung3'
                    ],
                ];

                foreach ($inputName as $key => $value) {
                    if ($request->hasFile($value['name'])) {
                        $file = $request->file($value['name']);
                        $filename = "{$value['fileName']}-rap-{$rap->id}-subkeg-{$nomen_sikd->kode_subkegiatan}-" . now()->format('Ymd_His') . ".pdf"; // Ganti dengan nama file yang sesuai
                        // Simpan file ke storage/public/file-rap/upload/{tahun}/skpd/{kode_unik_opd}/
                        Storage::disk('public')->putFileAs(
                            $rap->file_path,
                            $file,
                            $filename
                        );
                        $rap->{$value['name']} = $filename;
                    }
                }
            }
            $rap->save();
            DB::commit();
            return redirect()->to("/rap/{$jenis}/renja?skpd={$id_opd}")->with('success', 'RAP Berhasil Disimpan!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'RAP Gagal Disimpan!');
        }
    }

    public function update_rap(UpdateRapRequest $request, $jenis, $id_opd)
    {
        if (!$request->has('id_rap') || !$request->id_rap) {
            return redirect()->back()->with('error', 'RAP Belum Dipilih!');
        }
        $rap = RapOtsus::find($request->id_rap);
        if (!$rap) {
            return redirect()->back()->with('error', 'RAP tidak ditemukan!');
        }
        $dana_lain_collection = Sumberdana::whereIn('id', $request->input('dana_lain'))->get();

        if ($dana_lain_collection->isEmpty()) {
            return redirect()->back()->with('error', 'Sumber Pendanaan Lainnya tidak ditemukan! Hubungi Administrator');
        }

        $dana_lain = $dana_lain_collection->map(function ($item) {
            return [
                'id' => $item->id,
                'uraian' => $item->uraian,
            ];
        })->toJson();

        $lokus_collection = Lokus::whereIn('id', $request->input('lokus'))->get();

        if ($lokus_collection->isEmpty()) {
            return redirect()->back()->with('error', 'Lokus tidak ditemukan! Hubungi Administrator');
        }

        $lokus = $lokus_collection->map(function ($item) {
            return [
                'id' => $item->id,
                'kecamatan' => $item->kecamatan,
                'kampung' => $item->kampung,
            ];
        })->toJson();
        $rap->vol_subkeg = $request->input('vol_subkeg');
        $rap->anggaran = $request->input('anggaran');
        $rap->penerima_manfaat = $request->input('penerima_manfaat');
        $rap->jenis_layanan = $request->input('jenis_layanan');
        $rap->ppsb = $request->input('ppsb');
        $rap->multiyears = $request->input('multiyears');
        $rap->mulai = $request->input('mulai');
        $rap->selesai = $request->input('selesai');
        $rap->jenis_kegiatan = $request->input('jenis_kegiatan');
        $rap->koordinat = $request->input('koordinat');
        $rap->keterangan = $request->input('keterangan');
        $rap->link_file_dukung_lain = $request->input('link_file_dukung_lain');
        $rap->dana_lain = $dana_lain;
        $rap->lokus = $lokus;
        // Simpan file upload jika ada
        $inputName = [
            [
                'name' => 'file_kak_name',
                'fileName' => 'kak'
            ],
            [
                'name' => 'file_rab_name',
                'fileName' => 'rab'
            ],
            [
                'name' => 'file_pendukung1_name',
                'fileName' => 'pendukung1'
            ],
            [
                'name' => 'file_pendukung2_name',
                'fileName' => 'pendukung2'
            ],
            [
                'name' => 'file_pendukung3_name',
                'fileName' => 'pendukung3'
            ],
        ];
        foreach ($inputName as $key => $value) {
            if ($request->hasFile($value['name'])) {
                $file = $request->file($value['name']);
                $filename = "{$value['fileName']}-rap-{$rap->id}-subkeg-{$rap->kode_subkegiatan}-" . now()->format('Ymd_His') . ".pdf"; // Ganti dengan nama file yang sesuai
                // Simpan file ke storage/public/file-rap/upload/{tahun}/skpd/{kode_unik_opd}/
                Storage::disk('public')->putFileAs(
                    $rap->file_path,
                    $file,
                    $filename
                );
                $rap->{$value['name']} = $filename;
            }
        }
        $rap->save();
        return redirect()->to("/rap/{$jenis}/renja?skpd={$id_opd}")->with('success', 'RAP Berhasil Diupdate!');
    }

    public function restore_rap(Request $request)
    {
        if (!$request->has('id') || !$request->id) {
            return redirect()->back()->with('error', 'RAP Belum Dipilih!');
        }
        $raps = RapOtsus::onlyTrashed()->where('id', $request->id)->restore();
        return redirect()->back()->with('success', 'RAP Berhasil Dikembalikan!');
    }

    public function destroy_rap(Request $request)
    {
        if (!$request->has('id') || !$request->id) {
            return redirect()->back()->with('error', 'RAP Belum Dipilih!');
        }
        $raps = RapOtsus::onlyTrashed()->where('id', $request->id)->forceDelete();
        return redirect()->back()->with('success', 'RAP Berhasil Dihapus Permanen!');
    }

    /**
     * Old Method
     */

    public function rap_opd(Request $request)
    {
        if (!$request->has('id')) {
            return redirect('/rap')->with('error', 'Terjadi kesalahan!');
        }
        $opd = Opd::with([
            'raps.target_aktifitas',
            'raps' => fn($q) => $q->where('rap_otsuses.tahun', session()->get('tahun'))->orderBy('kode_subkegiatan'),
            // 'raps.subkegiatan',
        ])
            ->withSum([
                'raps as alokasi_bg' => function ($q) {
                    $q->where([
                        'rap_otsuses.sumberdana' => 'otsus 1%',
                        'rap_otsuses.tahun' => session()->get('tahun'),
                    ]);
                }
            ], 'anggaran')
            ->withSum([
                'raps as alokasi_sg' => function ($q) {
                    $q->where([
                        'rap_otsuses.sumberdana' => 'otsus 1,25%',
                        'rap_otsuses.tahun' => session()->get('tahun'),
                    ]);
                }
            ], 'anggaran')
            ->withSum([
                'raps as alokasi_dti' => function ($q) {
                    $q->where([
                        'rap_otsuses.sumberdana' => 'dti',
                        'rap_otsuses.tahun' => session()->get('tahun'),
                    ]);
                }
            ], 'anggaran')
            ->withSum(['raps as pagu' => function ($q) {
                $q->where([
                    'rap_otsuses.tahun' => session()->get('tahun'),
                ]);
            }], 'anggaran')
            ->find($request->id);
        // return $opd;
        if (!$opd) {
            return redirect('/rap')->with('error', 'Terjadi kesalahan!');
        }

        $klasifikasi_belanja = collect($opd->raps)->groupBy('klasifikasi_belanja')->map(function ($items, $key) {
            // return $items->sum('anggaran');
            return [
                'klasifikasi_belanja' => $key,
                'total_anggaran' => $items->sum('anggaran')
            ];
        })->values()->toArray();

        // return $opd;
        // return $klasifikasi_belanja;

        $referensi = [
            'sumberdana' => Sumberdana::get(),
            'lokus' => Lokus::get(),
        ];

        return view('rap.rap-opd', [
            'app' => [
                'title' => 'RAP',
                'desc' => 'RAP ' . $opd->text,
            ],
            'opd' => $opd,
            'referensi' => $referensi,
            'klasifikasi_belanja' => $klasifikasi_belanja,
        ]);
    }

    public function rap_indikator(Request $request)
    {

        // OpdTagOtsus::truncate();
        if (!$request->has('opd')) {
            return redirect('/rap')->with('error', 'Terjadi kesalahan!');
        }

        $opd = Opd::with([
            'tag_otsus' => fn($q) => $q->withCount('raps as raps')->orderBy('kode_target_aktifitas'),
            'tag_otsus.target_aktifitas',
        ])->find($request->opd);

        if (!$opd) {
            return redirect('/rap')->with('error', 'Terjadi kesalahan!');
        }

        $temas = B1TemaOtsus::get();

        // return $opd;

        return view('opd-indikator.opd-indikator', [
            'app' => [
                'title' => 'RAP',
                'desc' => 'RAP Indikator ' . $opd->text,
            ],
            'opd' => $opd,
            'temas' => $temas,
        ]);
    }

    public function rap_form(Request $request)
    {
        if (!$request->has('opd')) {
            return redirect('/rap')->with('error', 'Terjadi kesalahan!');
        }
        $opd = Opd::with([
            'tag_otsus.target_aktifitas' => fn($q) => $q->orderBy('kode_target_aktifitas'),
            'tag_bidang.subkegiatan',
        ])->find($request->opd);
        $sumberdanas = Sumberdana::get();
        $lokasi = Lokus::get();
        // return $opd;
        if (!$opd) {
            return redirect('/rap')->with('error', 'Terjadi kesalahan!');
        }
        // return $opd->tag_bidang;
        return view('rap.rap-form', [
            'app' => [
                'title' => 'RAP',
                'desc' => 'Form Input RAP',
            ],
            'opd' => $opd,
            'sumberdanas' => $sumberdanas,
            'lokasi' => $lokasi,
        ]);
    }

    public function rap_insert_form(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'opd' => 'required|exists:opds,id',
                'opd_tag_otsus' => 'required|exists:opd_tag_otsuses,id',
                'subkegiatan' => 'required|exists:a5_subkegiatans,id',
                'sumberdana' => 'required|exists:sumberdanas,uraian',
                'vol_subkeg' => 'required|numeric',
                'anggaran' => 'required|numeric',
                'mulai' => 'required',
                'selesai' => 'required',
                'penerima_manfaat' => 'required',
                'jenis_layanan' => 'required',
                'ppsb' => 'required',
                'multiyears' => 'required',
                'jenis_kegiatan' => 'required',
                'lokus' => 'required',
                'koordinat' => Rule::requiredIf($request->jenis_kegiatan == 'fisik'),
                'dana_lain' => 'required',
            ],
            [
                'opd.required' => 'Perangkat Daerah tidak boleh kosong!',
                'opd.exists' => 'Perangkat Daerah tidak ditemukan!',
                'opd_tag_otsus.required' => 'Target aktifitas tidak boleh kosong!',
                'opd_tag_otsus.exists' => 'Target aktifitas tidak ditemukan!',
                'subkegiatan.required' => 'Sub Kegiatan tidak boleh kosong!',
                'subkegiatan.exists' => 'Sub Kegiatan tidak ditemukan!',
                'sumberdana.required' => 'Sumberdana tidak boleh kosong!',
                'sumberdana.exists' => 'Sumberdana tidak ditemukan!',
                'vol_subkeg.required' => 'Volume kegiatan tidak boleh kosong!',
                'vol_subkeg.numeric' => 'Volume harus berupa angka!',
                'anggaran.required' => 'Anggaran kegiatan tidak boleh kosong!',
                'anggaran.numeric' => 'Anggaran harus berupa angka!',
                'jenis_kegiatan.required' => 'Jenis kegiatan tidak boleh kosong!',
                'mulai.required' => 'Mulai Pelaksanaan tidak boleh kosong!',
                'selesai.required' => 'Selesai Pelaksanaan tidak boleh kosong!',
                'penerima_manfaat.required' => 'Penerima Manfaat tidak boleh kosong!',
                'jenis_layanan.required' => 'Jenis Layanan tidak boleh kosong!',
                'ppsb.required' => 'PPSB tidak boleh kosong!',
                'multiyears.required' => 'Multiyears tidak boleh kosong!',
                'dana_lain.required' => 'Sumber Dana Lain tidak boleh kosong!',
                'lokus.required' => 'Lokasi Fokus tidak boleh kosong!',
                'koordinat.required' => 'Kegiatan fisik wajib ada koordinat!',
            ]
        );
        // return $request->all();
        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Terjadi kesalahan! data gagal di simpan!')
                ->withInput($request->all())
                ->withErrors($validator);
        }

        $opd = Opd::find($request->opd);
        $opd_tag_otsus = OpdTagOtsus::with('target_aktifitas')->find($request->opd_tag_otsus);
        $subkegiatan = A5Subkegiatan::find($request->subkegiatan);
        $alias_dana = $request->sumberdana == 'Otsus 1%' ? 'bg' : ($request->sumberdana == 'Otsus 1,25%' ? 'sg' : 'dti');
        $sikd = NomenklaturSikd::where([
            'kode_unik_subkegiatan' => $subkegiatan->kode_subkegiatan . '-' . $alias_dana,
        ])->first();


        $data = [
            'kode_unik_opd' => $opd->kode_unik_opd,
            'kode_unik_opd_tag_bidang' => $opd->kode_unik_opd . '-' . $subkegiatan->kode_bidang,
            'kode_unik_opd_tag_otsus' => $opd_tag_otsus->kode_unik_opd_tag_otsus,
            'kode_unik_sikd' => $subkegiatan->kode_subkegiatan . '-' . $alias_dana,
            'kode_opd' => $opd->kode_opd,
            'kode_tema' => $opd_tag_otsus->kode_tema,
            'kode_program' => $opd_tag_otsus->kode_program,
            'kode_keluaran' => $opd_tag_otsus->kode_keluaran,
            'kode_aktifitas' => $opd_tag_otsus->kode_aktifitas,
            'kode_target_aktifitas' => $opd_tag_otsus->kode_target_aktifitas,
            'kode_subkegiatan' => $subkegiatan->kode_subkegiatan,
            'nama_subkegiatan' => $subkegiatan->uraian,
            'satuan_subkegiatan' => $subkegiatan->satuan,
            'indikator_subkegiatan' => $subkegiatan->indikator,
            'klasifikasi_belanja' => $sikd->klasifikasi_belanja,
            'text_subkegiatan' => $subkegiatan->kode_subkegiatan . ' ' . $subkegiatan->uraian,
            'sumberdana' => $request->sumberdana,
            'penerima_manfaat' => $request->penerima_manfaat,
            'jenis_layanan' => $request->jenis_layanan,
            'jenis_kegiatan' => $request->jenis_kegiatan,
            'dana_lain' => Sumberdana::whereIn('id', $request->dana_lain)->select('id', 'uraian')->get()->toJson(),
            'lokus' => Lokus::whereIn('id', $request->lokus)->select('id', 'kecamatan', 'kampung')->get()->toJson(),
            'vol_subkeg' => $request->vol_subkeg,
            'anggaran' => $request->anggaran,
            'mulai' => $request->mulai,
            'selesai' => $request->selesai,
            'keterangan' => $request->keterangan,
            'ppsb' => $request->ppsb,
            'multiyears' => $request->multiyears,
            'koordinat' => $request->koordinat,
            'catatan' => $request->catatan,
            'tahun' => session()->get('tahun'),
        ];

        $validator_duplikasi_subkegiatan = Validator::make(
            $request->all(),
            [
                'subkegiatan' => [
                    function ($attribute, $value, $fail) use ($data) {
                        $exists = RapOtsus::where([
                            'kode_unik_opd_tag_otsus' => $data['kode_unik_opd_tag_otsus'],
                            'kode_subkegiatan' => $data['kode_subkegiatan'],
                            'sumberdana' => $data['sumberdana'],
                        ])->exists();
                        if ($exists) {
                            $fail("Subkegiatan sudah ada.");
                        }
                    }
                ]
            ]
        );

        if ($validator_duplikasi_subkegiatan->fails()) {
            return redirect()->back()->with('error', 'Terjadi kesalahan! Sub kegiatan sudah ada!')
                ->withInput($request->all())
                ->withErrors($validator_duplikasi_subkegiatan);
        }

        try {
            DB::beginTransaction();
            RapOtsus::create($data);
            DB::commit();
            return redirect()->to('rap/opd?id=' . $opd->id)->with('success', 'Data berhasil di simpan!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->to('rap/opd?id=' . $opd->id)->with('error', 'Data gagal di simpan!');
        }
    }

    public function rap_upload_indikator_opd(Request $request)
    {
        // return $request->all();

        try {
            DB::beginTransaction();

            $import = new OpdTagOtsusImport();
            $import->import($request->file('file'));

            if ($import->failures()->isNotEmpty()) {
                // return $import->failures()->pluck('values');
                $failures = [];
                foreach ($import->failures() as $failure) {
                    if (!empty($failure->values())) {
                        // return $failure;
                        $row = $failure->row();
                        $attribute = $failure->attribute() === 'kode_unik_opd_tag_otsus' ? 'kode_indikator' : $failure->attribute();
                        $attribute = $attribute === 'kode_target_aktifitas' ? 'kode_indikator' : $attribute;
                        $attribute = $attribute === 'kode_unik_opd' ? 'kode_skpd' : $attribute;
                        // $attribute = $attribute === 'vol_subkeg' ? 'volume_subkegiatan' : $attribute;
                        if (!isset($failures[$row])) {
                            $failures[$row] = [
                                'row' => $row,
                                'errors' => [],
                                'values' => $failure->values(),
                            ];
                        }
                        $failures[$row]['errors'][] = [
                            'attribute' => $attribute,
                            'message' => implode(', ', $failure->errors())
                        ];
                    }
                }

                // Ubah hasil menjadi array yang bersarang
                $failures = array_values($failures);
                // return $failures;
                DB::rollback();
                if (!empty($failures)) {
                    return back()
                        ->with([
                            'failures' => $failures,
                            'error' => 'Terjadi kesalahan selama proses unggah!'
                        ]);
                }
                return back()
                    ->with([
                        'info' => 'Data batal di simpan! Data yang di upload sudah ada!'
                    ]);
            } else {
                DB::commit();
                return back()->with('success', 'Data berhasil diimport!');
            }
        } catch (ValidationException $th) {
            DB::rollback();
            return back()->with([
                'error' => 'terjadi kesalahan! data gagal di simpan! : ' . $th,
            ]);
        }
    }

    public function rap_upload_subkegiatan(Request $request)
    {
        try {
            DB::beginTransaction();
            $import = new RapSubkegiatanImport();
            $import->import($request->file('rap_file'));

            if ($import->failures()->isNotEmpty()) {
                $failures = [];
                foreach ($import->failures() as $failure) {
                    // return $failure;
                    $row = $failure->row();
                    $attribute = $failure->attribute() === 'kode_unik_opd_tag_otsus' ? 'kode_indikator' : $failure->attribute();
                    $attribute = $attribute === 'kode_target_aktifitas' ? 'indikator' : $attribute;
                    $attribute = $attribute === 'vol_subkeg' ? 'volume_subkegiatan' : $attribute;
                    if (!isset($failures[$row])) {
                        $failures[$row] = [
                            'row' => $row,
                            'errors' => [],
                            'values' => $failure->values(),
                        ];
                    }
                    $failures[$row]['errors'][] = [
                        'attribute' => $attribute,
                        'message' => implode(', ', $failure->errors())
                    ];
                }

                // Ubah hasil menjadi array yang bersarang
                $failures = array_values($failures);
                DB::rollback();
                // return $failures;
                return back()
                    ->with([
                        'failures' => $failures,
                        'error' => 'Terjadi kesalahan selama proses unggah!'
                    ]);
            } else {
                DB::commit();
                return back()->with('success', 'Data berhasil diimport!');
            }
        } catch (ValidationException $e) {
            DB::rollback();
            if ($e->failures()) {
                $error = collect($e->failures())->map(function ($failure) {
                    return [
                        'row' => $failure->row(),
                        'attribute' => $failure->attribute(),
                        'errors' => implode(', ', $failure->errors()), // Menambahkan spasi setelah koma untuk kejelasan
                        'values' => $failure->values()
                    ];
                })->toArray();
            }
            return back()->with('error', 'Terjadi kesalahan saat impor.');
        }
    }

    public function rap_upload_data_dukung(Request $request)
    {
        $request->validate(
            [
                'id_rap' => 'required|exists:rap_otsuses,id',
                'opd_id' => 'required|exists:opds,id',
                'file_kak' => [
                    Rule::requiredIf(function () use ($request) {
                        $rap = RapOtsus::find($request->id_rap);
                        $path = $rap->file_path ?? null;
                        $fileName = $rap->file_kak_name ?? null;
                        if (is_null($fileName) && (!isset($path) || !is_file(Storage::disk('public')->path($path . $fileName)))) {
                            return true;
                        } elseif (!is_null($fileName) && (!isset($path) || !is_file(Storage::disk('public')->path($path . $fileName)))) {
                            return true;
                        }
                    }),
                    'mimes:pdf',
                    'max:5048', // Maksimum ukuran file 5 MB
                ],
                'file_rab' => [
                    Rule::requiredIf(function () use ($request) {
                        $rap = RapOtsus::find($request->id_rap);
                        $path = $rap->file_path ?? null;
                        $fileName = $rap->file_rab_name ?? null;
                        if (is_null($fileName) && (!isset($path) || !is_file(Storage::disk('public')->path($path . $fileName)))) {
                            return true;
                        } elseif (!is_null($fileName) && (!isset($path) || !is_file(Storage::disk('public')->path($path . $fileName)))) {
                            return true;
                        }
                    }),
                    'mimes:pdf',
                    'max:5048', // Maksimum ukuran file 5 MB
                ],
                'file_pendukung1' => 'nullable|mimes:pdf|max:5048',
                'file_pendukung2' => 'nullable|mimes:pdf|max:5048',
                'file_pendukung3' => 'nullable|mimes:pdf|max:5048',
                'link_file_dukung_lain' => 'nullable|url',
            ],
            [
                'id_rap.required' => 'Sub Kegiatan tidak ditemukan!',
                'id_rap.exists' => 'Sub Kegiatan tidak ditemukan!',

                'opd_id.required' => 'Perangkat Daerah tidak ditemukan!',
                'opd_id.exists' => 'Perangkat Daerah tidak ditemukan!',

                'file_kak.required' => 'File KAK tidak boleh kosong!',
                'file_kak.mimes' => 'File KAK hanya boleh berformat PDF!',
                'file_kak.max' => 'Ukuran file KAK tidak boleh lebih dari 5MB!',

                'file_rab.required' => 'File RAB tidak boleh kosong!',
                'file_rab.mimes' => 'File RAB hanya boleh berformat PDF!',
                'file_rab.max' => 'Ukuran file RAB tidak boleh lebih dari 5MB!',

                'file_pendukung1.mimes' => 'File Pendukung Pilihan 1 hanya boleh berformat PDF!',
                'file_pendukung1.max' => 'Ukuran file pendukung pilihan 1 tidak boleh lebih dari 5MB!',

                'file_pendukung2.mimes' => 'File Pendukung Pilihan 2 hanya boleh berformat PDF!',
                'file_pendukung2.max' => 'Ukuran file pendukung pilihan 2 tidak boleh lebih dari 5MB!',

                'file_pendukung3.mimes' => 'File Pendukung Pilihan 3 hanya boleh berformat PDF!',
                'file_pendukung3.max' => 'Ukuran file pendukung pilihan 3 tidak boleh lebih dari 5MB!',

                'link_file_dukung_lain.url' => 'google drive harus berupa link',
            ]
        );


        $rap = RapOtsus::findOrFail($request->id_rap);
        $opd = Opd::find($request->opd_id);

        if (!$rap) {
            return redirect()->back()->with('error', 'RAP tidak ditemukan!');
        }
        if (!$opd) {
            return redirect()->back()->with('error', 'Perangkat Daerah tidak ditemukan!');
        }
        $date = now()->format('Ymd_hms');
        $path = 'file-rap/uploads/' . session('tahun') . '/skpd/' . $opd->kode_unik_opd . '/';
        $filePrefix = ['kak', 'rab', 'pendukung1', 'pendukung2', 'pendukung3'];
        $fileNames = [];

        foreach ($filePrefix as $prefix) {
            if ($request->hasFile("file_$prefix")) {
                $fileNames[] = [
                    'check' => "$prefix-rap-{$rap->id}-subkeg-{$rap->kode_subkegiatan}",
                    'name' => "$prefix-rap-{$rap->id}-subkeg-{$rap->kode_subkegiatan}-{$date}" . '.' . $request->file("file_$prefix")->getClientOriginalExtension(),
                    'input' => "file_$prefix",
                    'attribute' => "file_{$prefix}_name",
                ];
            }
        }

        foreach ($fileNames as $fileItem) {
            $fullPath = $path . $fileItem['name'];

            if (!Storage::disk('public')->exists($fullPath)) {
                $scandir = Storage::disk('public')->allFiles($path);
                foreach ($scandir as $itemFileDir) {
                    $expItemFile = explode('/', $itemFileDir);
                    $findPrefix = explode('-', $expItemFile[5]);
                    if ($findPrefix[0] == $fileItem['check']) {
                        Storage::disk('public')->delete($itemFileDir);
                    }
                }
                $content = file_get_contents($request->file($fileItem['input'])->getRealPath());
                Storage::disk('public')->put($fullPath, $content);
            }
            $attribute = $fileItem['attribute'];
            $rap->$attribute = $fileItem['name'];
        }
        $rap->file_path = $path;
        $rap->link_file_dukung_lain = $request->link_file_dukung_lain;
        $rap->save();
        return redirect()->back()->with('success', 'File berhasil diupload!');
    }

    public function view_file(Request $request)
    {
        // return $request->all();
        $path = $request->get('path');
        $name = $request->get('name');

        if (!empty($name) && is_file(Storage::disk('public')->path($path . $name))) {
            $file = Storage::disk('public')->path($path . $name);
            return response()->file($file);
        }
        return abort(404, 'File not found.');
    }

    public function download_file(Request $request)
    {
        $file = $request->get('file');
        $name = str_replace('/', '_', $request->get('name'));

        if (!empty($name) && is_file(Storage::disk('public')->path($file))) {
            $file = Storage::disk('public')->path($file);
            return response()->download($file, $name);
        }
        return abort(404, 'File not found.');
    }

    public function rap_delete_data_dukung(Request $request)
    {
        if (!$request->has('id_rap') || !$request->has('filename')) {
            return redirect()->back()->with('error', 'Terjadi kesalahan! data gagal dihapus');
        }
        $attributes = ['file_kak_name', 'file_rab_name', 'file_pendukung1_name', 'file_pendukung2_name', 'file_pendukung3_name'];
        if (!in_array($request->filename, $attributes)) {
            return redirect()->back()->with('error', 'Terjadi kesalahan! data gagal dihapus');
        }
        $rap = RapOtsus::find($request->id_rap);
        if (empty($rap)) {
            return redirect()->back()->with('error', 'Terjadi kesalahan! data gagal dihapus');
        }
        $attribute = $request->filename;

        $path = $rap->file_path;
        $name = $rap->$attribute;
        $file = $path . $name;
        if (!Storage::disk('public')->delete($file)) {
            return redirect()->back()->with('error', 'Terjadi kesalahan! data gagal dihapus');
        }
        $rap->$attribute = null;
        $rap->save();
        return redirect()->back()->with('success', 'File berhasil dihapus!');
    }
}
