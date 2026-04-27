<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Mapa02;
use Illuminate\Auth\Access\HandlesAuthorization;

class Mapa02Policy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasRole(['asesor', 'superadmin']);
    }

    public function view(User $user, Mapa02 $mapa02): bool
    {
        return $user->hasRole(['asesor', 'superadmin']);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['asesor', 'superadmin']);
    }

    public function update(User $user, Mapa02 $mapa02): bool
    {
        return $user->hasRole(['asesor', 'superadmin']);
    }

    public function delete(User $user, Mapa02 $mapa02): bool
    {
        return $user->hasRole(['superadmin']);
    }
}
