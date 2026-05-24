<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompetencyUnit extends Model
{
    protected $fillable = [
        'scheme_id',
        'work_group_id',
        'unit_code',
        'unit_title',
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
     * Mendapatkan relasi workGroup.
     *
     * @return mixed
     */
    public function workGroup()
    {
        return $this->belongsTo(WorkGroup::class);
    }

    /**
     * Mendapatkan relasi competencyElements.
     *
     * @return mixed
     */
    public function competencyElements()
    {
        return $this->hasMany(CompetencyElement::class, 'competency_unit_id');
    }
}
