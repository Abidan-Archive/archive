{
    description = "Abidan Archive Server Site";

    nixConfig = {};

    inputs = {
        # Nixpkgs
        nixpkgs.url = "github:nixos/nixpkgs/nixos-24.05";
        nixpkgs-unstable.url = "github:nixos/nixpkgs/nixos-unstable";
        # Home manager
        home-manager.url = "github:nix-community/home-manager/release-24.05";
        home-manager.inputs.nixpkgs.follows = "nixpkgs";
        # Secrets
        sops-nix.url = "github:Mic92/sops-nix";
    };

    outputs = {
        self,
        nixpkgs,
        home-manager,
        ...
    } @ inputs:
    let
        inherit (self) outputs;
        vars = import ./vars.nix;
    in {
        nixosConfigurations.abidan = nixpkgs.lib.nixosSystem {
            system = "x86_64-linux";
            specialArgs = {inherit inputs outputs vars;};
            modules = [
                ./host.nix
                inputs.sops-nix.nixosModules.sops
            ];
            # home-manager.useGlobalPkgs = true;
            # home-manager.extraSpecialArgs = {inherit inputs outputs vars;};
            # home-manager.users.HOSTUSER = import ./home.nix;
        };
    };
}
