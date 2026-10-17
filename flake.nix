{
  inputs = {
    nixpkgs.url = "github:cachix/devenv-nixpkgs/rolling";
    systems.url = "github:nix-systems/default";
    devenv.url = "github:cachix/devenv";
    devenv.inputs.nixpkgs.follows = "nixpkgs";
  };

  nixConfig = {
    extra-trusted-public-keys = "devenv.cachix.org-1:w1cLUi8dv3hnoSPGAuibQv+f9TZLr6cv/Hm9XgU50cw=";
    extra-substituters = "https://devenv.cachix.org";
    allowUnfree = true;
  };

  outputs = {
    self,
    nixpkgs,
    devenv,
    systems,
    ...
  } @ inputs: let
    forEachSystem = nixpkgs.lib.genAttrs (import systems);
  in {
    packages = forEachSystem (system: {
      devenv-up = self.devShells.${system}.default.config.procfileScript;
    });

    formatter = forEachSystem (system: nixpkgs.legacyPackages.${system}.alejandra);

    devShells =
      forEachSystem
      (system: let
        pkgs = nixpkgs.legacyPackages.${system};
      in {
        default = devenv.lib.mkShell {
          inherit inputs pkgs;

          modules = [
            {
              packages = with pkgs; [
                # App
                ffmpeg
                audiowaveform
                redis

                # Development
                sops

                # LSP
                phpactor
                vscode-langservers-extracted
                emmet-language-server
                intelephense
                # nodePackages.svelte-language-server
                prettier
                # eslint_d
              ];

              env.DIRENV_LOG_FORMAT = "";
              scripts.art.exec = ''php artisan "$@"'';

              languages.php = {
                enable = true;
                package = pkgs.php83.buildEnv {
                  extraConfig = ''
                    upload_max_filesize = 500M
                    post_max_size = 550M
                  '';
                  extensions = {
                    all,
                    enabled,
                  }:
                    enabled ++ [all.redis];
                };
                extensions = [
                  "ctype"
                  "curl"
                  "dom"
                  "fileinfo"
                  "mbstring"
                  "openssl"
                  "pdo"
                  "session"
                  "tokenizer"
                  "xml"
                  "redis"
                ];
              };

              languages.javascript = {
                enable = true;
                package = pkgs.nodejs_24;
                pnpm = {
                  enable = true;
                  # Corepack runs the JS distribution pinned by packageManager.
                  # Nixpkgs' newer pnpm otherwise downloads a generic Linux
                  # executable when switching versions, which fails on NixOS.
                  package = (pkgs.writeShellScriptBin "pnpm" ''
                    exec ${pkgs.nodejs_24}/bin/corepack pnpm "$@"
                  '').overrideAttrs (_: {
                    version = pkgs.lib.removePrefix "pnpm@"
                      (builtins.fromJSON (builtins.readFile ./package.json)).packageManager;
                  });
                  install.enable = true;
                };
              };

              processes = {
                server.exec = "php artisan serve";
                queue.exec = "php artisan queue:work";
                vite.exec = "pnpm dev";
              };

              services.meilisearch = {
                enable = true;
              };

              services.mailhog = {
                enable = true;
              };

              services.redis = {
                enable = true;
              };

              services.mysql = {
                enable = true;
                initialDatabases = [
                  {name = "abidan";}
                  {name = "testing";}
                ];
                ensureUsers = [
                  {
                    name = "sail";
                    password = "password";
                    ensurePermissions = {"*.*" = "ALL PRIVILEGES";};
                  }
                ];
              };
            }
          ];
        };
      });
  };
}
