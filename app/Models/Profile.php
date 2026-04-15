<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'gender',
        'place_of_birth',
        'date_of_birth',
        'address',
        'home_postal_code',
        'phone_number',
        'education_qualifications',
        'company_name',
        'company_postal_code',
        'job_title',
        'company_address',
        'company_contact',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
