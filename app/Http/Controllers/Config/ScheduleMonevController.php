<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use App\Models\Config\ScheduleMonev;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ScheduleMonevController extends Controller
{
    public function schedule_monev_config(Request $request)
    {
        $data = ScheduleMonev::with([
            'created_by',
            'updated_by',
        ])
            ->where('tahun', session()
                ->get('tahun'))
            ->orderBy('created_at', 'desc')
            ->get();
        // return $data;
        return view('v1-1.config.schedules.monev.schedule-monev', [
            'app' => [
                'title' => 'Jadwal Monev',
                'desc' => 'Pengaturan Jadwal Monev',
            ],
            'data' => $data,
        ]);
    }

    public function new_schedule_monev_config(Request $request)
    {
        // return $request->all();
        $validator = Validator::make(
            $request->all(),
            [
                'tahapan' => 'required|in:rakotek,rap',
                'nama' => 'required|unique:schedule_monevs,nama',
                'keterangan' => 'required',
            ],
            [
                'tahapan.required' => 'Tahapan Jadwal belum dipilih',
                'tahapan.in' => 'Tahapan Jadwal tidak valid',
                'nama.required' => 'Nama Jadwal belum diisi',
                'nama.unique' => 'Nama Jadwal sudah ada',
                'keterangan.required' => 'Keterangan Jadwal belum diisi',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $scheduleMonevClass = ScheduleMonev::class;

        $cekAktif = $scheduleMonevClass::where('tahun', session()->get('tahun'))->where('status', true)->exists();

        if ($cekAktif) {
            return redirect()->back()->with('error', 'Jadwal Monev aktif masih ada');
        }

        ScheduleMonev::create([
            'tahapan' => $request->tahapan,
            'nama' => $request->nama,
            'keterangan' => $request->keterangan,
            'tahun' => session()->get('tahun'),
            'user_create_id' => Auth::user()->id,
        ]);

        return redirect()->back()->with('success', 'Jadwal Monev berhasil ditambahkan');
    }

    public function update_schedule_monev_config(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'required|exists:schedule_monevs,id',
                'nama' => 'required|unique:schedule_monevs,nama,' . $request->id,
                'keterangan' => 'required',
            ],
            [
                'id.required' => 'ID Jadwal belum dipilih',
                'id.exists' => 'Jadwal tidak ditemukan',
                'nama.required' => 'Nama Jadwal belum diisi',
                'nama.unique' => 'Nama Jadwal sudah ada',
                'keterangan.required' => 'Keterangan Jadwal belum diisi',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $scheduleMonev = ScheduleMonev::find($request->id);
        if (!$scheduleMonev) {
            return redirect()->back()->with('error', 'Jadwal Monev tidak ditemukan!');
        }

        $user_update_id = $scheduleMonev->nama !== $request->nama || $scheduleMonev->keterangan !== $request->keterangan ? Auth::user()->id :  $scheduleMonev->user_update_id;

        $scheduleMonev->nama = $scheduleMonev->nama == $request->nama ? $scheduleMonev->nama : $request->nama;
        $scheduleMonev->keterangan = $scheduleMonev->keterangan == $request->keterangan ? $scheduleMonev->keterangan : $request->keterangan;
        $scheduleMonev->user_update_id = $user_update_id;
        $scheduleMonev->save();

        return redirect()->back()->with('success', 'Jadwal Monev berhasil diperbarui');
    }

    public function lock_schedule_monev_config(Request $request)
    {
        if (!$request->has('id') || !$request->id) {
            return redirect()->back()->with('error', 'Jadwal Monev belum dipilih!');
        }

        $scheduleMonev = ScheduleMonev::find($request->id);
        if (!$scheduleMonev) {
            return redirect()->back()->with('error', 'Jadwal Monev tidak ditemukan!');
        }

        $scheduleMonev->status = false;
        $scheduleMonev->save();
        return redirect()->back()->with('success', 'Jadwal Monev berhasil dikunci');
    }

    public function activate_schedule_monev_config(Request $request)
    {
        if (!$request->has('id') || !$request->id) {
            return redirect()->back()->with('error', 'Jadwal Monev belum dipilih!');
        }

        // cek jadwal selain id yang masih aktif
        $jadwalAktif = ScheduleMonev::where('id', '!=', $request->id)->where('status', true)->first();
        if ($jadwalAktif) {
            return redirect()->back()->with('error', 'Jadwal Monev aktif masih ada')
                ->withErrors([
                    'aktif' => 'Kunci jadwal Monev yang aktif terlebih dahulu'
                ]);
        }

        $scheduleMonev = ScheduleMonev::find($request->id);
        if (!$scheduleMonev) {
            return redirect()->back()->with('error', 'Jadwal Monev tidak ditemukan!');
        }

        $scheduleMonev->status = $scheduleMonev->status ? false : true;
        $scheduleMonev->save();
        return redirect()->back()->with('success', 'Jadwal Monev berhasil diaktifkan');
    }
}
