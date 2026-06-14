<?php

use App\Http\Controllers\Api\ComandoController;
use App\Http\Controllers\Api\DispositivoController;
use Illuminate\Support\Facades\Route;

Route::get('/dispositivos/{direccion_id}', [DispositivoController::class, 'index']);
Route::post('/comandos', [ComandoController::class, 'store']);
