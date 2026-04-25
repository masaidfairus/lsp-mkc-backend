<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentList extends Model
{
    protected $fillable = [
        'title',
        'description',
    ];

    public function basicRequirementProof()
    {
        return $this->hasOne(BasicRequirementProof::class, 'list_id');
    }

    public function administrativeProof()
    {
        return $this->hasOne(AdministrativeProof::class, 'list_id');
    }

    public function portfolioScheme()
    {
        return $this->hasOne(PortfolioScheme::class, 'list_id');
    }

    public function fileProfile()
    {
        return $this->hasMany(FileProfile::class, 'list_id');
    }

    public function scopeForScheme($query, $schemeId, $relation)
    {
        return $query->whereHas($relation, function ($q) use ($schemeId) {
            $q->where('scheme_id', $schemeId);
        });
    }
}
