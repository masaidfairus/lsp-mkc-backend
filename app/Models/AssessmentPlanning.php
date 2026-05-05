<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

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
        'ak01_asesi_signature',
        'ak01_asesi_qr_code',
        'ak01_asesi_signature_date',
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

    public function signatureRequests(): MorphMany
    {
        return $this->morphMany(SignatureRequest::class, 'signable');
    }
}
