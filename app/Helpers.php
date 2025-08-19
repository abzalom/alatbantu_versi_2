<?php
if (!function_exists('formatIdr')) {
    function formatIdr($number, $decimals = 2)
    {
        return number_format($number, $decimals, ',', '.');
    }
}

if (!function_exists('formatNumber')) {
    function formatNumber($number, $decimals = 2)
    {
        // Cek apakah nilai desimalnya semuanya nol
        if (fmod($number, 1) == 0) {
            // Bilangan bulat, format tanpa desimal
            return number_format($number, 0, ',', '.');
        } else {
            // Ada desimal signifikan, tampilkan desimal
            return number_format($number, $decimals, ',', '.');
        }
    }
}

if (!function_exists('clearFloatFormat')) {
    function clearFloatFormat($input)
    {
        $number = $input;
        if (!is_numeric($input)) {
            $cleaned = str_replace('.', '', $input);
            // Ganti koma (,) dengan titik (.) untuk pemisahan desimal
            $cleaned = str_replace(',', '.', $cleaned);
            $number = (float) $cleaned;
        } else {
            $number = (float) $input;
        }
        return $number == 0.0 ? null : $number;
    }
}

if (!function_exists('tahun')) {
    function tahun()
    {
        return session()->get('tahun');
    }
}

if (!function_exists('showError')) {
    function showError($header = null, $message = null, $backUrl = null, $backText = 'Kembali')
    {
        return view('v1-1.show-error', [
            'app' => [
                'title' => 'Error',
                'desc' => 'Terjadi kesalahan',
            ],
            'header' => $header ? $header : 'Error',
            'data' => [
                'message' => $message ? $message : 'Terjadi kesalahan yang tidak diketahui.',
                'backUrl' => $backUrl ? $backUrl : url()->previous(),
                'backText' => $backText,
            ],
        ]);
    }
}


if (!function_exists('terbilang')) {
    function terbilang($nilai)
    {
        $huruf = [
            '',
            'satu',
            'dua',
            'tiga',
            'empat',
            'lima',
            'enam',
            'tujuh',
            'delapan',
            'sembilan',
            'sepuluh',
            'sebelas',
        ];
        $terbilang = '';
        // $nilai = 20;
        if ($nilai < 12) {
            $terbilang = $huruf[$nilai];
        } else if ($nilai < 20) {
            $terbilang = terbilang($nilai - 10) . ' belas';
        } else if ($nilai < 100) {
            $terbilang = terbilang($nilai / 10) . ' puluh ' . terbilang($nilai % 10);
        } else if ($nilai < 200) {
            $terbilang = 'seratus ' . terbilang($nilai - 100);
        } else if ($nilai < 1000) {
            $terbilang = terbilang($nilai / 100) . ' ratus ' . terbilang($nilai % 100);
        } else if ($nilai < 2000) {
            $terbilang = 'seribu ' . terbilang($nilai - 1000);
        } else if ($nilai < 1000000) {
            $terbilang = terbilang($nilai / 1000) . ' ribu ' . terbilang($nilai % 1000);
        } else if ($nilai < 1000000000) {
            $terbilang = terbilang($nilai / 1000000) . ' juta ' . terbilang($nilai % 1000000);
        } else if ($nilai < 1000000000000) {
            $terbilang = terbilang($nilai / 1000000000) . ' milyar ' . terbilang($nilai % 1000000000);
        } else if ($nilai < 1000000000000000) {
            $terbilang = terbilang($nilai / 1000000000000) . ' triliun ' . terbilang($nilai % 1000000000000);
        }
        return $terbilang;
    }
}
