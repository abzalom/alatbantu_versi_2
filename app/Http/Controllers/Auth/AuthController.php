<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Data\Opd;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login_auth(Request $request)
    {
        if (Auth::check()) {
            return redirect()->to('/');
        }

        return view('auth.login-auth');
    }

    public function process_login_auth(Request $request)
    {
        $validator = Validator::make(
            $request->except("_token"),
            [
                'username' => 'required',
                'password' => 'required',
                'tahun' => 'required',
            ],
            [
                'username.required' => 'Username tidak boleh kosong!',
                'password.required' => 'Password tidak boleh kosong!',
                'tahun.required' => 'Tahun harus dipilih!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->to('/auth/login')->withInput([
                'username' => $request->username,
                'tahun' => $request->tahun,
            ])
                ->withErrors($validator)
                ->with('pesan', 'Login gagal. terjadi kesalahan!');
        }

        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->rememberme)) {
            session([
                'tahun' => (int) $request->tahun,
            ]);

            $user = User::find(Auth::user()->id);

            return redirect()->to('/')->with('success', "Selamat datang $user->name!");
        }

        return redirect()->back()->with('pesan', 'Terjadi kesalahan! username tidak diketahui!');
    }

    public function logout_auth(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/auth/login');
    }
}
