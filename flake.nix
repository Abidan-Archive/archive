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

                # Development
                phpactor # LSP
                vscode-langservers-extracted
                emmet-language-server
              ];

              scripts.art.exec = ''php artisan "$@"'';

              languages.php = {
                enable = true;
                package = pkgs.php83;
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
                ];
              };

              processes = {
                server.exec = "php artisan serve";
                vite.exec = "npm run dev";
              };

              services.meilisearch = {
                enable = true;
              };

              services.mailhog = {
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
