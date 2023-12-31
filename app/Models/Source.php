<?php

namespace App\Models;

use App\Models\Event;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Storage;

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
