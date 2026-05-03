<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Mapa02;
use Illuminate\Auth\Access\HandlesAuthorization;

class Mapa02Policy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_mapa02');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Mapa02 $mapa02): bool
    {
        return $user->can('view_mapa02');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_mapa02');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Mapa02 $mapa02): bool
    {
        return $user->can('update_mapa02');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Mapa02 $mapa02): bool
    {
        return $user->can('delete_mapa02');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_mapa02');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Mapa02 $mapa02): bool
    {
        return $user->can('force_delete_mapa02');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_mapa02');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Mapa02 $mapa02): bool
    {
        return $user->can('restore_mapa02');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_mapa02');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Mapa02 $mapa02): bool
    {
        return $user->can('replicate_mapa02');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_mapa02');
    }
}
