<?php

use Illuminate\Support\Str;

return [
    // Preserve active sessions; changing this to json requires a planned logout.
    'serialization' => 'php',
    'cookie' => env('SESSION_COOKIE', Str::slug((string) env('APP_NAME', 'laravel'), '_').'_session'),
];
