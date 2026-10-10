# Production environment

After migrating, `production.env` contains production variables encrypted using
SOPS's native **dotenv** format, with one variable per line. Only encrypted files and public encryption
recipients belong in Git. Private age keys and temporary plaintext remain outside
the repository and deployment artifacts.

The NixOS host pins this repository, decrypts the file at activation, and supplies
the runtime `.env`. Follow `iteration110/docs/production-environment.md` for migration
and update instructions. No production credential values have been added here.
