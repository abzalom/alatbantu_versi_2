<?php

namespace App\Http\Controllers\Config;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Config\Schedule;
use App\Http\Controllers\Controller;

class ScheduleRapController extends Controller
{
    public function schedule_rap_config()
    {
        $jadwals = Schedule::with([
            'user_create',
            'user_update',
        ])
            ->where('tahun', session()->get('tahun'))
            ->orderBy('created_at', 'desc')
            ->get();
        // return $jadwals;
        return view('app.pengaturan.schedule.pengaturan-schedule', [
            'app' => [
                'title' => 'Pengaturan Jadwal',
                'desc' => 'Pengaturan Jadwal Pelaksanaan Sinkronisasi Data RAP',
            ],
            'jadwals' => $jadwals,
        ]);
    }

    public function new_rap_schedule(Request $request)
    {
        // return $request->all();

        $request->validate(
            [
                'tahapan' => 'required|in:rakortek,ranwal,rancangan,final,perubahan,pelaporan',
                'keterangan' => 'required',
                'mulai' => 'required|date_format:Y-m-d\TH:i',
                'selesai' => 'required|date_format:Y-m-d\TH:i',
            ],
            [
                'tahapan.required' => 'Tahapan belum dipilih',
                'tahapan.in' => 'Tahapan tidak valid',
                'keterangan.required' => 'Keterangan belum diisi',
                'mulai.required' => 'Tanggal mulai belum diisi',
                'mulai.date_format' => 'Format tanggal mulai tidak valid',
                'selesai.required' => 'Tanggal selesai belum diisi',
                'selesai.date_format' => 'Format tanggal selesai tidak valid',
            ]
        );


        $shceduleClass = Schedule::class;

        // Urutan tahapan yang benar
        $tahapan_berurutan = ['rakortek', 'ranwal', 'rancangan', 'final', 'perubahan'];

        // Ambil tahun sekarang
        $tahun_sekarang = date('Y');

        // Jika tahun yang diinput lebih kecil dari tahun sekarang, hanya boleh input "pelaporan"
        if (session()->get('tahun') < $tahun_sekarang) {
            return redirect()->back()->with('error', 'Untuk tahun di bawah ' . $tahun_sekarang . ', tdak diperbolehkan membuat jadwal!');
        }

        // Ambil tahapan terakhir yang tersimpan untuk tahun tertentu
        $tahapan_terakhir = $shceduleClass::where('tahun', session()->get('tahun'))
            ->orderBy('created_at', 'asc')
            ->pluck('tahapan')
            ->toArray();

        // Jika belum ada tahapan, pastikan input pertama adalah 'rakortek'
        if (empty($tahapan_terakhir) && $request->tahapan !== 'rakortek') {
            return redirect()->back()->with('error', 'Tahapan pertama harus "Rakortek"!');
        }

        $index_terakhir = array_search(end($tahapan_terakhir), $tahapan_berurutan);
        $checkIndexTerakhir = $index_terakhir ? $index_terakhir : 0;
        $index_baru = array_search($request->tahapan, $tahapan_berurutan);
        if ($index_baru !== $checkIndexTerakhir && $index_baru !== $checkIndexTerakhir + 1) {
            return redirect()->back()->with('error', 'Tahapan harus berurutan! Tahapan terakhir adalah "' . end($tahapan_terakhir) . '".');
        }

        $cekAktif = $shceduleClass::where([
            'status' => 1,
            'tahun' => session()->get('tahun'),
        ])->get();
        if ($cekAktif->isNotEmpty()) {
            return redirect()->back()->with('error', 'Jadwal aktif masih ada');
        }

        $duplikat = $shceduleClass::where([
            'tahapan' => $request->tahapan,
            'tahun' => session()->get('tahun'),
            'keterangan' => $request->keterangan,
        ])->exists();

        if ($duplikat) {
            return redirect()->back()->with('error', "Jadwal dengan tahapan: {$request->tahapan}, keterangan: {$request->keterangan} dan tahun " . session()->get('tahun') . " sudah ada");
        }

        $user = User::find($request->created_by);
        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan');
        }

        $data = [
            'tahapan' => $request->tahapan,
            'keterangan' => $request->keterangan,
            'tahun' => session()->get('tahun'),
            'mulai' => $request->mulai,
            'selesai' => $request->selesai,
            'status' => true,
            'created_by' => $user->id,
        ];

        Schedule::create($data);

        return redirect()->back()->with('success', 'Jadwal baru berhasil ditambahkan');
    }

    public function update_rap_schedule(Request $request)
    {
        // return $request->all();
        $request->validate(
            [
                'id' => 'required|exists:schedules,id',
                'keterangan' => 'required',
                'selesai' => 'required|date_format:Y-m-d\TH:i',
                'updated_by' => 'required|exists:users,id',
            ],
            [
                'id.required' => 'Jadwal belum tidak ditemukan',
                'keterangan.required' => 'Keterangan belum diisi',
                'selesai.required' => 'Tanggal selesai belum diisi',
                'selesai.date_format' => 'Format tanggal selesai tidak valid',
                'updated_by.required' => 'User tidak ditemukan',
            ]
        );

        $cekAktif = Schedule::where('status', 1)->whereNot('id', $request->id)->get();
        if ($cekAktif->isNotEmpty()) {
            return redirect()->back()->with('error', 'Jadwal aktif masih ada');
        }

        $jadwal = Schedule::find($request->id);
        $jadwal->keterangan = $request->keterangan;
        $jadwal->selesai = $request->selesai;
        $jadwal->updated_by = $request->updated_by;
        $jadwal->save();
        return redirect()->back()->with('success', 'Jadwal berhasil diperbarui');
    }

    public function lock_rap_schedule(Request $request)
    {
        $request->validate(
            [
                'id' => 'required|exists:schedules,id',
            ],
            [
                'id.required' => 'Jadwal belum tidak ditemukan',
            ]
        );
        $jadwal = Schedule::find($request->id);
        $jadwal->status = 0;
        $jadwal->penginputan = 0;
        $jadwal->save();
        return redirect()->back()->with('success', 'Jadwal berhasil dikunci');
    }

    public function penginputan_user_rap_schedule(Request $request)
    {
        // return $request->all();
        if (!$request->has('penginputan')) {
            return redirect()->back()->with('error', 'Terjadi kesalahan!');
        }
        if ($request->penginputan) {
            $request->validate(
                [
                    'id' => 'required|exists:schedules,id',
                    'penginputan' => 'required|in:true,false,1,0',
                ],
                [
                    'id.required' => 'Terjadi kesahalan!',
                    'penginputan.required' => 'Terjadi kesahalan!',
                    'penginputan.in' => 'Terjadi kesahalan!',
                ]
            );
            $jadwal = Schedule::find($request->id);
            if (!$jadwal) {
                return redirect()->back()->with('error', 'Jadwal tidak ditemukan');
            }
            $jadwal->penginputan = $request->penginputan == 'true' || $request->penginputan == 1 ? 1 : 0;
            $jadwal->save();
            $message = $jadwal->penginputan ? 'diaktifkan' : 'dinonaktifkan';
            return redirect()->back()->with('success', "Penginputan berhasil $message");
        }
    }
}
