<?php

use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\BlindController;
use App\Http\Controllers\Api\CommandController;
use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\Api\GasController;
use App\Http\Controllers\Api\HeatingController;
use App\Http\Controllers\Api\OutletController;
use Illuminate\Support\Facades\Route;

Route::get('/devices/{address_id}', [DeviceController::class, 'index']);
Route::get('/addresses/{address_id}/state', [AddressController::class, 'state']);
Route::post('/commands', [CommandController::class, 'store']);

Route::post('/heating', [HeatingController::class, 'store']);
Route::get('/heating/{address_id}', [HeatingController::class, 'show']);
Route::patch('/heating/{address_id}/calibrate', [HeatingController::class, 'calibrate']);

Route::post('/blinds', [BlindController::class, 'store']);
Route::patch('/blinds/{address_id}/open-all', [BlindController::class, 'openAll']);

Route::post('/outlets', [OutletController::class, 'store']);
Route::get('/outlets/{address_id}', [OutletController::class, 'index']);

Route::post('/gas', [GasController::class, 'store']);
Route::get('/gas/{address_id}', [GasController::class, 'index']);
Route::post('/gas/{address_id}/close-valve', [GasController::class, 'closeValve']);
