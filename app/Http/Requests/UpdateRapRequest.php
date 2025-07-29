<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateRapRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        // return [
        //     'anggaran' => 'required|numeric',
        // ];
        return [
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
            'file_kak_name' => 'sometimes|file|mimes:pdf|max:5120',
            'file_rab_name' => 'sometimes|file|mimes:pdf|max:5120',
            'file_pendukung1_name' => 'sometimes|file|mimes:pdf|max:5120',
            'file_pendukung2_name' => 'sometimes|file|mimes:pdf|max:5120',
            'file_pendukung3_name' => 'sometimes|file|mimes:pdf|max:5120',
            'link_file_dukung_lain' => 'nullable|url',
        ];
    }

    public function messages()
    {
        return [
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
            'koordinat.required_if' => 'Koordinat diperlukan untuk jenis kegiatan fisik.',
            'dana_lain.required' => 'Dana lain wajib diisi.',
            'keterangan.required' => 'Keterangan wajib diisi.',
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
                ->with('error', 'Gagal memperbarui RAP!')
                ->withErrors($validator)
                ->withInput()
        );
    }
}
