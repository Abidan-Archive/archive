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

## Wiki administrators and moderators

Run `php artisan migrate` to add the `wiki_admin` (**Wiki Administrator**) and
`wiki_moderator` (**Wiki Moderator**) roles to existing installations. The migration
preserves existing roles and assignments. Fresh ACL seeds include both roles too.
`AclSeeder` can also be rerun: it updates definitions by name, preserves IDs,
user assignments and additional permissions, and clears the authorization cache.

Assign either role using the existing Archive user-management role controls
(requires `admin_manage_permissions`). The roles grant wiki access only, with no
Archive administration permissions. Archive's `admin` and `mod` roles do not
implicitly grant wiki privileges.

Factories support `User::factory()->wikiAdmin()->create()` and
`User::factory()->wikiModerator()->create()`. These states can be chained to assign
both roles and create the role definitions if they are not already seeded.

For the `mediawiki` OAuth client with `profile` scope, the signed ID token and
UserInfo response contain `wiki_roles`, an array containing only those two role
names. The wiki uses PluggableAuth's mapped group synchronization:

| Archive role | Wiki group | Capabilities |
| --- | --- | --- |
| `wiki_admin` | `sysop` | Standard MediaWiki administrator rights, including interface editing and content/user moderation |
| `wiki_moderator` | `moderator` | Block users/email, protect pages, delete/restore pages, view deleted content, rollback and patrol edits |

Role grants and removals apply on the next wiki login, including the first login.
Existing wiki sessions are not refreshed immediately when an Archive role changes.
An empty or absent `wiki_roles` claim removes the two managed wiki groups; unrelated
local groups are retained. The wiki disables bureaucrat `userrights`, keeping role
assignment in Archive rather than exposing a separate wiki role-management path.

Provider regression tests can run without the development database:

```sh
DB_CONNECTION=sqlite DB_DATABASE=:memory: php artisan test --filter=OpenIdConnectTest
```
