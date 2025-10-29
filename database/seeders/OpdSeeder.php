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
        // Opd::truncate();
        $tags = [];
        foreach ($opds as $opd) {
            $createOpd = Opd::create([
                'kode_unik_opd' => $opd['tahun'] . '-' . $opd['kode_opd'],
                'kode_opd' => $opd['kode_opd'],
                'nama_opd' => $opd['nama_opd'],
                'tahun' => $opd['tahun'],
            ]);
            $kode_unik_opd = $createOpd->tahun . '-' . $createOpd->kode_opd;
            $expKode = explode('.', $createOpd->kode_opd);
            $kode_urusan1 = $expKode[0];
            $bid1 = $expKode[0] . '.' . $expKode[1];
            $kode_urusan2 = $expKode[2];
            $bid2 = $expKode[2] . '.' . $expKode[3];
            $kode_urusan3 = $expKode[4];
            $bid3 = $expKode[4] . '.' . $expKode[5];
            $kode_unik_opd_tag_bidang1 = $kode_unik_opd . '-' . $bid1;
            $kode_unik_opd_tag_bidang2 = $kode_unik_opd . '-' . $bid2;
            $kode_unik_opd_tag_bidang3 = $kode_unik_opd . '-' . $bid3;
            if (!isset($tags[$kode_unik_opd_tag_bidang1]) && $bid1 !== '0.00') {
                $tags[$kode_unik_opd_tag_bidang1] = [
                    'kode_unik_opd' => $kode_unik_opd,
                    'kode_unik_opd_tag_bidang' => $kode_unik_opd_tag_bidang1,
                    'kode_opd' => $createOpd->kode_opd,
                    'kode_urusan' => $kode_urusan1,
                    'kode_bidang' => $bid1,
                    'tahun' => $createOpd->tahun,
                ];
            }
            if (!isset($tags[$kode_unik_opd_tag_bidang2]) && $bid2 !== '0.00') {
                $tags[$kode_unik_opd_tag_bidang2] = [
                    'kode_unik_opd' => $kode_unik_opd,
                    'kode_unik_opd_tag_bidang' => $kode_unik_opd_tag_bidang2,
                    'kode_opd' => $createOpd->kode_opd,
                    'kode_urusan' => $kode_urusan2,
                    'kode_bidang' => $bid2,
                    'tahun' => $createOpd->tahun,
                ];
            }
            if (!isset($tags[$kode_unik_opd_tag_bidang3]) && $bid3 !== '0.00') {
                $tags[$kode_unik_opd_tag_bidang3] = [
                    'kode_unik_opd' => $kode_unik_opd,
                    'kode_unik_opd_tag_bidang' => $kode_unik_opd_tag_bidang3,
                    'kode_opd' => $createOpd->kode_opd,
                    'kode_urusan' => $kode_urusan3,
                    'kode_bidang' => $bid3,
                    'tahun' => $createOpd->tahun,
                ];
            }
        }

        // $opd_tag_bidangs = json_decode(Storage::disk('public')->get('data/opds/opd-tag-bidang.json'), true);
        OpdTagBidang::truncate();
        foreach ($tags as $tagOpd) {
            OpdTagBidang::updateOrCreate(
                [
                    'kode_unik_opd_tag_bidang' => $tagOpd['kode_unik_opd_tag_bidang'],
                ],
                [
                    'kode_unik_opd' => $tagOpd['kode_unik_opd'],
                    'kode_opd' => $tagOpd['kode_opd'],
                    'kode_urusan' => $tagOpd['kode_urusan'],
                    'kode_bidang' => $tagOpd['kode_bidang'],
                    'tahun' => $tagOpd['tahun'],
                ]
            );
        }
    }
}
