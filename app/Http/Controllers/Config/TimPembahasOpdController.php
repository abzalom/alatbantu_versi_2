<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use App\Models\Config\TimPembahasOpd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TimPembahasOpdController extends Controller
{
    public function index()
    {
        // ambil data + relasi OPD (optimalkan pilih kolom seperlunya)
        $data = TimPembahasOpd::with(['opd:id,kode_opd,nama_opd'])
            ->orderBy('urutan') // urut anggota dalam OPD
            ->get();

        // group per OPD, lalu bentuk struktur dengan members sebagai Collection
        $opds = $data
            ->sortBy('opd.kode_opd')
            ->groupBy(fn($t) => $t->opd->kode_opd)
            ->map(function ($group) {
                $first = $group->first();
                return (object) [
                    'id'      => $first->opd->id,
                    'kode_opd'     => $first->opd->kode_opd,
                    'nama_opd'     => $first->opd->nama_opd,
                    'opd'     => $first->opd->text,
                    'members' => $group->map(fn($t) => (object) [
                        'id'     => $t->id,
                        'urutan' => $t->urutan,
                        'nama'   => $t->nama,
                        'nip'    => $t->nip,
                        'jabatan' => $t->jabatan,
                        'tahun'  => $t->tahun,
                    ])->values(),
                ];
            })->values();
        // return $opds;
        return view('v1-1.config.tim_pembahas.opd.tim-pembahas-opd', [
            'app' => [
                'title' => 'Tim Pembahas OPD',
                'desc' => 'Manage Tim Pembahas Perangkat Daerah',
            ],
            'opds' => $opds,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'opd_id' => 'required|exists:opds,id',
                'nama' => 'required|string|max:255',
                'nip' => 'nullable|string|max:255',
                'jabatan' => 'nullable|string|max:100',
            ],
            [
                'opd_id.required' => 'Perangkat Daerah wajib diisi',
                'opd_id.exists' => 'Perangkat Daerah tidak ditemukan',
                'nama.required' => 'Nama lengkap & title wajib diisi',
                'nama.string' => 'Nama lengkap & title harus berupa teks',
                'nama.max' => 'Nama lengkap & title maksimal 255 karakter',
                'nip.string' => 'NIP harus berupa teks',
                'nip.max' => 'NIP maksimal 255 karakter',
                'jabatan.string' => 'Jabatan harus berupa teks',
                'jabatan.max' => 'Jabatan maksimal 100 karakter',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menambahkan anggota Tim Pembahas OPD')
                ->withErrors($validator)
                ->withInput();
        }

        // cek urutan terakhir dari tim pembahas opd berdasarkan opd_id. Urutan dimulai dari 0
        $lastUrutan = TimPembahasOpd::where('opd_id', $request->opd_id)->max('urutan');
        $request->merge(['urutan' => is_null($lastUrutan) ? 0 : $lastUrutan + 1]);

        $create = TimPembahasOpd::create([
            'urutan' => $request->urutan,
            'opd_id' => $request->opd_id,
            'nama' => $request->nama,
            'nip' => $request->nip,
            'jabatan' => $request->jabatan,
            'tahun' => session('tahun'),
        ]);

        if (url()->previous() === route('opd.create')) {
            return redirect()->route('opd.index')->with('success', 'Anggota Tim Pembahas OPD berhasil ditambahkan.');
        }
        return redirect()->back()->with('success', 'Anggota Tim Pembahas OPD berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'opd_id' => 'required|exists:opds,id',
                'nama' => 'required|string|max:255',
                'nip' => 'nullable|string|max:255',
                'jabatan' => 'nullable|string|max:100',
            ],
            [
                'opd_id.required' => 'Perangkat Daerah wajib diisi',
                'opd_id.exists' => 'Perangkat Daerah tidak ditemukan',
                'nama.required' => 'Nama lengkap & title wajib diisi',
                'nama.string' => 'Nama lengkap & title harus berupa teks',
                'nama.max' => 'Nama lengkap & title maksimal 255 karakter',
                'nip.string' => 'NIP harus berupa teks',
                'nip.max' => 'NIP maksimal 255 karakter',
                'jabatan.string' => 'Jabatan harus berupa teks',
                'jabatan.max' => 'Jabatan maksimal 100 karakter',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengupdate anggota Tim Pembahas OPD')
                ->withErrors($validator)
                ->withInput();
        }
        $team = TimPembahasOpd::find($id);
        if (!$team) {
            return redirect()->back()->with('error', 'Anggota Tim Pembahas OPD tidak ditemukan.');
        }
        $team->update([
            'nama' => $request->nama,
            'nip' => $request->nip,
            'jabatan' => $request->jabatan,
        ]);
        if (url()->previous() === route('opd.create')) {
            return redirect()->route('opd.index')->with('success', 'Anggota Tim Pembahas OPD berhasil diupdate.');
        }
        return redirect()->back()->with('success', 'Anggota Tim Pembahas OPD berhasil diupdate.');
    }
}
