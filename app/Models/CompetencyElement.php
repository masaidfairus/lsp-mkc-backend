<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompetencyElement extends Model
{
    protected $fillable = [
        'competency_unit_id',
        'title',
    ];

    public function competencyUnit()
    {
        return $this->belongsTo(CompetencyUnit::class);
    }

    public function performanceCriteria()
    {
        return $this->hasMany(PerformanceCriterion::class, 'element_id');
    }

    public function selfAssessments()
    {
        return $this->hasMany(SelfAssessment::class, 'element_id');
    }
}
