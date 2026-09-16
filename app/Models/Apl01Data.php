<?php

namespace App\Models;

use App\Enums\Gender;
use Illuminate\Database\Eloquent\Model;

class Apl01Data extends Model
{
    protected $table = 'apl01_data';

    protected $fillable = [
        'registration_id',
        'identity_number',
        'gender',
        'place_of_birth',
        'date_of_birth',
        'address',
        'home_postal_code',
        'phone_number',
        'education_id',
        'company_name',
        'job_title',
        'job_code',
        'company_address',
        'company_postal_code',
        'company_fax',
        'company_email',
        'company_contact',
        'asesi_signature',
        'asesi_qr_code',
        'asesi_signature_date',
    ];

    protected $casts = [
        'gender' => Gender::class,
        'date_of_birth' => 'date',
        'asesi_signature_date' => 'datetime',
    ];

    /**
     * Mendapatkan relasi registration.
     *
     * @return mixed
     */
    public function registration()
    {
        return $this->belongsTo(AssessmentRegistration::class);
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