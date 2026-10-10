<?php

use App\Models\Dialogue;
use App\Models\Event;
use App\Models\Report;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('consume {jsonPath}', function (string $jsonPath) {
    $data = json_decode(file_get_contents($jsonPath), true);

    foreach ($data as $i => $e) {
        $e['date'] = new Carbon($e['date']);
        $event = Event::create($e);

        if ($i > 0) {
            $this->newLine();
        }
        $this->line("Consuming $event->name");
        $this->withProgressBar($e['reports'], function ($r) use ($event) {
            try {
                $r['date'] = new Carbon($r['date']);
                $report = $event->reports()->create($r);
                $report->refresh();

                foreach ($r['dialogues'] as $i => $d) {
                    $d['order'] = $i;
                    $report->dialogues()->create($d);
                }

                foreach ($r['tags'] as $t) {
                    $tag = Tag::firstOrCreate(
                        ['name' => $t],
                        ['color' => str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT)]
                    );
                    $report->tags()->attach($tag);
                }
            } catch (Exception $e) {
                // $this->newLine();
                // $this->error('Error on '.$r['id']);
                // $this->newLine();
                throw $e;
            }
        });
    }
})->purpose('Import all the scraped web data');

Artisan::command('killReports', function () {
    if (! app()->environment('local')) {
        return;
    }
    // Clear out everything since we're testing
    Event::truncate();
    Report::truncate();
    Dialogue::truncate();
    Tag::truncate();
    $this->info('All dead :)');
})->purpose('Truncate all tables for scraped data mport.');

Artisan::command('notify-backup {context?}', function (?string $context = null) {
    Log::notice('Database backup job ran.', $context !== null ? compact('context') : []);
})->purpose('Just logs that the database backup job ran. Runs on exit.');

Artisan::command('oauth:keys {--show : Print a new private PEM to stdout for manual import into SOPS}', function () {
    $path = config('oauth.private_key_path');
    if (! $this->option('show') && File::exists($path)) {
        $this->info('OIDC signing key already exists.');

        return;
    }

    $key = openssl_pkey_new(['private_key_bits' => 2048, 'private_key_type' => OPENSSL_KEYTYPE_RSA]);
    if ($key === false || ! openssl_pkey_export($key, $pem)) {
        throw new RuntimeException('Unable to generate OIDC signing key.');
    }
    if ($this->option('show')) {
        $this->output->write($pem);

        return;
    }
    if (! app()->environment(['local', 'testing'])) {
        $this->error('Production signing keys are managed by SOPS. Use --show locally to generate a PEM for import.');

        return 1;
    }
    File::ensureDirectoryExists(dirname($path));
    $previousUmask = umask(0077);
    try {
        File::put($path, $pem);
    } finally {
        umask($previousUmask);
    }
    $this->info('OIDC signing key generated.');
})->purpose('Generate an OIDC signing key for local storage or manual SOPS import');
