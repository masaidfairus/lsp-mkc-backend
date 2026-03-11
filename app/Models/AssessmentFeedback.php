<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentFeedback extends Model
{
    protected $fillable = [
        'registration_id',
        'feedback_json',
    ];

    protected $casts = [
        'feedback_json' => 'array',
    ];

    public function registration()
    {
        return $this->belongsTo(AssessmentRegistration::class);
    }
}
