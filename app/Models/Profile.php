<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'gender',
        'date_of_birth',
        'address',
        'phone_number',
        'education_qualifications',
        'company_name',
        'job_title',
        'company_address',
        'company_contact',
        'asesi_type',
        'institution_name',
        'place_of_birth',
        'home_postal_code',
        'company_postal_code'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
