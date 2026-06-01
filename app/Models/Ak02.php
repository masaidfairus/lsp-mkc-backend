<?php

namespace App\Models;

use App\Models\AssessmentRegistration;
use App\Models\SignatureRequest;

class Ak02 extends AssessmentResult
{
    protected $table = 'assessment_results';

    /**
     * Booted.
     *
     * @return void
     */
    protected static function booted(): void
    {
        parent::booted();
        static::addGlobalScope('document_type', fn ($q) => $q->where('document_type', 'FR.AK.02'));
        static::creating(fn ($m) => $m->document_type = 'FR.AK.02');
    }

    /**
     * Mendapatkan relasi candidate.
     *
     * @return mixed
     */
    public function candidate()
    {
        return $this->hasOneThrough(
            User::class,
            AssessmentRegistration::class,
            'id',
            'id',
            'registration_id',
            'user_id'
        );
    }

    /**
     * Mendapatkan relasi scheme.
     *
     * @return mixed
     */
    public function scheme()
    {
        return $this->hasOneThrough(
            Scheme::class,
            AssessmentRegistration::class,
            'id',
            'id',
            'registration_id',
            'scheme_id'
        );
    }
}
