<?php

use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\CommandController;
use App\Http\Controllers\Api\DeviceController;
use Illuminate\Support\Facades\Route;

Route::get('/devices/{address_id}', [DeviceController::class, 'index']);
Route::get('/addresses/{address_id}/state', [AddressController::class, 'state']);
Route::post('/commands', [CommandController::class, 'store']);
