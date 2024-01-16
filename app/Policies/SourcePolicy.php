<?php

namespace App\Policies;

use App\Models\Source;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SourcePolicy
{
    /**
     * Perofrm pre-authorization check for admin
     */
    public function before(User $user, string $ability): bool|null
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
    public function view(User $user, Source $source): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('edit_event');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Source $source): bool
    {
        return $user->can('edit_event');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Source $source): bool
    {
        return $user->can('edit_event');
    }
}
