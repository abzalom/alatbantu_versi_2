<?php

namespace App\Http\Middleware\Web;

use App\Models\Config\Schedule;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            return redirect()->to('/auth/login');
        }

        $jadwal = Schedule::where('status', 1)->first();
        $jadwal_selesai = null;
        if ($jadwal) {
            $jadwal_selesai = $jadwal->selesai;
        }

        session()->put('jadwal_aktif', [
            'tahapan' => $jadwal ? $jadwal->tahapan : null,
            'keterangan' => $jadwal ? $jadwal->keterangan : null,
            'tahun' => $jadwal ? $jadwal->tahun : null,
            'status' => $jadwal ? $jadwal->status : null,
            'mulai' => $jadwal ? $jadwal->mulai : null,
            'selesai' => $jadwal_selesai ? $jadwal_selesai : null,
            'penginputan' => $jadwal ? $jadwal->penginputan : null,
        ]);
        session()->forget('jadwal_selesai');

        return $next($request);
    }
}
