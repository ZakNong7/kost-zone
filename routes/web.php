<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\KostController;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth Routes (untuk yang belum login)
Route::middleware('guest:owner')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
});

// Logout Route
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Owner Routes (harus login sebagai owner)
Route::middleware('auth:owner')->prefix('owner')->group(function () {
    Route::get('/dashboard', [OwnerController::class, 'dashboard'])->name('owner.dashboard');
    
    // Kost Management
    Route::post('/kost', [KostController::class, 'store'])->name('kost.store');
    Route::put('/kost/{kost}', [KostController::class, 'update'])->name('kost.update');
    Route::delete('/kost/{kost}', [KostController::class, 'destroy'])->name('kost.destroy');
});