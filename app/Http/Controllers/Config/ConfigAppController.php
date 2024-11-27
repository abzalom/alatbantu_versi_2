<?php

namespace App\Http\Controllers\Config;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ConfigAppController extends Controller
{
    public function config_app_session_tahun(Request $request)
    {
        session([
            'tahun' => (int) $request->tahun,
        ]);
        $user = User::find(Auth::user()->id);
        $generatedKey = bin2hex(random_bytes(64));
        $keyLen = strlen($generatedKey);
        $devideKkey = $keyLen / 2;
        $keyHalf1 = substr($generatedKey, 0, $devideKkey); // simpan ke session
        $keyHalf2 = substr($generatedKey, $devideKkey, $keyLen); // simpan ke file
        Storage::disk('local')->put("client/users/user-secret-key-{$user->id}.txt", $keyHalf2);
        $hasKey = Hash::make($generatedKey);
        $userData = [
            'user_id' => $user->id,
            'username' => $user->username,
            'role' => $user->getRoleNames()->toArray(),
            'tahun' => session()->get('tahun'),
        ];
        $userToken = $hasKey . '|' . $keyHalf1 . '|' . base64_encode(json_encode($userData));
        session(['user_token' => $userToken]);
        return redirect()->back()->with('success', 'Tahun berhasil diganti ke ' . session()->get('tahun'));
    }
}
