<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SelfAssessment extends Model
{
    protected $fillable = [
        'registration_id',
        'element_id',
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
    
    public function element()
    {
        return $this->belongsTo(CompetencyElement::class, 'element_id');
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
