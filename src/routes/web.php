<?php

use PavelVasilyev\AuthAjax\Controllers\Auth\AjaxAuthSessionController;
use PavelVasilyev\AuthAjax\Controllers\Auth\AjaxRegisterController;
use PavelVasilyev\AuthAjax\Controllers\Auth\AjaxVerifyEmailController;
use PavelVasilyev\AuthAjax\Controllers\Auth\AjaxPasswordResetController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web']], function () {
    Route::get('/', [\App\Http\Controllers\HomeController::class, 'show'])->name('home');

    Route::post('/auth', [AjaxAuthSessionController::class,'handle']);
    Route::get('/login', function(){
        return redirect(route('home'));
    })->name('login');

    Route::middleware('guest')->group(function () {
        Route::post('/reg', [AjaxRegisterController::class,'handle']);
        Route::get('/verify/{id}/{token}', [AjaxVerifyEmailController::class, 'verify']);
        Route::get('/reset-password/{token}', [AjaxPasswordResetController::class, 'create'])->name('password.reset');
    });
});



Route::middleware('auth.ajax')->group(function () { // маршруты закрытых страниц:
    //
});
