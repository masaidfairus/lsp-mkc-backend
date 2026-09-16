<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkGroup extends Model
{
    protected $fillable = [
        'scheme_id',
        'name',
    ];

    public function scheme()
    {
        return $this->belongsTo(Scheme::class);
    }

    public function competencyUnits()
    {
        return $this->hasMany(CompetencyUnit::class);
    }
}
