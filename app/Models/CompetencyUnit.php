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
    
    public function scheme()
    {
        return $this->belongsTo(Scheme::class);
    }

    public function workGroup()
    {
        return $this->belongsTo(WorkGroup::class);
    }

    public function competencyElements()
    {
        return $this->hasMany(CompetencyElement::class, 'competency_unit_id');
    }

    public function workGroup()
    {
        return $this->belongsTo(WorkGroup::class);
    }
}
