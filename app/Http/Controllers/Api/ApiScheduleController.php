<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Config\Schedule;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class ApiScheduleController extends Controller
{
    public function get_schedule(Request $request)
    {
        $schedule = Schedule::where('tahun', $request->tahun);
        $data = [];
        $validator = Validator::class;
        if ($request->has('id') || !empty($request->id)) {
            $validator = $validator::make($request->all(), [
                'id' => 'required|exists:schedules,id',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Missing some parameter\'s',
                    'alert' => 'danger',
                    'errors' => $validator->errors()
                ], 400);
            }

            $data = $schedule->where('id', $request->id);
        }

        $data = $schedule->get()->map(function ($item) {
            $created_by = User::find($item->created_by);
            $updated_by = User::find($item->updated_by);
            return [
                'id' => $item->id,
                'tahapan' => $item->tahapan,
                'keterangan' => $item->keterangan,
                'tahun' => $item->tahun,
                'mulai' => $item->mulai,
                'selesai' => $item->selesai,
                'status' => $item->status,
                'penginputan' => $item->penginputan,
                'created_by' => $created_by->name,
                'updated_by' => $updated_by ? $updated_by->name : null,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function get_schedule_active(Request $request)
    {
        $data = DB::table('schedules')->where('status', 1)->get();
        return response()->json([
            'active' => $data->isNotEmpty() ? true : false,
            'message' => $data->isNotEmpty() ? 'Jadwal aktif ditemukan' : 'Jadwal aktif tidak ditemukan',
        ]);
    }
}
