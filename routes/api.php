<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ServerController;
use App\Http\Controllers\Api\TunnelController;
use App\Http\Controllers\Api\PortManagerController;


Route::prefix('tunnel')->name('api.tunnel.')->group(function () {
    Route::post('/handler/{key}', [PortManagerController::class, 'handler'])->name('handler');
});


Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', UserController::class);
    Route::apiResource('tunnels', TunnelController::class);
    Route::post('tunnels/{tunnel}/close', [TunnelController::class, 'close']);
    Route::apiResource('servers', ServerController::class);
});
