<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreRapRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $new_key = 'new_' . session('tahun');
        $update_key = 'update_' . session('tahun');

        // Ambil input key_form
        $key_form = $this->input('key_form');

        // Cek apakah ini insert atau update
        $isInsert = $key_form === hash('sha256', $new_key);
        $isUpdate = $key_form === hash('sha256', $update_key);

        // Rules umum untuk insert dan update (shared)
        $commonRules = [
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
        ];

        if ($isInsert) {
            return array_merge($commonRules, [
                'id_opd' => 'required|exists:opds,id',
                'jenis' => 'required|in:bg,sg,dti',
                'opd_tag_otsus' => 'required|exists:opd_tag_otsuses,id',
                'id_subkegiatan' => 'required|exists:nomenklatur_sikds,id',
                'tahun' => 'required|integer|min:2022|max:' . (date('Y') + 1),
            ]);
        }

        return $commonRules;
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'id_opd.required' => 'Perangkat Daerah wajib dipilih.',
            'jenis.required' => 'Jenis sumber dana wajib dipilih.',
            'opd_tag_otsus.required' => 'Tag Otsus wajib dipilih.',
            'id_subkegiatan.required' => 'Subkegiatan wajib dipilih.',
            'vol_subkeg.required' => 'Volume subkegiatan wajib diisi.',
            'anggaran.required' => 'Anggaran wajib diisi.',
            'penerima_manfaat.required' => 'Penerima manfaat wajib dipilih.',
            'jenis_layanan.required' => 'Jenis layanan wajib dipilih.',
            'ppsb.required' => 'PPSB wajib dipilih.',
            'multiyears.required' => 'Status multiyears wajib dipilih.',
            'mulai.required' => 'Tanggal mulai wajib diisi.',
            'selesai.required' => 'Tanggal selesai wajib diisi.',
            'selesai.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
            'jenis_kegiatan.required' => 'Jenis kegiatan wajib dipilih.',
            'jenis_kegiatan.in' => 'Jenis kegiatan harus berupa fisik atau nonfisik.',
            'koordinat.required' => 'Koordinat wajib diisi jika jenis kegiatan adalah fisik.',
            'koordinat.required_if' => 'Koordinat harus diisi jika jenis kegiatan adalah fisik.',
            'lokus.required' => 'Lokus wajib dipilih.',
            'dana_lain.required' => 'Dana lain wajib dipilih.',
            'keterangan.required' => 'Keterangan wajib diisi.',
            'file_kak_name.required' => 'File KAK wajib diunggah.',
            'file_kak_name.mimes' => 'File KAK harus berupa file PDF.',
            'file_kak_name.max' => 'File KAK maksimal 5 MB.',
            'file_rab_name.required' => 'File RAB wajib diunggah.',
            'file_rab_name.mimes' => 'File RAB harus berupa file PDF.',
            'file_rab_name.max' => 'File RAB maksimal 5 MB.',
            'file_pendukung1_name.required' => 'File pendukung 1 wajib diunggah.',
            'file_pendukung1_name.mimes' => 'File pendukung 1 harus berupa file PDF.',
            'file_pendukung1_name.max' => 'File pendukung 1 maksimal 5 MB.',
            'file_pendukung2_name.mimes' => 'File pendukung 2 harus berupa file PDF.',
            'file_pendukung2_name.max' => 'File pendukung 2 maksimal 5 MB.',
            'file_pendukung3_name.mimes' => 'File pendukung 3 harus berupa file PDF.',
            'file_pendukung3_name.max' => 'File pendukung 3 maksimal 5 MB.',
            'link_file_dukung_lain.url' => 'Link file pendukung lain harus berupa URL yang valid.',
            'tahun.integer' => 'Tahun harus berupa angka.',
            'tahun.min' => 'Tahun minimal 2022.',
            'tahun.max' => 'Tahun maksimal adalah tahun ini ditambah satu.',
            'tahun.required' => 'Tahun wajib diisi.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // You can manipulate the request data before validation here
        // For example, you might want to trim strings or convert types
        $this->merge([
            'vol_subkeg' => $this->input('vol_subkeg') ? str_replace(',', '.', str_replace('.', '', $this->input('vol_subkeg'))) : null,
            'anggaran' => $this->input('anggaran') ? str_replace(',', '.', str_replace('.', '', $this->input('anggaran'))) : null,
        ]);
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            redirect()->back()
                ->with('error', 'Terjadi kesalahan!')
                ->withErrors($validator)
                ->withInput()
        );
    }
}
