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

use function Laravel\Prompts\error;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:255'],
            'identity_type' => ['required', 'in:id,passport'],
            'identity_number' => [
                'required',
                'unique:users,identity_number',
                $request->identity_type === 'id' ? 'digits:16' : 'alpha_num'
            ],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed']
        ], [
            'email.unique' => 'E2001',
            'identity_number.unique' => 'E2002',
            '*.required' => 'E2003',
            'identity_number.digits' => 'E2004',
        ]);

        if ($validator->fails()) {
            $errorCode = $validator->errors()->first();
            return $this->errorResponse($errorCode, 422);
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
