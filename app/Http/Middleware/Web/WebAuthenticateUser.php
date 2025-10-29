<?php

namespace App\Http\Middleware\Web;

use App\Models\Config\Schedule;
use App\Models\Config\ScheduleMonev;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class WebAuthenticateUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            // Redirect user if not authenticated
            return redirect()->to('/auth/login')->with('error', 'Silahkan login dulu');
        }

        if (Auth::check() && !Auth::user()->hasRole('admin')) {
            $userId = Auth::id();
            $currentSessionId = Session::getId();

            // Hapus semua session user sebelumnya kecuali yang sekarang
            DB::table('sessions')
                ->where('user_id', $userId)
                ->where('id', '!=', $currentSessionId)
                ->delete();
        }

        if (
            !session()->has('tahun') || session()->get('tahun') == null
            // || !session()->has('jadwal_aktif') || empty(session()->get('jadwal_aktif'))
            // || !session()->has('jadwal_monev') || empty(session()->get('jadwal_monev'))
        ) {
            // return redirect()->to('/session/tahun')->with('error', 'Silakan pilih tahun terlebih dahulu.');
            Auth::logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return redirect('/auth/login')->with('error', 'Sesi Anda telah berakhir. Silakan login kembali.');
        }

        $jadwal = Schedule::where([
            'status' => 1,
            'tahun' => session()->get('tahun'),
        ])->first();

        $jadwalMonev = ScheduleMonev::where([
            'status' => 1,
            'tahun' => session()->get('tahun'),
        ])->first();

        session()->put('jadwal_aktif', [
            'id' => $jadwal ? $jadwal->id : null,
            'tahapan' => $jadwal ? $jadwal->tahapan : null,
            'keterangan' => $jadwal ? $jadwal->keterangan : null,
            'tahun' => $jadwal ? $jadwal->tahun : null,
            'status' => $jadwal ? $jadwal->status : null,
            'mulai' => $jadwal ? $jadwal->mulai : null,
            'selesai' => $jadwal ? $jadwal->selesai : null,
            'penginputan' => $jadwal ? $jadwal->penginputan : null,
            'created_by' => $jadwal ? $jadwal->created_by : null,
            'updated_by' => $jadwal ? $jadwal->updated_by : null,
        ]);

        session()->put('jadwal_monev', [
            'id' => $jadwalMonev ? $jadwalMonev->id : null,
            'nama' => $jadwalMonev ? $jadwalMonev->nama : null,
            'tahapan' => $jadwalMonev ? $jadwalMonev->tahapan : null,
            'keterangan' => $jadwalMonev ? $jadwalMonev->keterangan : null,
            'tahun' => $jadwalMonev ? $jadwalMonev->tahun : null,
            'status' => $jadwalMonev ? $jadwalMonev->status : null,
            'user_create_id' => $jadwalMonev ? $jadwalMonev->user_create_id : null,
            'user_update_id' => $jadwalMonev ? $jadwalMonev->user_update_id : null,
        ]);

        return $next($request);
    }
}
