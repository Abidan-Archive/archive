<?php

return [
    'disks' => [
        // Laravel 12 changed the fallback to app/private; retain existing paths.
        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            'throw' => false,
            'report' => false,
        ],
    ],
];
