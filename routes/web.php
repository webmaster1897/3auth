<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

Route::get('/', fn () => redirect()->route('login'));

// Only guests (not logged in) can see these
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Only logged-in users can see these (authentication)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Only logged-in users with role=admin can see these (authorization)
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin', fn () => view('admin'))->name('admin');
    });
});