<?php

namespace App\Models\Concerns;

use App\Contracts\Likeable;
use App\Models\Like;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
* Only for User
*/
trait HasLikes
{
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function hasLiked(Likeable $likeable): bool
    {
        if (! $likeable->exists) {
            return false;
        }

        return $likeable->likes()
            ->whereHas('user', fn ($q) => $q->whereId($this->id))
            ->exists();
    }

    public function like(Likeable $likeable): self
    {
        if ($this->hasLiked($likeable)) {
            return $this;
        }

        (new Like())
            ->user()->associate($this)
            ->likeable()->associate($likeable)
            ->save();
        if (method_exists($likeable, 'searchable')) {
            $likeable->refresh()->searchable();
        }

        return $this;
    }

    public function unlike(Likeable $likeable): self
    {
        if (! $this->hasLiked($likeable)) {
            return $this;
        }

        $likeable->likes()
            ->whereHas('user', fn ($q) => $q->whereId($this->id))
            ->delete();
        if (method_exists($likeable, 'searchable')) {
            $likeable->refresh()->searchable();
        }

        return $this;
    }

}
