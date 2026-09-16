<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Enums\UserStatus;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * Is valid nik.
     *
     * @param  mixed  $nik
     * @return mixed
     */
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

    /**
     * Is valid passport.
     *
     * @param  mixed  $passport
     * @return mixed
     */
    private function isValidPassport($passport)
    {
        return preg_match('/^[A-Z0-9]{7,9}$/i', $passport);
    }

    /**
     * Melakukan pendaftaran (register).
     *
     * @param  Request  $request
     * @return mixed
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:255'],
            'identity_type' => ['required', 'in:id,passport'],
            'identity_number' => ['required', 'unique:users,identity_number'],
            'email' => ['required', 'email:rfc,dns', 'unique:users'],
            'password' => [
                'required',
                'confirmed',
                Password::min(6)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols()
            ],
            // Profile fields
            'gender' => ['sometimes', 'in:male,female'],
            'place_of_birth' => ['sometimes', 'string', 'max:1000'],
            'date_of_birth' => ['sometimes', 'date', 'before:today'],
            'address' => ['sometimes', 'string', 'max:1000'],
            'home_postal_code' => ['sometimes', 'numeric', 'digits:5'],
            'phone_number' => ['sometimes', 'string', 'regex:/^(\+62|0)8[1-9][0-9]{6,10}$/'],
            'education_id' => ['sometimes', 'exists:master_education,id'],
            'company_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'job_title' => ['sometimes', 'nullable', 'string', 'max:255'],
            'job_code' => ['sometimes', 'nullable', 'string', 'max:255'],
            'company_address' => ['sometimes', 'nullable', 'string', 'max:1000'],
            'company_postal_code' => ['sometimes', 'nullable', 'numeric'],
            'company_contact' => ['sometimes', 'nullable', 'string', 'max:20'],
            'company_email' => ['sometimes', 'nullable', 'email'],
            'company_fax' => ['sometimes', 'nullable', 'string'],
            'asesi_type' => ['sometimes', 'in:pribadi,institusi'],
            'institution_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'signature' => ['sometimes', 'nullable', 'string'],
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

        $user = User::create([
            'name' => $validated['name'],
            'identity_type' => $validated['identity_type'],
            'identity_number' => $validated['identity_number'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

        $user->asesiPassword()->create([
            'asesi_password' => $validated['password']
        ]);

        // Create Profile if Profile data is present
        $profileFields = [
            'gender', 'place_of_birth', 'date_of_birth', 'address',
            'home_postal_code', 'phone_number', 'education_id',
            'company_name', 'job_title', 'job_code', 'company_address',
            'company_postal_code', 'company_contact', 'company_email',
            'company_fax', 'asesi_type', 'institution_name'
        ];
        $profileData = array_intersect_key($validated, array_flip($profileFields));
        if (!empty($profileData)) {
            $user->profile()->create($profileData);
        }

        // Create User Signature if present
        if (!empty($validated['signature'])) {
            $user->userSignature()->create([
                'signature_image' => 'data:image/png;base64,' . $validated['signature'],
                'name' => $validated['name']
            ]);
        }

        $user->load('asesiPassword');

        $verificationUrl = URL::temporarySignedRoute(
            'api.verify',
            now()->addMinutes(10),
            ['id' => $user->id, 'hash' => sha1($user->getEmailForVerification())]
        );

        try {
            $user->notify(new VerifyEmailNotification($verificationUrl));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal mengirim email verifikasi: ' . $e->getMessage());
        }

        $user->asesiPassword()->delete();

        return (new UserResource($user))->additional([
            'message' => 'Link verifikasi telah dikirim ke email anda.'
        ]);
    }

    /**
     * Melakukan proses masuk (login).
     *
     * @param  Request  $request
     * @return mixed
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => [
                'required',
                'exists:users,email',
                'email',
            ],
            'password' => [
                'required',
            ],
        ], [
            'email.exists' => 'Email atau password salah.',
            'email.required' => 'Email tidak boleh kosong.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi tidak boleh kosong.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'metadata' => [
                    'code' => 'E4002',
                    'message' => $validator->errors()->first()
                ],
                'response' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        $user = User::where('email', $request->email)->first();

        // ADA TIGA ROLE NANTI : admin, asesor, asesi (user)
        if ($user?->role === 'admin' || $user?->role === 'asesor') {
            if (Auth::attempt($validated)) {
                $request->session()->regenerate();
                return redirect()->route('admin');
            }

            return $this->errorResponse('E1004', 401);
        }

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

    /**
     * Melakukan proses keluar (logout).
     *
     * @param  Request  $request
     * @return mixed
     */
    public function logout(Request $request)
    {
        if (Auth::user()->role == 'admin' || Auth::user()->role == 'asesor') {
            Auth::logout();
            $request->session()->invalidate();
        } else {
            $request->user()->tokens()->delete();
            return $this->successResponse([], 'Kamu telah keluar dari akun');
        }

        return redirect()->route('login');
    }

    /**
     * Memverifikasi.
     *
     * @param  Request  $request
     * @return mixed
     */
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

        $user->update(['status' => UserStatus::ACTIVE]);

        return view('auth.activation');
    }

    /**
     * Resend.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function resend(Request $request)
    {
        $request->validate([
            'email' => 'required|email:rfc',
        ]);

        $user = User::where('email', $request->email)->firstOrFail();

        if ($user->hasVerifiedEmail()) {
            return $this->successResponse([], 'Email sudah terverifikasi');
        }

        $verificationUrl = URL::temporarySignedRoute(
            'api.verify',
            now()->addMinutes(10),
            ['id' => $user->id, 'hash' => sha1($user->getEmailForVerification())]
        );

        try {
            $user->notify(new VerifyEmailNotification($verificationUrl));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal mengirim email verifikasi (resend): ' . $e->getMessage());
        }
        return $this->successResponse([], 'Link verifikasi telah dikirim ke email anda.');
    }

    /**
     * Memeriksa status.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function checkStatus(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'email_verified' => !is_null($user->email_verified_at),
            'email' => ($user->email),
        ]);
    }
}