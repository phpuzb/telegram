{
  config,
  lib,
  pkgs,
  ...
}: let
  cfg = config.services.phpuzb.telegram;
in {
  options.services.phpuzb.telegram = {
    enable = lib.mkEnableOption "PHP Community Telegram bot service";

    user = lib.mkOption {
      type = lib.types.str;
      default = "phpuzb-telegram";
      description = "User under which the bot runs.";
    };

    group = lib.mkOption {
      type = lib.types.str;
      default = "phpuzb-telegram";
      description = "Group under which the bot runs.";
    };

    package = lib.mkOption {
      type = lib.types.package;
      description = "The PHPUZB Telegram bot package (should contain src/index.php).";
    };

    phpPackage = lib.mkOption {
      type = lib.types.package;
      default = pkgs.php84;
      description = "PHP package used to run the bot and PHP-FPM pool.";
    };

    environment = lib.mkOption {
      type = lib.types.attrsOf lib.types.str;
      default = {};
      example = {
        TELEGRAM_BOT_TOKEN = "xxxx";
        APP_ENV = "production";
      };
      description = "Environment variables passed to the Telegram bot process and PHP-FPM.";
    };

    nginx = {
      enable = lib.mkEnableOption "Enable nginx site for the bot";
      serverName = lib.mkOption {
        type = lib.types.str;
        default = "localhost";
        description = "Server name for nginx configuration.";
      };
      port = lib.mkOption {
        type = lib.types.port;
        default = 8080;
        description = "Port on which nginx listens.";
      };
    };

    config = lib.mkIf cfg.enable {
      users.users.${cfg.user} = {
        isSystemUser = true;
        group = cfg.group;
      };

      users.groups.${cfg.group} = {};

      systemd.services.phpuzb-telegram = {
        description = "PHPUZB Telegram Bot Service";
        after = ["network.target"];
        wantedBy = ["multi-user.target"];

        serviceConfig = {
          User = cfg.user;
          Group = cfg.group;
          Restart = "always";
          WorkingDirectory = "${cfg.package}/share/php";
          ExecStart = "${cfg.phpPackage}/bin/php ${cfg.package}/share/php/src/index.php";
          Environment = lib.mapAttrsToList (n: v: "${n}=${v}") cfg.environment;
        };
      };

      services.nginx = lib.mkIf cfg.nginx.enable {
        enable = true;

        virtualHosts.${cfg.nginx.serverName} = {
          listen = [
            {
              addr = "0.0.0.0";
              port = cfg.nginx.port;
            }
          ];
          root = "${cfg.package}/share/php";
          index = "index.php";
          locations."/".extraConfig = ''
            try_files $uri /index.php$is_args$args;
          '';
          locations."~ \.php$".extraConfig = ''
            fastcgi_pass unix:${config.services.phpfpm.pools.phpuzb.socket};
            include ${pkgs.nginx}/conf/fastcgi_params;
            fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
          '';
        };
      };
      services.phpfpm.pools.phpuzb = {
        user = cfg.user;
        group = cfg.group;
        settings = {
          "listen.owner" = cfg.user;
          "listen.group" = cfg.group;
        };
      };
    };
  };
}
