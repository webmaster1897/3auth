<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\UserController;

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
    //    Route::get('/admin', fn () => view('User.userpage'))->name('admin');
 //       Route::get('/user/create', [UserController::class, 'create']);

Route::get('/admin',fn () => view('layouts.app'))->name('admin');
Route::get('/users', [UserController::class, 'index']);       // list all users
Route::get('/users/{id}', [UserController::class, 'show']);   // one user (for edit)
Route::post('/users', [UserController::class, 'store']);      // create
Route::put('/users/{id}', [UserController::class, 'update']); // update
Route::delete('/users/{id}', [UserController::class, 'destroy']); // delete
Route::post('/upload-image', [UserController::class, 'upload']); // upload
    });
});