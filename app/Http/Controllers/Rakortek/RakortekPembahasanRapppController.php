<?php

namespace App\Http\Controllers\Rakortek;

use App\Http\Controllers\Controller;
use App\Models\Data\Opd;
use App\Models\Tagging\Otsus\OpdTagOtsus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RakortekPembahasanRapppController extends Controller
{
    public function pembahasan_rappp()
    {
        $opds = auth()->user()->hasRole('user')
            ? auth()->user()->opds()
            : new Opd;
        $data = $opds->whereHas('tag_otsus')->orderBy('kode_opd')->get();
        return view('v1-1.rakortek.pembahasan.rappp.rakortek-pembahasan-rappp', [
            'app' => [
                'title' => 'Rakortek RAPPP',
                'desc' => 'Pembahasan Rencana Anggaran & Program (RAPPP)',
            ],
            'data' => $data,
            'admin' => auth()->user()->hasRole('admin') ? true : false,
        ]);
    }

    public function pembahasan_rappp_opd(Request $request)
    {
        $id_opd = $request->input('id');
        $relations = [
            'tag_otsus',
            'tag_otsus.tema',
            'tag_otsus.program',
            'tag_otsus.keluaran',
            'tag_otsus.aktifitas',
            'tag_otsus.target_aktifitas',
        ];
        $opd = auth()->user()->hasRole('user')
            ? auth()->user()->opds()->whereHas('tag_otsus')
            ->with($relations)
            ->find($id_opd)
            : Opd::whereHas('tag_otsus')
            ->with($relations)
            ->find($id_opd);

        if (!$opd) {
            return redirect()->to('/pembahasan/rakortek/rappp')
                ->with('error', 'Perangkat Daerah tidak ditemukan atau tidak memiliki data RAPPP.');
        }
        $data = [];
        foreach ($opd->tag_otsus as $tag) {
            if (!isset($data[$tag->kode_tema])) {
                $data[$tag->kode_tema] = [
                    'id' => $tag->id,
                    'kode_tema' => $tag->kode_tema,
                    'uraian_tema' => $tag->tema->uraian,
                    'programs' => [],
                ];
            }
            if (!isset($data[$tag->kode_tema]['programs'][$tag->kode_program])) {
                $data[$tag->kode_tema]['programs'][$tag->kode_program] = [
                    'id' => $tag->id,
                    'kode_program' => $tag->kode_program,
                    'uraian_program' => $tag->program->uraian,
                    'keluarans' => [],
                ];
            }
            if (!isset($data[$tag->kode_tema]['programs'][$tag->kode_program]['keluarans'][$tag->kode_keluaran])) {
                $data[$tag->kode_tema]['programs'][$tag->kode_program]['keluarans'][$tag->kode_keluaran] = [
                    'id' => $tag->id,
                    'kode_keluaran' => $tag->kode_keluaran,
                    'uraian_keluaran' => $tag->keluaran->uraian,
                    'aktifitas' => [],
                ];
            }
            if (!isset($data[$tag->kode_tema]['programs'][$tag->kode_program]['keluarans'][$tag->kode_keluaran]['aktifitas'][$tag->kode_aktifitas])) {
                $data[$tag->kode_tema]['programs'][$tag->kode_program]['keluarans'][$tag->kode_keluaran]['aktifitas'][$tag->kode_aktifitas] = [
                    'id' => $tag->id,
                    'kode_aktifitas' => $tag->kode_aktifitas,
                    'uraian_aktifitas' => $tag->aktifitas->uraian,
                    'target_aktifitas' => [],
                ];
            }
            if (!isset($data[$tag->kode_tema]['programs'][$tag->kode_program]['keluarans'][$tag->kode_keluaran]['aktifitas'][$tag->kode_aktifitas]['target_aktifitas'][$tag->kode_target_aktifitas])) {
                $data[$tag->kode_tema]['programs'][$tag->kode_program]['keluarans'][$tag->kode_keluaran]['aktifitas'][$tag->kode_aktifitas]['target_aktifitas'][$tag->kode_target_aktifitas] = [
                    'id' => $tag->id,
                    'kode_target_aktifitas' => $tag->kode_target_aktifitas,
                    'uraian_target_aktifitas' => $tag->target_aktifitas->uraian,
                    'rappp' => [
                        'id' => $tag->id,
                        'kode_unik_opd' => $tag->kode_unik_opd,
                        'kode_unik_opd_tag_otsus' => $tag->kode_unik_opd_tag_otsus,
                        'volume' => $tag->volume,
                        'satuan' => $tag->satuan,
                        'sumberdana' => $tag->sumberdana,
                        'alias_dana' => $tag->alias_dana,
                        'pembahasan' => $tag->pembahasan,
                        'catatan' => $tag->catatan,
                        'validasi' => $tag->validasi,
                        'tahun' => $tag->tahun,
                    ]
                ];
            }
        }

        // Convert all nested associative arrays in $data to indexed arrays
        foreach ($data as &$tema) {
            $tema['programs'] = array_values(array_map(function ($program) {
                $program['keluarans'] = array_values(array_map(function ($keluaran) {
                    $keluaran['aktifitas'] = array_values(array_map(function ($aktifitas) {
                        $aktifitas['target_aktifitas'] = array_values($aktifitas['target_aktifitas']);
                        return $aktifitas;
                    }, $keluaran['aktifitas']));
                    return $keluaran;
                }, $program['keluarans']));
                return $program;
            }, $tema['programs']));
        }

        $data = array_values($data);
        // return $data;
        return view('v1-1.rakortek.pembahasan.rappp.rakortek-pembahasan-rappp-opd', [
            'app' => [
                'title' => 'Pembahasan RAPPP',
                'desc' => 'Pembahasan Rencana Anggaran & Program (RAPPP) untuk OPD',
            ],
            'data' => $data,
            'opd' => $opd,
            'admin' => auth()->user()->hasRole('admin') ? true : false,
        ]);
    }

    public function save_pembahasan_rappp_opd(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'opd_tag_otsus_id' => 'required|integer|exists:opd_tag_otsuses,id',
                'pembahasan' => 'required|in:setujui,perbaikan,tolak',
                'catatan' => 'nullable|string',
            ],
            [
                'opd_tag_otsus_id.required' => 'ID OPD Tag Otsus harus diisi.',
                'opd_tag_otsus_id.integer' => 'ID OPD Tag Otsus harus berupa angka.',
                'opd_tag_otsus_id.exists' => 'ID OPD Tag Otsus tidak ditemukan.',
                'pembahasan.required' => 'Status pembahasan harus diisi.',
                'pembahasan.in' => 'Status pembahasan harus salah satu dari: setujui, perbaikan, tolak.',
                'catatan.string' => 'Catatan harus berupa teks.',
            ],
        );
        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Gagal menyimpan pembahasan RAPPP.')
                ->withErrors($validator);
        }
        $tagOtsus = OpdTagOtsus::find($request->input('opd_tag_otsus_id'));
        if (!$tagOtsus) {
            return redirect()->back()
                ->with('error', 'RAPPP Progam OPD Otsus tidak ditemukan.');
        }
        $tagOtsus->pembahasan = $request->input('pembahasan');
        $tagOtsus->catatan = $request->input('catatan');
        $tagOtsus->save();
        return redirect()->back()->with('success', 'Pembahasan RAPPP berhasil disimpan.');
    }

    public function validasi_pembahasan_rappp_opd(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'opd_tag_otsus_id' => 'required|integer|exists:opd_tag_otsuses,id',
            ],
            [
                'opd_tag_otsus_id.required' => 'ID OPD Tag Otsus harus diisi.',
                'opd_tag_otsus_id.integer' => 'ID OPD Tag Otsus harus berupa angka.',
                'opd_tag_otsus_id.exists' => 'ID OPD Tag Otsus tidak ditemukan.',
            ],
        );
        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Gagal memvalidasi pembahasan RAPPP.')
                ->withErrors($validator);
        }
        $tagOtsus = OpdTagOtsus::find($request->input('opd_tag_otsus_id'));
        if (!$tagOtsus) {
            return redirect()->back()
                ->with('error', 'RAPPP Progam OPD Otsus tidak ditemukan.');
        }
        if ($tagOtsus->pembahasan == 'perbaikan') {
            return redirect()->back()
                ->with('error', 'RAPPP Progam OPD Otsus tidak dapat divalidasi karena masih dalam status perbaikan.');
        }
        $tagOtsus->validasi = $tagOtsus->validasi ? false : true;
        $tagOtsus->save();
        $rtn_msg = $tagOtsus->validasi
            ? 'Pembahasan RAPPP berhasil divalidasi.'
            : 'Pembahasan RAPPP berhasil dibatalkan validasinya.';
        return redirect()->back()->with('success', $rtn_msg);
    }

    public function save_all_pembahasan_rappp_opd(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'opd_id' => 'required|integer|exists:opds,id',
                'opd_tag_otsus_id' => 'required|array',
                'opd_tag_otsus_id.*' => 'integer|exists:opd_tag_otsuses,id',
                'pembahasan' => 'required|in:setujui,perbaikan,tolak',
                'catatan' => 'nullable|string',
            ],
            [
                'opd_id.required' => 'Perangkat Daerah harus diisi.',
                'opd_id.integer' => 'Perangkat Daerah harus berupa angka.',
                'opd_id.exists' => 'Perangkat Daerah tidak ditemukan.',
                'opd_tag_otsus_id.required' => 'ID OPD Tag Otsus harus diisi.',
                'opd_tag_otsus_id.array' => 'ID OPD Tag Otsus harus berupa array.',
                'opd_tag_otsus_id.*.integer' => 'ID OPD Tag Otsus harus berupa angka.',
                'opd_tag_otsus_id.*.exists' => 'ID OPD Tag Otsus tidak ditemukan.',
                'pembahasan.required' => 'Status pembahasan harus diisi.',
                'pembahasan.in' => 'Status pembahasan harus salah satu dari: setujui, perbaikan, tolak.',
                'catatan.string' => 'Catatan harus berupa teks.',
            ],
        );
        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Gagal menyimpan pembahasan RAPPP.')
                ->withErrors($validator);
        }
        $opd = Opd::find($request->input('opd_id'));
        if (!$opd) {
            return redirect()->back()
                ->with('error', 'Perangkat Daerah tidak ditemukan.');
        }
        $opdTagOtsuses = OpdTagOtsus::whereIn('id', $request->input('opd_tag_otsus_id'))->get();
        if ($opdTagOtsuses->isEmpty()) {
            return redirect()->back()
                ->with('error', 'RAPPP Progam OPD Otsus tidak ditemukan.');
        }
        if (!in_array($opd->kode_unik_opd, $opdTagOtsuses->pluck('kode_unik_opd')->toArray())) {
            return redirect()->back()
                ->with('error', 'RAPPP Progam OPD Otsus tidak ditemukan untuk Perangkat Daerah ini.');
        }
        foreach ($opdTagOtsuses as $tagOtsus) {
            $tagOtsus->pembahasan = $request->input('pembahasan');
            $tagOtsus->catatan = $request->input('catatan');
            $tagOtsus->save();
        }
        return redirect()->back()->with('success', 'Pembahasan RAPPP berhasil disimpan untuk semua.');
    }

    public function validasi_all_pembahasan_rappp_opd(Request $request)
    {
        // return $request->all();
        $validator = Validator::make(
            $request->all(),
            [
                'opd_id' => 'required|integer|exists:opds,id',
                'opd_tag_otsus_id' => 'required|array',
                'opd_tag_otsus_id.*' => 'integer|exists:opd_tag_otsuses,id',
            ],
            [
                'opd_id.required' => 'Perangkat Daerah harus diisi.',
                'opd_id.integer' => 'Perangkat Daerah harus berupa angka.',
                'opd_id.exists' => 'Perangkat Daerah tidak ditemukan.',
                'opd_tag_otsus_id.required' => 'ID OPD Tag Otsus harus diisi.',
                'opd_tag_otsus_id.array' => 'ID OPD Tag Otsus harus berupa array.',
                'opd_tag_otsus_id.*.integer' => 'ID OPD Tag Otsus harus berupa angka.',
                'opd_tag_otsus_id.*.exists' => 'ID OPD Tag Otsus tidak ditemukan.',
            ],
        );
        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Gagal memvalidasi pembahasan RAPPP.')
                ->withErrors($validator);
        }
        $opd = Opd::find($request->input('opd_id'));
        if (!$opd) {
            return redirect()->back()
                ->with('error', 'Perangkat Daerah tidak ditemukan.');
        }
        $opdTagOtsuses = OpdTagOtsus::whereIn('id', $request->input('opd_tag_otsus_id'))->get();
        if ($opdTagOtsuses->isEmpty()) {
            return redirect()->back()
                ->with('error', 'RAPPP Progam OPD Otsus tidak ditemukan.');
        }
        if (!in_array($opd->kode_unik_opd, $opdTagOtsuses->pluck('kode_unik_opd')->toArray())) {
            return redirect()->back()
                ->with('error', 'RAPPP Progam OPD Otsus tidak ditemukan untuk Perangkat Daerah ini.');
        }
        foreach ($opdTagOtsuses as $tagOtsus) {
            if ($tagOtsus->pembahasan == 'perbaikan') {
                return redirect()->back()
                    ->with('error', 'RAPPP Progam OPD Otsus tidak dapat divalidasi karena masih dalam status perbaikan.');
            }
            $tagOtsus->validasi = $tagOtsus->validasi ? false : true;
            $tagOtsus->save();
        }
        return redirect()->back()->with('success', 'Pembahasan RAPPP berhasil divalidasi untuk semua.');
    }
}
