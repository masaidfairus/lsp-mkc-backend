<?php

use App\Http\Controllers\Admin\SchemeController;
use App\Http\Controllers\Asesi\Apl01Controller;
use App\Http\Controllers\Asesi\Apl02Controller;
use App\Http\Controllers\Asesi\PortfolioController;
use App\Http\Controllers\Asesi\ProfileController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\PDF\PdfApl01Controller;
use App\Http\Controllers\PDF\PdfApl02Controller;
use App\Http\Middleware\EnsureApiKeyIsValid;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// EMAIL VERIFICATION
Route::get('/verify-email/{id}/{hash}', [AuthController::class, 'verify'])->name('api.verify');

// PUBLIC ROUTES
Route::middleware(EnsureApiKeyIsValid::class)->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/verify-email/resend', [AuthController::class, 'resend']);
    Route::post('/check-verification', [AuthController::class, 'checkStatus']);

    Route::post('/forgot-password', [ResetPasswordController::class, 'forgotPassword']);
    Route::post('/verify-otp', [ResetPasswordController::class, 'verifyOtp']);
    Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword']);
});

// AUTHENTICATED ROUTES
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn(Request $request) => new UserResource($request->user()));
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/change-password', [ResetPasswordController::class, 'changePassword']);

    // Profil Asesi
    Route::apiResource('profile', ProfileController::class)->only([
        'show',
        'store',
        'update'
    ]);

    // Skema Publik
    route::apiResource('schemes', SchemeController::class)->only([
        'index',
        'show'
    ]);

    // APL-01
    Route::post('apl01/{step}', [Apl01Controller::class, 'store']);
    Route::get('registrations/{registration}/apl01', [Apl01Controller::class, 'show']);

    // APL-02
    Route::get('registrations/{registration}/apl02-form', [Apl02Controller::class, 'form']);
    Route::post('registrations/{registration}/self-assessments', [Apl02Controller::class, 'submit']);

    // Portfolio Asesi
    Route::apiResource('portfolios', PortfolioController::class)->only([
        'index', 'store', 'destroy'
    ]);
});

// PDF Generate
Route::middleware('auth:sanctum')->prefix('pdf')->group(function () {
    Route::post('apl01', [PdfApl01Controller::class, 'generate']);
    Route::post('apl02', [PdfApl02Controller::class, 'generate']);
});