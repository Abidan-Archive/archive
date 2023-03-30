<?php

namespace App\Models;

use App\Models\Traits\HasLikes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

/**
 * App\Models\Report
 *
 * @property int $id
 * @property string|null $footnote
 * @property \Illuminate\Support\Carbon $date
 * @property string|null $source_label
 * @property string|null $source_href
 * @property string|null $legacy_permalink
 * @property int $event_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Dialogue> $dialogues
 * @property-read int|null $dialogues_count
 * @property-read \App\Models\Event|null $event
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tag> $tags
 * @property-read int|null $tags_count
 * @method static \Illuminate\Database\Eloquent\Builder|Report newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Report newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Report onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Report query()
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereFootnote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereLegacyPermalink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereSourceHref($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereSourceLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Report withoutTrashed()
 * @mixin \Eloquent
 */
class Report extends Model
{
    use HasFactory, HasLikes, SoftDeletes, Searchable;

    protected $appends = [
        // 'likes',
        'permalink'
    ];

    protected $casts = ['date' => 'date'];

    protected $fillable = ['footnote', 'date', 'source_label', 'source_href', 'legacy_permalink'];

    protected $with = ['event', 'dialogues'];

    public function event(): BelongsTo {
        return $this->belongsTo(Event::class);
    }

    public function dialogues(): HasMany {
        return $this->hasMany(Dialogue::class);
    }

    public function tags(): BelongsToMany {
        return $this->belongsToMany(Tag::class);
    }

    protected function permalink(): Attribute {
        return Attribute::make(
            get: fn($value, $attr) => route('report.show', $this)
        );
    }

    public function toSearchableArray(): Array {
        return [
            'date' => $this->date,
            'footnote' => $this->footnote,
            'dialogues' => $this->dialogues->map->only(['speaker', 'line'])->all(),
            'tags' => $this->tags->map->only('name')->all()
        ];
    }

}
