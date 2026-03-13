<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdministrativeEvidenceType extends Model
{
    protected $fillable = [
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function adminEvidence()
    {
        return $this->hasMany(AdministrativeEvidence::class);
    }
}
