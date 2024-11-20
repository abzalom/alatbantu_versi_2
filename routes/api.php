<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiOpdController;
use App\Http\Controllers\Api\ApiRapController;
use App\Http\Controllers\Api\ApiIndikatorOtsus;
use App\Http\Controllers\Api\ApiOpdIndikatorController;
use App\Http\Controllers\Api\ApiTaggingController;
use App\Http\Controllers\Api\Auth\ApiAuthController;
use App\Http\Controllers\Api\Otsus\ApiAlokasiOtsusController;
use App\Http\Controllers\TestController;
use App\Http\Middleware\ApiCustomAuth;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(ApiCustomAuth::class)->controller(ApiAuthController::class)->group(function () {
    Route::post('/auth/password/confirm', 'confirm_password');
});

Route::controller(ApiOpdController::class)->group(function () {
    Route::post('/data/opd', 'api_opd');
    Route::post('/data/opd/subkegiatan', 'api_subkegiatan_opd');
});
Route::controller(ApiAlokasiOtsusController::class)->group(function () {
    Route::post('/data/otsus/alokasi_dana', 'api_alokasi_dana');
    Route::post('/data/otsus/alokasi_dana/insert', 'api_insert_alokasi_dana');
    Route::post('/data/otsus/alokasi_dana/update', 'api_update_alokasi_dana');
    Route::post('/data/otsus/alokasi_dana/delete', 'api_delete_alokasi_dana');
});
Route::controller(ApiIndikatorOtsus::class)->group(function () {
    Route::post('/data/otsus/indikator/tema', 'api_indikator_tema');
    Route::post('/data/otsus/indikator/program', 'api_indikator_program');
    Route::post('/data/otsus/indikator/keluaran', 'api_indikator_keluaran');
    Route::post('/data/otsus/indikator/aktifitas_utama', 'api_indikator_aktifitas_utama');
    Route::post('/data/otsus/indikator/target_aktifitas_utama', 'api_indikator_target_aktifitas_utama');
    Route::post('/data/otsus/indikator/target_aktifitas_utama/volume', 'api_edit_volume_indikator_target_aktifitas_utama');
    Route::post('/data/otsus/indikator/target_aktifitas_utama/volume/reset', 'api_reset_volume_indikator_target_aktifitas_utama');
});

Route::controller(ApiOpdIndikatorController::class)->group(function () {
    Route::post('/data/indikator/opd', 'indikator_opd');
    Route::post('/data/indikator/opd/add', 'indikator_add_opd');
    Route::post('/data/indikator/opd/update', 'indikator_update_opd');
});

Route::controller(ApiRapController::class)->group(function () {
    Route::post('/data/rap', 'rap_opd');
    Route::post('/data/rap/delete/indikator', 'rap_delete_indikator_opd');
    Route::post('/data/rap/update', 'rap_opd_update');
    Route::post('/data/rap/delete', 'rap_opd_delete');
    Route::post('/data/rap/file-check', 'rap_file_check');
});

Route::controller(ApiTaggingController::class)->group(function () {
    Route::post('/data/tagging/indikator/target_aktifitas/rap', 'indikator_target_aktifitas_tag_rap');
});

Route::controller(TestController::class)->group(function () {
    Route::get('/test', 'test')->withoutScopedBindings();
});
