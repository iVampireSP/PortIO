<?php

use App\Http\Controllers\Admin\TunnelController;
use App\Http\Controllers\Admin\IndexController;
use App\Http\Controllers\Admin\ServerController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ClientController;
use Illuminate\Support\Facades\Route;

Route::withoutMiddleware('auth:admin')->group(function() {
    Route::get('/login', [IndexController::class, 'index'])->name('login');
    Route::post('/login', [IndexController::class, 'login']);
});

// Auth group
Route::group(['middleware' => 'auth'], function () {
    Route::get('/', [IndexController::class, 'index'])->name('index');

    Route::resource('users', UserController::class);
    Route::resource('servers', ServerController::class);
    Route::resource('tunnels', TunnelController::class);
    Route::resource('clients', ClientController::class);


    Route::get('/logout', [IndexController::class, 'logout'])->name('logout');
});
