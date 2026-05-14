<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentReport extends Model
{
    protected $fillable = [
        'registration_id',
        'document_type',
        'main_data',
        'validation_data',
    ];

    protected $casts = [
        'main_data' => 'array',
        'validation_data' => 'array',
    ];

    public function registration()
    {
        return $this->belongsTo(AssessmentRegistration::class, 'registration_id');
    }
}
