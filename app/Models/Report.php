<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = ['footnote', 'source'];

    public function event() {
        $this->belongsTo(Event::class);
    }

    public function tags() {
        $this->belongsToMany(Tag::class);
    }
}
