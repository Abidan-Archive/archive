<?php

namespace App\Policies;

use App\Contracts\Likeable;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LikePolicy
{
    public function like(User $user, Likeable $likeable): Response
    {
        if (! $likeable->exists) {
            return Response::deny("Cannot like an object that doesn't exist");
        }
        if ($user->hasLiked($likeable)) {
            return Response::deny('Cannot like the same thing twice');
        }

        return Response::allow();
    }

    public function unlike(User $user, Likeable $likeable): Response
    {
        if (! $likeable->exists) {
            return Response::deny("Cannot unlike an object that doesn't exist");
        }
        if (! $user->hasLiked($likeable)) {
            return Response::deny('Cannot unlike without liking first');
        }

        return Response::allow();
    }
}
