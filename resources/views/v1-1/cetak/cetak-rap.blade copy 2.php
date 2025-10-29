<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    {{-- JANGAN pakai <base> saat Browsershot::html() --}}
    {{-- <base href=""> --}}

    {{-- Bootstrap via inlineCss dari controller --}}
    @isset($inlineCss)
        <style>
            {!! $inlineCss !!}
        </style>
    @endisset

    <title>{{ $app['title'] ?? 'Cetak Laporan' }}</title>

    <style>
        /* ==== Halaman & cetak ==== */
        /* F4 landscape: 330mm x 210mm.
       Ganti ke: 210mm 330mm (F4 portrait) atau 8.5in 14in (Legal). */
        @page {
            size: 330mm 210mm;
            /* <— ubah jika perlu */
            margin: 15mm 10mm 12mm 10mm;
            /* top right bottom left */
        }

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
            font-size: 70%;
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

        /* Paksa tabel patuh terhadap lebar yg kita tentukan */
        table {
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

        #signature {
            text-align: center;
            width: 250px;
            margin-left: auto;
            margin-right: 40px;
            margin-top: 10mm;
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

        <table class="table table-bordered table-striped mt-5">
            <thead class="table-info align-middle">
                <tr>
                    <th>Target Aktifitas Utama</th>
                    <th>Nomenklatur</th>
                    <th>Klasifikasi Belanja</th>
                    <th>Target Kinerja</th>
                    <th class="text-end">Pagu Alokasi</th>
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
                        @php
                            $lokasi_fokus = json_decode($rap->lokus ?? '[]', true) ?: [];
                            $sumberdana_lain = json_decode($rap->dana_lain ?? '[]', true) ?: [];
                        @endphp
                        <tr>
                            <td>{{ $tagging->target_aktifitas->text ?? '-' }}</td>
                            <td>{{ $rap->text_subkegiatan }}</td>
                            <td>{{ $rap->klasifikasi_belanja }}</td>
                            <td>{{ trim(($rap->vol_subkeg ?? '') . ' ' . ($rap->satuan_subkegiatan ?? '')) }}</td>
                            <td class="text-end">{{ formatNumber($rap->anggaran) }}</td>
                            <td>{{ $rap->jenis_kegiatan }}</td>
                            <td>
                                @if (count($lokasi_fokus))
                                    <ul class="m-0" style="padding-left:16px;">
                                        @foreach ($lokasi_fokus as $lokus)
                                            <li>{{ ($lokus['kecamatan'] ?? '-') . ' | ' . ($lokus['kampung'] ?? '-') }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $rap->koordinat ?? '-' }}</td>
                            <td>{{ ($rap->jenis_layanan ?? '') === 'terkait' ? 'Terkait langsung ke masyarakat' : 'Kegiatan Pendukung' }}</td>
                            <td>{{ ($rap->penerima_manfaat ?? '') === 'oap' ? 'Khusus OAP' : 'Umum (OAP & Non-OAP)' }}</td>
                            <td>{{ $rap->ppsb ?? '-' }}</td>
                            <td>
                                @if (count($sumberdana_lain))
                                    <ul class="m-0" style="padding-left:16px;">
                                        @foreach ($sumberdana_lain as $dana)
                                            <li>{{ $dana['uraian'] ?? '-' }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $rap->multiyears ?? '-' }}</td>
                            <td class="text-center">
                                {{ $rap->mulai ?? '-' }} <br> s.d <br> {{ $rap->selesai ?? '-' }}
                            </td>
                            <td>{{ $rap->keterangan ?? '' }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>

        <div id="signature">
            <span>Burmeso, {{ now()->format('d F Y') }}</span><br>
            <span>{{ $opd->kepala_aktif ? strtoupper($opd->kepala_aktif->jabatan->label()) : '' }} {{ strtoupper($opd->nama_opd) }}</span>
            <br><br><br><br><br>
            <span>{{ strtoupper($opd->kepala_aktif->nama ?? '________Nama________') }}</span><br>
            <span>{{ strtoupper($opd->kepala_aktif->nip ?? '_________NIP_________') }}</span><br>
            <span>{{ strtoupper($opd->kepala_aktif->pangkat ?? '_______Pangkat_______') }}</span>
        </div>
    </div>
</body>

</html>
