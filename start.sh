#!/usr/bin/env bash
# Locate the mongodb.so extension provided by php82Extensions.mongodb (Nix)
# and start the PHP built-in server with it loaded.

set -e

PORT="${PORT:-5000}"

# Find the first matching mongodb.so in the Nix store.
MONGO_SO=$(ls /nix/store/*-php-mongodb-*/lib/php/extensions/mongodb.so 2>/dev/null | head -n 1)

if [ -z "$MONGO_SO" ]; then
  echo "ERROR: mongodb.so not found in /nix/store. Make sure replit.nix declares pkgs.php82Extensions.mongodb." >&2
  exit 1
fi

cat > /tmp/paylite-php.ini <<EOF
extension=$MONGO_SO
session.save_path = "/tmp"
display_errors = Off
log_errors = On
error_reporting = E_ALL
EOF

exec php -c /tmp/paylite-php.ini -S 0.0.0.0:"$PORT" -t .
