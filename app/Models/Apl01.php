<?php

namespace App\Models;

class Apl01 extends AssessmentRegistration
{
    protected $table = 'assessment_registrations';

    /**
     * Mendapatkan relasi filteredUserFiles.
     *
     * @return mixed
     */
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

    /**
     * Mendapatkan relasi filteredAplUserFiles.
     *
     * @return mixed
     */
    public function filteredAplUserFiles()
    {
        return $this->hasMany(FileProfile::class, 'user_id', 'user_id')
            ->whereHas('documentList', function ($query) {
                $query->has('basicRequirementProof')
                    ->orHas('administrativeProof');
            });
    }

    /**
     * Mendapatkan relasi portfolioFiles.
     *
     * @return mixed
     */
    public function portfolioFiles()
    {
        return $this->hasMany(FileProfile::class, 'user_id', 'user_id')
            ->whereHas('documentList.portfolioScheme', function ($query) {
                $query->where('scheme_id', $this->scheme_id);
            });
    }
}
