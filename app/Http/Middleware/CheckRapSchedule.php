<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRapSchedule
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Jika user bukan admin, lakukan pengecekan jadwal
        if (!auth()->user()->hasRole('admin')) {
            // Ambil sekali biar tidak bolak-balik memanggil helper
            $rap = jadwal_rap();

            // Urutan/teks sesuai kebutuhanmu
            if (jadwal_monev()) {
                return $this->deny($request, 'Monev masih berlangsung!');
            }
            if (!$rap) {
                return $this->deny($request, 'Jadwal RAP belum dibuat!');
            }
            if (!$rap->status) {
                return $this->deny($request, 'Jadwal RAP tidak aktif!');
            }
            if (!$rap->aktif) {
                return $this->deny($request, 'Jadwal RAP telah selesai!');
            }
            if (!$rap->penginputan) {
                return $this->deny($request, 'Penginputan RAP sedang ditutup!');
            }
        }
        return $next($request);
    }

    private function deny(Request $request, string $message)
    {
        // Jika request AJAX/JSON, kembalikan JSON; selain itu redirect back
        if ($request->expectsJson()) {
            return response()->json(['message' => $message], 403);
        }
        return back()->with('error', $message);
    }
}
