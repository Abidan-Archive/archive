<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Znck\Eloquent\Relations\BelongsToThrough;
use Znck\Eloquent\Traits\BelongsToThrough as BelongsToThroughTrait;

/**
 * @mixin IdeHelperStub
 */
class Stub extends Model
{
    use HasFactory, BelongsToThroughTrait;

    const DIRECTORY = 'sources';

    protected $fillable = ['id', 'prompt', 'from', 'to'];

    protected $appends = ['audioUrl'];

    /**
    * Register methods to model hooks
    */
    protected static function booted(): void {
        // Delete associated files in storage when model deleted
        static::deleting(function (Stub $model) {
            Storage::delete(self::DIRECTORY.'/'.$model->filename);
        });
        static::created(function (Stub $model) {
            $model->createFile();
        });
    }

    public function event(): BelongsToThrough  {
        return $this->belongsToThrough(Event::class, Source::class);
    }

    public function source(): BelongsTo {
        return $this->belongsTo(Source::class);
    }

    public function audioUrl(): Attribute {
        return Attribute::make(get: fn($value, $attributes) =>
            $attributes['filename'] !== null
            ? Storage::url(self::DIRECTORY.'/'.$attributes['filename'])
            : null);
    }

    private function createFile(): void {
        $input = Storage::disk('public')->path(Source::DIRECTORY).'/'.$this->source->filename;
        $filename = implode('_', [$this->source->id, $this->id, Str::random(40)]).'.'.pathinfo($input, PATHINFO_EXTENSION);
        $output = Storage::disk('public')->path(self::DIRECTORY).'/'.$filename;
        $process = new Process(['ffmpeg', '-ss', $this->from, '-t', $this->to - $this->from, '-c copy', '-i', $input, '-o', $output]);
        $process->run();
        if (!$process->isSuccessful()) throw new ProcessFailedException($process);
        $this->filename = $filename;
        $this->save();
    }

}
