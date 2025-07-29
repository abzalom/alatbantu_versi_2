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
        $data = ScheduleMonev::where('tahun', session()->get('tahun'))
            ->orderBy('created_at', 'desc')
            ->get();
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
                'nama' => 'required|unique:schedule_monevs,nama',
                'keterangan' => 'required',
            ],
            [
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
            'nama' => $request->nama,
            'keterangan' => $request->keterangan,
            'tahun' => session()->get('tahun'),
            'created_by' => Auth::user()->id,
        ]);

        return redirect()->back()->with('success', 'Jadwal Monev berhasil ditambahkan');
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
}
