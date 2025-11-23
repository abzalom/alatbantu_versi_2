<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SikdRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => "Nomenklatur OTSUS BG 1%",
                'url' => 'https://web.djpk.kemenkeu.go.id/danaotsus/otsus/perencanaan/rap-subkegiatan-1?search=',
                'jenis' => 'nomenklatur',
                'param_key' => '',
                'param_value' => '',
                'method' => 'get',
                'sumberdana' => 'bg',
            ],
            [
                'name' => "Nomenklatur OTSUS SG 2%",
                'url' => 'https://web.djpk.kemenkeu.go.id/danaotsus/otsus/perencanaan/rap-subkegiatan-2?search=',
                'jenis' => 'nomenklatur',
                'param_key' => '',
                'param_value' => '',
                'method' => 'get',
                'sumberdana' => 'sg',
            ],
            [
                'name' => "Nomenklatur DTI",
                'url' => 'https://web.djpk.kemenkeu.go.id/danaotsus/otsus/perencanaan/rap-subkegiatan-3?search=',
                'jenis' => 'nomenklatur',
                'param_key' => '',
                'param_value' => '',
                'method' => 'get',
                'sumberdana' => 'dti',
            ],
        ];

        // truncate the table before seeding
        DB::table('request_sikd_djpks')->truncate();

        // Insert data into the table
        DB::table('request_sikd_djpks')->insert($data);
    }
}
