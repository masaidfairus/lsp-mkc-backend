<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SelfAssessment extends Model
{
    protected $fillable = [
        'registration_id',
        'criteria_id',
        'is_competent',
        'evidence_id',
        'is_verified_by_assessor',
        'assessor_notes',
    ];

    protected $casts = [
        'is_competent' => 'boolean',
        'is_verified_by_assessor' => 'boolean',
    ];

    protected $attributes = [
        'is_competent' => false,
        'is_verified_by_assessor' => false,
    ];
    
    public function criteria()
    {
        return $this->belongsTo(PerformanceCriterion::class);
    }

    public function registration()
    {
        return $this->belongsTo(AssessmentRegistration::class);
    }

    public function evidence()
    {
        return $this->belongsTo(Apl01Evidence::class);
    }
}
