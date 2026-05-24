<?php

namespace App\Models;

class Ak03 extends AssessmentResult
{
    protected $table = 'assessment_results';

    /**
     * Mendapatkan relasi booted.
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::addGlobalScope('document_type', fn ($q) => $q->where('document_type', 'FR.AK.03'));
        static::creating(fn ($m) => $m->document_type = 'FR.AK.03');
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
