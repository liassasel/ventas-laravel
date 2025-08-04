#!/bin/bash

echo "Esperando a PostgreSQL..."
while ! nc -z db 5432; do
  sleep 1
done
echo "PostgreSQL disponible"

if [ ! -d "node_modules" ]; then
  npm install
fi

npm run build

php artisan migrate --force

exec apache2-foreground