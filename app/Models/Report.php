<?php

namespace App\Models;

use App\Models\Traits\HasLikes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use HasFactory, HasLikes, SoftDeletes;

    protected $appends = ['likes'];

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
}
