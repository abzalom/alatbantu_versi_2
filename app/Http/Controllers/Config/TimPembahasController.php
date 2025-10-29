<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use App\Models\Config\TimPembahas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TimPembahasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teams = TimPembahas::all();
        return view('v1-1.config.tim_pembahas.bappeda.tim-pembahas', [
            'app' => [
                'title' => 'Tim Pembahas Bappeda',
                'desc' => 'Manage Tim Pembahas Bappeda',
            ],
            'teams' => $teams,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('v1-1.config.tim_pembahas.bappeda.tim-pembahas-form', [
            'app' => [
                'title' => 'Tambah Tim Pembahas',
                'desc' => 'Tambah Anggota Tim Pembahas baru',
            ],
            'team' => null,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return $request->all();
        $request->validate(
            [
                'nama'      => 'required|string|max:255',
                'nip'       => ['nullable', 'string', 'regex:/^\d{18}$/', 'unique:tim_pembahas,nip'],
                // 'nip'    => 'nullable|numeric|digits:18|exists:tim_pembahas,nip',
                'jabatan'   => 'nullable|string|max:100',
                'role'      => 'nullable|in:ketua,anggota',
            ],
            [
                'nama.required' => 'Nama lengkap & title wajib diisi',
                'nama.string' => 'Nama lengkap & title harus berupa teks',
                'nama.max' => 'Nama lengkap & title maksimal 255 karakter',
                'nip.numeric' => 'NIP harus berupa angka',
                'nip.digits' => 'NIP harus terdiri dari 18 digit',
                'nip.exists' => 'NIP sudah terdaftar',
                'jabatan.string' => 'Jabatan harus berupa teks',
                'jabatan.max' => 'Jabatan maksimal 100 karakter',
                'role.in' => 'Peran tidak valid',
            ]
        );

        if ($request->input('role') === 'ketua') {
            // Cek apakah sudah ada ketua
            $existingKetua = TimPembahas::where('role', 'ketua')->first();
            if ($existingKetua) {
                return back()->withErrors(['role' => 'Sudah ada ketua dalam tim pembahas. Hanya boleh ada satu ketua.'])->withInput();
            }
        }

        // Simpan data ke database (sesuaikan dengan model dan tabel Anda)
        $create = TimPembahas::create([
            'nama' => $request->input('nama'),
            'nip' => $request->input('nip'),
            'jabatan' => $request->input('jabatan'),
            'role' => $request->input('role') ?? 'anggota',
            'tahun' => session('tahun'),
        ]);

        // cek jika request url sebelumnya dari config/team/bappeda/create atau bappeda.create jika tidak maka redirect back
        if (url()->previous() === route('bappeda.create')) {
            return redirect()->route('bappeda.index')->with('success', 'Anggota Tim Pembahas berhasil ditambahkan.');
        }
        return redirect()->back()->with('success', 'Anggota Tim Pembahas berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $team = TimPembahas::find($id);
        if (!$team) {
            return redirect()->route('bappeda.update')->with('error', 'Anggota Tim Pembahas tidak ditemukan.');
        }
        return view('v1-1.config.tim_pembahas.bappeda.tim-pembahas-form', [
            'app' => [
                'title' => 'Edit Anggota Tim Pembahas',
                'desc' => 'Edit detail anggota tim pembahas : ' . $team->nama,
            ],
            'team' => $team,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $team = TimPembahas::find($id);
        if (!$team) {
            return redirect()->route('bappeda.update')->with('error', 'Anggota Tim Pembahas tidak ditemukan.');
        }

        $validator = Validator::make(
            $request->all(),
            [
                'nama' => 'required|string|max:255',
                'nip' => ['nullable', 'numeric', 'digits:18', Rule::unique('tim_pembahas', 'nip')->ignore($team->id)],
                'jabatan' => 'nullable|string|max:100',
                'role' => 'nullable|in:ketua,anggota',
            ],
            [
                'nama.required' => 'Nama lengkap & title wajib diisi',
                'nama.string' => 'Nama lengkap & title harus berupa teks',
                'nama.max' => 'Nama lengkap & title maksimal 255 karakter',
                'nip.numeric' => 'NIP harus berupa angka',
                'nip.digits' => 'NIP harus terdiri dari 18 digit',
                'nip.exists' => 'NIP sudah terdaftar',
                'jabatan.string' => 'Jabatan harus berupa teks',
                'jabatan.max' => 'Jabatan maksimal 100 karakter',
                'role.in' => 'Peran tidak valid',
            ]
        );
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        if (isset($validated['role']) && $validated['role'] === 'ketua' && $team->role !== 'ketua') {
            // Cek apakah sudah ada ketua
            $existingKetua = TimPembahas::where('role', 'ketua')->first();
            if ($existingKetua) {
                return back()->withErrors(['role' => 'Sudah ada ketua dalam tim pembahas. Hanya boleh ada satu ketua.'])->withInput();
            }
        }

        // Update data di database
        $team->update([
            'nama' => $validated['nama'],
            'nip' => $validated['nip'],
            'jabatan' => $validated['jabatan'],
            'role' => $validated['role'] ?? $team->role,
        ]);
        // cek jika request url sebelumnya dari config/team/bappeda atau bappeda.index jika tidak maka redirect back
        if (url()->previous() === route('bappeda.edit', $team->id)) {
            return redirect()->route('bappeda.index')->with('success', 'Anggota Tim Pembahas berhasil diperbarui.');
        }
        return redirect()->back()->with('success', 'Anggota Tim Pembahas berhasil diperbarui.');
    }

    /**
     * Update order of the specified resource in storage.
     */
    public function add_tim_opd(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'opd_id' => 'required|integer|exists:opds,id',
                'tim_pembahas' => 'required|array|min:1',
                'tim_pembahas.*' => 'required|integer|exists:tim_pembahas,id',
            ],
            [
                'opd_id.required' => 'OPD wajib dipilih.',
                'opd_id.integer' => 'ID OPD harus berupa angka.',
                'opd_id.exists' => 'OPD tidak ditemukan di database.',
                'tim_pembahas.required' => 'Tim Pembahas wajib dipilih.',
                'tim_pembahas.array' => 'Tim Pembahas harus berupa array.',
                'tim_pembahas.min' => 'Pilih minimal satu anggota Tim Pembahas.',
                'tim_pembahas.*.required' => 'Setiap anggota Tim Pembahas wajib diisi.',
                'tim_pembahas.*.integer' => 'Setiap anggota Tim Pembahas harus berupa angka.',
                'tim_pembahas.*.exists' => 'Anggota Tim Pembahas tidak ditemukan di database.',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }
        $validated = $validator->validated();
        // cek jumlah tim baru + existing maksimal 5
        $existingCount = DB::table('opd_tim_pembahas')->where([
            'opd_id' => $validated['opd_id'],
            'tahun' => session('tahun'),
        ])->count();
        $newCount = count($validated['tim_pembahas']);
        if (($existingCount + $newCount) > 5) {
            return back()->withErrors(['tim_pembahas' => 'Jumlah total anggota Tim Pembahas untuk OPD ini tidak boleh lebih dari 5.']);
        }
        // set urutan terakhir
        $maxOrder = DB::table('opd_tim_pembahas')->where([
            'opd_id' => $validated['opd_id'],
            'tahun' => session('tahun'),
        ])->max('urutan');
        $order = $maxOrder ? $maxOrder : 0;
        // insert data
        $insertData = [];
        foreach ($validated['tim_pembahas'] as $timId) {
            $order++;
            $insertData[] = [
                'opd_id' => $validated['opd_id'],
                'tim_pembahas_id' => $timId,
                'tahun' => session('tahun'),
                'urutan' => $order,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('opd_tim_pembahas')->insert($insertData);
        return redirect()->back()->with('success', 'Anggota Tim Pembahas berhasil ditambahkan.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $team = TimPembahas::find($id);
        if (!$team) {
            return redirect()->route('bappeda.update')->with('error', 'Anggota Tim Pembahas tidak ditemukan.');
        }
        $team->delete();
        return redirect()->route('bappeda.update')->with('success', 'Anggota Tim Pembahas berhasil dihapus.');
    }
}
