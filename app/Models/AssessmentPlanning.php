<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentPlanning extends Model
{
    protected $fillable = [
        'registration_id',
        'methods',
        'is_ak01_confirmed',
        'confirmed_at',
        'version',
    ];

    protected $casts = [
        'is_ak01_confirmed' => 'boolean',
        'confirmed_at' => 'datetime',
        'methods' => 'array',
    ];

    public function registration()
    {
        return $this->belongsTo(AssessmentRegistration::class);
    }
}
