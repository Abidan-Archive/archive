# Production environment

`production.env` contains Laravel production variables encrypted using SOPS's
native **dotenv** format, with one variable per line. Only encrypted files and
public encryption recipients belong in Git. Private age keys and temporary
plaintext remain outside the repository and deployment artifacts.

The NixOS host pins this repository, decrypts the file at activation, and supplies
the runtime `.env` through `/etc/abidan/runtime.env`. After editing the encrypted
environment with SOPS and committing it to `prod`, update the host's
`abidan-environment` input and rebuild NixOS. The host automatically refreshes
Laravel's configuration cache when the rendered environment changes. See
`iteration110/docs/production-environment.md` for the commands and rollout details.

The OIDC signing PEM is stored separately as `ABIDAN_OIDC_PRIVATE_KEY` in
`iteration110/secrets.yaml`, encrypted with SOPS. That host configuration also owns
the shared MediaWiki OAuth client secret. NixOS supplies their runtime file paths
through `OAUTH_PRIVATE_KEY_PATH` and `OAUTH_CLIENT_MEDIAWIKI_SECRET_FILE`, overriding
any values for those paths in `production.env`. See
[`../docs/production-oidc-secrets.md`](../docs/production-oidc-secrets.md) for details.

Application release artifacts exclude `secrets/` and plaintext environment files;
GitHub Actions does not decrypt the production environment or signing PEM.
