<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentList extends Model
{
    protected $fillable = [
        'title',
        'description',
    ];

    /**
     * Mendapatkan relasi basicRequirementProof.
     *
     * @return mixed
     */
    public function basicRequirementProof()
    {
        return $this->hasOne(BasicRequirementProof::class, 'list_id');
    }

    /**
     * Mendapatkan relasi basicRequirementProofs.
     *
     * @return mixed
     */
    public function basicRequirementProofs()
    {
        return $this->hasMany(BasicRequirementProof::class, 'list_id');
    }

    /**
     * Mendapatkan relasi administrativeProof.
     *
     * @return mixed
     */
    public function administrativeProof()
    {
        return $this->hasOne(AdministrativeProof::class, 'list_id');
    }

    /**
     * Mendapatkan relasi administrativeProofs.
     *
     * @return mixed
     */
    public function administrativeProofs()
    {
        return $this->hasMany(AdministrativeProof::class, 'list_id');
    }

    /**
     * Mendapatkan relasi portfolioScheme.
     *
     * @return mixed
     */
    public function portfolioScheme()
    {
        return $this->hasOne(PortfolioScheme::class, 'list_id');
    }

    /**
     * Mendapatkan relasi fileProfile.
     *
     * @return mixed
     */
    public function fileProfile()
    {
        return $this->hasMany(FileProfile::class, 'list_id');
    }

    /**
     * Mendapatkan relasi aplPortfolio.
     *
     * @return mixed
     */
    public function aplPortfolio()
    {
        return $this->hasMany(Apl02Portfolio::class, 'list_id');
    }

    /**
     * Scope for scheme.
     *
     * @param  mixed  $query
     * @param  mixed  $schemeId
     * @param  mixed  $relation
     * @return mixed
     */
    public function scopeForScheme($query, $schemeId, $relation)
    {
        return $query->whereHas($relation, function ($q) use ($schemeId) {
            $q->where('scheme_id', $schemeId);
        });
    }
}
