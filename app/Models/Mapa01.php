<?php

namespace App\Models;

class Mapa01 extends AssessmentPlanning
{
    protected $table = 'assessment_plannings';

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
            'id',          // FK on assessment_registrations
            'id',          // FK on users
            'registration_id', // Local key on assessment_plannings
            'user_id'      // Local key on assessment_registrations
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
