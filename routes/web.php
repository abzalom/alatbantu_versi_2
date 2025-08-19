<?php

// Ubah konfigurasi PHP
@ini_set('upload_max_size', '5M');
@ini_set('post_max_size', '5M');

use App\Http\Controllers\Alatbantu\BantuPaguSkpdController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Cetak\CetakRapController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Otsus\OtsusController;
use App\Http\Controllers\Otsus\RapOtsusController;
use App\Http\Controllers\Config\ConfigAppController;
use App\Http\Controllers\Config\ConfigOpdController;
use App\Http\Controllers\Config\ConfigPaguSkpdController;
use App\Http\Controllers\Config\ScheduleMonevController;
use App\Http\Controllers\Config\ScheduleRapController;
use App\Http\Controllers\Config\SessionController;
use App\Http\Controllers\Config\SinkronDataToLocalController;
use App\Http\Controllers\Data\DataPublishSikdController;
use App\Http\Controllers\djpk\sinkronSikdDjpkController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Laporan\RekapIndikatorOtsusController;
use App\Http\Controllers\Rakortek\RakortekPembahasanRapppController;
use App\Http\Controllers\Rakortek\RakortekPembahasanUrusanController;
use App\Http\Controllers\Rakortek\RakortekRapppController;
use App\Http\Controllers\Rakortek\RakortekUrusanController;
use App\Http\Controllers\Ref\ReferensiDataController;
use App\Http\Controllers\TestController;
use App\Http\Middleware\PrivateRouteMiddleware;
use App\Http\Middleware\ProductionCheck;
use App\Http\Middleware\Web\WebAuthenticateUser;
use App\Http\Middleware\Web\WebRoleMustAdmin;

Route::middleware(PrivateRouteMiddleware::class)->group(function () {
    Route::controller(SessionController::class)->group(function () {
        Route::post('/private/session/set_timezone', 'set_timezone');
    });
});

Route::controller(SessionController::class)->group(function () {
    Route::get('/session/tahun', 'tahun');
    Route::post('/session/tahun', 'set_tahun');
});

Route::controller(AuthController::class)->group(function () {
    Route::get('/auth/login', 'login_auth');
    Route::post('/auth/login', 'process_login_auth');
});

