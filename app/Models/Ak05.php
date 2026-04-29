<?php

namespace App\Models;

class Ak05 extends AssessmentReport
{
    protected $table = 'assessment_reports';

    protected static function booted(): void
    {
        static::addGlobalScope('document_type', fn ($q) => $q->where('document_type', 'FR.AK.05'));
        static::creating(fn ($m) => $m->document_type = 'FR.AK.05');
    }

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
