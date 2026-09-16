<?php

namespace App\Models;

class Ak05 extends AssessmentReport
{
    protected $table = 'assessment_reports';

    /**
     * Mendapatkan relasi booted.
     *
     * @return void
     */
    protected static function booted(): void
    {
        parent::booted();
        static::addGlobalScope('document_type', fn ($q) => $q->where('document_type', 'FR.AK.05'));
        static::creating(fn ($m) => $m->document_type = 'FR.AK.05');
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
