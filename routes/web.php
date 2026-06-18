<?php

use App\Http\Controllers\Addresses\AddressController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Clients\ClientController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Devices\DeviceController;
use Illuminate\Support\Facades\Route;

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('clients', ClientController::class);

    Route::resource('clients.addresses', AddressController::class)
        ->shallow()
        ->except(['index'])
        ->parameters(['addresses' => 'address']);

    Route::resource('addresses.devices', DeviceController::class)
        ->shallow()
        ->except(['index', 'show'])
        ->parameters(['devices' => 'device']);
});
