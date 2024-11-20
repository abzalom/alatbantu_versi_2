<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiRequestController extends Controller
{
    public function test_redirect()
    {
        return redirect()->to('/otsus/alokasi_dana')->with('error', 'Test menampilkan bootstrap toast');
    }
    public function get_session()
    {
        return session()->all();
    }
}
