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
use App\Imports\OpdTagOtsusImport;
use App\Imports\Rap\RapSubkegiatanImport;
use App\Models\Otsus\DanaAlokasiOtsus;
use App\Models\Otsus\Data\B1TemaOtsus;
use App\Models\Nomenklatur\A5Subkegiatan;
use App\Models\Tagging\Otsus\OpdTagOtsus;
use Illuminate\Support\Facades\Validator;
use App\Models\Otsus\Data\B5TargetAktifitasUtamaOtsus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class RapOtsusController extends Controller
{
    public function rap()
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
                'alokasi' => $tkdd->alokasi_bg,
                'pagu' => $rap_bg,
                'selisih' => $tkdd->alokasi_bg - $rap_bg,
            ],
            'Otsus 1,25%' => [
                'nama' => 'SG 1%',
                'alokasi' => $tkdd->alokasi_sg,
                'pagu' => $rap_sg,
                'selisih' => $tkdd->alokasi_sg - $rap_sg,
            ],
            'DTI' => [
                'nama' => 'DTI',
                'alokasi' => $tkdd->alokasi_dti,
                'pagu' => $rap_dti,
                'selisih' => $tkdd->alokasi_dti - $rap_dti,
            ],
            'unknow' => [
                'nama' => 'UNKNOW',
                'alokasi' => 0,
                'pagu' => $rap_unknow,
                'selisih' => 0 - $rap_unknow,
            ],
        ];

        // return $alokasi_otsus;

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
            // dump($dataKlasBel);
        }

        // return $dataKlasBel;
        // return array_values($alokasi_otsus);

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

    public function rap_opd(Request $request)
    {
        if (!$request->has('id')) {
            return redirect('/rap')->with('error', 'Terjadi kesalahan!');
        }
        $opd = Opd::with([
            'raps.target_aktifitas',
            'raps' => fn($q) => $q->where('rap_otsuses.tahun', session()->get('tahun'))->orderBy('kode_subkegiatan')
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
                'jenis_kegiatan' => 'required',
                'mulai' => 'required',
                'selesai' => 'required',
                'penerima_manfaat' => 'required',
                'jenis_layanan' => 'required',
                'ppsb' => 'required',
                'multiyears' => 'required',
                'dana_lain' => 'required',
                'lokus' => 'required',
                'koordinat' => Rule::requiredIf($request->jenis_kegiatan == 'fisik'),
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


        $data = [
            'kode_unik_opd' => $opd->kode_unik_opd,
            'kode_unik_opd_tag_bidang' => $opd->kode_unik_opd . '-' . $subkegiatan->kode_bidang,
            'kode_unik_opd_tag_otsus' => $opd_tag_otsus->kode_unik_opd_tag_otsus,
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
            'klasifikasi_belanja' => $subkegiatan->klasifikasi_belanja,
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

    public function download_file(Request $request)
    {
        $path = $request->get('path');
        $name = $request->get('name');

        if (!empty($name) && is_file(Storage::disk('public')->path($path . $name))) {
            $file = Storage::disk('public')->path($path . $name);
            return response()->file($file);
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
