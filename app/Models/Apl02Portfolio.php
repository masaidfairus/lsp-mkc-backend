<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Apl02Portfolio extends Model
{
    protected $fillable = [
        'registration_id',
        'portfolio_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function registration()
    {
        return $this->belongsTo(AssessmentRegistration::class);
    }

    public function portfolio()
    {
        return $this->belongsTo(Portfolio::class);
    }
}
