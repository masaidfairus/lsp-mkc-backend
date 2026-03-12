<?php

namespace App\Models;

use App\Enums\Apl01Status;
use App\Enums\Apl02Status;
use App\Enums\CertificationPurpose;
use App\Enums\Result;
use Illuminate\Database\Eloquent\Model;

class AssessmentRegistration extends Model
{
    protected $fillable = [
        'user_id',
        'scheme_id',
        'assessor_id',
        'status_apl01',
        'status_apl02',
        'final_result',
        'assessed_at',
    ];

    protected $casts = [
        'status_apl01' => Apl01Status::class,
        'status_apl02' => Apl02Status::class,
        'assessed_at' => 'datetime',
    ];

    public function candidate()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function assessor()
    {
        return $this->belongsTo(User::class, 'assessor_id', 'id');
    }

    public function scheme()
    {
        return $this->belongsTo(Scheme::class, 'scheme_id');
    }

    public function evidence()
    {
        return $this->hasMany(Apl01Evidence::class, 'registration_id');
    }

    public function evaluations()
    {
        return $this->hasMany(AssessorEvaluation::class);
    }

    public function selfAssessment()
    {
        return $this->hasMany(SelfAssessment::class, 'registration_id');
    }

    public function plannings()
    {
        return $this->hasMany(AssessmentPlanning::class);
    }

    public function payments()
    {
        return $this->hasOne(Payment::class, 'registration_id');
    }

    public function aplData()
    {
        return $this->hasOne(Apl01Data::class, 'registration_id');
    }
}
