<?php

// app/Http/Middleware/RequestContext.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class RequestContext
{
    public function handle($request, Closure $next)
    {
        $rid = (string) Str::uuid();
        // set context global utk semua Log::
        Log::withContext([
            'request_id' => $rid,
            'ip'         => $request->ip(),
            'user_id'    => optional($request->user())->id,
            'url'        => $request->fullUrl(),
            'method'     => $request->method(),
        ]);
        // opsional kirim ke response header
        $response = $next($request);
        $response->headers->set('X-Request-Id', $rid);
        return $response;
    }
}
