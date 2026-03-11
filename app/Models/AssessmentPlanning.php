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
        'mapa_context_json',
        'ak01_agreed_asesi',
        'ak01_agreed_assessor',
        'ak01_agreed_at',
        'reasonable_adjustments_notes',
    ];

    protected $casts = [
        'is_ak01_confirmed' => 'boolean',
        'ak01_agreed_asesi' => 'boolean',
        'ak01_agreed_assessor' => 'boolean',
        'confirmed_at' => 'datetime',
        'methods' => 'array',
        'mapa_context_json' => 'array',
    ];

    public function registration()
    {
        return $this->belongsTo(AssessmentRegistration::class);
    }
}
