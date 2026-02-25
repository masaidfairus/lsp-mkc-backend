<?php

use App\Http\Controllers\AuthController;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
// Route::apiResource('users', UserController::class);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return new UserResource($request->user());
});

Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1');
Route::post('/verify-email/resend', [AuthController::class, 'resend'])->middleware('throttle:5,1');
Route::get('/verify-email/{id}/{hash}', [AuthController::class, 'verify'])->name('api.verify');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');