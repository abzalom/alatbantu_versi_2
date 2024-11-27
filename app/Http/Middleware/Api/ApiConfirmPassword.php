<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ApiConfirmPassword
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
            ], 500);
        }

        $token = explode('|', $request->header('x-token'));
        $hashKey = $token[0];
        $halfKey1 = $token[1];
        $userData = json_decode(base64_decode($token[2]), true);
        $halfKey2 = Storage::disk('local')->get('client/users/user-secret-key-' . $userData['user_id'] . '.txt');
        if (! Hash::check($halfKey1 . $halfKey2, $hashKey)) {
            return response()->json([
                'success' => false,
                'alert' => 'danger',
                'message' => 'unauthorized'
            ], 401);
        }
        if (!$request->has('username')) {
            return response()->json([
                'alert' => 'error',
                'status' => false,
                'message' => 'Error! Some parameter\'s is missing!'
            ], 400);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'password' => 'required',
            ],
            [
                'password.required' => 'Password tidak boleh kosong!',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan!',
                'alert' => 'danger',
                'errors' => $validator->errors()
            ], 442);
        }

        if (!Auth::attempt(['username' => $userData['username'], 'password' => $request->password])) {
            return response()->json([
                'success' => false,
                'alert' => 'danger',
                'message' => 'Unauthenticated'
            ], 401);
        };

        return $next($request);
    }
}
