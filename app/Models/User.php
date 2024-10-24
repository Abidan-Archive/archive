<?php

namespace App\Models;

use App\Models\Concerns\HasLikes;
use App\Models\Concerns\HasBans;
use App\Models\Concerns\HasRoles;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;

/**
 * @mixin IdeHelperUser
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasBans, HasFactory, HasLikes, HasRoles, Notifiable;

    protected $appends = ['is_sso'];

    protected $fillable = [
        'username',
        'email',
        'password',
        'discord_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'discord_id',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }


    public function ips(): BelongsToMany
    {
        return $this->belongsToMany(Ip::class)->withTimestamps();
    }

    protected function isSso(): Attribute
    {
        return Attribute::make(get: fn ($value, $attr): bool => ! is_null($this->discord_id));
    }

    /**
     * Returns properties that are shared across all inertia responses
     *
     * @return array<string,mixed>
     */
    public function sharedInertiaProps(): array {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'is_sso' => $this->is_sso,
            'roles' => $this->roles->pluck('name'),
            'permissions' => $this->permissions->pluck('name'),
        ];
    }

    /**
    * Used for responding to autocomplete searches
    */
    public static function autocomplete(string $q): Collection {
        return self::select('id', 'username', 'email')
            ->whereAny(['id', 'username', 'email'], 'LIKE', $q)
            ->get()
            ->map(fn(User $user) => [
                'value' => $user->id,
                'label' => $user->username,
                'keywords' => implode(', ', [$user->id, $user->email])
            ]);
    }
}
