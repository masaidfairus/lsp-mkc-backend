<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchemeRequirement extends Model
{
    protected $fillable = [
        'scheme_id',
        'requirement_name',
        'is_mandatory',
    ];

    protected $casts = [
        'is_mandatory' => 'boolean',
    ];

    public function scheme()
    {
        return $this->belongsTo(Scheme::class);
    }

    public function evidence()
    {
        return $this->hasMany(Apl01Evidence::class);
    }
}
