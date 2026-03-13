<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdministrativeEvidence extends Model
{
    protected $fillable = [
        'registration_id',
        'evidence_type_id',
        'file_path',
        'status',
        'is_valid_admin',
        'admin_notes',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'is_valid_admin' => 'boolean',
    ];

    public function registration()
    {
        return $this->belongsTo(AssessmentRegistration::class);
    }

    public function adminEvidenceType()
    {
        return $this->belongsTo(AdministrativeEvidenceType::class);
    }
}