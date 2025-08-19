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
        $users = User::whereNot('id', Auth::id())
            ->whereNot('username', 'admin')
            ->with(['opds'])->get();
        // return $users;
        return view('app.pengaturan.users.pengaturan-users', [
            'app' => [
                'title' => 'Pengaturan',
                'desc' => 'Pengaturan Users',
            ],
            'users' => $users,
        ]);
    }
}
