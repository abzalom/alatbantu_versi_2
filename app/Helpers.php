<?php

use App\Models\Config\Schedule;
use App\Models\Config\ScheduleMonev;
use Illuminate\Support\Facades\DB;

if (!function_exists('formatIdr')) {
    function formatIdr($number, $decimals = 2)
    {
        return number_format($number, $decimals, ',', '.');
    }
}

if (!function_exists('formatNumber')) {
    function formatNumber($number, int $decimals = 2): string
    {
        // Kosong/null → kembalikan string kosong (jangan format)
        if ($number === null || $number === '') {
            return '';
        }

        // Normalisasi jika string (hapus 'Rp', spasi, titik ribuan, set desimal ke '.')
        if (is_string($number)) {
            $n = trim($number);
            if ($n === '') return '';
            $n = preg_replace('/[^0-9,.\-]/', '', $n); // sisakan digit, koma, titik, minus

            if (strpos($n, ',') !== false && strpos($n, '.') !== false) {
                // asumsikan: titik = ribuan, koma = desimal (format Indonesia)
                $n = str_replace('.', '', $n);
                $n = str_replace(',', '.', $n);
            } elseif (strpos($n, ',') !== false) {
                // hanya koma → anggap sebagai desimal
                $n = str_replace(',', '.', $n);
            }

            if (!is_numeric($n)) return '';
            $number = (float) $n;
        }

        // Di sini pasti angka
        $isInteger = fmod((float)$number, 1.0) == 0.0;

        return number_format((float)$number, $isInteger ? 0 : $decimals, ',', '.');
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

if (!function_exists('jadwal_rap')) {
    function jadwal_rap()
    {
        $jadwal_rap = Schedule::where('status', true)->first();
        return $jadwal_rap ? $jadwal_rap : null;
    }
}

if (!function_exists('jadwal_rap')) {
    function jadwal_rap()
    {
        $jadwal_rap = Schedule::where('status', true)->first();
        return $jadwal_rap ? $jadwal_rap : null;
    }
}

if (!function_exists('input_rap')) {
    function input_rap()
    {
        $jadwal_monev = jadwal_monev();
        $jadwal_rap = jadwal_rap();
        return !$jadwal_monev && $jadwal_rap && $jadwal_rap->tahapan !== 'rakortek' &&  $jadwal_rap->aktif && $jadwal_rap->penginputan;
    }
}

if (!function_exists('jadwal_monev')) {
    function jadwal_monev()
    {
        $jadwal_monev = ScheduleMonev::where('status', true)->first();
        return $jadwal_monev ? $jadwal_monev : null;
    }
}

if (!function_exists('formatNip')) {
    function formatNip($nip)
    {
        return preg_replace("/(\d{8})(\d{6})(\d{1})/", "$1 $2 $3 ", $nip);
    }
}
