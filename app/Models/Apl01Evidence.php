<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Apl01Evidence extends Model
{
    protected $table = 'apl01_evidence';

    protected $fillable = [
        'registration_id', 'requirement_id',
        'file_path', 'is_valid_admin', 'admin_notes',
        'status', 'verified_by', 'verified_at',
    ];

    protected $casts = [
        'is_valid_admin' => 'boolean',
        'verified_at'    => 'datetime',
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