## About Abidan Archive

The Abidan Archive is website responsible for recording all words-of-will-wight aka wows. Where the fanbase strives to record all canon non-book external information on Will's various works.

## Contributing

Thank you for considering contributing to the Abidan Archive Project! Reach out to any of the developers or community moderators should you wish to contribute.

## Directory Structure

```text
.
├── .github/workflows/  # CI checks, release builds, and deployment
├── app/                # Laravel application code
│   ├── Contracts/      # Application interfaces
│   ├── Enums/          # Enumerated domain values
│   ├── Http/
│   │   ├── Controllers/ # HTTP request handlers, including authentication/OIDC
│   │   ├── Middleware/  # Request and response middleware
│   │   └── Requests/    # Form validation and request authorization
│   ├── Jobs/           # Queued jobs, including audio processing
│   ├── Listeners/      # Application event listeners
│   ├── Models/         # Eloquent models
│   │   ├── Concerns/   # Shared model traits
│   │   └── Scopes/     # Query scopes
│   ├── Policies/       # Resource authorization
│   ├── Providers/      # Service registration, gates, and rate limiters
│   └── Services/       # Application services, including OIDC token signing
├── bin/                # Deployment script and legacy development helpers
├── bootstrap/
│   ├── app.php         # Routing, middleware, and exception-handler setup
│   ├── providers.php   # Application service-provider registration
│   ├── cache/          # Generated framework caches
│   └── ssr/            # Generated Inertia SSR bundle (Git-ignored)
├── config/             # Application and integration configuration
├── database/
│   ├── factories/      # Test/model factories
│   ├── migrations/     # Database schema migrations
│   └── seeders/        # Initial data and access-control roles/permissions
├── docker/             # Laravel Sail image and database setup files
├── docs/               # Upgrade, local wiki login, and production OIDC guides
├── lang/               # Translation strings
├── public/             # Web entrypoint and static assets; build/ is generated
├── resources/
│   ├── js/
│   │   ├── Components/ # Reusable Svelte components
│   │   ├── Layouts/    # Shared page layouts
│   │   ├── Pages/      # Inertia page components
│   │   ├── lib/        # Frontend utilities
│   │   ├── app.js      # Browser entrypoint
│   │   └── ssr.js      # Server-side rendering entrypoint
│   ├── scss/           # Stylesheets
│   └── views/          # Blade templates
├── routes/
│   ├── admin.php       # Administration routes
│   ├── auth.php        # Authentication and account-verification routes
│   ├── console.php     # Artisan command definitions
│   ├── oauth.php       # OpenID Connect provider routes
│   └── web.php         # Main web routes and route-file registration
├── secrets/            # SOPS-encrypted production environment and documentation
├── storage/            # Runtime files: uploads/audio, caches, sessions, and logs
├── tests/              # PHPUnit tests and the SSR smoke-check script
├── artisan             # Laravel CLI entrypoint
├── composer.json       # PHP dependencies; composer.lock pins their versions
├── docker-compose.yml  # Optional Docker/Sail development services
├── flake.nix           # Nix/devenv development shell and local services
├── package.json        # Frontend dependencies/scripts and pinned pnpm version
├── pnpm-lock.yaml      # Locked frontend dependency tree
└── vite.config.js      # Client and SSR build configuration
```

The tree highlights application code and important entrypoints. Generated
dependencies (`vendor/`, `node_modules/`) and local environment files are omitted.
Artisan commands live in `routes/console.php`; middleware and exception handling
are configured in `bootstrap/app.php`.

## Nix Development Environment

The development environment uses devenv through `flake.nix`. Enter it with:

```sh
nix develop --impure
```

Direnv uses the same flake through `.envrc`. The shell supplies PHP 8.3 and Node 24;
pnpm runs through Corepack using the version pinned in `package.json`. Frontend
dependencies are installed automatically when the shell detects a changed lockfile.
This project does not have a standalone `devenv.nix` for `devenv shell`.

Start the configured application processes and local services with:

```sh
nix run --impure .#devenv-up
```

## Security Vulnerabilities

If you discover a security vulnerability within this project, please send an e-mail to Michael Manning via  [hey@manning390.com](mailto:hey@manning390.com). All security vulnerabilities will be promptly addressed.


## License

The Abidan Archive is open-sourced and is built using the Laravel framework.

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
