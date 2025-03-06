<?php
if (!function_exists('formatIdr')) {
    function formatIdr($number, $decimals = 2)
    {
        return number_format($number, $decimals, ',', '.');
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
        return $number;
    }

    if (!function_exists('tahun')) {
        function tahun()
        {
            return session()->get('tahun');
        }
    }
}
