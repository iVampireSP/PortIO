<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ServerController;
use App\Http\Controllers\Api\TunnelController;
use App\Http\Controllers\Api\PortManagerController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\TrafficController;
use App\Http\Controllers\Application\UserController as ApplicationUserController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Review\ReviewController;
use App\Http\Controllers\Api\TrafficActivateCodeController;

Route::prefix('tunnel')->name('api.tunnel.')->group(function () {
    Route::post('/handler/{key}', [PortManagerController::class, 'handler'])->name('handler');
});

Route::prefix('review')->group(function () {
    Route::resource('/tunnels', ReviewController::class)->only([
        'index', 'update', 'destroy'
    ]);
});


Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [UserController::class, 'user']);
    Route::apiResource('tunnels', TunnelController::class);
    Route::post('tunnels/{tunnel}/close', [TunnelController::class, 'close']);
    Route::apiResource('servers', ServerController::class);

    Route::apiResource('clients', ClientController::class);
    Route::post('codes/use', [TrafficActivateCodeController::class, 'useActivateCode'])->name('codes.useActivateCode');

    Route::get('traffic', [TrafficController::class, 'free']);
    Route::post('traffic', [TrafficController::class, 'sign']);

    Route::get('price', [TrafficController::class, 'price']);
    Route::get('providers', [TrafficController::class, 'providers']);
    Route::get('providers/{provider}/payments', [TrafficController::class, 'payments']);
    Route::post('providers/{provider}/charge', [TrafficController::class, 'charge']);
    Route::post('providers/{provider}/ticket', [TicketController::class, 'submit']);


    Route::post('tokens', [UserController::class, 'create']);
    Route::delete('tokens', [UserController::class, 'deleteAll']);

});

Route::prefix('application')->name('application.')->middleware('whmcs_api')->group(function () {
    Route::post('users/{user:email}/traffic', [ApplicationUserController::class, 'addTraffic']);
});
