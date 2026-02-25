<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessorEvaluation extends Model
{
    protected $fillable = [
        'registration_id',
        'criteria_id',
        'form_code',
        'is_valid',
        'is_authentic',
        'is_current',
        'is_sufficient',
        'result',
        'notes',
    ];

    protected $casts = [
        'is_valid' => 'boolean',
        'is_authentic' => 'boolean',
        'is_current' => 'boolean',
        'is_sufficient' => 'boolean',
    ];

    public function criteria()
    {
        return $this->belongsTo(PerformanceCriterion::class, 'criteria_id');
    }

    public function registration()
    {
        return $this->belongsTo(AssessmentRegistration::class, 'registration_id');
    }
}
