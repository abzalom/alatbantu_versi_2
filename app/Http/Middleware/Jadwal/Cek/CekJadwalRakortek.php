<?php

namespace App\Http\Middleware\Jadwal\Cek;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CekJadwalRakortek
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Jika user bukan admin, lakukan pengecekan jadwal
        if (!$request->user()->hasRole('admin')) {
            // Lakukan pengecekan jadwal
            $jadwal = jadwal_rap();
            if ($jadwal && $jadwal->tahapan === 'rakortek') {
                return redirect()->to('/rakortek/urusan')->with('error', 'Tahapan Rakortek masih berlangsung!');
            }
        }
        return $next($request);
    }
}
