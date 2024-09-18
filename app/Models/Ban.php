<?php

namespace App\Models;

use App\Enums\BanType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Log;

/**
 * @mixin IdeHelperBan
 */
class Ban extends Model
{
    use SoftDeletes;

    protected $fillable = ['expires', 'type', 'reason'];

    public function users(): MorphToMany
    {
        return $this->morphedByMany(User::class, 'bannable')->withTimestamps();
    }

    public function ips(): MorphToMany
    {
        return $this->morphedByMany(Ip::class, 'bannable')->withTimestamps();
    }

    public function scopeActive(Builder $query): Builder {
        return $query->where('expires', '>', now());
    }

    public static function hammer(mixed $bannables, BanType $type, string $reason, ?Carbon $expires = null): self
    {
        $ban = new self;
        $ban->expires = $expires;
        $ban->type = $type->name;
        $ban->reason = $reason;

        if (! is_array($bannables)) {
            $bannables = [$bannables];
        }
        foreach ($bannables as &$bannable) {
            if (! $bannable instanceof ($type->bannable())) {
                $bannable = ($type->bannable())::findOrFail($bannable);
            }
            $bannable->bans()->save($ban);

            Log::channel('ban')->notice('{class} {bannable} ({bannable_id}) recieved displinary {ban} ban ({ban_id}) from {mod} ({mod_id}) until {duration}: {reason}', [
                'class' => get_class_short($bannable),
                'bannable' => $bannable->username ?? $bannable->ip,
                'bannable_id' => $bannable->id,
                'ban' => $type->name,
                'ban_id' => $ban->id,
                'mod' => auth()->user()->username ?? 'unknown',
                'mod_id' => auth()->user()->id ?? '?',
                'duration' => $expires != null ?
                    $expires->toDateTimeString() :
                    'forever',
                'reason' => $reason,
            ]);
        }

        return $ban;
    }
}
