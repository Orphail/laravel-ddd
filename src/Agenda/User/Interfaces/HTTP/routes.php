<?php

use Illuminate\Support\Facades\Route;
use Src\Agenda\User\Interfaces\HTTP\GetRandomAvatarController;
use Src\Agenda\User\Interfaces\HTTP\UserController;

Route::group([
    'prefix' => 'user'
], function () {
    Route::get('random-avatar', GetRandomAvatarController::class);

    Route::get('index', [UserController::class, 'index']);
    Route::get('{id}', [UserController::class, 'show']);
    Route::post('', [UserController::class, 'store']);
    Route::put('{id}', [UserController::class, 'update']);
    Route::delete('{id}', [UserController::class, 'destroy']);
});
