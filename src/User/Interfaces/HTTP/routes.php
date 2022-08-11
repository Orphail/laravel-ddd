<?php

use Illuminate\Support\Facades\Route;
use Src\User\Interfaces\HTTP\UserController;

Route::group([
    'prefix' => 'user'
], function () {
    Route::get('index', [UserController::class, 'index']);
    Route::get('random-avatar', [UserController::class, 'getRandomAvatar']);
    Route::get('{id}', [UserController::class, 'show']);
    Route::post('', [UserController::class, 'store']);
    Route::patch('{id}', [UserController::class, 'update']);
    Route::delete('{id}', [UserController::class, 'destroy']);
});
