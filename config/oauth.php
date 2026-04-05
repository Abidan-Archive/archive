<?php

return [
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
            'secret' => env('OAUTH_CLIENT_MEDIAWIKI_SECRET'),
            'redirect_uris' => [
                env('MEDIAWIKI_URL', 'https://wiki.abidanarchive.com').'/wiki/Special:PluggableAuthLogin',
            ],
            'name' => 'MediaWiki',
        ],
    ],
];
