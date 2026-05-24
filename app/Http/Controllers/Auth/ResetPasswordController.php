<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

use function Illuminate\Support\now;

class ResetPasswordController extends Controller
{
    /**
     * Change password.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'new_password' => [
                'required',
                'confirmed',
                Password::min(6)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols()
            ]
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return $this->errorResponse('E1002', 401);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return $this->successResponse([], 'Password berhasil diganti');
    }

    /**
     * Forgot password.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return $this->errorResponse('E1001', 401);
        }

        $otp = rand(100000, 999999);
        
        Cache::put('otp_' . $request->email, $otp, now()->addMinutes(10));

        Mail::to($user->email)->send(new OtpMail($otp));

        return $this->successResponse([], 'Kode OTP dikirimkan ke email Anda.');
    }

    /**
     * Memverifikasi otp.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp' => ['required']
        ]);

        $cachedOtp = Cache::get('otp_' . $request->email);

        if (!$cachedOtp || $cachedOtp != $request->otp) {
            return $this->errorResponse('E3003', 401);
        }

        $resetToken = Str::random(64);
        Cache::put('reset_token_' . $resetToken, $request->email, now()->addMinutes(15));
        Cache::forget('otp_' . $request->email);

        return $this->successResponse(['reset_token' => $resetToken], 'Kode OTP diverifikasi');
    }

    /**
     * Mengatur ulang password.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'reset_token' => ['required'],
            'new_password' => ['required', 'min:8', 'confirmed'],
        ]);

        $email = Cache::get('reset_token_' . $request->reset_token);

        if (!$email) {
            return $this->errorResponse('E3004', 401);
        }

        $user = User::where('email', $email)->first();
        $user->update(['password' => Hash::make($request->new_password)]);

        Cache::forget('reset_token_' . $request->reset_token);

        return $this->successResponse([], 'Password berhasil diganti');
    }
}
