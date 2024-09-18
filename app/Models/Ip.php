<?php

namespace App\Models;

use App\Models\Concerns\HasBans;
// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;

/**
 * @mixin IdeHelperIp
 */
class Ip extends Model
{
    use HasBans;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['ip'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['ip'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public static function autocomplete(string $q): Collection {
        return self::whereAny(['id', 'ip'], 'LIKE', $q)
            ->get()
            ->map(fn(Ip $ip) => [
                'value' => $ip->id,
                'label' => $ip->ip,
                'keywords' => implode(', ', [
                    $ip->id,
                    str_contains($ip->ip, ':')
                    ? 'ipv6'
                    : 'ipv4'
                ])
            ]);
    }
}
