<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentPlanning extends Model
{
    protected $fillable = [
        'registration_id',
        'mapa01_data',
        'mapa02_data',
        'ak01_data',
        'ak07_data',
        'version',
        'confirmed_at',
    ];

    protected $casts = [
        'mapa01_data' => 'array',
        'mapa02_data' => 'array',
        'ak01_data' => 'array',
        'ak07_data' => 'array',
        'confirmed_at' => 'datetime',
    ];

    public function registration()
    {
        return $this->belongsTo(AssessmentRegistration::class);
    }
}
