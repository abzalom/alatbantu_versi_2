<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/vendors/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <title>{{ $app['title'] ?? 'Cetak Laporan' }}</title>
    <style>
        .border-view {
            border: 1px solid #000;
        }

        #kop_line {
            border: 2px solid #000;
            background-color: #000;
            width: 100%;
            margin-bottom: 50px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="d-flex flex-row align-items-center justify-content-between m-3">
            <div id="logo" class="text-center" style="width: 10%">
                <img src="/assets/img/mamberamo_raya_resize_250_x_251.png" alt="Logo" style="width: 100px; height: auto;">
            </div>
            <div id="kop" class="text-center" style="width: 90%">
                <h2>PEMERINTAH KABUPATEN MAMBERAMO RAYA</h2>
                <h3>{{ $opd->nama_opd }}</h3>
                <h5>Rencana Anggaran Dan Program {{ $header_kop }}</h5>
                <h5>Tahun Anggaran {{ session('tahun') }}</h5>
            </div>
        </div>

        <div id="kop_line">
        </div>

        <div id="isi_berita">
            <p>
                Pada hari ini, {{ now()->isoFormat('dddd') }} Tanggal {{ now()->isoFormat('D') }} ({{ terbilang(now()->isoFormat('D')) }}) Bulan {{ now()->isoFormat('MMMM') }} Tahun {{ now()->isoFormat('YYYY') }}
                telah dilakukan <b><u>Pembahasan Rencangan Anggaran Dan Program (RAP) {{ ucwords(strtolower($opd->nama_opd)) }} Kabupaten Mamberamo Raya Tahun {{ session('tahun') }}</u></b>, dengan menyepakati sebagai berikut :
            </p>
            <p>
                1. Target Aktifitas Utama dalam Program Percepata Pembangunan Papua yang disepakati :
            </p>
            <table class="table table-sm table-bordered table-striped">
                <thead class="table-info">
                    <tr>
                        <th class="text-center" colspan="2">Uraian</th>
                        <th class="text-center">Satuan</th>
                        <th class="text-center">Target Volume</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $program)
                        <tr>
                            <th>Program</th>
                            <th>{{ $program['uraian'] }}</th>
                            <th></th>
                            <th></th>
                        </tr>
                        @foreach ($program['aktifitas'] as $aktifitas)
                            <tr>
                                <th>Aktifitas Utama</th>
                                <th>{{ $aktifitas['uraian'] }}</th>
                                <th></th>
                                <th></th>
                            </tr>
                            @foreach ($aktifitas['target_aktifitas'] as $target_aktifitas)
                                <tr>
                                    <td>Targte Aktifitas Utama</td>
                                    <td>{{ $target_aktifitas['uraian'] }}</td>
                                    <td class="text-center">{{ $target_aktifitas['satuan'] }}</td>
                                    <td class="text-center">{{ formatNumber($target_aktifitas['volume']) }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    @endforeach
                </tbody>
            </table>
            <p>
                2. Rencana Program Dan Pendanaan (RAP) Sub Kegiatan yang disepakati :
            </p>
            <table class="table table-sm table-bordered table-striped" style="font-size: 80%">
                <thead class="table-primary align-middle">
                    <tr>
                        <th>Status</th>
                        <th>Uraian</th>
                        <th>Target</th>
                        <th>Anggaran</th>
                        <th>Detail</th>
                        {{-- <th>Penerima Manfaat</th>
                        <th>Jenis Layanan</th>
                        <th>Jenis Kegiatan</th>
                        <th>Waktu Pelaksanaan</th>
                        <th>PPSB</th>
                        <th>Multiyears</th>
                        <th>Dukungan Dana Lain</th> --}}
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
