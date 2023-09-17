<?php


use Illuminate\Support\Facades\Route;
use Src\Auth\Presentation\HTTP\AuthController;

Route::group([
    'prefix' => 'auth'
], function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('', [AuthController::class, 'login']);
});
