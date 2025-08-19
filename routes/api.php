<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiOpdController;
use App\Http\Controllers\Api\ApiRapController;
use App\Http\Controllers\Api\ApiIndikatorOtsus;
use App\Http\Controllers\Api\ApiOpdIndikatorController;
use App\Http\Controllers\Api\ApiScheduleController;
use App\Http\Controllers\Api\ApiTaggingController;
use App\Http\Controllers\Api\ApiTestController;
use App\Http\Controllers\Api\Auth\ApiAuthController;
use App\Http\Controllers\Api\Data\ApiUsersController;
use App\Http\Controllers\Api\Data\Sinkron\ApiSinkronDjpkSikdController;
use App\Http\Controllers\Api\Otsus\ApiAlokasiOtsusController;
use App\Http\Controllers\Api\User\ApiUserRapController;
use App\Http\Controllers\TestController;
use App\Http\Middleware\Api\ApiAdminOnly;
use App\Http\Middleware\Api\ApiAuthToken;
use App\Http\Middleware\Api\ApiConfirmPassword;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::middleware(ApiAuthToken::class)->group(function () {
    Route::middleware(ApiConfirmPassword::class)->group(function () {
        Route::controller(ApiAuthController::class)->group(function () {
            Route::post('/auth/password/confirm', 'confirm_password');
        });
    });

    Route::controller(ApiAdminOnly::class)->group(function () {
        Route::controller(ApiOpdController::class)->group(function () {
            Route::post('/data/opd', 'api_opd');
            Route::post('/data/opd/subkegiatan', 'api_subkegiatan_opd');
            Route::post('/save/kepala_opd/save', 'api_save_kepala_opd');
            Route::post('/update/kepala_opd/status', 'api_update_kepala_opd_status');
        });
        Route::controller(ApiUsersController::class)->group(function () {
            Route::post('/data/user', 'get_data_user');
            Route::post('/data/user/create', 'create_data_user');
            Route::post('/data/user/update', 'update_data_user');
            Route::post('/data/user/reset-password', 'reset_password_user');
            Route::post('/data/user/lock-user', 'lock_user');
            Route::post('/data/user/unlock-user', 'unlock_user');
            Route::post('/data/user/skpd', 'skpd_user');
            Route::post('/data/user/tagging-skpd', 'tagging_skpd_user');
            Route::post('/data/user/remove-skpd', 'remove_skpd_user');
        });

        Route::controller(ApiAlokasiOtsusController::class)->group(function () {
            Route::post('/data/otsus/alokasi_dana', 'api_alokasi_dana');
            Route::post('/data/otsus/alokasi_dana/insert', 'api_insert_alokasi_dana');
            Route::post('/data/otsus/alokasi_dana/update', 'api_update_alokasi_dana');
            Route::post('/data/otsus/alokasi_dana/delete', 'api_delete_alokasi_dana');
        });

        Route::controller(ApiIndikatorOtsus::class)->group(function () {
            Route::post('/data/otsus/indikator/target_aktifitas_utama/volume', 'api_edit_volume_indikator_target_aktifitas_utama');
            Route::post('/data/otsus/indikator/target_aktifitas_utama/volume/reset', 'api_reset_volume_indikator_target_aktifitas_utama');
        });

        Route::controller(ApiOpdIndikatorController::class)->group(function () {
            Route::post('/data/indikator/opd', 'indikator_opd');
            Route::post('/data/indikator/opd/add', 'indikator_add_opd');
            Route::post('/data/indikator/opd/update', 'indikator_update_opd');
        });

        Route::controller(ApiRapController::class)->group(function () {
            Route::post('/data/rap/create/{jenis}/renja', 'create_renja_rap');
            Route::post('/data/rap/delete/indikator', 'rap_delete_indikator_opd');
            Route::post('/data/rap/update', 'rap_opd_update');
            Route::post('/data/rap/delete', 'rap_opd_delete');
        });

        Route::controller(ApiTaggingController::class)->group(function () {
            Route::post('/data/tagging/indikator/target_aktifitas/rap', 'indikator_target_aktifitas_tag_rap');
            Route::post('/data/tagging/opd/otsus/new', 'new_tagging_opd_otsus');
            Route::post('/data/tagging/opd/otsus/delete', 'delete_tagging_opd_otsus');
            Route::post('/data/tagging/target/opd_list', 'list_opd_target');
        });

        Route::controller(ApiSinkronDjpkSikdController::class)->group(function () {
            Route::post('/data/sinkron/djpk/sikd', 'sinkron_data_djpk_sikd');
            Route::post('/data/sinkron/djpk/sikd/create', 'create_data_djpk_sikd');
        });
        Route::controller(ApiScheduleController::class)->group(function () {
            Route::post('/schedule/get_schedule', 'get_schedule');
            Route::post('/schedule/get/active', 'get_schedule_active');
        });
    });

    Route::controller(ApiUserRapController::class)->group(function () {
        Route::post('/user/rap/update', 'user_update_rap');
    });

    Route::controller(ApiTestController::class)->group(function () {
        Route::post('/user/test/token', 'user_update_rap');
    });

    Route::controller(ApiIndikatorOtsus::class)->group(function () {
        Route::post('/data/otsus/indikator/tema', 'api_indikator_tema');
        Route::post('/data/otsus/indikator/program', 'api_indikator_program');
        Route::post('/data/otsus/indikator/keluaran', 'api_indikator_keluaran');
        Route::post('/data/otsus/indikator/aktifitas_utama', 'api_indikator_aktifitas_utama');
        Route::post('/data/otsus/indikator/target_aktifitas_utama', 'api_indikator_target_aktifitas_utama');
    });

    Route::controller(ApiRapController::class)->group(function () {
        Route::post('/data/rap', 'rap_opd');
        Route::post('/data/rap/file-check', 'rap_file_check');
        Route::post('/data/rap_by_target_aktifitas', 'rap_by_target_aktifitas');
    });
});
