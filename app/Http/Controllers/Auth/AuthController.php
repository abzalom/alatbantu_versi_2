<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Data\Opd;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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
                ->with('error', 'Login gagal. terjadi kesalahan!');
        }

        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->rememberme)) {
            $user = User::find(Auth::user()->id);
            $generatedKey = bin2hex(random_bytes(64));
            $keyLen = strlen($generatedKey);
            $devideKkey = $keyLen / 2;
            $keyHalf1 = substr($generatedKey, 0, $devideKkey); // simpan ke session
            $keyHalf2 = substr($generatedKey, $devideKkey, $keyLen); // simpan ke file
            Storage::disk('local')->put("client/users/user-secret-key-{$user->id}.txt", $keyHalf2);
            $hasKey = Hash::make($generatedKey);
            session([
                'tahun' => (int) $request->tahun,
            ]);
            $userData = [
                'user_id' => $user->id,
                'username' => $user->username,
                'role' => $user->getRoleNames()->toArray(),
                'tahun' => session()->get('tahun'),
            ];
            $userToken = $hasKey . '|' . $keyHalf1 . '|' . base64_encode(json_encode($userData));
            session(['user_token' => $userToken]);
            return redirect()->to('/')->with('success', "Selamat datang $user->name!");
        }

        return redirect()->back()->with('error', 'Terjadi kesalahan! username tidak diketahui!');
    }

    public function logout_auth(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/auth/login');
    }
}
