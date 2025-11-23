<?php

namespace App\Http\Controllers\Otsus;

use App\Models\Data\Opd;
use App\Models\Data\Lokus;
use App\Models\Rap\RapOtsus;
use Illuminate\Http\Request;
use App\Models\Data\Sumberdana;
use Illuminate\Validation\Rule;
use App\Imports\OpdTagOtsusImport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Models\Otsus\DanaAlokasiOtsus;
use App\Models\Otsus\Data\B1TemaOtsus;
use App\Http\Requests\InsertRapRequest;
use App\Http\Requests\UpdateRapRequest;
use Illuminate\Support\Facades\Storage;
use App\Imports\Rap\RapSubkegiatanImport;
use App\Models\Config\TimPembahas;
use App\Models\Nomenklatur\A5Subkegiatan;
use App\Models\Tagging\Otsus\OpdTagOtsus;
use Illuminate\Support\Facades\Validator;
use App\Models\Nomenklatur\NomenklaturSikd;
use App\Models\Tagging\Nomenklatur\OpdTagBidang;
use Illuminate\Support\Facades\Log;
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
        if (!in_array($jenis, ['bg', 'sg', 'dti'])) {
            abort(404);
        }
        $user = Auth::user();
        $query = $user->hasRole('user') ? $user->opds() : new Opd();
        $opds = $query->whereHas('tag_otsus', function ($query) use ($jenis) {
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
                });
            }], 'anggaran')
            ->orderBy('kode_opd');
        if ($query->count() > 0) {
            $opds = $opds->whereIn('id', $query->pluck('id'));
        }
        $opds = $opds->get();

        $alokasiKolom = 'alokasi_' . $jenis;
        $alokasi_otsus = DanaAlokasiOtsus::where('tahun', session()->get('tahun'))
            ->first();
        // return $alokasi_otsus;
        $pagu_alokasi = $alokasi_otsus ? $alokasi_otsus->$alokasiKolom : 0;

        if (Auth::user()->hasRole('user')) {
            $pagu_alokasi = 0;
            foreach ($opds as $itemOpd) {
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
        if ($user->hasRole('user')) {
            $dataKlasBel = $dataKlasBel->whereIn('kode_unik_opd', $opds->pluck('kode_unik_opd'));
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
            ])
            ->whereIn('kode_unik_opd', $opds->pluck('kode_unik_opd'));
        if ($opds->count() > 0) {
            $total_input_rap =  $total_input_rap->whereIn('kode_unik_opd', $opds->pluck('kode_unik_opd'));
        }
        $total_input_rap =  $total_input_rap->sum('anggaran');

        // return $total_input_rap;

        return view('v1-1.rap.rap', [
            'app' => [
                'title' => 'RAP',
                'desc' => 'RAP Perangkat Daerah',
            ],
            'jenis' => $jenis,
            'opds' => $opds,
            'dataKlasBel' => $dataKlasBel,
            'pagu_alokasi' =>  $pagu_alokasi,
            'total_input_rap' => $total_input_rap,
            'selisih_input' => $pagu_alokasi - $total_input_rap,
            'sumberdana' => $sumberdana,
        ]);
    }

    public function renja_rap(Request $request, $jenis)
    {
        $user = Auth::user();
        $sumberdana = $jenis == 'bg' ? 'Otsus 1%' : ($jenis == 'sg' ? 'Otsus 1,25%' : 'DTI');
        $query = $user->hasRole('user') // jika role user: batasi ke OPD yang dimiliki user
            ? $user->opds()->where('opds.id', $request->skpd)   // pakai opds.id agar aman saat join pivot
            : Opd::where('id', $request->skpd); // jika bukan user: langsung ke model Opd

        // filter wajib: hanya OPD yang punya tag_otsus sesuai
        $query = $query->whereHas('tag_otsus', function ($q) use ($request) {
            $q->where('alias_dana', $request->jenis)
                ->where('pembahasan', 'setujui')
                ->where('validasi', true);
        })->with([
            'tag_otsus' => fn($q) => $q->where([
                'alias_dana' => $request->jenis,
                'pembahasan' => 'setujui',
                'validasi' => true,
            ]),
            'tag_otsus',
            'tag_otsus.raps' => fn($q) => $q->withTrashed(),
            'tag_otsus.raps.tagging.target_aktifitas',
            'kepala_aktif', // relasi ke kepala OPD
            'tim_pembahas_opd', // relasi ke TimPembahasOpd
            'tim_pembahas', // relasi ke TimPembahas (Khusus Bappeda)
            'pagu', // relasi ke pagu otsus opd
        ]);
        $opd = $query->first();
        if (!$opd) {
            return redirect()->to('/rap/' . $jenis)->with('error', 'Perangkat Daerah tidak ditemukan!');
        }
        // return $jenis;
        // return $opd->pagu;

        $nomen_sikd = NomenklaturSikd::whereIn('kode_bidang', $opd->tag_bidang->pluck('kode_bidang'))
            ->where('sumberdana', $jenis)
            ->get();

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
        ])->groupBy('klasifikasi_belanja')
            ->get(); // Grup berdasarkan klasifikasi belanja

        $lokasi = Lokus::select(
            'id',
            DB::raw('CONCAT(kecamatan, " | ", kampung) as lokasi'),
        )->get();

        $dana_lain = Sumberdana::whereNot('uraian', $sumberdana)->get();

        $tim_pembahas = new TimPembahas;
        $ketua_tim_pembahas = $tim_pembahas::where('role', 'ketua')->first();

        return view('v1-1.rap.rap-opd', [
            'app' => [
                'title' => 'RAP',
                'desc' => 'RAP ' . $opd->text,
            ],
            'opd' => $opd,
            'jenis' => $jenis,
            'sumberdana' => $sumberdana,
            'dataKlasBel' => $dataKlasBel,
            'jumlah_kegiatan' => $jumlah_kegiatan,
            'jumlah_subkegiatan' => $jumlah_subkegiatan,
            'lokasi' => $lokasi,
            'dana_lain' => $dana_lain,
            'nomen_sikd' => $nomen_sikd,
            'tim_pembahas' => $tim_pembahas::all(), // TimPembahasRap diganti TimPembahas
            'ketua_tim_pembahas' => $ketua_tim_pembahas,
        ]);
    }

    public function renja_form_rap(Request $request, $jenis, $id_opd)
    {
        if (!in_array($jenis, ['bg', 'sg', 'dti'])) {
            return redirect()->back()->with('error', 'Jenis RAP tidak ditemukan!');
        }
        $user = Auth::user();
        $query = $user->hasRole('user') ? $user->opds() : new Opd();
        $opd = $query->whereHas('tag_otsus', function ($q) use ($jenis) {
            $q->where('alias_dana', $jenis)
                ->where('pembahasan', 'setujui')
                ->where('validasi', true);
        })->with('pagu');

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
            $opd = $opd->where('id', $id_opd)->first();

            if (!$opd->pagu || $opd->pagu->$jenis <= 0) {
                return redirect()->back()
                    ->with('error', 'Pagu RAP ' . ($jenis == 'bg' ? 'OTSUS 1%' : ($jenis == 'sg' ? 'OTSUS 1,25%' : 'DTI')) . ' belum ditetapkan! Hubungi Administrator')
                    ->withErrors(['anggaran' => 'Pagu RAP belum ditetapkan'])
                    ->withInput($request->all());
            }

            // cek jika rap->pembahasan sama dengan ['setujui', 'tolak'] dan rap->validasi tidak true
            if (in_array($opd->raps->first()->pembahasan, ['setujui', 'tolak']) && !$opd->raps->first()->validasi) {
                return redirect('/rap/' . $jenis . '/renja?skpd=' . $id_opd)->with('error', 'RAP tidak dapat di edit!');
            }
        } else {
            // Jika tidak ada edit, baru panggil find di sini
            $opd = $opd->find($id_opd);
            // return $opd;
            if (auth()->user()->hasRole('user') && (!$opd->pagu || !$opd->pagu->$jenis || $opd->pagu->$jenis <= 0)) {
                return redirect('/rap/' . $jenis . '/renja?skpd=' . $id_opd)->with('error', 'Belum ada batasan pagu! hubungi Administrator!');
            }

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

    /** @var Illuminate\Http\Request */
    public function insert_new_rap(InsertRapRequest $request, $jenis, $id_opd)
    {
        if (!in_array($jenis, ['bg', 'sg', 'dti'])) {
            return redirect()->back()->with('error', 'Jenis RAP tidak ditemukan!');
        }
        $opd = Opd::with([
            'tag_otsus' => fn($q) => $q->where('id', $request->input('opd_tag_otsus')),
            'pagu',
        ])->withSum(['raps as alokasi_rap' => function ($q) use ($jenis) {
            $q->where('rap_otsuses.alias_dana', $jenis);
        }], 'anggaran')->find($id_opd);
        if (!$opd) {
            return redirect()->back()->with('error', 'Perangkat Daerah tidak ditemukan! Hubungi Administrator');
        }
        // return $opd;
        $total_pagu_plus_inputan = $opd->alokasi_rap + $request->input('anggaran');
        if (!$opd->pagu) {
            return redirect()->back()->with('error', 'Pagu RAP ' . ($jenis == 'bg' ? 'OTSUS 1%' : ($jenis == 'sg' ? 'OTSUS 1,25%' : 'DTI')) . ' belum ditetapkan! Hubungi Administrator')
                ->withErrors(['anggaran' => 'Pagu RAP belum ditetapkan'])
                ->withInput($request->all());
        }
        // return "Masih Cukup";
        // return $total_pagu_plus_inputan;
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
        $sumberdana = $jenis == 'bg' ? 'otsus 1%' : ($jenis == 'sg' ? 'otsus 1,25%' : 'dti');
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
        $check_rap = RapOtsus::where([
            'kode_unik_opd' => $opd->kode_unik_opd,
            'kode_unik_opd_tag_bidang' => $opd_tag_bidang->kode_unik_opd_tag_bidang,
            'kode_unik_opd_tag_otsus' => $opd_tag_otsus->kode_unik_opd_tag_otsus,
            'kode_unik_sikd' => $nomen_sikd->kode_unik_subkegiatan,
            'sumberdana' => $jenis == 'bg' ? 'Otsus 1%' : ($jenis == 'sg' ? 'Otsus 1,25%' : 'DTI'),
            'tahun' => session()->get('tahun'),
            'deleted_at' => null,
        ])->first();
        if ($check_rap) {
            return redirect()->back()->with('error', 'Subkegiatan sudah ada! Gunakan fitur edit untuk mengubah data')
                ->withErrors(['id_subkegiatan' => 'Subkegiatan sudah ada'])
                ->withInput($request->all());
        }
        if ($opd->pagu->$jenis < $total_pagu_plus_inputan) {
            // return 'Pagu RAP ' . ($jenis == 'bg' ? 'OTSUS 1%' : ($jenis == 'sg' ? 'OTSUS 1,25%' : 'DTI')) . ' tidak mencukupi! Maksimal Pagu: ' . formatIdr($opd->pagu->$jenis);
            return redirect()->back()->with('error', 'Pagu RAP ' . ($jenis == 'bg' ? 'OTSUS 1%' : ($jenis == 'sg' ? 'OTSUS 1,25%' : 'DTI')) . ' tidak mencukupi! Maksimal Pagu: ' . formatNumber($opd->pagu->$jenis))
                ->withErrors(['anggaran' => 'Anggaran melebihi batas maksimal'])
                ->withInput($request->all());
        }
        $rap = [
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
            'link_file_dukung_lain' => $request->input('link_file_dukung_lain'),
            'tahun' => session()->get('tahun'),
        ];
        // return $rap;
        try {
            DB::beginTransaction();
            $createdRap = RapOtsus::updateOrCreate([
                'kode_unik_opd' => $opd->kode_unik_opd,
                'kode_unik_opd_tag_bidang' => $opd_tag_bidang->kode_unik_opd_tag_bidang,
                'kode_unik_opd_tag_otsus' => $opd_tag_otsus->kode_unik_opd_tag_otsus,
                'kode_unik_sikd' => $nomen_sikd->kode_unik_subkegiatan,
            ], $rap);
            if ($createdRap) {
                $file_path = 'file-rap/uploads/' . session('tahun') . '/skpd/' . $opd->kode_unik_opd . '/' . str_replace('.', '-', $createdRap->kode_subkegiatan) . '-' . $createdRap->id . '/';
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
                        // simpan file baru
                        $file = $request->file($value['name']);
                        $filename = "{$value['fileName']}-rap-{$createdRap->id}-subkeg-{$nomen_sikd->kode_subkegiatan}-" . now()->format('Ymd_His') . ".pdf"; // Ganti dengan nama file yang sesuai
                        // Simpan file ke storage/public/file-rap/upload/{tahun}/skpd/{kode_unik_opd}/
                        Storage::disk('public')->putFileAs(
                            $file_path,
                            $file,
                            $filename
                        );
                        $createdRap->{$value['name']} = $filename;
                        $createdRap->file_path = $file_path;
                    }
                }
            }
            $createdRap->save();
            DB::commit();
            Log::channel('controller')->info('rap.store.success', [
                'opd' => $opd->only(['id', 'kode_opd', 'text']),
                'rap' => $createdRap->only(['id', 'kode_subkegiatan', 'nama_subkegiatan', 'anggaran']),
            ]);
            return redirect()->to("/rap/{$jenis}/renja?skpd={$id_opd}")->with('success', 'RAP Berhasil Disimpan!');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::channel('controller')->error('rap.store.fail', [
                'error' => $th->getMessage(),
                'line'  => $th->getLine(),
                'file'  => $th->getFile(),
            ]);
            // throw $th;
            return redirect()->back()->with('error', 'RAP Gagal Disimpan!');
        }
    }

    public function update_rap(UpdateRapRequest $request, $jenis, $id_opd)
    {
        // return $request->all();
        if (!in_array($jenis, ['bg', 'sg', 'dti'])) {
            return redirect()->back()->with('error', 'Jenis RAP tidak ditemukan!');
        }
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
        $deleteFileNames = [
            [
                'name' => 'delete_file_pendukung1',
                'fileName' => 'file_pendukung1_name'
            ],
            [
                'name' => 'delete_file_pendukung2',
                'fileName' => 'file_pendukung2_name'
            ],
            [
                'name' => 'delete_file_pendukung3',
                'fileName' => 'file_pendukung3_name'
            ],
        ];
        foreach ($deleteFileNames as $key => $value) {
            if ($request->has($value['name'])) {
                // Hapus file dari storage
                if (Storage::disk('public')->exists($rap->file_path . $rap->{$value['fileName']})) {
                    Storage::disk('public')->delete($rap->file_path . $rap->{$value['fileName']});
                }
                // Set field nama file ke null
                $rap->{$value['fileName']} = null;
            }
        }
        $rap->save();
        return redirect()->to("/rap/{$jenis}/renja?skpd={$id_opd}")->with('success', 'RAP Berhasil Diupdate!');
    }

    public function restore_rap(Request $request): RedirectResponse
    {
        if (!$request->has('id') || !$request->id) {
            return redirect()->back()->with('error', 'RAP Belum Dipilih!');
        }
        $raps = RapOtsus::onlyTrashed()->where('id', $request->id)->restore();
        return redirect()->back()->with('success', 'RAP Berhasil Dikembalikan!');
    }

    public function destroy_rap(Request $request): RedirectResponse
    {
        if (!$request->has('id') || !$request->id) {
            return redirect()->back()->with('error', 'RAP Belum Dipilih!');
        }
        $raps = RapOtsus::onlyTrashed()->where('id', $request->id)->forceDelete();
        return redirect()->back()->with('success', 'RAP Berhasil Dihapus Permanen!');
    }

    public function rap_upload_data_dukung(Request $request): RedirectResponse
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

    public function kirim_rap(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'id_rap' => 'required|exists:rap_otsuses,id',
            ],
            [
                'id_rap.required' => 'Terjadi kesalahan ID RAP! Hubungi Administrator!',
                'id_rap.exists' => 'RAP Tidak ditemukan! Hubungi Administrator!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Terjadi kesalahan! data gagal di simpan!')
                ->withErrors($validator);
        }
        $rap = RapOtsus::find($request->id_rap);
        if (!$rap) {
            return redirect()->back()->with('error', 'RAP tidak ditemukan!');
        }
        $rap->kirim = true;
        $rap->save();
        return redirect()->back()->with('success', 'Renja RAP telah dikirim untuk dibahas!');
    }


    public function pembahasan_rap(Request $request): RedirectResponse
    {
        if (!auth()->user()->hasRole('admin')) {
            return redirect()->back()->with('error', 'Hak akses anda terbatas!');
        }
        $validator = Validator::make(
            $request->all(),
            [
                'id_rap' => 'required|exists:rap_otsuses,id',
                'pembahasan' => 'required|in:setujui,perbaiki,tolak',
                'catatan' => 'required'
            ],
            [
                'id_rap.required' => 'Terjadi kesalahan ID RAP! Hubungi Administrator!',
                'id_rap.exists' => 'RAP Tidak ditemukan! Hubungi Administrator!',
                'pembahasan.required' => 'Status pembahasan belum ditentukan!',
                'pembahasan.in' => 'Status pembahasan hanya boleh ["setujui", "perbaiki", "tolak"]!',
                'catatan.required' => 'Cacatan pembahasan wajib di isi!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Terjadi kesalahan! data gagal di simpan!')
                ->withErrors($validator);
        }

        $rap = RapOtsus::find($request->id_rap);
        if (!$rap) {
            return redirect()->back()->with('error', 'RAP tidak ditemukan!');
        }

        if ($rap->validasi) {
            return redirect()->back()->with('error', 'RAP tidak dapat dibahas, karena sudah divalidasi!');
        }

        if (!$rap->kirim) {
            return redirect()->back()->with('error', 'RAP tidak dapat dibahas, karena belum dikirim oleh OPD!');
        }

        $rap->pembahasan = $request->pembahasan;
        $rap->catatan = $request->catatan;
        $rap->save();

        return redirect()->back()->with('success', 'Renja RAP telah dibahas!');
    }

    public function validasi_rap(Request $request): RedirectResponse
    {
        $validator = Validator::make(
            $request->all(),
            [
                'id_rap' => 'required|exists:rap_otsuses,id',
                'validasi' => 'required|in:1,0',
            ],
            [
                'id_rap.required' => 'Terjadi kesalahan ID RAP! Hubungi Administrator!',
                'id_rap.exists' => 'RAP Tidak ditemukan! Hubungi Administrator!',
                'validasi.required' => 'Terjadi kesalahan validasi! Hubungi Administrator!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Terjadi kesalahan! data gagal di simpan!')
                ->withErrors($validator);
        }


        $rap = RapOtsus::find($request->id_rap);
        if (!$rap) {
            return redirect()->back()->with('error', 'RAP tidak ditemukan!');
        }
        if ($rap->pembahasan == 'perbaiki') {
            return redirect()->back()->with('error', 'RAP tidak dapat divalidasi, karena masih dalam status perbaiki!');
        }

        $rap->validasi = $request->validasi;
        $rap->save();

        $message = $rap->validasi == 1 ? 'RAP telah divalidasi!' : 'RAP telah dibatalkan validasinya!';
        $msgKey = $rap->validasi == 1 ? 'success' : 'info';
        return redirect()->back()->with($msgKey, $message);
    }

    public function rap_delete_data_dukung(Request $request): RedirectResponse
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
