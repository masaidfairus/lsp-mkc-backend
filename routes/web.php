<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/active', function () {
    return view('auth.activation');
})->name('verif');

Route::get('/', function () {
    return view('auth.signin');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);

// Route Grup ini buat semua yang hanya bisa diakses sama asesor maupun admin
Route::middleware(['auth', 'is_admin:admin,asesor'])->group(function () {
    Route::get('/admin', function () {
        return view('welcome'); // Ganti ke admin dashboard
    })->name('admin');

    Route::get('/dashboard', function () {
        return view('pages.dashboard');
    })->name('dashboard');

    Route::get('/logout', [AuthController::class, 'logout']);
});
