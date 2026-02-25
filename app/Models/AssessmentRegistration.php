<?php

namespace App\Models;

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
        return $this->belongsTo(Scheme::class);
    }

    public function evidence()
    {
        return $this->hasMany(Apl01Evidence::class);
    }

    public function evaluations()
    {
        return $this->hasMany(AssessorEvaluation::class);
    }

    public function selfAssessment()
    {
        return $this->hasMany(SelfAssessment::class);
    }

    public function plannings()
    {
        return $this->hasMany(AssessmentPlanning::class);
    }
}
