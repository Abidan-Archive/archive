<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Znck\Eloquent\Relations\BelongsToThrough;
use Znck\Eloquent\Traits\BelongsToThrough as BelongsToThroughTrait;

class Stub extends Model
{
    use HasFactory, BelongsToThroughTrait;

    protected $fillable = ['id', 'prompt', 'from', 'to'];

    public function event():BelongsToThrough  {
        return $this->belongsToThrough(Event::class, Source::class);
    }

    public function source(): BelongsTo {
        return $this->belongsTo(Source::class);
    }

}
