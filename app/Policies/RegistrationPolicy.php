<?php

namespace App\Policies;

use App\Models\AssessmentRegistration;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RegistrationPolicy
{
    public function view(User $user, AssessmentRegistration $assessmentRegistration): bool
    {
        return $user->id === $assessmentRegistration->user_id;
    }

    public function assess(User $user, AssessmentRegistration $assessmentRegistration)
    {
        return $user->id === $assessmentRegistration->assessor_id;
    }
}
