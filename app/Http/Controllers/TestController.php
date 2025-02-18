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
    public function testing(Request $request)
    {
        date_default_timezone_set('Asia/Jayapura');
        echo date_default_timezone_get();
        echo "<br>";
        $jadwal = DB::table('schedules')->where('status', 1)->first();
        $current = date('Y-m-d H:i:s');
        echo $current . "<br>";
        $diff = date_diff(date_create($current), date_create($jadwal->selesai));
        echo $diff->format('%R%a hari %H jam %I menit %s detik');

        // $api_url = "http://worldtimeapi.org/api/ip";
        // $response = file_get_contents($api_url);
        // $data = json_decode($response, true);

        // if ($data && isset($data['timezone'])) {
        //     date_default_timezone_set($data['timezone']);
        //     echo "Zona Waktu: " . date_default_timezone_get() . "<br>";
        //     echo "Waktu Sekarang: " . date('Y-m-d H:i:s');
        // } else {
        //     echo "Gagal mengambil zona waktu.";
        // }
    }
}
