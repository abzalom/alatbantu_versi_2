<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ApiCustomAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->has('username') || !$request->has('username')) {
            return response()->json([
                'alert' => 'error',
                'status' => false,
                'message' => 'Error! Some parameter\'s is missing!'
            ], 400);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'username' => 'required',
                'password' => 'required',
            ],
            [
                'username.required' => 'Username tidak boleh kosong!',
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

        if (!Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            return response()->json([
                'success' => false,
                'alert' => 'danger',
                'message' => 'Unauthenticated'
            ], 401);
        }

        return $next($request);
    }
}
