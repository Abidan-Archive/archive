<?php

namespace App\Models;

use App\Contracts\Likeable;
use App\Models\Concerns\InertiaPaginate;
use App\Models\Concerns\Likes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

/**
 * @mixin IdeHelperReport
 */
class Report extends Model implements Likeable
{
    use HasFactory, Likes, SoftDeletes, Searchable, InertiaPaginate;

    protected $appends = ['permalink', 'is_liked'];

    protected $casts = ['date' => 'date'];

    protected $fillable = ['footnote', 'date', 'source_label', 'source_href', 'legacy_permalink'];

    protected $with = ['event', 'dialogues'];

    protected $withCount = ['likes'];

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

    protected function isLiked(): Attribute
    {
        return Attribute::make(get: fn($value, $attr): bool => auth()->check() && auth()->user()->hasLiked($this));
    }

    public function toSearchableArray(): Array {
        return [
            'date' => $this->date,
            'footnote' => $this->footnote,
            'dialogues' => $this->dialogues->map->only(['speaker', 'line'])->all(),
            'tags' => $this->tags->pluck('name')->toArray(),
            'likes' => $this->likes_count,
        ];
    }

}
