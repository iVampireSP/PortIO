<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpaController;

// Login
Route::get('/auth/login', [AuthController::class, 'redirect'])->name('login');
Route::get('/auth/callback', [AuthController::class, 'callback'])->name('callback');
Route::get('/auth/logout', [AuthController::class, 'logout'])->name('logout');

// SPA
Route::middleware('auth.session')->get('/{any?}', [HomeController::class, 'index'])->where('any', '.*')->name('index');
