<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkGroup extends Model
{
    protected $fillable = [
        'scheme_id',
        'name',
    ];

    /**
     * Mendapatkan relasi scheme.
     *
     * @return mixed
     */
    public function scheme()
    {
        return $this->belongsTo(Scheme::class);
    }

    /**
     * Mendapatkan relasi competencyUnits.
     *
     * @return mixed
     */
    public function competencyUnits()
    {
        return $this->hasMany(CompetencyUnit::class);
    }
}
