<?php

namespace App\Models\Concerns;

use App\Models\Ban;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasBans
{
    public function bans(): MorphToMany
    {
        return $this->morphToMany(Ban::class, 'bannable')->withTimestamps();
    }

    public function isBanned(): bool {
        return $this->morphToMany(Ban::class, 'bannable')->active()->exists();
    }
}
