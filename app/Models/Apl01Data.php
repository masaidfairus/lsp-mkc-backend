<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Apl01Data extends Model
{
    protected $table = 'apl01_data';

    protected $fillable = [
        'user_id',
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
        'date_of_birth' => 'date',
    ];

    public function registration()
    {
        return $this->belongsTo(AssessmentRegistration::class);
    }

    public function evidences()
    {
        return $this->hasMany(Apl01Evidence::class, 'registration_id');
    }
}