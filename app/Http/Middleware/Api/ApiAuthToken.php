<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->hasHeader('x-token')) {
            return response()->json([
                'success' => false,
                'alert' => 'danger',
                'message' => 'error missing key!'
            ]);
        }

        $token = explode('|', $request->header('x-token'));
        $hashKey = $token[0];
        $halfKey1 = $token[1];
        $userData = json_decode(base64_decode($token[2]), true);
        $halfKey2 = Storage::disk('local')->get('client/users/user-secret-key-' . $userData['user_id'] . '.txt');
        if (! Hash::check($halfKey1 . $halfKey2, $hashKey)) {
            return response()->json(['unauthorized'], 401);
        }
        if (!Auth::loginUsingId($userData['user_id'])) {
            return response()->json(['User tidak ditemukan'], 401);
        };
        $request->merge(['token_tahun' => $userData['tahun']]);
        return $next($request);
    }
}
