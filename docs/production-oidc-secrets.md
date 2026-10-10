# Production OIDC secrets

For migration of the complete production environment to SOPS, see
`iteration110/docs/production-environment.md`. The workflow uses the host-managed
`.env`; Laravel environment decryption is no longer part of deployment.
NixOS refreshes the config cache automatically when the rendered environment changes.
New releases trigger the same refresh through a narrowly scoped passwordless sudo
rule; no sudo password is passed in deployment.

The `iteration110` NixOS configuration owns the signing key in SOPS and supplies
its runtime path through `OAUTH_PRIVATE_KEY_PATH`. It also renders the shared
MediaWiki client secret to a separate file readable by the application, selected
with `OAUTH_CLIENT_MEDIAWIKI_SECRET_FILE`. That file is read at request time;
only its path is stored in cached configuration. When a file is configured, an
unreadable file does not fall back to `OAUTH_CLIENT_MEDIAWIKI_SECRET`.

For manual import into SOPS, run `php artisan oauth:keys --show` in a private local
development terminal. It prints a new RSA private PEM without writing any files.
Do not run it in CI or a shared/recorded terminal. The default command still stores
local/testing keys; it does not create production key files.

The deployment workflow links each release's `.env` to `/etc/abidan/runtime.env`.
Deploy the NixOS configuration first so the rendered environment exists. The signing
PEM is not copied into releases or build artifacts. The new server wiki is at
`https://next.abidanarchive.com`.
