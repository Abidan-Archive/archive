<?php

namespace App\Models;

use App\Contracts\Likeable;
use App\Models\Concerns\HasRoles;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @mixin IdeHelperUser
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    protected $appends = ['is_sso'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'discord_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'discord_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function bans(): MorphToMany
    {
        return $this->morphToMany(Ban::class, 'bannable')->withTimestamps();
    }

    public function ips(): BelongsToMany
    {
        return $this->belongsToMany(Ip::class)->withTimestamps();
    }

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

    protected function isSso(): Attribute
    {
        return Attribute::make(get: fn ($value, $attr): bool => ! is_null($this->discord_id));
    }
}
