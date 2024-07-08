<?php

return [

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'discord' => [
        'client_id' => env('DISCORD_CLIENT_ID'),
        'client_secret' => env('DISCORD_CLIENT_SECRET'),
        'redirect' => env('DISCORD_REDIRECT_URI'),
    ],

    'recaptcha_v3' => [
        'sitekey' => env('GOOGLE_RECAPTCHA_V3_SITE_KEY'),
        'secret' => env('GOOGLE_RECAPTCHA_V3_SECRET_KEY'),
    ],

];
