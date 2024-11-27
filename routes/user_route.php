<?php

use App\Http\Middleware\RoleOnlyUser;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthenticateUser;
use App\Http\Controllers\Users\UserRapController;
use App\Http\Controllers\Otsus\RapOtsusController;
use App\Http\Middleware\Web\WebAuthenticateUser;
use App\Http\Middleware\Web\WebRoleOnlyUser;

Route::middleware([WebAuthenticateUser::class, WebRoleOnlyUser::class])->group(function () {
    Route::get('/user/rap/', function () {
        return redirect()->to('/user/rap/skpd');
    });
    Route::controller(UserRapController::class)->group(function () {
        Route::get('/user/rap/skpd', 'skpd_user');
        Route::get('/user/rap/skpd/{id}', 'rap_user');
        // Route::post('/user/rap/update', 'rap_update_user');
    });
});
