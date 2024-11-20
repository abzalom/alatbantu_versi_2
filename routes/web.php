<?php

use App\Http\Controllers\Alatbantu\BantuPaguSkpdController;
use App\Http\Controllers\Api\ApiRequestController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Otsus\OtsusController;
use App\Http\Controllers\Otsus\RapOtsusController;
use App\Http\Controllers\Config\ConfigAppController;
use App\Http\Controllers\Config\ConfigOpdController;
use App\Http\Controllers\Ref\ReferensiDataController;
use App\Http\Controllers\Referensi\RefLokasiController;
use App\Http\Controllers\TestController;
use App\Http\Middleware\AuthenticateUser;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\RoleMustAdmin;

Route::controller(AuthController::class)->group(function () {
    Route::get('/auth/login', 'login_auth');
    Route::post('/auth/login', 'process_login_auth');
});

Route::middleware(AuthenticateUser::class)->group(function () {
    Route::get('/', function () {
        return view('home', [
            'app' => [
                'title' => 'RAP OTSUS',
                'desc' => 'Halaman Home',
            ],
        ]);
    });


    Route::controller(AuthController::class)->group(function () {
        Route::post('/auth/logout', 'logout_auth');
    });

    Route::controller(ReferensiDataController::class)->group(function () {
        Route::get('/ref/data', 'ref_data');
    });

    Route::controller(ConfigAppController::class)->group(function () {
        Route::post('/config/app/session/tahun', 'config_app_session_tahun');
    });

    Route::middleware(RoleMustAdmin::class)->group(function () {
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

        Route::get('/portfolio/aboutme', function () {
            return view('Portfolio.dashboard');
        });

        Route::controller(BantuPaguSkpdController::class)->group(function () {
            Route::get('/bantu/pagu', 'pagu_alatbantu');
        });

        Route::controller(TestController::class)->group(function () {
            Route::get('/test', 'test');
        });
    });

    Route::controller(RapOtsusController::class)->group(function () {
        Route::get('/rap/download/file', 'download_file')->name('download.file');
        Route::post('/rap/opd/upload-data-dukung', 'rap_upload_data_dukung');
        Route::post('/rap/opd/delete-data-dukung', 'rap_delete_data_dukung');
    });
});
