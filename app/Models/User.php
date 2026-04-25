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

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function userSignature()
    {
        return $this->hasOne(UserSignature::class);
    }

    public function asesiPassword()
    {
        return $this->hasOne(AsesiPassword::class, 'user_id', 'id');
    }

    public function activationTokens()
    {
        return $this->hasMany(ActivationTokens::class);
    }

    public function registrations()
    {
        return $this->hasMany(AssessmentRegistration::class, 'user_id', 'id');
    }

    public function assignments()
    {
        return $this->hasMany(AssessmentRegistration::class, 'assessor_id', 'id');
    }

    public function userFile()
    {
        return $this->hasMany(FileProfile::class, 'user_id', 'id');
    }

    public function adminFileValidation()
    {
        return $this->hasMany(FileProfile::class, 'verified_by', 'id');
    }
}
