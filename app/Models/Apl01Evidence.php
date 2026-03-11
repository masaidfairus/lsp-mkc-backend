<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Apl01Evidence extends Model
{
    protected $fillable = [
        'registration_id',
        'requirement_id',
        'file_path',
        'is_valid_admin',
        'admin_notes',
        'status',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'is_valid_admin' => 'boolean',
        'verified_at' => 'datetime',
    ];

    public function getFileUrlAttribute()
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
        return $this->belongsTo(SchemeRequirement::class);
    }
}
