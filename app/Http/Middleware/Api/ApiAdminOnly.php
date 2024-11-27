<?php

namespace App\Http\Middleware\Api;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiAdminOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->hasHeader('x-token')) {
            return response()->json([
                'success' => false,
                'alert' => 'danger',
                'message' => 'error missing key!'
            ]);
        }
        $token = explode('|', $request->header('x-token'));
        $data = json_decode(base64_decode($token[2]), true);
        $user = User::find($data['user_id']);
        if (!$user->hasRole(['admin'])) {
            return response()->json([
                'success' => false,
                'alert' => 'danger',
                'message' => 'unautorized!'
            ], 401);
        }
        return $next($request);
    }
}
