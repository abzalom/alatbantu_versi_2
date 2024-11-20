<?php

namespace Database\Seeders;

use App\Models\Data\Opd;
use App\Models\Tagging\Nomenklatur\OpdTagBidang;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OpdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $opds = json_decode(Storage::disk('public')->get('data/opds/opds.json'), true);
        Opd::truncate();
        foreach ($opds as $opd) {
            Opd::create([
                'kode_unik_opd' => $opd['tahun'] . '-' . $opd['kode_opd'],
                'kode_opd' => $opd['kode_opd'],
                'nama_opd' => $opd['nama_opd'],
                'tahun' => $opd['tahun'],
            ]);
        }

        $opd_tag_bidangs = json_decode(Storage::disk('public')->get('data/opds/opd-tag-bidang.json'), true);
        OpdTagBidang::truncate();
        foreach ($opd_tag_bidangs as $opd_tag_bidang) {
            OpdTagBidang::updateOrCreate(
                [
                    'kode_unik_opd_tag_bidang' => $opd_tag_bidang['tahun'] . '-' . $opd_tag_bidang['kode_opd'] . '-' . $opd_tag_bidang['kode_bidang'],
                    'tahun' => $opd_tag_bidang['tahun'],
                ],
                [
                    'kode_unik_opd' => $opd_tag_bidang['tahun'] . '-' . $opd_tag_bidang['kode_opd'],
                    'kode_unik_opd_tag_bidang' => $opd_tag_bidang['tahun'] . '-' . $opd_tag_bidang['kode_opd'] . '-' . $opd_tag_bidang['kode_bidang'],
                    'kode_opd' => $opd_tag_bidang['kode_opd'],
                    'kode_urusan' => $opd_tag_bidang['kode_urusan'],
                    'kode_bidang' => $opd_tag_bidang['kode_bidang'],
                ]
            );
        }
    }
}
