<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SessionController extends Controller
{
    public function set_timezone(Request $request)
    {
        $request->validate([
            'timezone' => 'required|string'
        ]);

        // Simpan ke session
        Session::put('user_timezone', $request->timezone);
    }
}
