<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentPlanning extends Model
{
    protected $fillable = [
        'registration_id',
        'ak01_data',
        'mapa01_data',
        'mapa02_data',
        'ak07_data',
        'methods',
        'is_ak01_confirmed',
        'confirmed_at',
        'version',
    ];

    protected $casts = [
        'is_ak01_confirmed' => 'boolean',
        'confirmed_at' => 'datetime',
        'methods' => 'array',
        'ak01_data' => 'array',
        'mapa01_data' => 'array',
        'mapa02_data' => 'array',
        'ak07_data' => 'array',
    ];

    public function registration()
    {
        return $this->belongsTo(AssessmentRegistration::class);
    }
}
