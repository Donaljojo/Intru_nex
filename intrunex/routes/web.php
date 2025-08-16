<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return 'Public homepage — no login required';
});


Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', fn () => 'Welcome, Admin!');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])
    ->name('home');
