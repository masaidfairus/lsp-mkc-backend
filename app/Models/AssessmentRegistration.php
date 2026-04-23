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
        'tuk',
        'status_apl01',
        'status_apl02',
        'final_result',
        'assessed_at',
        'certification_purpose',
    ];

    protected $casts = [
        'status_apl01' => Apl01Status::class,
        'status_apl02' => Apl02Status::class,
        'certification_purpose' => CertificationPurpose::class,
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

    public function evaluations()
    {
        return $this->hasMany(AssessorEvaluation::class);
    }

    public function selfAssessments()
    {
        return $this->hasMany(SelfAssessment::class, 'registration_id');
    }

    public function plannings()
    {
        return $this->hasMany(AssessmentPlanning::class, 'registration_id');
    }

    public function feedback()
    {
        return $this->hasMany(AssessmentFeedback::class);
    }

    public function appeals()
    {
        return $this->hasMany(Appeal::class);
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
