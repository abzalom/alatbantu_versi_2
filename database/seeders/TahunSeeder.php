<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TahunSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tahun_anggaran')->truncate();
        $years = range(2022, 2027);
        foreach ($years as $year) {
            DB::table('tahun_anggaran')->insert([
                'tahun' => $year,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
