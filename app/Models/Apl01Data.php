<?php

namespace App\Models;

use App\Enums\Gender;
use Illuminate\Database\Eloquent\Model;

class Apl01Data extends Model
{
    protected $table = 'apl01_data';

    protected $fillable = [
        'registration_id',
        'gender',
        'place_of_birth',
        'date_of_birth',
        'address',
        'home_postal_code',
        'phone_number',
        'education_qualifications',
        'company_name',
        'job_title',
        'company_address',
        'company_postal_code',
        'company_contact',
    ];

    protected $casts = [
        'gender' => Gender::class,
        'date_of_birth' => 'date',
    ];

    public function registration()
    {
        return $this->belongsTo(AssessmentRegistration::class);
    }
}