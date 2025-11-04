{ pkgs ? import <nixpkgs> {} }:

let
  php = pkgs.php84.withExtensions ({ all, enabled }:
    enabled ++ (with all; [
      curl
      mbstring
      pdo
      pdo_pgsql
      openssl
      iconv
    ])
  );
in
pkgs.mkShell {
  packages = [ 
    php 
    php.packages.composer 
  ];

  shellHook = ''
    echo "PHP $(php -v | head -n1)"
    echo "Composer $(composer --version)"
  '';
}
