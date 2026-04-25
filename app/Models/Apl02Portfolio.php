<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Apl02Portfolio extends Model
{
    protected $fillable = [
        'registration_id',
        'file_profile_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function registration()
    {
        return $this->belongsTo(AssessmentRegistration::class);
    }

    public function fileProfile()
    {
        return $this->belongsTo(FileProfile::class);
    }
}
