<?php

use Illuminate\Support\Facades\Route;
use Src\Agenda\Company\Presentation\HTTP\CompanyAddressController;
use Src\Agenda\Company\Presentation\HTTP\CompanyContactController;
use Src\Agenda\Company\Presentation\HTTP\CompanyController;
use Src\Agenda\Company\Presentation\HTTP\CompanyDepartmentController;

Route::group([
    'prefix' => 'company'
], function () {
    Route::get('index', [CompanyController::class, 'index']);
    Route::get('{id}', [CompanyController::class, 'show']);
    Route::post('', [CompanyController::class, 'store']);
    Route::put('{id}', [CompanyController::class, 'update']);
    Route::delete('{id}', [CompanyController::class, 'destroy']);

    Route::post('{id}/address', [CompanyAddressController::class, 'add']);
    Route::put('{id}/address/{address_id}', [CompanyAddressController::class, 'update']);
    Route::delete('{id}/address/{address_id}', [CompanyAddressController::class, 'remove']);

    Route::post('{id}/contact', [CompanyContactController::class, 'add']);
    Route::put('{id}/contact/{contact_id}', [CompanyContactController::class, 'update']);
    Route::delete('{id}/contact/{contact_id}', [CompanyContactController::class, 'remove']);

    Route::post('{id}/department', [CompanyDepartmentController::class, 'add']);
    Route::put('{id}/department/{department_id}', [CompanyDepartmentController::class, 'update']);
    Route::delete('{id}/department/{department_id}', [CompanyDepartmentController::class, 'remove']);
});
