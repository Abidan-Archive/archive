<?php

use Illuminate\Support\Str;

// Keep using framework connection defaults while retaining the pre-13 Redis
// namespace, so existing queued jobs and Redis-backed data remain reachable.
$configuration = require base_path('vendor/laravel/framework/config/database.php');
$configuration['redis']['options']['prefix'] = env(
    'REDIS_PREFIX',
    Str::slug((string) env('APP_NAME', 'laravel'), '_').'_database_'
);

return $configuration;
