{ config, lib, pkgs, ... }:
{
  imports =
    [ # Include the results of the hardware scan.
      ./hardware-configuration.nix
    ];

  # Use the GRUB 2 boot loader.
  boot.loader.grub.enable = true;

  networking.usePredictableInterfaceNames = false; # Linode: Linode guides assume eth0
  networking.useDHCP = false; # Linode: Disable DHCP globally, will not need it
  networking.interfaces.eth0.useDHCP = true; # Linode: Required for ssh?
  # Open ports in the firewall.
  networking.firewall.allowedTCPPorts = [ 80 443 ];

  # Linode region is Atlanta
  time.timeZone = "America/New_York";

  users.users.telariel = {
    isNormalUser = true;
    home = "/home/telariel";
    extraGroups = [ "wheel" "networkmanager" "acme" "nginx" ];
    openssh.authorizedKeys.key = [ "ssh-ed25519 AAAAC3NzaC1lZDI1NTE5AAAAIHJbtlS3h7escz5e1Jgdgc4ZHfH4adAxNq9AwXPWw0+a telariel" ];
  };


  # List packages installed in system profile. To search, run:
  # $ nix search wget
  environment.systemPackages = with pkgs; [
    vim # Do not forget to add an editor to edit configuration.nix! The Nano editor is also installed by default.
    wget
    inetutils # Linode: used by support
    mtr # Linode: used by support
    sysstat # Linode: used by linode support
    git
    (php83.buildEnv {
      extensions = ({ enabled, all }: (with all; enabled ++ [
        ctype
        curl
        dom
        fileinfo
        mbstring
        openssl
        pdo
        session
        tokenizer
        xml
      ]));
      extraConfig = ''
      '';
    })
    php83Packages.composer
    ffmpeg # Required by app for audio snips
    audiowaveform # Required by app for generating waveform .dats
  ];

  # Some programs need SUID wrappers, can be configured further or are
  # started in user sessions.
  # programs.mtr.enable = true;
  # programs.gnupg.agent = {
  #   enable = true;
  #   enableSSHSupport = true;
  # };

  # List services that you want to enable:

  # Enable the OpenSSH daemon.
  services.openssh = {
    enable = true;
    settings.PasswordAuthentication = false;
    settings.KbdInteractiveAuthentication = false;
    settings.PermitRootLogin = "no";
  };

  # Linode: metric gathering service
  services.longview = {
    enable = true;
    apiKeyFile = "/run/keys/longview-api-key";
  };

  services.nginx = {
    enabled = true;
    recommendedGzipSettings = true;
    recommendedOptimisation = true;
    recommendedProxySettings = true;
    recommendedTlsSettings = true;
    virtualHosts = {
      "abidanarchive.com" = {
        default = true;
        forceSSL = true;
        enableACME = true;
        listen = [
          { port = 80; },
          { addr = "[::]"; port = 80; }
        ];
        serverName = "abidanarchive.com";
        root = "/var/www/abidanarchive.com/public";
        index = "index.html index.htm index.php";

        locations."/".tryFiles = "$uri $uri/ /index.php?$query_string";
        locations."= /favicon.ico".extraConfig = ''
          access_log off;
          log_not_found off;
        '';
        locations."= /robots.txt".extraConfig = ''
          access_log off;
          log_not_found off;
        '';
        locations."~ \\.php$".extraConfig = ''
          fastcgi_pass unix:${config.services.phpfpm.pools.mypool.socket};
          fastgci_index index.php;
          fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
          include ${pkgs.nginx}/conf/fastcgi_params;
        '';
        locations."~ /\\.(?!well-known).*".extraConfig = ''
          deny all;
        '';
        extraConfig = ''
          add_header X-Frame-Options "SAMEORIGIN";
          add_header X-XSS-Protection "1; mode=block";
          add_header X-Content-Type-Options "nosniff";
          charset utf-8;
          error_page 404 /index.php;
        '';
      };

      # Redirect 'www' to 'non-www'
      "www.abidanarchive.com" = {
        forceSSL = true;
        enableACME = true;
        globalRedirect = "abidanarchive.com";
      };
    };
  };

  # SSL certificate renawl settings
  security.acme = {
    acceptTerms = true;
    defaults.email = "hey@manning390.com";
    defaults.group = "nginx";
  };

  # /var/lib/acme.challenges must be writable by the ACME user
  # and readable by the nginx user.
  users.users.nginx.extraGroups = [ "acme" ];

  services.phpfpm.pools.mypool = {
    user = config.services.nginx.user;
    phpPackage = environment.systemPackages.php83;
    settings = {
        "listen.owner" = config.services.nginx.user;
        "pm" = "ondemand"; # We likely have low traffic
        "pm.max_children" = 4;
        "pm.process_idle_timeout" = 120;
        # "pm" = "dynamic"; # In case we don't calculate these
        # "pm.max_children" = ?;
        # "pm.start_servers" = ?;
        # "pm.min_spare_servers" = ?;
        # "pm.max_spare_servers" = ?;
        "pm.max_requests" = 500;
        "request_termination_timeout" = 300;
    };
  };

  services.mysql = {
    enable = true;
    package = pkgs.mariadb;

    ensureDatabases = [ "abidan" ];
    ensureUseres = [{
        name = "telariel"
        ensurePermissions = { "abidan.*" = "ALL PRIVILEGES"; };
    }];
  };

  services.meilisearch = {
    enable = true;
    environment = "production";
    masterKeyEnvironmentFile = "/run/keys/meilisearch-key";
    # The format for the key file is the following:
    # MEILI_MASTER_KEY=my_secret_key
  };

  # This option defines the first version of NixOS you have installed on this particular machine,
  # and is used to maintain compatibility with application data (e.g. databases) created on older NixOS versions.
  #
  # Most users should NEVER change this value after the initial install, for any reason,
  # even if you've upgraded your system to a new NixOS release.
  #
  # This value does NOT affect the Nixpkgs version your packages and OS are pulled from,
  # so changing it will NOT upgrade your system.
  #
  # This value being lower than the current NixOS release does NOT mean your system is
  # out of date, out of support, or vulnerable.
  #
  # Do NOT change this value unless you have manually inspected all the changes it would make to your configuration,
  # and migrated your data accordingly.
  #
  # For more information, see `man configuration.nix` or https://nixos.org/manual/nixos/stable/options#opt-system.stateVersion .
  system.stateVersion = "23.11"; # Did you read the comment?

}

