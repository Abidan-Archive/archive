<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stub extends Model
{
    use HasFactory;

    protected $fillable = ['prompt', 'from', 'to'];

    protected static function booted(): void {
        static::creating(fn(Stub $model) =>
            $model->id = (($model->source->stubs->max('id') ?? 0) + 1),
        );
    }

    public function source(): BelongsTo {
        return $this->belongsTo(Source::class);
    }
}
