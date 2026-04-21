<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerformanceCriterion extends Model
{
    protected $fillable = [
        'element_id',
        'name',
        'description',
    ];

    public function element()
    {
        return $this->belongsTo(CompetencyElement::class);
    }

    public function evaluations()
    {
        return $this->hasMany(AssessorEvaluation::class);
    }
}
