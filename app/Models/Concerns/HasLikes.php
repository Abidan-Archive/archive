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
    protected ?array $cachedLikes = null;

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Automatically preloadlikes the first time they are accessed for subsequent calls.
     */
    protected function preloadLikes(): void
    {
        if ($this->cachedLikes === null) {
            $this->cachedLikes = $this->likes()
                ->get()
                ->keyBy(fn ($like) => $like->likeable_type . ':' . $like->likeable_id)
                ->toArray();
        }
    }

    public function hasLiked(Likeable $likeable): bool
    {
        if (!$likeable->exists) {
            return false;
        }

        $this->preloadLikes();

        return isset($this->cachedLikes[get_class($likeable).':'.$likeable->getKey()]);
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

        $this->cachedLikes = null; // Clear cache

        if (method_exists($likeable, 'searchable')) {
            $likeable->refresh()->searchable();
        }

        return $this;
    }

    public function unlike(Likeable $likeable): self
    {
        if (!$this->hasLiked($likeable)) {
            return $this;
        }

        $likeable->likes()
            ->whereHas('user', fn ($q) => $q->whereId($this->id))
            ->delete();

        $this->cachedLikes = null; // Clear cache

        if (method_exists($likeable, 'searchable')) {
            $likeable->refresh()->searchable();
        }

        return $this;
    }
}
