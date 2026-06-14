<?php

use App\Http\Controllers\Clientes\ClienteController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Direcciones\DireccionController;
use App\Http\Controllers\Dispositivos\DispositivoController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/clientes-redir', fn () => redirect()->route('clientes.index'))->name('clientes.redirect');

Route::resource('clientes', ClienteController::class);

Route::resource('clientes.direcciones', DireccionController::class)
    ->shallow()
    ->except(['index'])
    ->parameters(['direcciones' => 'direccion']);

Route::resource('direcciones.dispositivos', DispositivoController::class)
    ->shallow()
    ->except(['index', 'show'])
    ->parameters(['dispositivos' => 'dispositivo']);
