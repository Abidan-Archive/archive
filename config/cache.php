<?php

use Illuminate\Support\Str;

return [
    'serializable_classes' => false,
    // Keep the existing namespace across the Laravel 13 upgrade.
    'prefix' => env('CACHE_PREFIX', Str::slug((string) env('APP_NAME', 'laravel'), '_').'_cache_'),
];
