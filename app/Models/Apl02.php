<?php

namespace App\Models;

class Apl02 extends AssessmentRegistration
{
    protected $table = 'assessment_registrations';

    public function portfolioFiles()
    {
        return $this->hasMany(FileProfile::class, 'user_id', 'user_id')
            ->whereHas('documentList.portfolioScheme', function ($query) {
                $query->where('scheme_id', $this->scheme_id);
            });
    }
}
