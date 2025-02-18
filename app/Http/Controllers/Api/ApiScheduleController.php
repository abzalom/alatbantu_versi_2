<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ApiScheduleController extends Controller
{
    public function get_schedule_active(Request $request)
    {
        $data = DB::table('schedules')->where('status', 1)->get();
        return response()->json([
            'active' => $data->isNotEmpty() ? true : false,
            'message' => $data->isNotEmpty() ? 'Jadwal aktif ditemukan' : 'Jadwal aktif tidak ditemukan',
        ]);
    }
}
