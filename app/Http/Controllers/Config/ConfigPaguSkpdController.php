<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use App\Models\Data\Opd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConfigPaguSkpdController extends Controller
{
    public function config_pagu(Request $request)
    {
        $data = Opd::with('pagu')->orderBy('kode_opd', 'asc')->get();
        return view('v1-1.config.pagu.config-pagu-skpd', [
            'app' => [
                'title' => 'Pengaturan Pagu SKPD',
                'desc' => 'Pengaturan Batasan Pagu SKPD',
            ],
            'data' => $data,
        ]);
    }

    public function save_config_pagu(Request $request)
    {
        $request->merge(
            [
                'bg' => $request->bg ? str_replace(',', '.', str_replace('.', '', $request->bg)) : null,
                'sg' => $request->sg ? str_replace(',', '.', str_replace('.', '', $request->sg)) : null,
                'dti' => $request->dti ? str_replace(',', '.', str_replace('.', '', $request->dti)) : null,
            ]
        );

        $validator = Validator::make(
            $request->all(),
            [
                'id_opd' => 'required|exists:opds,id',
                'bg' => 'nullable|numeric',
                'sg' => 'nullable|numeric',
                'dti' => 'nullable|numeric',
            ],
            [
                'id_opd.required' => 'Perangat Dearah tidak ditemukan.',
                'id_opd.exists' => 'Perangat Dearah tidak ditemukan.',
                'bg.numeric' => 'Nilai Pagu Otsus 1% yang bersifat umum harus berupa angka.',
                'sg.numeric' => 'Nilai Pagu Otsus 1,25% yang bersifat khusus harus berupa angka.',
                'dti.numeric' => 'Nilai DTI harus berupa angka.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        $opd = Opd::find($request->id_opd);
        if (!$opd) {
            return redirect()->back()
                ->withErrors(['id_opd' => 'Perangkat Daerah tidak ditemukan.']);
        }

        $data = [
            'kode_unik_opd' => $opd->kode_unik_opd,
            'sg' => $request->sg ?? null,
            'bg' => $request->bg ?? null,
            'dti' => $request->dti ?? null,
            'tahun' => session('tahun'),
        ];
        // if (!$data['sg'] && !$data['bg'] && !$data['dti']) {
        //     return redirect()->back()->with('error', 'Minimal salah satu nilai Pagu harus diisi.');
        // }
        $opd->pagu()->updateOrCreate(
            ['kode_unik_opd' => $data['kode_unik_opd'], 'tahun' => $data['tahun']],
            $data
        );
        return redirect()->back()->with('success', 'Pengaturan Pagu SKPD berhasil disimpan.');
    }
}
