<?php

return [

    'channels' => [
        'ban' => [
            'driver' => 'stack',
            'channels' => ['single', 'discord'],
        ],

        'discord' => [
            'driver' => 'custom',
            'via' => MarvinLabs\DiscordLogger\Logger::class,
            'level' => 'debug',
            'url' => env('LOG_DISCORD_WEBHOOK_URL'),
            'ignore_exceptions' => env('LOG_DISCORD_IGNORE_EXCEPTIONS', false),
        ],
    ],

];
