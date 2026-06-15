<?php

use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\CommandController;
use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\Api\HeatingController;
use Illuminate\Support\Facades\Route;

Route::get('/devices/{address_id}', [DeviceController::class, 'index']);
Route::get('/addresses/{address_id}/state', [AddressController::class, 'state']);
Route::post('/commands', [CommandController::class, 'store']);

Route::post('/heating', [HeatingController::class, 'store']);
Route::get('/heating/{address_id}', [HeatingController::class, 'show']);
Route::patch('/heating/{address_id}/calibrate', [HeatingController::class, 'calibrate']);
