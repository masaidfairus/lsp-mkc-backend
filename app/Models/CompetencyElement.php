<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompetencyElement extends Model
{
    protected $fillable = [
        'competency_unit_id',
        'title',
    ];

    /**
     * Mendapatkan relasi competencyUnit.
     *
     * @return mixed
     */
    public function competencyUnit()
    {
        return $this->belongsTo(CompetencyUnit::class);
    }

    /**
     * Mendapatkan relasi performanceCriteria.
     *
     * @return mixed
     */
    public function performanceCriteria()
    {
        return $this->hasMany(PerformanceCriterion::class, 'element_id');
    }

    /**
     * Mendapatkan relasi selfAssessments.
     *
     * @return mixed
     */
    public function selfAssessments()
    {
        return $this->hasMany(SelfAssessment::class, 'element_id');
    }
}
