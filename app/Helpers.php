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
