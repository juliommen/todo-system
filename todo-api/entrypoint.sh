#!/bin/sh
set -e

echo "Aguardando banco"
until php -r "new PDO('mysql:host=${DB_HOST};dbname=${DB_NAME}', '${DB_USER}', '${DB_PASSWORD}');" 2>/dev/null; do
  sleep 2
done

echo "Rodando migrations"
php vendor/bin/doctrine-migrations migrate --no-interaction

echo "Subindo API"
exec "$@"