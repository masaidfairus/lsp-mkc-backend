<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Models\ActivationTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    protected $fillable = [
        'role',
        'name',
        'identity_type',
        'identity_number',
        'email',
        'password',
        'status',
        'registration_number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Mendapatkan relasi profile.
     *
     * @return mixed
     */
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * Mendapatkan relasi userSignature.
     *
     * @return mixed
     */
    public function userSignature()
    {
        return $this->hasOne(UserSignature::class);
    }

    /**
     * Mendapatkan relasi asesiPassword.
     *
     * @return mixed
     */
    public function asesiPassword()
    {
        return $this->hasOne(AsesiPassword::class, 'user_id', 'id');
    }

    /**
     * Mendapatkan relasi activationTokens.
     *
     * @return mixed
     */
    public function activationTokens()
    {
        return $this->hasMany(ActivationTokens::class);
    }

    /**
     * Mendapatkan relasi registrations.
     *
     * @return mixed
     */
    public function registrations()
    {
        return $this->hasMany(AssessmentRegistration::class, 'user_id', 'id');
    }

    /**
     * Mendapatkan relasi assignments.
     *
     * @return mixed
     */
    public function assignments()
    {
        return $this->hasMany(AssessmentRegistration::class, 'assessor_id', 'id');
    }

    /**
     * Mendapatkan relasi userFile.
     *
     * @return mixed
     */
    public function userFile()
    {
        return $this->hasMany(FileProfile::class, 'user_id', 'id');
    }

    /**
     * Mendapatkan relasi adminFileValidation.
     *
     * @return mixed
     */
    public function adminFileValidation()
    {
        return $this->hasMany(FileProfile::class, 'verified_by', 'id');
    }
}
