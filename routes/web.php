<?php

use App\Http\Controllers\Alatbantu\BantuPaguSkpdController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Otsus\OtsusController;
use App\Http\Controllers\Otsus\RapOtsusController;
use App\Http\Controllers\Config\ConfigAppController;
use App\Http\Controllers\Config\ConfigOpdController;
use App\Http\Controllers\djpk\sinkronSikdDjpkController;
use App\Http\Controllers\Laporan\RekapIndikatorOtsusController;
use App\Http\Controllers\Ref\ReferensiDataController;
use App\Http\Controllers\TestController;
use App\Http\Middleware\Web\WebAuthenticateUser;
use App\Http\Middleware\Web\WebRoleMustAdmin;

Route::controller(AuthController::class)->group(function () {
    Route::get('/auth/login', 'login_auth');
    Route::post('/auth/login', 'process_login_auth');
});

Route::middleware(WebAuthenticateUser::class)->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('/auth/logout', 'logout_auth');
    });

    Route::controller(ReferensiDataController::class)->group(function () {
        Route::get('/ref/data', 'ref_data');
        Route::get('/ref/nomenklatur', 'ref_nomenklatur');
        Route::get('/ref/nomenklatur/cetak', 'ref_cetak_nomenklatur');
        Route::post('/ref/nomenklatur/update/sikd', 'update_nomenklatur_sikd');
    });

    Route::controller(ConfigAppController::class)->group(function () {
        Route::post('/config/app/session/tahun', 'config_app_session_tahun');
    });

    Route::middleware(WebRoleMustAdmin::class)->group(function () {

        Route::get('/', function () {
            return view('home', [
                'app' => [
                    'title' => 'RAP OTSUS',
                    'desc' => 'Halaman Home',
                ],
            ]);
        });

        Route::controller(UserController::class)->group(function () {
            Route::get('/config/user', 'user_config');
        });

        Route::controller(OtsusController::class)->group(function () {
            Route::get('/otsus', 'otsus');
            Route::get('/otsus/indikator', 'otsus_indikator');
            Route::get('/otsus/alokasi_dana', 'otsus_alokasi_dana');
            Route::post('/otsus/alokasi_dana', 'otsus_alokasi_dana_save');
        });

        Route::controller(RapOtsusController::class)->group(function () {
            Route::get('/rap', 'rap');
            Route::get('/rap/opd', 'rap_opd');
            Route::get('/rap/indikator', 'rap_indikator');
            Route::post('/rap/indikator/upload', 'rap_upload_indikator_opd');
            Route::get('/rap/opd/form-subkegiatan', 'rap_form');
            Route::post('/rap/opd/form-subkegiatan', 'rap_insert_form');
            Route::post('/rap/opd/upload-subkegiatan', 'rap_upload_subkegiatan');
            Route::post('/rap/opd/upload-data-dukung', 'rap_upload_data_dukung');
        });

        Route::get('/config', function () {
            return redirect()->to('/config/opd');
        });

        Route::controller(ConfigOpdController::class)->group(function () {
            Route::get('/config/opd', 'config_opd');
            Route::post('/config/sinkron/opd-sipd', 'config_sinkron_opd');
        });

        Route::get('/sinkron', function () {
            return redirect()->to('/');
        });

        Route::get('/sinkron/djpk', function () {
            return redirect()->to('/sinkron/djpk/sikd/nomenklatur');
        });

        Route::get('/sinkron/djpk/sikd', function () {
            return redirect()->to('/sinkron/djpk/sikd/nomenklatur');
        });

        Route::controller(sinkronSikdDjpkController::class)->group(function () {
            Route::get('/sinkron/djpk/sikd/{jenis}', 'request_sikd_djpk');
            Route::get('/sinkron/djpk/sikd/{jenis}/{sumberdana}', 'request_sumberdana_sikd_djpk');
            Route::post('/sinkron/djpk/sikd/{jenis}/{sumberdana}', 'insert_raquest_sumberdana_sikd_djpk');
            Route::post('/sinkron/djpk/sikd/request/create-link', 'create_link_sikd_djpk');
            Route::post('/sinkron/djpk/sikd/request/update-link', 'update_link_sikd_djpk');
        });

        Route::get('/portfolio/aboutme', function () {
            return view('Portfolio.dashboard');
        });

        Route::controller(BantuPaguSkpdController::class)->group(function () {
            Route::get('/bantu/pagu', 'pagu_alatbantu');
        });

        Route::controller(RekapIndikatorOtsusController::class)->group(function () {
            Route::get('/rekap/indikator', 'rekap_indikator');
            Route::get('/rekap/indikator/rap', 'rekap_rap_indikator');
        });

        Route::controller(TestController::class)->group(function () {
            Route::get('/test', 'testing');
        });
    });

    Route::controller(RapOtsusController::class)->group(function () {
        Route::get('/rap/view/file', 'view_file')->name('view.file');
        Route::get('/rap/download/file', 'download_file')->name('download.file');
        Route::post('/rap/opd/upload-data-dukung', 'rap_upload_data_dukung');
        Route::post('/rap/opd/delete-data-dukung', 'rap_delete_data_dukung');
    });
});
