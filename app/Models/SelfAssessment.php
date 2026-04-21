<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SelfAssessment extends Model
{
    protected $fillable = [
        'registration_id',
        'is_competent',
        'element_id',
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
        return $this->belongsTo(CompetencyElement::class);
    }

    public function registration()
    {
        return $this->belongsTo(AssessmentRegistration::class);
    }
}
