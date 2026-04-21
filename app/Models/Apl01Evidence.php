<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\EvidenceStatus;
use App\Enums\ValidationResult;
use Illuminate\Support\Facades\Storage;

class Apl01Evidence extends Model
{
    protected $table = 'apl01_evidence';

    protected $fillable = [
        'registration_id', 'requirement_id',
        'file_path', 'is_valid_admin', 'admin_notes',
        'status', 'validation_result', 'verified_by', 'verified_at',
    ];

    protected $casts = [
        'is_valid_admin' => 'boolean',
        'verified_at'    => 'datetime',
        'status' => EvidenceStatus::class,
        'validation_result' => ValidationResult::class,
    ];

    public function apl01Data()
    {
        return $this->file_path ? Storage::url($this->file_path) : null;
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function registration()
    {
        return $this->belongsTo(AssessmentRegistration::class, 'registration_id');
    }

    public function requirement()
    {
        return $this->belongsTo(SchemeRequirement::class, 'requirement_id');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}