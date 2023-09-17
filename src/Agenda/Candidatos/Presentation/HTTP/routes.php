<?php

use Illuminate\Support\Facades\Route;
use Src\Agenda\Candidatos\Presentation\HTTP\CandidatosController;

Route::get('leads', [CandidatosController::class, 'index']);
Route::get('lead/{id}', [CandidatosController::class, 'show']);
Route::post('lead', [CandidatosController::class, 'store']);
