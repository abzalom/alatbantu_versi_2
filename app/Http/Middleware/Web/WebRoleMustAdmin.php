<?php

namespace App\Http\Middleware\Web;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class WebRoleMustAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = User::find(Auth::user()->id);

            // Periksa role pengguna
            if ($user->hasRole('user')) {
                // Arahkan ke /skpd jika perannya adalah user
                return redirect('/user/rap');
            }
        }
        return $next($request);
    }
}
