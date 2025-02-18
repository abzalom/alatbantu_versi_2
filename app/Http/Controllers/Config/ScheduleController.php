<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use App\Models\Config\Schedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    public function schedule_config()
    {
        $jadwals = Schedule::with([
            'user_create',
            'user_update',
        ])
            ->withTrashed()
            ->get();
        // return $jadwals;
        return view('app.pengaturan.schedule.pengaturan-schedule', [
            'app' => [
                'title' => 'Pengaturan Jadwal',
                'desc' => 'Pengaturan Jadwal Pelaksanaan Sinkronisasi Data',
            ],
            'jadwals' => $jadwals,
        ]);
    }

    public function new_schedule(Request $request)
    {
        $request->validate(
            [
                'tahapan' => 'required|in:ranwal, rancangan, final, perubahan, pelaporan',
                'keterangan' => 'required',
                'tahun' => 'required|numeric',
                'mulai' => 'required|date_time_format:Y-m-d\TH:i',
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

        $cekAktif = Schedule::where('status', 1)->get();
        if ($cekAktif->isNotEmpty()) {
            return redirect()->back()->with('error', 'Jadwal aktif masih ada');
        }

        $user = User::find($request->created_by);
        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan');
        }

        $data = [
            'tahapan' => $request->tahapan,
            'keterangan' => $request->keterangan,
            'tahun' => $request->tahun,
            'mulai' => $request->mulai,
            'selesai' => $request->selesai,
            'status' => true,
            'created_by' => $user->id,
        ];

        Schedule::create($data);

        return redirect()->back()->with('success', 'Jadwal baru berhasil ditambahkan');
    }

    public function update_schedule(Request $request)
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

    public function lock_schedule(Request $request)
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

    public function action_schedule(Request $request)
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
