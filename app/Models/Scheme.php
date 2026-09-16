<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scheme extends Model
{
    protected $fillable = [
        'code',
        'name',
        'type',
        'description',
        'certification_fee',
        'recertification_fee',
    ];

    protected $casts = [
        'certification_fee' => 'float',
        'recertification_fee' => 'float',
    ];

    /**
     * Mendapatkan relasi competencyUnits.
     *
     * @return mixed
     */
    public function competencyUnits()
    {
        return $this->hasMany(CompetencyUnit::class);
    }

    /**
     * Mendapatkan relasi registrations.
     *
     * @return mixed
     */
    public function registrations()
    {
        return $this->hasMany(AssessmentRegistration::class);
    }

    /**
     * Mendapatkan relasi basicRequirementProofs.
     *
     * @return mixed
     */
    public function basicRequirementProofs()
    {
        return $this->hasMany(BasicRequirementProof::class);
    }

    /**
     * Mendapatkan relasi administrativeProofs.
     *
     * @return mixed
     */
    public function administrativeProofs()
    {
        return $this->hasMany(AdministrativeProof::class);
    }

    /**
     * Mendapatkan relasi portfolios.
     *
     * @return mixed
     */
    public function portfolios()
    {
        return $this->hasMany(PortfolioScheme::class);
    }

    /**
     * Mendapatkan relasi workGroups.
     *
     * @return mixed
     */
    public function workGroups()
    {
        return $this->hasMany(WorkGroup::class);
    }
}
