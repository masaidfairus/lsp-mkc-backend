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

    public function competencyUnits()
    {
        return $this->hasMany(CompetencyUnit::class);
    }

    public function registrations()
    {
        return $this->hasMany(AssessmentRegistration::class);
    }

    public function basicRequirementProofs()
    {
        return $this->hasMany(BasicRequirementProof::class);
    }

    public function administrativeProofs()
    {
        return $this->hasMany(AdministrativeProof::class);
    }

    public function portfolios()
    {
        return $this->hasMany(PortfolioScheme::class);
    }
}
