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
        'education_id',
        'company_name',
        'company_postal_code',
        'company_fax',
        'company_email',
        'job_title',
        'job_code',
        'company_address',
        'company_contact',
        'asesi_type',
        'institution_name',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    /**
     * Mendapatkan relasi user.
     *
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mendapatkan relasi education.
     *
     * @return mixed
     */
    public function education()
    {
        return $this->belongsTo(MasterEducation::class, 'education_id');
    }
}
