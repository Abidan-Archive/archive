# Local MediaWiki login

The application provides an OpenID Connect authorization-code flow with S256 PKCE,
RS256-signed ID tokens, and public keys at `/oauth/jwks`. MediaWiki redirects guests
to the normal Laravel login screen and reuses the Laravel session for existing users.

For the NixOS nginx wiki at `http://wiki.localhost`, configure the Laravel `.env`:

```dotenv
APP_URL=http://localhost:8000
MEDIAWIKI_URL=http://wiki.localhost
OAUTH_CLIENT_MEDIAWIKI_SECRET=secret
```

The client secret must match MediaWiki's `clientsecret`. Run in the development shell:

```sh
php artisan config:clear
php artisan oauth:keys
```

Keep the generated `storage/app/oauth-private.pem` across restarts. It is not the
Laravel `APP_KEY`. The default callback is
`http://wiki.localhost/wiki/Special:PluggableAuthLogin`; if the wiki uses a different
article path, set `MEDIAWIKI_REDIRECT_URI` to the exact `Redirect URL` in its
OpenIDConnect debug log. Only registered callback URLs are accepted.

Use `http://localhost:8000` for the main site in the browser, matching `APP_URL` and
the wiki's `providerURL`. `127.0.0.1` and `localhost` have different browser cookies.
Port 5173 is the Vite asset/HMR server, not the OIDC provider.

After enabling the MediaWiki NixOS module and rebuilding its host, open the wiki's
login page and select **Abidan Archive**. A wiki user record is created automatically
on first login; credentials and registration are managed by Laravel. The two apps
keep separate sessions, so signing out of Laravel does not currently sign out of
an already-authenticated wiki session.

Provider regression tests can run without the development database:

```sh
DB_CONNECTION=sqlite DB_DATABASE=:memory: php artisan test --filter=OpenIdConnectTest
```
