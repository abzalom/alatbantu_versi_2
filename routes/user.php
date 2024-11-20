<?php

use App\Http\Middleware\RoleOnlyUser;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthenticateUser;
use App\Http\Controllers\Users\UserRapController;
use App\Http\Controllers\Otsus\RapOtsusController;

Route::middleware([AuthenticateUser::class, RoleOnlyUser::class])->group(function () {
    Route::controller(UserRapController::class)->group(function () {
        Route::get('/user/rap', 'rap_user');
    });
});
