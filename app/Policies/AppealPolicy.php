<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Appeal;
use Illuminate\Auth\Access\HandlesAuthorization;

class AppealPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasRole(['asesi', 'asesor', 'admin', 'superadmin']);
    }

    public function view(User $user, Appeal $appeal): bool
    {
        if ($user->hasRole('asesi')) {
            return $user->id === $appeal->registration->user_id;
        }
        return $user->hasRole(['asesor', 'admin', 'superadmin']);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['asesi', 'superadmin']);
    }

    public function update(User $user, Appeal $appeal): bool
    {
        return $user->hasRole(['admin', 'superadmin']);
    }

    public function delete(User $user, Appeal $appeal): bool
    {
        return $user->hasRole(['superadmin']);
    }
}
