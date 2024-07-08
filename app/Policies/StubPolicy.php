<?php

namespace App\Policies;

use App\Models\Stub;
use App\Models\User;

class StubPolicy
{
    /**
     * Perform pre-authorization check for admin
     */
    public function before(User $user, string $ability): ?bool
    {
        return $user->hasRole('admin') ? true : null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Stub $stub): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('edit_report');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Stub $stub): bool
    {
        return $user->can('edit_report');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Stub $stub): bool
    {
        return $user->can('edit_event');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Stub $stub): bool
    {
        return $user->can('edit_event');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Stub $stub): bool
    {
        return $user->hasRole('admin');
    }
}
