<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="/assets/img/mamberamo_raya.ico" type="image/x-icon">

    {{-- JANGAN pakai <base> saat Browsershot::html() --}}
    {{-- <base href=""> --}}

    {{-- Bootstrap via inlineCss dari controller --}}
    @isset($inlineCss)
    <style>
        {
            ! ! $inlineCss ! !
        }
    </style>
    @endisset

    <title>{{ $app['title'] ?? 'Cetak Laporan' }}</title>

    <style>
        /* ==== Halaman & cetak ==== */
        /* F4 landscape: 330mm x 210mm.
       Ganti ke: 210mm 330mm (F4 portrait) atau 8.5in 14in (Legal). */
        /* @page { */
        /* size: 330mm 210mm; */
        /* <— ubah jika perlu */
        /* margin: 15mm 10mm 12mm 10mm; */
        /* top right bottom left */
        /* } */

        html,
        body {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            /* jangan padding di body agar tidak kepotong */
            font-family: Arial, sans-serif;
            /* font-size: 70%; */
            line-height: 1.35;
        }

        .content {
            padding: 10mm;
        }

        /* pakai wrapper untuk jarak tepi dalam */

        .line {
            border-top: 2px solid #000;
            margin: 20px 0;
            width: 100%;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            margin-bottom: 6mm;
        }

        .logo {
            flex: 0 0 15%;
            text-align: center;
        }

        .logo img {
            width: 100px;
            height: auto;
        }

        .header-surat {
            flex: 0 0 75%;
        }

        .header-text {
            font-size: 15px;
            font-weight: 600;
            margin: 0;
            padding: 0;
        }

        Paksa tabel patuh terhadap lebar yg kita tentukan table {
            table-layout: fixed;
            /* KUNCI: konten tidak lagi menentukan lebar */
            width: 100%;
        }

        thead {
            display: table-header-group;
        }

        /* header repeat */
        tfoot {
            display: table-footer-group;
        }

        /* kalau nanti perlu footer repeat */
        tr,
        th,
        td {
            page-break-inside: avoid;
        }

        /* Biar teks panjang tidak mendorong kolom lain mengecil */
        th,
        td {
            white-space: normal;
            overflow-wrap: anywhere;
            /* bungkus di mana saja */
            word-break: break-word;
            /* cadangan untuk engine lain */
            hyphens: auto;
            /* optional: pisah kata */
            padding: .4rem .5rem;
        }


        thead th {
            background: #e7f1ff !important;
        }

        /* Tetapkan lebar per-kolom (sesuaikan angka sesuai kebutuhanmu) */
        thead th:nth-child(1),
        tbody td:nth-child(1) {
            width: 12%;
        }

        /* Target Aktifitas Utama */
        thead th:nth-child(2),
        tbody td:nth-child(2) {
            width: 12%;
        }

        /* Nomenklatur */
        thead th:nth-child(3),
        tbody td:nth-child(3) {
            width: 6%;
        }

        /* Klasifikasi Belanja */
        thead th:nth-child(4),
        tbody td:nth-child(4) {
            width: 7%;
        }

        /* Target Kinerja */
        thead th:nth-child(5),
        tbody td:nth-child(5) {
            width: 7%;
        }

        /* Pagu Alokasi */
        thead th:nth-child(6),
        tbody td:nth-child(6) {
            width: 7%;
        }

        /* Jenis Kegiatan */
        thead th:nth-child(7),
        tbody td:nth-child(7) {
            width: 8%;
        }

        /* Lokasi Fokus */
        thead th:nth-child(8),
        tbody td:nth-child(8) {
            width: 8%;
        }

        /* Titik Koordinat */
        thead th:nth-child(9),
        tbody td:nth-child(9) {
            width: 7%;
        }

        /* Jenis Layanan */
        thead th:nth-child(10),
        tbody td:nth-child(10) {
            width: 7%;
        }

        /* Penerima Manfaat */
        thead th:nth-child(11),
        tbody td:nth-child(11) {
            width: 7%;
        }

        /* Program Bersama Provinsi Papua */
        thead th:nth-child(12),
        tbody td:nth-child(12) {
            width: 7%;
        }

        /* Sinergi Dana Lain */
        thead th:nth-child(13),
        tbody td:nth-child(13) {
            width: 6%;
        }

        /* Multiyears */
        thead th:nth-child(14),
        tbody td:nth-child(14) {
            width: 7%;
        }

        /* Jadwal Pelaksanaan */
        thead th:nth-child(15),
        tbody td:nth-child(15) {
            width: 18%;
        }

        /* Keterangan (dibatasi) */

        .text-end {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* Kolom-kolom yang berisi teks panjang agar tidak meledak layout */
        td,
        th {
            word-break: break-word;
        }
    </style>
</head>

<body>
    @php
    $logoPath = public_path('assets/img/mamberamo_raya_resize_250_x_251.png');
    $logoSrc = file_exists($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : asset('assets/img/mamberamo_raya_resize_250_x_251.png'); // fallback http
    @endphp

    <div class="content">
        <div class="header">
            <div class="logo">
                <img src="{{ $logoSrc }}" alt="Kabupaten Mamberamo Raya" class="img-fluid">
            </div>
            <div class="header-surat text-center">
                <div class="header-text">RENCANA ANGGARAN DAN PROGRAM PENGGUNAAN</div>
                <div class="header-text">{{ $header_sumberdana }}</div>
                <div class="header-text">{{ $opd->nama_opd }}</div>
                <div class="header-text">KABUPATEN MAMBERAMO RAYA TAHUN ANGGARAN {{ date('Y') }}</div>
            </div>
        </div>

        <div class="line"></div>

        <p style="text-indent: 0.68cm; text-align: justify;">
            Pada hari ini, {{ now()->format('d') }} bulan {{ now()->format('F') }} tahun {{ now()->format('Y') }}, telah diselenggarakan pembahasan Rencana Anggaran dan Program Penggunaan <b>{{ ucwords(strtolower($header_sumberdana)) }}</b> pada <b>{{ ucwords(strtolower($opd->nama_opd)) }}</b> Kabupaten Mamberamo Raya Tahun Anggaran {{ date('Y') }}.
        </p>
        <p style="text-indent: 0; text-align: justify;">
            pembahasan dilaksanakan bersama-sama dengan hasil kriteria penilaian sebagai berikut:
        <ol>
            <li>Duplikasi pendanaan RAP yang bersumber dari Dana Otonomi Khusus 1% (satu persen), Dana Otonomi Khusus 1,25% (satu koma dua lima persen), dan DTI;</li>
            <li>Sinergi Kegiatan RAP Dana Otonomi Khusus 1% (satu persen), Dana Otonomi Khusus 1,25% (satu koma dua lima persen), dan DTI;</li>
            <li>Penyusunan RAP telah mempertimbangkan hasil pemantauan dan evaluasi TKD untuk penerimaan dalam rangka Otonomi Khusus;</li>
            <li>Kesesuaian penggunaan Dana dalam rangka Otonomi Khusus dengan ketentuan perundang-undangan;</li>
            <li>Kesesuaian RAP dengan kewenangan provinsi/kabupaten/kota sesuai ketentuan perundang-undangan;</li>
            <li>Kesesuaian RAP dengan RIPPP, RAPPP, dan RPJMN dengan memperhatikan hasil Musrenbang Otsus;</li>
            <li>Kesesuaian RAP dengan ketentuan Penggunaan, target Keluaran, dan Hasil;</li>
            <li>Kewajaran unit cost/volume/satuan Keluaran;</li>
            <li>Duplikasi RAP yang bersumber dari Dana Otonomi Khusus 1,25% (satu koma dua lima persen), dan DTI serta dana lainnya (DAK Fisik, DAK NonFisik, hibah ke Daerah, dan/atau Belanja Kementerian/Lembaga); dan</li>
            <li>Kesesuaian RAP dengan Kegiatan yang tidak dapat dibiayai oleh TKD untuk penerimaan dalam rangka Otonomi Khusus.</li>
        </ol>
        </p>
        <p>
            Berdasarkan hasil pembahasan tersebut di atas, maka disepakati :
        </p>
        A. Rencana Pendanaan yang diusulkan dalam RAP yang bersumber dari {{ ucwords(strtolower($header_sumberdana)) }} adalah sebesar Rp. {{ number_format($opd->pagu_usulan, 2, ',', '.') }} ( {{ ucwords(terbilang($opd->pagu_usulan ?? 0)) }}).
        <p></p>
        B. Rincian Klasifikasi Belanja RAP yang bersumber dari {{ ucwords(strtolower($header_sumberdana)) }} adalah sebagai berikut:
        <ul>
            @foreach ($opd->klasfikasi_belanja as $itemKlasBelanja)
            <li>{{ $itemKlasBelanja['nama'] }} Rp. {{ number_format($itemKlasBelanja['total'], 2, ',', '.') }}</li>
            @endforeach
        </ul>
        <p></p>
        C. Evaluasi Penilaian Program Prioritas Pembangunan Sebagaimana tertuang di dalam Rencana Aksi Perceptan Pembangunan Papua dan Papua Barat (RAPPP)
        <p></p>
        <table class="table table-bordered table-striped" style="font-size: 80%;">
            <thead>
                <tr>
                    <th class="text-center">Uraian</th>
                    <th class="text-center">Tema/ Program/ Keluaran/ Aktifitas Utama/ Target Aktifitas</th>
                    <th class="text-center">Volume/ Satuan</th>
                    <th class="text-center">Hasil Pembahasan</th>
                    <th class="text-center">Catatan Pembahasan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tema_program as $tema)
                <tr>
                    <th>Tema Pembangunan</th>
                    <th>{{ $tema['text'] }}</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                @foreach ($tema['program'] as $program)
                <tr>
                    <th>Program Prioritas</th>
                    <th>{{ $program['text'] }}</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                @foreach ($program['aktifitas'] as $aktifitas)
                <tr>
                    <th>Aktifitas Utama</th>
                    <th>{{ $aktifitas['text'] }}</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                @foreach ($aktifitas['target_aktifitas'] as $target_aktifitas)
                <tr>
                    <td>Target Aktifitas Utama</td>
                    <td>{{ $target_aktifitas['text'] }}</td>
                    <td>{{ $target_aktifitas['target'] }}</td>
                    <td>{{ $target_aktifitas['pembahasan'] }}</td>
                    <td>{{ $target_aktifitas['catatan'] }}</td>
                </tr>
                @endforeach
                @endforeach
                @endforeach
                @endforeach
            </tbody>
        </table>

        <div class="text-center d-flex flex-column mb-4">
            <span>Burmeso, {{ now()->format('d F Y') }}</span>
            <span>Mengetahui,</span>
        </div>

        <div class="d-flex align-items-stretch justify-content-between" style="margin-bottom: 50px">
            <div class=" d-flex flex-column align-items-center justify-content-between" style="height: 180px">
                <span>KETUA TIM PEMBAHAS</span>

                <div class="d-flex flex-column align-items-center">
                    <span>{{ $ketua_tim_pembahas ? strtoupper($ketua_tim_pembahas->nama) : '________Nama________' }}</span>
                    <span>{{ $ketua_tim_pembahas ? 'NIP. ' . strtoupper(formatNip($ketua_tim_pembahas->nip)) : '_________NIP_________' }}</span>
                    <span>{{ $ketua_tim_pembahas ? strtoupper($ketua_tim_pembahas->pangkat) : '_______Pangkat_______' }}</span>
                </div>
            </div>
            <div class="d-flex flex-column align-items-center justify-content-between">
                <span>{{ $opd->kepala_aktif ? strtoupper($opd->kepala_aktif->jabatan->label()) : '' }} {{ strtoupper($opd->nama_opd) }}</span>

                <div class="d-flex flex-column align-items-center">
                    <span>{{ $opd->kepala_aktif ? strtoupper($opd->kepala_aktif->nama) : '________Nama________' }}</span>
                    <span>{{ $opd->kepala_aktif ? strtoupper('NIP. ' . formatNip($opd->kepala_aktif->nip)) : '_________NIP_________' }}</span>
                    <span>{{ $opd->kepala_aktif ? strtoupper($opd->kepala_aktif->pangkat) : '_______Pangkat_______' }}</span>
                </div>
            </div>
        </div>

        <table class="table table-bordered">
            <thead class="text-center align-middle">
                <tr>
                    <th colspan="3">Tim Bappeda</th>
                    <th colspan="3">Tim Perangkat Daerah</th>
                </tr>
                <tr>
                    <th>No.</th>
                    <th>Nama/ NIP</th>
                    <th>TTD</th>
                    <th>No.</th>
                    <th>Nama/ NIP</th>
                    <th>TTD</th>
                </tr>
            </thead>
        </table>
    </div>
</body>

</html>