<?php

namespace App\Http\Controllers\Api;

use App\Models\Data\Opd;
use App\Models\Data\Lokus;
use App\Models\Rap\RapOtsus;
use Illuminate\Http\Request;
use App\Models\Data\Sumberdana;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Tagging\Otsus\OpdTagOtsus;
use Illuminate\Support\Facades\Validator;
use App\Models\Otsus\Data\B5TargetAktifitasUtamaOtsus;
use App\Models\Scopes\TahunScope;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ApiRapController extends Controller
{
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
                        'opd' => fn($q) => $q->where('tahun', $request->tahun)
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
        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'exists:rap_otsuses,id',
                'sumberdana' => 'required|exists:sumberdanas,uraian',
                'vol_subkeg' => 'required',
                'anggaran' => 'required',
                'mulai' => 'required',
                'selesai' => 'required',
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
            ],
            [
                'sumberdana.required' => 'tidak boleh kosong!',
                'vol_subkeg.required' => 'tidak boleh kosong!',
                'anggaran.required' => 'tidak boleh kosong!',
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
            $rap->sumberdana = $request->sumberdana;
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
}
