<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;

// Welcome page
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Login routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Logout route
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Registration routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Dashboard (protected)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Redirect authenticated users from welcome to dashboard
Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : view('welcome');
});
