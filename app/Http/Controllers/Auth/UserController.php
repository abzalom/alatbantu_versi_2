<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Data\Opd;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function user_config(Request $request)
    {
        $data = User::whereNot('id', Auth::user()->id)
            ->withTrashed()
            ->get();
        $opds = Opd::get();
        // return $data;
        return view('app.pengaturan.users.pengaturan-users', [
            'app' => [
                'title' => 'Pengaturan',
                'desc' => 'Pengaturan Users',
            ],
            'data' => $data,
            'opds' => $opds,
        ]);
    }
}
