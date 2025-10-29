<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMonevSchedule
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $jadwal_monev = jadwal_monev();
        if ($jadwal_monev && $jadwal_monev->status) {
            $user = auth()->user();
            if (!$user || !$user->hasRole('admin')) {
                return $this->deny($request);
            }
        }
        return $next($request);
    }

    public function deny(Request $request): Response
    {
        $jadwal_monev = jadwal_monev();
        $route = $jadwal_monev->tahapan === 'rakotek' ? '/monev/rakortek' : '/monev/rap';
        $message = $jadwal_monev->tahapan === 'rakotek' ? 'Monev Rakortek sedang berlangsung!' : 'Monev RAP sedang berlangsung!';
        return redirect()->to($route)->with('error', $message);
    }
}
