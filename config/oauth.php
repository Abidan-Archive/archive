<?php

return [
    'private_key_path' => env('OAUTH_PRIVATE_KEY_PATH', storage_path('app/oauth-private.pem')),

    /*
    |--------------------------------------------------------------------------
    | OAuth Provider Clients
    |--------------------------------------------------------------------------
    |
    | External applications that can authenticate via this Laravel app.
    | Each client needs a client_id, client_secret, and allowed redirect URIs.
    |
    */

    'clients' => [
        'mediawiki' => [
            'secret' => env('OAUTH_CLIENT_MEDIAWIKI_SECRET_FILE') ? null : env('OAUTH_CLIENT_MEDIAWIKI_SECRET'),
            'secret_file' => env('OAUTH_CLIENT_MEDIAWIKI_SECRET_FILE'),
            'redirect_uris' => [
                env('MEDIAWIKI_REDIRECT_URI') ?: rtrim(env('MEDIAWIKI_URL') ?: 'https://wiki.abidanarchive.com', '/').'/wiki/Special:PluggableAuthLogin',
            ],
            'name' => 'MediaWiki',
        ],
    ],
];
