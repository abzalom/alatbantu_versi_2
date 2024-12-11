<?php

namespace App\Http\Middleware\Web;

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
            return redirect()->to('/auth/login')->with('pesan', 'Anda harus login terlebih dahulu!');
        }
        return $next($request);
    }
}
