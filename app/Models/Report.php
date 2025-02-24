<?php

namespace App\Models;

use App\Contracts\Likeable as LikeableContract;
use App\Http\Requests\UpdateReportRequest;
use App\Models\Concerns\InertiaPaginate;
use App\Models\Concerns\Likeable;
use App\Models\Scopes\ReviewedScope;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

/**
 * @mixin IdeHelperReport
 */
class Report extends Model implements LikeableContract
{
    use HasFactory, InertiaPaginate, Likeable, Searchable, SoftDeletes;

    protected $appends = ['permalink', 'is_liked'];

    protected $fillable = ['footnote', 'date', 'source_label', 'source_href', 'legacy_permalink', 'reviewed_at'];

    protected $with = ['event', 'dialogues', 'tags'];

    protected $withCount = ['likes'];

    protected static function booted(): void
    {
        static::addGlobalScope(new ReviewedScope);
    }

    protected function casts(): array
    {
        return [
            'date' => 'date:Y-m-d',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function dialogues(): HasMany
    {
        return $this->hasMany(Dialogue::class);
    }

    /** Depreciated */
    public function proffers(): HasMany
    {
        return $this->hasMany(Proffer::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function stub(): HasOne
    {
        return $this->hasOne(Stub::class)->withDefault(null);
    }

    protected function permalink(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attr) => route('report.show', $this)
        );
    }

    protected function isLiked(): Attribute
    {
        return Attribute::make(get: fn ($value, $attr): bool => auth()->check() && auth()->user()->hasLiked($this));
    }

    public function toSearchableArray(): array
    {
        return [
            'date' => $this->date,
            'footnote' => $this->footnote,
            'dialogues' => $this->dialogues->map->only(['speaker', 'line'])->all(),
            'tags' => $this->tags->pluck('name')->toArray(),
            'likes' => $this->likes_count,
        ];
    }

    /**
     * Patching logic for this Model. Used multiple times so abstracted to here.
     */
    public function patch(UpdateReportRequest $request)
    {
        $data = $request->validated();

        $this->fill($data);
        // dd($data);

        // Update event if need be
        if ($request->has('event_id')) {
            $this->event_id = $data['event_id'];
        }

        // Put dialogs
        if ($request->has('dialogues')) {
            $this->dialogues()->delete();
            foreach($data['dialogues'] as $i => $d) {
                $d['order'] = $i;
                $this->dialogues()->create($d);
            }
        }

        // Update tags
        if ($request->has('tags')) {
            $tags = Tag::select('id')->whereIn('name', $request->tags)->get()->pluck('id');
            $this->tags()->sync($tags);
        }

        return $this->save();
    }
}
