<?php

namespace App\Models;

use App\Enums\AssessmentStatus;
use App\Enums\CertificationPurpose;
use App\Enums\Result;
use Illuminate\Database\Eloquent\Model;

class AssessmentRegistration extends Model
{
    protected $fillable = [
        'user_id',
        'scheme_id',
        'assessor_id',
        'tuk',
        'status',
        'final_result',
        'assessed_at',
        'admin_notes',
    ];

    protected $casts = [
        'status' => AssessmentStatus::class,
        'certification_purpose' => CertificationPurpose::class,
        'assessed_at' => 'datetime',
    ];

    public function candidate()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function assessor()
    {
        return $this->belongsTo(User::class, 'assessor_id', 'id');
    }

    public function scheme()
    {
        return $this->belongsTo(Scheme::class, 'scheme_id');
    }

    public function selfAssessments()
    {
        return $this->hasMany(SelfAssessment::class, 'registration_id');
    }

    public function plannings()
    {
        return $this->hasMany(AssessmentPlanning::class, 'registration_id');
    }

    public function appeals()
    {
        return $this->hasMany(Appeal::class, 'registration_id');
    }

    public function payments()
    {
        return $this->hasOne(Payment::class, 'registration_id');
    }

    public function aplData()
    {
        return $this->hasOne(Apl01Data::class, 'registration_id');
    }

    public function aplPortfolio()
    {
        return $this->hasMany(Apl02Portfolio::class, 'registration_id');
    }

    public function filteredUserFiles()
    {
        return $this->hasMany(FileProfile::class, 'user_id', 'user_id')
            ->where(function ($query) {
                $query->whereHas('documentList.basicRequirementProof', function ($q) {
                    $q->where('scheme_id', $this->scheme_id);
                })
                    ->orWhereHas('documentList.administrativeProof'); // Dan semua yang tipe administratif
            });
    }

    public function filteredAplUserFiles()
    {
        return $this->hasMany(FileProfile::class, 'user_id', 'user_id')
            ->whereHas('documentList', function ($query) {
                $query->has('basicRequirementProof')
                    ->orHas('administrativeProof');
            });
    }

    public function portfolioFiles()
    {
        return $this->hasMany(FileProfile::class, 'user_id', 'user_id')
            ->whereHas('documentList.portfolioScheme', function ($query) {
                $query->where('scheme_id', $this->scheme_id);
            });
    }

    public function reports()
    {
        return $this->hasMany(AssessmentReport::class, 'registration_id');
    }

    public function instruments()
    {
        return $this->hasMany(AssessmentInstrument::class, 'registration_id');
    }

    public function results()
    {
        return $this->hasMany(AssessmentResult::class, 'registration_id');
    }

    public function validations()
    {
        return $this->hasMany(AssessmentValidation::class, 'registration_id');
    }
}
