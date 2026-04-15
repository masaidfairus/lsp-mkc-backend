<?php

use App\Http\Controllers\AsesiController;
use App\Http\Controllers\AssessmentRegistrationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\SchemeController;
use App\Http\Controllers\APL01Controller;
use App\Http\Middleware\EnsureApiKeyIsValid;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
    Route::get('/user', function (Request $request) {
        return new UserResource($request->user());
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/change-password', [ResetPasswordController::class, 'changePassword']);

    Route::apiResource('profile', AsesiController::class)->only([
        'show',
        'store',
        'update'
    ]);

    Route::apiResource('schemes', SchemeController::class)->only([
        'index',
        'show'
    ]);

    // ── APL01 ──────────────────────────────────────────────────────
    // PENTING: POST dan GET spesifik harus di atas route wildcard {id}
    Route::post('apl01/{step}',              [AssessmentRegistrationController::class, 'storeApl01']);
    Route::get('apl01/{registration}/pdf',   [APL01Controller::class, 'show']);
    Route::get('registrations/{registration}/apl01', [AssessmentRegistrationController::class, 'showApl01']);

    // ── APL02 ──────────────────────────────────────────────────────
    Route::get('registrations/{registration}/apl02-form',        [AssessmentRegistrationController::class, 'form']);
    Route::post('registrations/{registration}/apl02-evidence',   [AssessmentRegistrationController::class, 'uploadBuktiRelevan']);
    Route::post('registrations/{registration}/self-assessments', [AssessmentRegistrationController::class, 'submitAnswers']);
    Route::post('registrations/{registration}/ak01',             [AssessmentRegistrationController::class, 'storeAk01']);
});