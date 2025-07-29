<?php

namespace App\Http\Controllers\Api;

use App\Models\Data\Opd;
use App\Models\Data\Lokus;
use App\Models\Rap\RapOtsus;
use Illuminate\Http\Request;
use App\Models\Data\Sumberdana;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Nomenklatur\NomenklaturSikd;
use App\Models\Tagging\Otsus\OpdTagOtsus;
use Illuminate\Support\Facades\Validator;
use App\Models\Otsus\Data\B5TargetAktifitasUtamaOtsus;
use App\Models\Scopes\TahunScope;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ApiRapController extends Controller
{

    public function create_renja_rap(Request $request, $jenis)
    {
        // return response()->json($request->file('file_kak_name')->getRealPath(), 403);
        if (!$jenis || !in_array($jenis, ['bg', 'sg', 'dti'])) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan!'
            ], 403);
        }
        $tahun = $tahun = $request->input('tahun') ?? $request->input('token_tahun');
        $sumberdana = $jenis === 'bg' ? 'Otsus 1%' : ($jenis === 'sg' ? 'Otsus 1,25%' : 'dti');
        $request->merge([
            'anggaran' => str_replace(',', '.', str_replace('.', '', $request->anggaran)),
            'vol_subkeg' => str_replace(',', '.', str_replace('.', '', $request->vol_subkeg)),
            // 'sumberdana' => $jenis === 'bg' ? 'Otsus 1%' : ($jenis === 'sg' ? 'Otsus 1,25%' : 'dti'),
        ]);
        $validator = Validator::make(
            $request->all(),
            [
                'opd' => 'required|exists:opds,id',
                'opd_tag_otsus' => 'required|exists:opd_tag_otsuses,id',
                'subkegiatan' => 'required|exists:nomenklatur_sikds,id',
                // 'sumberdana' => 'required|exists:sumberdanas,uraian',
                'sumberdana' => 'exists:sumberdanas,uraian',
                'vol_subkeg' => 'required|numeric',
                'anggaran' => 'required|numeric',
                'jenis_kegiatan' => 'required',
                'mulai' => 'required',
                'selesai' => 'required',
                'penerima_manfaat' => 'required',
                'jenis_layanan' => 'required',
                'ppsb' => 'required',
                'multiyears' => 'required',
                'dana_lain' => 'required|array',
                'dana_lain.*' => 'exists:sumberdanas,id',
                'lokus' => 'required|array',
                'lokus.*' => 'exists:lokuses,id',
                'koordinat' => Rule::requiredIf($request->jenis_kegiatan == 'fisik'),
                'keterangan' => 'required',
                'file_kak_name' => 'required|file|mimes:pdf|max:2048',
                'file_rab_name' => 'required|file|mimes:pdf|max:2048',
                'file_pendukung1_name' => 'nullable|file|mimes:pdf|max:2048',
                'file_pendukung2_name' => 'nullable|file|mimes:pdf|max:2048',
                'file_pendukung3_name' => 'nullable|file|mimes:pdf|max:2048',
                'link_file_dukung_lain' => 'nullable|url',
            ],
            [
                'opd.required' => 'Perangkat Daerah tidak boleh kosong!',
                'opd.exists' => 'Perangkat Daerah tidak ditemukan!',
                'opd_tag_otsus.required' => 'Target aktifitas tidak boleh kosong!',
                'opd_tag_otsus.exists' => 'Target aktifitas tidak ditemukan!',
                'subkegiatan.required' => 'Sub Kegiatan tidak boleh kosong!',
                'subkegiatan.exists' => 'Sub Kegiatan tidak ditemukan!',
                // 'sumberdana.required' => 'Sumberdana tidak boleh kosong!',
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
                'dana_lain.*.exists' => 'Sumber Dana Lain tidak ditemukan!',
                'lokus.required' => 'Lokasi Fokus tidak boleh kosong!',
                'lokus.*.exists' => 'Lokasi Fokus tidak ditemukan!',
                'koordinat.required' => 'Kegiatan fisik wajib ada koordinat!',
                'keterangan.required' => 'Keterangan tidak boleh kosong!',
                // File KAK
                'file_kak_name.required' => 'File KAK tidak boleh kosong!',
                'file_kak_name.file' => 'File KAK harus berupa file!',
                'file_kak_name.mimes' => 'File KAK harus berupa file PDF!',
                'file_kak_name.max' => 'File KAK maksimal 2MB!',
                // File RAB
                'file_rab_name.required' => 'File RAB tidak boleh kosong!',
                'file_rab_name.file' => 'File RAB harus berupa file!',
                'file_rab_name.mimes' => 'File RAB harus berupa file PDF!',
                'file_rab_name.max' => 'File RAB maksimal 2MB!',
                // File Pendukung 1
                // 'file_pendukung1_name.required' => 'File Pendukung 1 tidak boleh kosong!',
                'file_pendukung1_name.file' => 'File Pendukung 1 harus berupa file!',
                'file_pendukung1_name.mimes' => 'File Pendukung 1 harus berupa file PDF!',
                'file_pendukung1_name.max' => 'File Pendukung 1 maksimal 2MB!',
                // File Pendukung 2
                // 'file_pendukung2_name.required' => 'File Pendukung 2 tidak boleh kosong!',
                'file_pendukung2_name.file' => 'File Pendukung 2 harus berupa file!',
                'file_pendukung2_name.mimes' => 'File Pendukung 2 harus berupa file PDF!',
                'file_pendukung2_name.max' => 'File Pendukung 2 maksimal 2MB!',
                // File Pendukung 3
                // 'file_pendukung3_name.required' => 'File Pendukung 3 tidak boleh kosong!',
                'file_pendukung3_name.file' => 'File Pendukung 3 harus berupa file!',
                'file_pendukung3_name.mimes' => 'File Pendukung 3 harus berupa file PDF!',
                'file_pendukung3_name.max' => 'File Pendukung 3 maksimal 2MB!',
                // File Pendukung Lainnya
                'link_file_dukung_lain.url' => 'Link file pendukung lain harus berupa URL yang valid!',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan! Data gagal disimpan!',
                'alert' => 'danger',
                'errors' => $validator->errors()
            ], 403);
        }
        $opd = Opd::find($request->opd);
        $tag = OpdTagOtsus::find($request->opd_tag_otsus);
        $nomenSikd = NomenklaturSikd::find($request->subkegiatan);
        $dana_lain = Sumberdana::select(['id', 'uraian'])->whereIn('id', $request->dana_lain)->get();
        $lokus = Lokus::select(['id', 'kecamatan', 'kampung'])->whereIn('id', $request->lokus)->get();
        // return $dana_lain->toJson();

        $data = [
            'kode_unik_opd' => $opd->kode_unik_opd,
            'kode_unik_opd_tag_bidang' => $opd->kode_unik_opd . '-' . $nomenSikd->kode_bidang,
            'kode_unik_opd_tag_otsus' => $tag->kode_unik_opd_tag_otsus,
            'kode_unik_sikd' => $nomenSikd->kode_unik_subkegiatan,
            'kode_opd' => $opd->kode_opd,
            'kode_tema' => $tag->kode_tema,
            'kode_tema' => $tag->kode_tema,
            'kode_program' => $tag->kode_program,
            'kode_keluaran' => $tag->kode_keluaran,
            'kode_aktifitas' => $tag->kode_aktifitas,
            'kode_target_aktifitas' => $tag->kode_target_aktifitas,
            'kode_kegiatan' => $nomenSikd->kode_kegiatan,
            'kode_subkegiatan' => $nomenSikd->kode_subkegiatan,
            'nama_subkegiatan' => $nomenSikd->nama_subkegiatan,
            'indikator_subkegiatan' => $nomenSikd->indikator,
            'satuan_subkegiatan' => $nomenSikd->satuan,
            'klasifikasi_belanja' => $nomenSikd->klasifikasi_belanja,
            'text_subkegiatan' => $nomenSikd->text,
            'sumberdana' => $sumberdana,
            'alias_dana' => $jenis,
            'penerima_manfaat' => $request->penerima_manfaat,
            'jenis_layanan' => $request->jenis_layanan,
            'jenis_kegiatan' => $request->jenis_kegiatan,
            'dana_lain' => $dana_lain->toJson(),
            'lokus' => $lokus->toJson(),
            'vol_subkeg' => $request->vol_subkeg,
            'anggaran' => $request->anggaran,
            'mulai' => $request->mulai,
            'selesai' => $request->selesai,
            'keterangan' => $request->keterangan,
            'ppsb' => $request->ppsb,
            'multiyears' => $request->multiyears,
            'koordinat' => $request->koordinat,
            'keterangan' => $request->keterangan,
            'link_file_dukung_lain' => $request->link_file_dukung_lain,
            'tahun' => $tahun,
        ];

        // return response()->json($data, 200);

        try {
            DB::beginTransaction();
            $creating = RapOtsus::create($data);
            $rap = RapOtsus::find($creating->id);

            $date = now()->format('Ymd_hms');
            // $path = 'file-rap/uploads/' . $tahun . '/skpd/' . $opd->kode_unik_opd . '/';
            $fileAttributes = ['file_kak_name', 'file_rab_name', 'file_pendukung1_name', 'file_pendukung2_name', 'file_pendukung3_name'];

            foreach ($fileAttributes as $attribute) {
                if ($request->hasFile($attribute)) {
                    $file = $request->file($attribute);
                    $uploadName = str_replace(['file_', '_name'], '', $attribute);
                    $filename = "{$uploadName}-rap-{$rap->id}-subkeg-{$rap->kode_subkegiatan}-{$date}." . $file->getClientOriginalExtension();
                    $path = "file-rap/uploads/{$tahun}/skpd/{$opd->kode_unik_opd}/{$filename}";

                    if (Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->delete($path); // hapus file lama
                    }

                    $success = Storage::disk('public')->put($path, file_get_contents($file->getRealPath()));
                    if (!$success) {
                        DB::rollback();
                        return response()->json([
                            'success' => false,
                            'message' => "Gagal upload file: {$attribute}",
                            'alert' => 'danger'
                        ], 500);
                    }

                    $rap->$attribute = $filename;
                }
            }
            $rap->file_path = 'file-rap/uploads/' . $tahun . '/skpd/' . $opd->kode_unik_opd . '/';;
            $rap->save();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan!',
                'alert' => 'success',
                'data' => $rap,
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan! data gagal disimpan!',
                'alert' => 'danger',
                'errorMsg' => $th->getMessage(),
            ]);
        }
    }

    public function rap_opd(Request $request)
    {
        // return response()->json($request->all(), 200);
        $data = null;
        $keys = $request->keys(); // Mendapatkan semua key dari request
        if (count($keys) == 0) {
            $data = RapOtsus::withoutGlobalScope(TahunScope::class)->with('target_aktifitas')->get();
            $message = 'Data found ' . $data->count() . '!';
            if ($data->count() < 1) {
                $message = 'Data Not Found!';
            }
            return response()->json([
                'success' => true,
                'message' => $message,
                'alert' => 'success',
                'data' => $data,
            ]);
        } else {
            $kode = [
                'id',
                'kode_unik_opd',
                'kode_unik_opd_tag_bidang',
                'kode_unik_opd_tag_otsus',
                'kode_opd',
                'kode_tema',
                'kode_program',
                'kode_keluaran',
                'kode_aktifitas',
                'kode_target_aktifitas',
                'tahun',
            ];
            // return $kode;

            $matchingKeys = array_intersect($keys, $kode);
            if ($matchingKeys) {
                $data = RapOtsus::withoutGlobalScope(TahunScope::class);
                foreach ($kode as $attribute) {
                    if ($request->has($attribute)) {
                        $data = $data->withoutGlobalScope(TahunScope::class)->where([$attribute => $request->$attribute]);
                    }
                }
                if ($request->has('with') || $request->with && $request->with == 'opd') {
                    $data = $data->with([
                        'opd' => fn($q) => $q->where('tahun', $request->token_tahun)
                    ]);
                }
                $data = $data->with('target_aktifitas')->get();
            }

            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan! attribute tidak benar!',
                    'alert' => 'danger',
                ]);
            }
            $message = 'Data found ' . $data->count() . '!';
            if ($data->count() < 1) {
                $message = 'Data Not Found!';
            }
            return response()->json([
                'success' => true,
                'message' => $message,
                'alert' => 'success',
                'data' => $data,
            ]);
        }
    }

    public function rap_delete_indikator_opd(Request $request)
    {
        try {
            DB::beginTransaction();
            OpdTagOtsus::find($request->id)->delete();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil di hapus!',
                'alert' => 'success',
            ], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan! data gagal di hapus.',
                'alert' => 'success',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function rap_opd_update(Request $request)
    {
        $request->merge([
            'anggaran' => str_replace(',', '.', str_replace('.', '', $request->anggaran)),
            'vol_subkeg' => str_replace(',', '.', str_replace('.', '', $request->vol_subkeg)),
        ]);
        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'required|exists:rap_otsuses,id',
                'sumberdana' => 'nullable|exists:sumberdanas,uraian|in:Otsus 1%,Otsus 1,25%,DTI',
                'vol_subkeg' => 'required|numeric',
                'anggaran' => 'required|numeric',
                'mulai' => 'required|date',
                'selesai' => 'required|date',
                'penerima_manfaat' => 'required',
                'jenis_layanan' => 'required',
                'jenis_kegiatan' => 'required',
                'ppsb' => 'required',
                'multiyears' => 'required',
                'dana_lain' => 'required|min:1',
                'dana_lain.*' => 'required',
                'lokus' => 'required|min:1',
                'lokus.*' => 'required',
                'koordinat' => 'required_if:jenis_kegiatan,fisik',
                'keterangan' => 'required',
            ],
            [
                'id.required' => 'tidak boleh kosong!',
                'id.exists' => 'rap tidak ditemukan!',
                'sumberdana.exists' => 'sumber dana tidak ditemukan!',
                'sumberdana.in' => 'sumber dana tidak falid!',
                'vol_subkeg.required' => 'tidak boleh kosong!',
                'vol_subkeg.numeric' => 'harus berupa angka!',
                'anggaran.required' => 'tidak boleh kosong!',
                'anggaran.numeric' => 'harus berupa angka!',
                'mulai.required' => 'tidak boleh kosong!',
                'selesai.required' => 'tidak boleh kosong!',
                'penerima_manfaat.required' => 'tidak boleh kosong!',
                'jenis_layanan.required' => 'tidak boleh kosong!',
                'jenis_kegiatan.required' => 'tidak boleh kosong!',
                'ppsb.required' => 'tidak boleh kosong!',
                'multiyears.required' => 'tidak boleh kosong!',
                'dana_lain.required' => 'tidak boleh kosong!',
                'dana_lain.*.required' => 'tidak boleh kosong!',
                'lokus.required' => 'tidak boleh kosong!',
                'lokus.*.required' => 'tidak boleh kosong!',
                'koordinat.required_if' => 'tidak boleh kosong jika jenis kegiatan fisik!',
                'keterangan.required' => 'tidak boleh kosong!',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan! data gagal tersimpan!',
                'alert' => 'danger',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();
            $rap = RapOtsus::withoutGlobalScope(TahunScope::class)->with('tagging')->find($request->id);
            $rap->vol_subkeg = $request->vol_subkeg;
            $rap->anggaran = $request->anggaran;
            $rap->mulai = $request->mulai;
            $rap->selesai = $request->selesai;
            $rap->penerima_manfaat = $request->penerima_manfaat;
            $rap->jenis_layanan = $request->jenis_layanan;
            $rap->jenis_kegiatan = $request->jenis_kegiatan;
            $rap->ppsb = $request->ppsb;
            if ($request->has('sumberdana') || $request->sumberdana) {
                $rap->sumberdana = $request->sumberdana;
            }
            $rap->multiyears = $request->multiyears;
            $rap->dana_lain = Sumberdana::whereIn('id', $request->dana_lain)->select('id', 'uraian')->get()->toJson();
            $rap->lokus = Lokus::whereIn('id', $request->lokus)->select('id', 'kecamatan', 'kampung')->get()->toJson();
            $rap->koordinat = $request->koordinat;
            $rap->keterangan = $request->keterangan;
            $rap->catatan = $request->catatan;

            $rap->save();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil tersimpan!',
                'alert' => 'success',
                'data' => $rap,
            ], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan! data gagal tersimpan!',
                'alert' => 'success',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function rap_opd_delete(Request $request)
    {
        if (!$request->has('id') || !$request->id) {
            return response()->json([
                'success' => true,
                'message' => 'Terjadi kesalahan!',
                'alert' => 'danger',
            ], 500);
        }

        try {
            DB::beginTransaction();
            $rap = RapOtsus::withoutGlobalScope(TahunScope::class)->find($request->id);
            $rap->delete();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil di hapus!',
                'alert' => 'success',
            ], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'success' => true,
                'message' => 'Terjadi kesalahan!',
                'alert' => 'danger',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function rap_file_check(Request $request)
    {
        if (!$request->has('id_rap')) {
            return response()->json([
                'status' => false,
                'message' => 'Some parameter\'s is required!',
            ], 422);
        }
        $rap = RapOtsus::find($request->id_rap);

        if ($rap->exists()) {
            $data = [
                'kak' => [
                    'exists' => false,
                    'filename' => 'kak',
                    'file' => $rap->file_path . $rap->file_kak_name,
                    'name' => $rap->file_kak_name,
                ],
                'rab' => [
                    'exists' => false,
                    'filename' => 'rab',
                    'file' => $rap->file_path . $rap->file_rab_name,
                    'name' => $rap->file_rab_name,
                ],
                'pendukung1' => [
                    'exists' => false,
                    'filename' => 'pendukung1',
                    'file' => $rap->file_path . $rap->file_pendukung1_name,
                    'name' => $rap->file_pendukung1_name,
                ],
                'pendukung2' => [
                    'exists' => false,
                    'filename' => 'pendukung2',
                    'file' => $rap->file_path . $rap->file_pendukung2_name,
                    'name' => $rap->file_pendukung2_name,
                ],
                'pendukung3' => [
                    'exists' => false,
                    'filename' => 'pendukung3',
                    'file' => $rap->file_path . $rap->file_pendukung3_name,
                    'name' => $rap->file_pendukung3_name,
                ],
            ];
            if (!empty($rap->file_kak_name) && is_file(Storage::disk('public')->path($rap->file_path . $rap->file_kak_name))) {
                $data['kak']['exists'] = true;
            }
            if (!empty($rap->file_rab_name) && is_file(Storage::disk('public')->path($rap->file_path . $rap->file_rab_name))) {
                $data['rab']['exists'] = true;
            }
            if (!empty($rap->file_pendukung1_name) && is_file(Storage::disk('public')->path($rap->file_path . $rap->file_pendukung1_name))) {
                $data['pendukung1']['exists'] = true;
            }
            if (!empty($rap->file_pendukung2_name) && is_file(Storage::disk('public')->path($rap->file_path . $rap->file_pendukung2_name))) {
                $data['pendukung2']['exists'] = true;
            }
            if (!empty($rap->file_pendukung3_name) && is_file(Storage::disk('public')->path($rap->file_path . $rap->file_pendukung3_name))) {
                $data['pendukung3']['exists'] = true;
            }
            return response()->json([
                'status' => true,
                'data' => array_values($data),
            ], 200);
        }
        return response()->json([
            'status' => false,
            'message' => 'Data RAP Not found!'
        ], 200);
    }

    public function rap_by_target_aktifitas(Request $request)
    {
        if (!$request->has('id_target_aktifitas') || !$request->id_target_aktifitas) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan parameter!',
                'alert' => 'danger',
            ], 422);
        }

        $opd_tag_otsus = OpdTagOtsus::find($request->id_target_aktifitas);
        if (!$opd_tag_otsus) {
            return response()->json([
                'success' => false,
                'message' => 'Target Aktifitas tidak ditemukan!',
                'alert' => 'danger',
            ], 404);
        }
        $raps = $opd_tag_otsus->raps()->withoutGlobalScope(TahunScope::class)->get();
        return response()->json([
            'success' => $raps->count() ? true : false,
            'message' => $raps->count() ? 'Data found ' . $raps->count() . '!' : 'Data Not Found!',
            'alert' => $raps->count() ? 'success' : 'danger',
            'data' => $raps,
        ], 200);
    }
}
