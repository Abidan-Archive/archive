<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Stub extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'prompt', 'from', 'to'];

    public function event(): HasOneThrough {
        return $this->hasOneThrough(Event::class, Source::class);
    }

    public function source(): BelongsTo {
        return $this->belongsTo(Source::class);
    }

}
