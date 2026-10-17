<?php

namespace Tests\Feature;

use App\Jobs\CreateStubFileJob;
use App\Models\Event;
use App\Models\Source;
use App\Models\Stub;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;
use Tests\TestCase;

class AudioProcessingTest extends TestCase
{
    use RefreshDatabase;

    public function test_waveform_and_queued_audio_processing_with_real_tools(): void
    {
        $finder = new ExecutableFinder;
        if (! $finder->find('ffmpeg') || ! $finder->find('audiowaveform')) {
            $this->markTestSkipped('Run in the development shell with ffmpeg and audiowaveform.');
        }
        Storage::fake('public');
        Storage::disk('public')->makeDirectory(Stub::DIRECTORY);
        $input = tempnam(sys_get_temp_dir(), 'abidan-audio-');
        try {
            (new Process(['ffmpeg', '-v', 'error', '-y', '-f', 'lavfi', '-i',
                'sine=frequency=440:duration=2', '-f', 'wav', $input]))->mustRun();
            $event = Event::create(['name' => 'Audio upgrade check', 'date' => '2026-10-17']);
            $source = Source::createFromFile($event, new UploadedFile($input, 'tone.wav', 'audio/wav', null, true), 1);
            $disk = Storage::disk('public');
            $disk->assertExists(Source::DIRECTORY.'/'.$source->filename);
            $disk->assertExists(Source::DIRECTORY.'/'.$source->dat);

            // Exercise the job's actual processing while avoiding SQLite's lack
            // of support for the legacy composite-key foreign reference.
            $stub = Mockery::mock(Stub::class)->makePartial();
            $stub->fill(['id' => 1, 'from' => 0, 'to' => 1]);
            $stub->setRelation('source', $source);
            $stub->shouldReceive('save')->once()->andReturnTrue();
            (new CreateStubFileJob($stub))->handle();
            $disk->assertExists(Stub::DIRECTORY.'/'.$stub->filename);
            $this->assertGreaterThan(0, $disk->size(Stub::DIRECTORY.'/'.$stub->filename));
        } finally {
            @unlink($input);
        }
    }
}
