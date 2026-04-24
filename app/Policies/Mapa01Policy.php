<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Mapa01;
use Illuminate\Auth\Access\HandlesAuthorization;

class Mapa01Policy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasRole(['asesor', 'superadmin']);
    }

    public function view(User $user, Mapa01 $mapa01): bool
    {
        return $user->hasRole(['asesor', 'superadmin']);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['asesor', 'superadmin']);
    }

    public function update(User $user, Mapa01 $mapa01): bool
    {
        return $user->hasRole(['asesor', 'superadmin']);
    }

    public function delete(User $user, Mapa01 $mapa01): bool
    {
        return $user->hasRole(['superadmin']);
    }
}
