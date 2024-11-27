<?php

namespace App\Http\Controllers;

use App\Models\Data\Opd;
use App\Models\Nomenklatur\A5Subkegiatan;
use App\Models\Rap\RapOtsus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class TestController extends Controller
{
    public function test(Request $request) {}
}
