Set-Content "C:\xampp\htdocs\paylite\start.sh" "#!/usr/bin/env bash
set -e

PORT=""`${PORT:-5000}""

MONGO_SO=`$(ls /nix/store/*-php-mongodb-*/lib/php/extensions/mongodb.so 2>/dev/null | head -n 1)

if [ -z ""`$MONGO_SO"" ]; then
  echo ""ERROR: mongodb.so not found"" >&2
  exit 1
fi

cat > /tmp/paylite-php.ini <<EOF
extension=`$MONGO_SO
session.save_path = ""/tmp""
display_errors = Off
log_errors = On
error_reporting = E_ALL
EOF

composer install --no-dev --optimize-autoloader --ignore-platform-req=ext-mongodb

exec php -c /tmp/paylite-php.ini -S 0.0.0.0:`$PORT -t . router.php
"