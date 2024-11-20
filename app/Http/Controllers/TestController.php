<?php

namespace App\Http\Controllers;

use App\Models\Data\Opd;
use App\Models\Nomenklatur\A5Subkegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{

    public function test(Request $request)
    {
        return A5Subkegiatan::where('kode_subkegiatan', '3.27.02.2.06.0003')->first()->kode_bidang;
    }
}
