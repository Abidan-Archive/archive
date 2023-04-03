<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stub extends Model
{
    use HasFactory;

    protected $fillable = ['prompt', 'from', 'to'];

    public function source(): BelongsTo {
        return $this->belongsTo(Source::class);
    }
}
