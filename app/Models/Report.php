<?php

namespace App\Models;

use App\Models\Traits\HasLikes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Report extends Model
{
    use HasFactory, HasLikes, SoftDeletes, Searchable;

    protected $appends = ['likes'];

    protected $with = ['dialogues'];

    protected $casts = ['date' => 'date'];

    protected $fillable = ['footnote', 'date', 'source_label', 'source_href'];

    public function event() {
        return $this->belongsTo(Event::class);
    }

    public function dialogues() {
        return $this->hasMany(Dialogue::class);
    }

    public function tags() {
        return $this->belongsToMany(Tag::class);
    }

    protected function permalink(): Attribute {
        return Attribute::make(
            get: fn($value, $attr) => route('report.show', $this)
        );
    }

    public function copyText(): string {
        $out = $this->dialogues->reduce(
            fn($acc, $d) => $acc .= $d->speaker . '\n\n' . $d->line . '\n\n', '');
        if($this->footnote)
            $out .= 'Footnote: ' . $this->footnote . '\n\n';
        $out .= $this->permalink;

        return $out;
    }

    public function toSearchableArray() {
        return [
            'date' => $this->date,
            'footnote' => $this->footnote,
            'dialogues' => $this->dialogues->map->only(['speaker', 'line'])->all(),
            'tags' => $this->tags->map->only('name')->all()
        ];
    }

}
