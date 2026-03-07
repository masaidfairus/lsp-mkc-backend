<?php

use App\Http\Controllers\AsesiController;
use App\Http\Controllers\AssessmentRegistrationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Middleware\EnsureApiKeyIsValid;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/verify-email/{id}/{hash}', [AuthController::class, 'verify'])->name('api.verify');

Route::group(['middleware' => EnsureApiKeyIsValid::class], function () {
    Route::post('/verify-email/resend', [AuthController::class, 'resend']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/check-verification', [AuthController::class, 'checkStatus']);

    Route::post('/forgot-password', [ResetPasswordController::class, 'forgotPassword']);
    Route::post('/verify-otp', [ResetPasswordController::class, 'verifyOtp']);
    Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword']);
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/store-profile', [AsesiController::class, 'storeProfile']);
    Route::get('/profile', [AsesiController::class, 'showProfile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return new UserResource($request->user());
    });
    Route::post('/change-password', [ResetPasswordController::class, 'changePassword']);
    Route::post('/apl01/step/{step}', [AssessmentRegistrationController::class, 'store']);
});