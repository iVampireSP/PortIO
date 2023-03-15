<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ServerController;
use App\Http\Controllers\Api\TunnelController;



Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', UserController::class);
    Route::apiResource('tunnels', TunnelController::class);
    Route::apiResource('servers', ServerController::class);
});
