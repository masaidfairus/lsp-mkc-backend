<?php

use App\Http\Controllers\Asesi\Ak01Controller;
use App\Http\Controllers\Asesi\Ak04Controller;
use App\Http\Controllers\Asesi\Ak07Controller;
use App\Http\Controllers\Asesi\SchemeController;
use App\Http\Controllers\Asesi\Apl01Controller;
use App\Http\Controllers\Asesi\Apl02Controller;
use App\Http\Controllers\Asesi\ProfileController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Asesi\BankAccountController;
use App\Http\Controllers\Asesi\DocumentProfileController;
use App\Http\Controllers\Asesi\PaymentController;
use App\Http\Controllers\Asesi\RegistrationController;
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
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('verify-email/resend', [AuthController::class, 'resend']);
    Route::post('check-verification', [AuthController::class, 'checkStatus']);
    Route::post('forgot-password', [ResetPasswordController::class, 'forgotPassword']);
    Route::post('verify-otp', [ResetPasswordController::class, 'verifyOtp']);
    Route::post('reset-password', [ResetPasswordController::class, 'resetPassword']);

    Route::get('bank-accounts', [BankAccountController::class, 'index']);
});

// AUTHENTICATED ROUTES
Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', fn(Request $request) => new UserResource($request->user()));
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('change-password', [ResetPasswordController::class, 'changePassword']);

    // Ambil semua data pendaftaran
    Route::get('registrations', [RegistrationController::class, 'index']);

    // Invoice
    Route::get('registrations/{registration}/invoice', [PaymentController::class, 'show']);

    // Profil Asesi
    Route::apiResource('profile', ProfileController::class)->only([
        'index',
        'store',
        'update'
    ]);

    // Skema Publik
    Route::apiResource('schemes', SchemeController::class)->only([
        'index',
        'show'
    ]);

    // Ambil Dokumen per ID Skema
    Route::get('schemes/{scheme}/basic-requirements', [DocumentProfileController::class, 'showBasicRequirementBySchemeId']);
    Route::get('schemes/{scheme}/portfolios', [DocumentProfileController::class, 'showPortfolioBySchemeId']);

    // Upload File Asesi
    Route::apiResource('document-profiles', DocumentProfileController::class)->only([
        'index',
        'store',
        'update'
    ]);

    // Show All Document List
    Route::get('document-lists', [DocumentProfileController::class, 'showDocumentLists']);

    // APL-01
    Route::get('registrations/{registration}/apl01', [Apl01Controller::class, 'show']);
    Route::post('apl01/{step}', [Apl01Controller::class, 'store']);

    // APL-02
    Route::get('registrations/{registration}/apl02', [Apl02Controller::class, 'form']);
    Route::post('registrations/{registration}/apl02', [Apl02Controller::class, 'submit']);
    Route::patch('registrations/{registration}/apl02', [Apl02Controller::class, 'toogleActive']);

    // AK-01
    Route::get('registrations/{registration}/ak01', [Ak01Controller::class, 'show']);
    Route::post('registrations/{registration}/ak01', [Ak01Controller::class, 'store']);

    // AK-04
    Route::get('registrations/{registration}/ak04', [Ak04Controller::class, 'show']);
    Route::post('registrations/{registration}/ak04', [Ak04Controller::class, 'store']);

    // AK-07
    Route::get('registrations/{registration}/ak07', [Ak07Controller::class, 'show']);
    Route::post('registrations/{registration}/ak07', [Ak07Controller::class, 'store']);

    // PDF Generate
    Route::get('apl01/{registration}/pdf', [PdfApl01Controller::class, 'show']);
    Route::prefix('pdf')->group(function () {
        Route::post('apl01', [PdfApl01Controller::class, 'generate']);
        Route::post('apl02', [PdfApl02Controller::class, 'generate']);
    });
});