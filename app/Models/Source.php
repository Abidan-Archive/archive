<?php

namespace App\Models;

use App\Models\Event;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Storage;

/**
 * App\Models\Source
 *
 * @property int $id
 * @property string $name
 * @property string $path
 * @property int $event_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Event|null $event
 * @method static \Illuminate\Database\Eloquent\Builder|Source newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Source newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Source query()
 * @method static \Illuminate\Database\Eloquent\Builder|Source whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Source whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Source whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Source whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Source wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Source whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Source extends Model
{
    use HasFactory;

    const DIRECTORY = 'sources';

    protected $appends = ['path'];

    protected $fillable = ['name', 'filename'];

    protected static function booted(): void {
        static::creating(fn(Source $model) =>
            $model->id = (($model->event->sources->max('id') ?? 0) + 1),
        );
    }

    protected function path(): Attribute {
        return Attribute::make(get:
            function($value, $attributes) {
                if (array_key_exists('filename', $attributes))
                    return Storage::url(self::DIRECTORY.'/'.$attributes['filename']);
                return null;
            }
        );
    }

    public function event(): BelongsTo {
        return $this->belongsTo(Event::class);
    }

    public function stubs(): HasMany {
        return $this->hasMany(Stub::class);
    }
}
