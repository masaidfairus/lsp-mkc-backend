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

    /**
     * Mendapatkan relasi element.
     *
     * @return mixed
     */
    public function element()
    {
        return $this->belongsTo(CompetencyElement::class);
    }
}
