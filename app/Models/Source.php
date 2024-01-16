<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\UploadedFile;
use Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class Source extends Model
{
    use HasFactory;

    const DIRECTORY = 'sources';

    protected $fillable = ['id', 'name', 'filename'];

    protected $appends = ['url', 'dat_url'];

    /**
    * Register methods to model hooks
    */
    protected static function booted(): void {
        // Delete associated files in storage when model deleted
        static::deleting(function (Source $model) {
            Storage::delete(self::DIRECTORY.'/'.$model->filename);
            Storage::delete(self::DIRECTORY.'/'.$model->dat);
        });
    }

    public function event(): BelongsTo {
        return $this->belongsTo(Event::class);
    }

    public function stubs(): HasMany {
        return $this->hasMany(Stub::class);
    }

    protected function dat(): Attribute {
        return Attribute::make(get: fn($value, $attributes) =>
            pathinfo($attributes['filename'], PATHINFO_FILENAME).'.dat');
    }

    protected function datUrl(): Attribute {
        return Attribute::make(get: fn($value, $attributes) =>
            Storage::url(self::DIRECTORY.'/'.pathinfo($attributes['filename'], PATHINFO_FILENAME).'.dat'));
    }

    protected function url(): Attribute {
        return Attribute::make(get: fn($value, $attributes) =>
            Storage::url(self::DIRECTORY.'/'.$attributes['filename']));
    }

    /**
     * Creates a new source relative to event from file
     */
    public static function createFromFile(Event $event, UploadedFile $file, int $id): self {
        $filename = implode('_', [$event->id, $id, $file->hashName()]); // <event_id>_<source_id>_<random_hash>.<ext>
        // Directory, Name, Disk
        $file->storeAs(self::DIRECTORY, $filename, 'public'); // storage/app/public/sources/<FILENAME>
        self::createDat($filename);
        return $event->sources()->create([
            'id' => $id,
            'name' => $file->getClientOriginalName(),
            'filename' => $filename,
        ]);
    }

    /**
     * Create a dat file from audiowaveform for fast waveform rendering when scrubbing
     */
    private static function createDat(string $filename): void {
        $dir = Storage::disk('public')->path(self::DIRECTORY.'/');
        $out = pathinfo($filename, PATHINFO_FILENAME).'.dat';
        $process = new Process(['audiowaveform', '-q', '-i', $dir.$filename, '-o', $dir.$out]);
        $process->run();
        if (!$process->isSuccessful()) throw new ProcessFailedException($process);
    }
}
