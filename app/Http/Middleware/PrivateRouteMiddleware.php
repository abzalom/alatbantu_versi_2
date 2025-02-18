<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PrivateRouteMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $allowedReferer = env('APP_URL'); // Pastikan hanya request dari domain ini
        if (!$request->headers->has('Referer') || !str_starts_with($request->headers->get('Referer'), $allowedReferer)) {
            abort(403, 'Unauthorized access');
        }
        return $next($request);
    }
}
