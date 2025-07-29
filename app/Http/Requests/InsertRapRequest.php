<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class InsertRapRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id_opd' => 'required|exists:opds,id',
            'jenis' => 'required|in:bg,sg,dti',
            'opd_tag_otsus' => 'required|exists:opd_tag_otsuses,id',
            'id_subkegiatan' => 'required|exists:nomenklatur_sikds,id',
            'vol_subkeg' => 'required|numeric',
            'anggaran' => 'required|numeric',
            'penerima_manfaat' => 'required|in:oap,umum',
            'jenis_layanan' => 'required|in:terkait,pendukung',
            'ppsb' => 'required|in:ya,tidak',
            'multiyears' => 'required|in:ya,tidak',
            'mulai' => 'required|date_format:Y-m-d',
            'selesai' => 'required|date_format:Y-m-d|after_or_equal:mulai',
            'jenis_kegiatan' => 'required|in:fisik,nonfisik',
            'lokus' => 'required|array',
            'lokus.*' => 'exists:lokuses,id',
            'koordinat' => Rule::requiredIf($this->jenis_kegiatan == 'fisik'),
            'dana_lain' => 'required|array',
            'dana_lain.*' => 'exists:sumberdanas,id',
            'keterangan' => 'required|string',
            'file_kak_name' => 'required|file|mimes:pdf|max:5120',
            'file_rab_name' => 'required|file|mimes:pdf|max:5120',
            'file_pendukung1_name' => 'nullable|file|mimes:pdf|max:5120',
            'file_pendukung2_name' => 'nullable|file|mimes:pdf|max:5120',
            'file_pendukung3_name' => 'nullable|file|mimes:pdf|max:5120',
            'link_file_dukung_lain' => 'nullable|url',
            'tahun' => 'required|integer|min:2022|max:' . (date('Y') + 1),
        ];
    }

    public function messages()
    {
        return [
            'id_opd.required' => 'ID OPD wajib diisi.',
            'jenis.required' => 'Jenis wajib dipilih.',
            'opd_tag_otsus.required' => 'Target Aktifitas Utama wajib dipilih.',
            'id_subkegiatan.required' => 'Sub Kegiatan wajib dipilih.',
            'vol_subkeg.required' => 'Volume sub kegiatan wajib diisi.',
            'anggaran.required' => 'Anggaran wajib diisi.',
            'penerima_manfaat.required' => 'Penerima manfaat wajib dipilih.',
            'jenis_layanan.required' => 'Jenis layanan wajib dipilih.',
            'ppsb.required' => 'PPSB wajib dipilih.',
            'multiyears.required' => 'Multiyears wajib dipilih.',
            'mulai.required' => 'Tanggal mulai wajib diisi.',
            'selesai.required' => 'Tanggal selesai wajib diisi.',
            'jenis_kegiatan.required' => 'Jenis kegiatan wajib dipilih.',
            'lokus.required' => 'Lokus wajib dipilih.',
            'koordinat.required' => 'Koordinat diperlukan untuk jenis kegiatan fisik.',
            'dana_lain.required' => 'Sumber dana lain wajib diisi.',
            'keterangan.required' => 'Keterangan wajib diisi.',
            // File validation messages
            'file_kak_name.required' => 'File KAK wajib diunggah.',
            'file_rab_name.required' => 'File RAB wajib diunggah.',
        ];
    }

    protected function prepareForValidation(): void
    {
        // You can manipulate the request data before validation here
        // For example, you might want to trim strings or convert types
        $this->merge([
            'vol_subkeg' => $this->input('vol_subkeg') ? str_replace(',', '.', str_replace('.', '', $this->input('vol_subkeg'))) : null,
            'anggaran' => $this->input('anggaran') ? str_replace(',', '.', str_replace('.', '', $this->input('anggaran'))) : null,
        ]);
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            redirect()->back()
                ->with('error', 'Gagal menambahkan RAP!')
                ->withErrors($validator)
                ->withInput()
        );
    }
}
