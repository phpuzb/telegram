{pkgs ? import <nixpkgs> {}}: let
  php = pkgs.php84.withExtensions (
    {
      all,
      enabled,
    }:
      enabled
      ++ (with all; [
        curl
        mbstring
        pdo
        pdo_pgsql
        openssl
        iconv
      ])
  );
in
  pkgs.php.buildComposerProject2 (finalAttrs: {
    pname = "phpuzb-telegram";
    version = "1.0.0";

    src = pkgs.lib.cleanSource ./.;

    composerLock = ./composer.lock;

    php = php;

    composerStrictValidation = false;

    vendorHash = "sha256-/B99+wEEQS8XaLI6fOB75e4C2yv5bY/Zb4G0Q8R25XY=";

    nativeBuildInputs = [pkgs.makeWrapper];
    postInstall = ''
      mkdir -p $out/bin
      makeWrapper ${php}/bin/php $out/bin/phpuzb-telegram \
        --add-flags "$out/share/php/${finalAttrs.pname}/src/index.php"
    '';

    meta = with pkgs.lib; {
      description = "PHP Uzbekistan Telegram Assistant";
    };
  })
