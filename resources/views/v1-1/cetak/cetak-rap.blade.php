<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/vendors/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <title>{{ $app['title'] ?? 'Cetak Laporan' }}</title>
    <style>
        body {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
            padding: 10px;
            font-size: 70% !important;
        }

        .line {
            border-top: 2px solid black;
            margin: 20px 0;
            width: 100%;
        }

        .header {
            /* border: 1px solid black; */
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
        }

        .logo {
            /* border: 1px solid black; */
            flex: 0 0 15%;
            text-align: center;
        }

        .logo img {
            width: 100px;
            height: auto;
        }

        .header-surat {
            flex: 0 0 75%;
            /* border: 1px solid black; */
        }

        .header-text {
            font-size: 15px;
            font-weight: 600;
            margin: 0;
            padding: 0;
        }

        #signature {
            text-align: center;
            /* semua teks di dalamnya rata tengah */
            width: 250px;
            /* atur lebar blok sesuai kebutuhan */
            margin-left: auto;
            /* dorong ke kanan */
            margin-right: 40px;
            /* kasih jarak dari sisi kanan */
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="logo">
            <img src="/assets/img/mamberamo_raya_resize_250_x_251.png" alt="Kabupaten Mamberamo Raya" class="img-fluid">
        </div>
        <div class="header-surat text-center">
            <div class="header-text">RENCANA ANGGARAN DAN PROGRAM PENGGUNAAN</div>
            <div class="header-text">{{ $header_sumberdana }}</div>
            <div class="header-text">{{ $opd->nama_opd }}</div>
            <div class="header-text">KABUPATEN MAMBERAMO RAYA TAHUN ANGGARAN {{ date('Y') }}</div>
        </div>
    </div>

    <div class="line"></div>

    <table class="table table-bordered table-striped mt-5">
        <thead class="table-info align-middle">
            <tr>
                <th>Target Aktifitas Utama</th>
                <th>Nomenklatur</th>
                <th>Klasifikasi Belanja</th>
                <th>Target Kinerja</th>
                <th>Pagu Alokasi</th>
                <th>Jenis Kegiatan</th>
                <th>Lokasi Fokus</th>
                <th>Titik Koordinat</th>
                <th>Jenis Layanan</th>
                <th>Penerima Manfaat</th>
                <th>Program Bersama Provinsi Papua</th>
                <th>Sinergi Dana Lain</th>
                <th>Multiyears</th>
                <th>Jadwal Pelaksanaan</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($opd->tag_otsus as $tagging)
                @foreach ($tagging->raps as $rap)
                    <tr>
                        <td>{{ $tagging->target_aktifitas->text }}</td>
                        <td>{{ $rap->text_subkegiatan }}</td>
                        <td>{{ $rap->klasifikasi_belanja }}</td>
                        <td>{{ $rap->vol_subkeg . ' ' . $rap->satuan_subkegiatan }}</td>
                        <td class="text-end">{{ formatNumber($rap->anggaran) }}</td>
                        <td>{{ $rap->jenis_kegiatan }}</td>
                        <td>
                            @php
                                $lokasi_fokus = json_decode($rap->lokus, true);
                            @endphp
                            <ul class="m-0 p-0" style="padding-left: 8px!important;">
                                @foreach ($lokasi_fokus as $lokus)
                                    <li>{{ $lokus['kecamatan'] . ' | ' . $lokus['kampung'] }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>{{ $rap->koordinat }}</td>
                        <td>{{ $rap->jenis_layanan == 'terkait' ? 'Terkait langsung ke masyarakat' : 'Kegiatan Pendukung' }}</td>
                        <td>{{ $rap->penerima_manfaat == 'oap' ? 'Khusus OAP' : 'Umum (OAP & Non-OAP)' }}</td>
                        <td>{{ $rap->ppsb }}</td>
                        <td>
                            @php
                                $sumberdana_lain = json_decode($rap->dana_lain, true);
                            @endphp
                            <ul class="m-0 p-0" style="padding-left: 8px!important;">
                                @foreach ($sumberdana_lain as $dana)
                                    <li>{{ $dana['uraian'] }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>{{ $rap->multiyears }}</td>
                        <td class="text-center">
                            {{ $rap->mulai }} <br> s.d <br> {{ $rap->selesai }}
                        </td>
                        <td>{{ $rap->keterangan }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

    <div id="signature">
        <span>Mengetahui,</span>
        <br>
        <span>{{ $opd->kepala_aktif ? $opd->kepala_aktif->jabatan->label() : '' }} {{ ucwords(strtolower($opd->nama_opd)) }}</span>
        <br>
        <br>
        <br>
        <br>
        <br>
        <span>{{ $opd->kepala_aktif ? $opd->kepala_aktif->nama : '________Nama________' }}</span>
        <br>
        <span>{{ $opd->kepala_aktif ? $opd->kepala_aktif->nip : '_________NIP_________' }}</span>
        <br>
        <span>{{ $opd->kepala_aktif ? strtoupper($opd->kepala_aktif->pangkat) : '_______Pangkat_______' }}</span>
    </div>
</body>

</html>
