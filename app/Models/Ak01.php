<?php

namespace App\Models;

class Ak01 extends AssessmentPlanning
{
    protected $table = 'assessment_plannings';

    public function candidate()
    {
        return $this->hasOneThrough(
            User::class,
            AssessmentRegistration::class,
            'id', // Foreign key on assessment_registrations table...
            'id', // Foreign key on users table...
            'registration_id', // Local key on assessment_plannings table...
            'user_id' // Local key on assessment_registrations table...
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
