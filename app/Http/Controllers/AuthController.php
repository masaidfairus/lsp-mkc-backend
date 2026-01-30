<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    private function isValidNik($nik)
    {
        if (!preg_match('/^\d{16}$/', $nik)) {
            return false;
        }

        $day = (int) substr($nik, 6, 2);
        $month = (int) substr($nik, 8, 2);
        $year = (int) substr($nik, 10, 2);
        $currentYear = (int) date('y');
        $year += ($year <= $currentYear) ? 2000 : 1900;

        if ($day > 40) {
            $day -= 40;
        }

        return checkdate($month, $day, $year);
    }

    private function isValidPassport($passport)
    {
        return preg_match('/^[A-Z0-9]{7,9}$/i', $passport);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:255'],
            'identity_type' => ['required', 'in:id,passport'],
            'identity_number' => ['required', 'unique:users,identity_number'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed']
        ], [
            'email.unique' => 'E2001',
            'identity_number.unique' => 'E2002',
            '*.required' => 'E2003',
        ]);

        $identityType = $request->identity_type;
        $identityNumber = $request->identity_number;

        if ($validator->fails()) {
            $errorCode = $validator->errors()->first();
            return $this->errorResponse($errorCode, 422);
        }

        if ($identityType === 'id' && !$this->isValidNik($request->identity_number)) {
            if (!$this->isValidNik($identityNumber)) {
                return $this->errorResponse('E2004', 422);
            }
        }

        if ($identityType === 'passport' && !$this->isValidPassport($request->identity_number)) {
            if (!$this->isValidPassport($identityNumber)) {
                return $this->errorResponse('E2005', 422);
            }
        }

        $validated = $validator->validated();

        $user = User::create($validated);

        $verificationUrl = URL::temporarySignedRoute(
            'api.verify',
            now()->addMinutes(10),
            ['id' => $user->id, 'hash' => sha1($user->getEmailForVerification())]
        );

        $user->notify(new VerifyEmailNotification($verificationUrl));

        return (new UserResource($user))->additional([
            'message' => 'Link verifikasi telah dikirim ke email anda.'
        ]);
    }

    public function verify(Request $request)
    {
        if (!$request->hasValidSignature()) {
            return $this->errorResponse('E3001', 403);
        }

        $user = User::findOrFail($request->id);

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new Verified($user));
        }

        return $this->successResponse([], 'Email berhasil diverifikasi.');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => [
                'required',
                'exists:users',
                'email',
            ],
            'password' => [
                'required',
            ],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return $this->errorResponse('E1001', 401);
        }

        if (!Hash::check($request->password, $user->password)) {
            return $this->errorResponse('E1002', 401);
        }

        if (!$user->email_verified_at) {
            return $this->errorResponse('E1003', 403);
        }

        $token = $user->createToken($user->name)->plainTextToken;
        return (new UserResource($user))->additional([
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return $this->successResponse([], 'Kamu telah keluar dari akun');
    }
}