Route::middleware(WebAuthenticateUser::class)->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('/auth/logout', 'logout_auth');
    });

    Route::controller(ReferensiDataController::class)->group(function () {
        Route::get('/ref/bantuan', 'ref_data');
        Route::get('/ref/nomenklatur', 'ref_nomenklatur');
        Route::get('/ref/nomenklatur/cetak', 'ref_cetak_nomenklatur');
        Route::post('/ref/nomenklatur/update/sikd', 'update_nomenklatur_sikd');
    });

    Route::controller(ConfigAppController::class)->group(function () {
        Route::post('/config/app/session/tahun', 'config_app_session_tahun');
    });

    Route::controller(HomeController::class)->group(function () {
        Route::get('/', 'home');
    });

    Route::middleware(WebRoleMustAdmin::class)->group(function () {

        Route::controller(UserController::class)->group(function () {
            Route::get('/config/user', 'user_config');
        });

        Route::controller(ScheduleRapController::class)->group(function () {
            Route::get('/config/schedule/rap', 'schedule_rap_config');
            Route::post('/config/schedule/rap/input_user', 'penginputan_user_rap_schedule');
            Route::post('/config/schedule/rap/new', 'new_rap_schedule');
            Route::post('/config/schedule/rap/update', 'update_rap_schedule');
            Route::post('/config/schedule/rap/lock', 'lock_rap_schedule');
        });

        Route::controller(ScheduleMonevController::class)->group(function () {
            Route::get('/config/schedule/monev', 'schedule_monev_config');
            Route::post('/config/schedule/monev/new', 'new_schedule_monev_config');
            Route::post('/config/schedule/monev/lock', 'lock_schedule_monev_config');
        });

        Route::controller(ConfigPaguSkpdController::class)->group(function () {
            Route::get('/config/pagu', 'config_pagu');
            Route::post('/config/pagu', 'save_config_pagu');
        });

        Route::controller(OtsusController::class)->group(function () {
            Route::get('/otsus', 'otsus');
            Route::get('/otsus/indikator', 'otsus_indikator');
            Route::get('/otsus/alokasi_dana', 'otsus_alokasi_dana');
            Route::post('/otsus/alokasi_dana', 'otsus_alokasi_dana_save');
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
            // Route::post('/sinkron/djpk/sikd/{jenis}/{sumberdana}', 'insert_raquest_sumberdana_sikd_djpk');
            Route::post('/sinkron/djpk/insert/create-link', 'create_link_sikd_djpk');
            Route::post('/sinkron/djpk/insert/update-link', 'update_link_sikd_djpk');
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

        Route::controller(DataPublishSikdController::class)->group(function () {
            Route::get('/data/sikd/rap/{sumberdana}', 'rap_otsus');
            Route::post('/data/sikd/rap/{sumberdana}/destroy', 'destroy_rap_otsus');
        });
    });

    Route::controller(ConfigOpdController::class)->group(function () {
        Route::any('/config', function () {
            return redirect()->to('/config/opd');
        });
        Route::get('/config/opd', 'config_opd');
        Route::post('/config/sinkron/opd-sipd', 'config_sinkron_opd');
    });

    Route::get('/rakortek', function () {
        return redirect()->to('/rakortek/urusan');
    });

    Route::controller(RakortekUrusanController::class)->group(function () {
        Route::get('/rakortek/urusan', 'rakortek_indikator_urusan');
        Route::get('/rakortek/urusan/{opd}', 'opd_rakortek_indikator_urusan');
        Route::post('/rakortek/urusan/{opd}/save/target_daerah', 'opd_save_target_daerah_rakortek_indikator_urusan');
    });

    Route::controller(RakortekPembahasanUrusanController::class)->group(function () {
        Route::get('/pembahasan/rakortek', function () {
            return redirect()->to('/pembahasan/rakortek/urusan');
        });
        Route::get('/pembahasan/rakortek/urusan', 'pembahasan_urusan');
        Route::get('/pembahasan/rakortek/urusan/opd', 'pembahasan_urusan_opd');
        Route::middleware(WebRoleMustAdmin::class)->group(function () {
            Route::post('/pembahasan/rakortek/urusan/opd', 'save_pembahasan_urusan_opd');
            Route::post('/pembahasan/rakortek/urusan/opd/validasi', 'validasi_pembahasan_urusan_opd');
        });
    });

    Route::controller(RakortekRapppController::class)->group(function () {
        Route::get('/rakortek/rappp', 'rakortek_rappp');
        Route::get('/rakortek/rappp/opd', 'opd_rakortek_rappp');
        Route::post('/rakortek/rappp/opd', 'save_opd_rakortek_rappp');
        Route::patch('/rakortek/rappp/opd', 'update_opd_rakortek_rappp');
        Route::delete('/rakortek/rappp/opd', 'delete_opd_rakortek_rappp');
        Route::post('/rakortek/rappp/restore', 'restore_rakortek_rappp');
        Route::post('/rakortek/rappp/destroy', 'destroy_rakortek_rappp');
    });

    Route::controller(RakortekPembahasanRapppController::class)->group(function () {
        Route::get('/pembahasan/rakortek/rappp', 'pembahasan_rappp');
        Route::get('/pembahasan/rakortek/rappp/opd', 'pembahasan_rappp_opd');
        Route::middleware(WebRoleMustAdmin::class)->group(function () {
            Route::post('/pembahasan/rakortek/rappp/opd', 'save_pembahasan_rappp_opd');
            Route::post('/pembahasan/rakortek/rappp/opd/pembahasan/setujui/all', 'save_all_pembahasan_rappp_opd');
            Route::post('/pembahasan/rakortek/rappp/opd/validasi', 'validasi_pembahasan_rappp_opd');
            Route::post('/pembahasan/rakortek/rappp/opd/validasi/all', 'validasi_all_pembahasan_rappp_opd');
        });
    });

    Route::controller(RapOtsusController::class)->group(function () {
        Route::get('/rap/{jenis}', 'rap');
        Route::get('/rap/{jenis}/renja', 'renja_rap');
        Route::get('/rap/{jenis}/renja/{id_opd}/form', 'renja_form_rap');
        Route::post('/rap/{jenis}/renja/{id_opd}/form', 'insert_new_rap');
        Route::post('/rap/{jenis}/renja/{id_opd}/form/update', 'update_rap');
        Route::post('/rap/validasi', 'validasi_rap');
        Route::post('/rap/pembahasan', 'pembahasan_rap');
        Route::post('/rap/restore', 'restore_rap');
        Route::post('/rap/destroy', 'destroy_rap');
    });

    Route::controller(RapOtsusController::class)->group(function () {
        Route::get('/rap/view/file', 'view_file')->name('view.file');
        Route::get('/rap/download/file', 'download_file')->name('download.file');
        Route::post('/rap/opd/upload-data-dukung', 'rap_upload_data_dukung');
        Route::post('/rap/opd/delete-data-dukung', 'rap_delete_data_dukung');

        // Alternative route for view files
        Route::get('/file-rap/uploads/{tahun}/skpd/{skpd}/{file}', 'get_file_rap');
    });

    Route::middleware(ProductionCheck::class)->controller(SinkronDataToLocalController::class)->group(function () {});

    Route::controller(CetakRapController::class)->group(function () {
        Route::get('/cetak/rap', 'cetak_rap');
    });
});

Route::controller(TestController::class)->group(function () {
    // Route::get('/test/{jenis}/renja/{id_opd}/form', 'test_form');
    // Route::post('/test/{jenis}/renja/{id_opd}/form', 'post_test');
    // Route::get('/test', 'preg_testing');
    // Route::get('/error', 'error_test');
    Route::get('/test', 'quick_count_psu_papua');
});
