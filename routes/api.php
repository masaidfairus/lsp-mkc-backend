<?php

use App\Http\Controllers\AssessmentRegistrationController;
use App\Http\Controllers\AuthController;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/verify-email/{id}/{hash}', [AuthController::class, 'verify'])->name('api.verify');
Route::post('/verify-email/resend', [AuthController::class, 'resend']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return new UserResource($request->user());
    });
    Route::post('/apl01/step/{step}', [AssessmentRegistrationController::class, 'store']);
});