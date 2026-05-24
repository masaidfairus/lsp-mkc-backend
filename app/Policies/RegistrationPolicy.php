<?php

namespace App\Policies;

use App\Models\AssessmentRegistration;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RegistrationPolicy
{
    /**
     * View.
     *
     * @param  User  $user
     * @param  AssessmentRegistration  $assessmentRegistration
     * @return bool
     */
    public function view(User $user, AssessmentRegistration $assessmentRegistration): bool
    {
        return $user->id === $assessmentRegistration->user_id;
    }

    /**
     * Assess.
     *
     * @param  User  $user
     * @param  AssessmentRegistration  $assessmentRegistration
     * @return mixed
     */
    public function assess(User $user, AssessmentRegistration $assessmentRegistration)
    {
        return $user->id === $assessmentRegistration->assessor_id;
    }
}
